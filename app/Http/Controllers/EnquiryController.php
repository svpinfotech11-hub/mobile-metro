<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EnquiryModel;
use App\Models\EnquiryserviceModel;
use Illuminate\Support\Facades\DB;
use App\Models\CFTModel;
use Illuminate\Support\Facades\Log;
use App\Models\Payment;


class EnquiryController extends Controller
{
     public function index(Request $request)
    {
        $title = 'Enquiry Details';
        $query = EnquiryModel::query();

        // Filter on Enquiry table
        if ($request->filled('order_no')) {
            $query->where('order_no', 'like', '%' . $request->order_no . '%');
        }

        // Filter on related Customer table
        $query->when($request->filled('email') || $request->filled('mobile_no') || $request->filled('customer_name'), function ($q) use ($request) {
            $q->whereHas('customer', function ($q2) use ($request) {
                if ($request->filled('email')) {
                    $q2->where('email', 'like', '%' . $request->email . '%');
                }
                if ($request->filled('mobile_no')) {
                    $q2->where('mobile_no', 'like', '%' . $request->mobile_no . '%');
                }
                if ($request->filled('customer_name')) {
                    $q2->where('customer_name', 'like', '%' . $request->customer_name . '%');
                }
            });
        });
         // Filter enquiry created_at
        if ($request->filled('created_at')) {
            $query->whereDate('tbl_enquiry.created_at', $request->created_at);
        }

        // ✅ Explicit table name + check query
        $query->orderBy('tbl_enquiry.id', 'desc');

        // $enquiry_details = $query->paginate(10)->appends($request->query());
    $enquiry_details = $query->with('payments')->orderBy('id', 'desc')->paginate(10); // paginate to 10 records per page

        return view('admin.enquiry.enquiry_list', compact('title', 'enquiry_details'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
 

    public function show($id)
    {
        $title = 'Enquiry Summaries';
    
        $enquiry = DB::table('tbl_enquiry')->where('id', $id)->first();
        if (!$enquiry) {
            abort(404, 'Enquiry Not Found');
        }
    
        $customer = DB::table('tbl_customer')
            ->where('id', $enquiry->customer_id)
            ->first();
    
        /* -------------------- Decode Products -------------------- */
        $items = [];
        if (!empty($enquiry->products_item)) {
            $json = trim($enquiry->products_item, '"');
            $json = stripslashes($json);
            $items = json_decode($json, true) ?? [];
        }
    
        /* -------------------- 1️⃣ Calculate TOTAL CFT -------------------- */
        $totalCft = 0;
    
        foreach ($items as &$item) {
    
            $product = DB::table('tbl_product')
                ->whereRaw('LOWER(product_name) LIKE ?', [
                    '%' . strtolower(trim($item['product_name'] ?? '')) . '%'
                ])
                ->first();
    
            if (!$product) {
                $item['product_cft'] = 0;
                $item['total_cft']   = 0;
                continue;
            }
    
            $productCft = (float) $product->product_cft;
            $quantity   = (int) ($item['quantity'] ?? 1);
    
            $item['product_cft'] = $productCft;
            $item['total_cft']   = $productCft * $quantity;
    
            $totalCft += $item['total_cft'];
        }
    
        /* -------------------- 2️⃣ Apply CFT Slab ONCE -------------------- */
        $cftRateDetails = $this->getCftRate($totalCft);
    
        $rateType     = strtoupper($cftRateDetails['rate_type']);
        $cftRate      = $cftRateDetails['cft_rate'];
        $cftProfit    = $cftRateDetails['cft_profit'];
        $totalCftCost = $cftRateDetails['total_cost'];
    
        /* -------------------- Assign same rate to items (display only) --- */
        foreach ($items as &$item) {
            $item['rate_type']  = $rateType;
            $item['cft_rate']   = $cftRate;
            $item['cft_profit'] = $cftProfit;
        }
    
        /* -------------------- 3️⃣ KM Calculation -------------------- */
        $cftId = $this->getCftId($totalCft);
        $kmDistance = $enquiry->km_distance ?? 0;
        $kmRateDetails = $this->getKmRate($kmDistance, $cftId);
    
        /* -------------------- 4️⃣ Final Totals -------------------- */
        $enquiry->items            = $items;
        $enquiry->total_cft        = round($totalCft, 2);
        $enquiry->total_amount     = round($totalCftCost, 2);
        $enquiry->total_km_cost    = round($kmRateDetails['total_km_cost'], 2);
        $enquiry->grand_total_cost = $enquiry->total_amount + $enquiry->total_km_cost;
    
        $enquiry->km_distance  = $kmDistance;
        $enquiry->km_profit = $kmRateDetails['km_profit'];
        $enquiry->km_rate      = $kmRateDetails['km_rate'];
        $enquiry->km_rate_type = $kmRateDetails['rate_type'];
    
        /* -------------------- Payments -------------------- */
        $allPayments = Payment::where('enquiry_id', $id)
            ->orderByDesc('id')
            ->get();
    
        $lastPayment = $allPayments->first();
        $advancePaid = $allPayments->sum('amount');
    
        $quotationAmount = $lastPayment
            ? $lastPayment->total_amount
            : $enquiry->total_amount;
    
        return view(
            'admin.enquiry.enquiry_summries',
            compact(
                'title',
                'enquiry',
                'customer',
                'allPayments',
                'lastPayment',
                'advancePaid',
                'quotationAmount',
                'totalCftCost',
                'rateType',
                'cftRate',
                'cftProfit'
            )
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
   
    
        public function get_service_enquiry(Request $request)
    {
        $title = 'Enquiry Service Details';

        $query = EnquiryserviceModel::select('enquiry_service_tbl.*')
            ->join('sub_category_tbl', 'enquiry_service_tbl.service_name', '=', 'sub_category_tbl.sub_categoryname')
            ->where('sub_category_tbl.sub_category_service', 0);

        // Filter on enquiry_service table
        if ($request->filled('order_no')) {
            $query->where('enquiry_service_tbl.order_no', 'like', '%' . $request->order_no . '%');
        }
        
             if ($request->filled('created_date')) {
            $query->whereDate('enquiry_service_tbl.created_at', $request->created_date);
        }

        // Filter on related customer table
        if ($request->filled('email') || $request->filled('mobile_no') || $request->filled('customer_name')) {
            $query->whereHas('customer', function ($q2) use ($request) {
                if ($request->filled('email')) {
                    $q2->where('email', 'like', '%' . $request->email . '%');
                }
                if ($request->filled('mobile_no')) {
                    $q2->where('mobile_no', 'like', '%' . $request->mobile_no . '%');
                }
                if ($request->filled('customer_name')) {
                    $q2->where('customer_name', 'like', '%' . $request->customer_name . '%');
                }
            });
        }

        // Fetch in descending order
        $query->orderBy('enquiry_service_tbl.id', 'desc');

        // Paginate results and retain filters
        $enquiry_details = $query->paginate(10)->appends($request->query());

        return view('admin.enquiry.service_enquiry_list', compact('title', 'enquiry_details'));
    }

    public function get_other_enquiry(Request $request)
    {
        $title = 'Other Enquiry Service Details';

        $query = EnquiryserviceModel::select('enquiry_service_tbl.*')
            ->join('sub_category_tbl', 'enquiry_service_tbl.service_name', '=', 'sub_category_tbl.sub_categoryname')
            ->whereIn('sub_category_tbl.sub_category_service', [1, 2, 3]);


        // Filter on enquiry_service table
        if ($request->filled('order_no')) {
            $query->where('enquiry_service_tbl.order_no', 'like', '%' . $request->order_no . '%');
        }
        
            if ($request->filled('created_date')) {
            $query->whereDate('enquiry_service_tbl.created_at', $request->created_date);
        }

        // Filter on related customer table
        if ($request->filled('email') || $request->filled('mobile_no') || $request->filled('customer_name')) {
            $query->whereHas('customer', function ($q2) use ($request) {
                if ($request->filled('email')) {
                    $q2->where('email', 'like', '%' . $request->email . '%');
                }
                if ($request->filled('mobile_no')) {
                    $q2->where('mobile_no', 'like', '%' . $request->mobile_no . '%');
                }
                if ($request->filled('customer_name')) {
                    $q2->where('customer_name', 'like', '%' . $request->customer_name . '%');
                }
            });
        }

        // Fetch in descending order
        $query->orderBy('enquiry_service_tbl.id', 'desc');

        // Paginate results and retain filters
        $enquiry_details = $query->paginate(10)->appends($request->query());

        return view('get-other-enquiry', compact('title', 'enquiry_details'));
    }

    
   
    private function getCftRate($product_cft)
    {
        $rateInfo = CFTModel::where('from_cft', '<=', $product_cft)
            ->where('to_cft', '>=', $product_cft)
            ->first();
    
        if (!$rateInfo) {
            return [
                'cft_rate'      => 0,
                'cft_profit'    => 0,
                'total_cost'    => 0,
                'total_amount'  => 0,
                'rate_type'     => 'N/A'
            ];
        }
    
        $rate     =  $rateInfo->cft_rate;
        $profit  = (float) $rateInfo->cft_profit;
        $rateRaw = (int) $rateInfo->rate_type;
    
        // ---------------- FIXED ----------------
        if ($rateRaw === 0) {
    
            $rateType    = 'FIXED';
            $totalAmount = $rate;
            $totalCost   = $rate + $profit;
    
        }
        // ---------------- PER CFT ----------------
        elseif ($rateRaw === 1) {
    
            $rateType    = 'PER CFT';
            $totalAmount = $rate * $product_cft;
            $totalCost   = $totalAmount + $profit;
    
        }
        // ---------------- INVALID ----------------
        else {
    
            \Log::error('INVALID RATE TYPE IN DB', [
                'rate_type' => $rateInfo->rate_type,
                'product_cft' => $product_cft
            ]);
    
            return [
                'cft_rate'      => $rate,
                'cft_profit'    => $profit,
                'total_cost'    => 0,
                'total_amount'  => 0,
                'rate_type'     => 'INVALID'
            ];
        }
    
        return [
            'cft_rate'      => $rate,
            'cft_profit'    => $profit,
            'total_cost'    => round($totalCost, 2),
            'total_amount'  => round($totalAmount, 2),
            'rate_type'     => $rateType
        ];
    }


    private function getKmRate($km_distance, $cft_id)
    {
        $km_distance = (int) ceil($km_distance);
    
        $rateInfo = DB::table('km_rate_tb')
            ->where('cft_id', $cft_id)
            ->where('from_km', '<=', $km_distance)
            ->where('to_km', '>=', $km_distance)
            ->first();
    
        if (!$rateInfo) {
            return [
                'km_rate'       => 0,
                'km_profit'     => 0,
                'total_km_cost' => 0,
                'rate_type'     => 'N/A',
                'rate_profile'  => 0
            ];
        }
    
        $rateRaw = (int) $rateInfo->rate_type; 
        $rate    = (float) $rateInfo->km_rate;
        $profit  = (float) $rateInfo->km_profit;
    
        if ($rateRaw === 0) {
            $rateType    = 'FIXED';
            $totalAmount = $rate;
            $totalKmCost = $rate + $profit;
        } elseif ($rateRaw === 1) {
            $rateType    = 'PER KM';
            $totalAmount = $rate * $km_distance;
            $totalKmCost = $totalAmount + $profit;
        } else {
            $rateType    = 'INVALID';
            $totalAmount = 0;
            $totalKmCost = 0;
        }
    
        return [
            'km_rate'       => $rate,
            'km_profit'     => $profit,
            'total_km_cost' => round($totalKmCost, 2),
            'rate_type'     => strtoupper($rateType),
            'rate_profile'  => round($totalAmount, 2)
        ];
    }


     private function getCftId($totalCft)
    {
        return DB::table('cft_rate_tbl')
            ->whereRaw('CAST(from_cft AS DECIMAL(10,2)) <= ?', [$totalCft])
            ->whereRaw('CAST(to_cft AS DECIMAL(10,2)) >= ?', [$totalCft])
            ->value('id'); // returns only id
    }
    
    public function get_other_service_enquiry_delete(Request $request, $id)
    {
        // First try deleting by ID
        $deleted = EnquiryserviceModel::where('id', $id)->delete();
    
        // dd($deleted);
        if ($deleted) {
            return back()->with('success', 'Record deleted successfully by ID.');
        }
    
        // If ID doesn't exist, try order_no if sent
        if ($request->order_no) {
            $deleted = EnquiryModel::where('order_no', $request->order_no)->delete();
    
            if ($deleted) {
                return back()->with('success', 'Record deleted successfully by order number.');
            }
        }
    
        return back()->with('error', 'Record not found.');
    }

    public function get_service_enquiry_destroy(Request $request, $id){
        // First try deleting by ID
        $deleted = EnquiryserviceModel::where('id', $id)->delete();
    
        // dd($deleted);
        if ($deleted) {
            return back()->with('success', 'Record deleted successfully by ID.');
        }
    
        // If ID doesn't exist, try order_no if sent
        if ($request->order_no) {
            $deleted = EnquiryModel::where('order_no', $request->order_no)->delete();
    
            if ($deleted) {
                return back()->with('success', 'Record deleted successfully by order number.');
            }
        }
    
        return back()->with('error', 'Record not found.');
    }
    
      public function getServiceEnquiryDestroy($id){
    
            $record = EnquiryModel::findOrFail($id);
            $record->delete();
            return redirect()->back()->with('success', 'Record Deleted SuccessFully!');
        }
    
    }

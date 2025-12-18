<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\CFTModel;
use App\Models\EnquiryModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EnquiryserviceModel;
use Illuminate\Support\Facades\Log;

class EnquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index(Request $request)
    // {
    //     $title = 'Enquiry Details';
    //     // $enquiry_details = EnquiryModel::all();
    //     $query = EnquiryModel::query();

    //     if($request->filled('order_no')){
    //         $query = $query->where('order_no',$request->order_no);
    //     }

    //     $enquiry_details = $query->paginate(10)->appends($request->query());
    //     return view('admin.enquiry.enquiry_list', compact('title','enquiry_details'));
    // }

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
    $enquiry_details = $query->with('payments')->orderBy('id', 'desc')->paginate(10);

        return view('admin.enquiry.enquiry_list', compact('title', 'enquiry_details'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {}

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


//     public function show($id)
//     {
//         $title = 'Enquiry Summaries';
//         $enquiry = DB::table('tbl_enquiry')->where('id', $id)->first();

//         if (!$enquiry) {
//             abort(404, 'Enquiry Not Found');
//         }

//         $customer = DB::table('tbl_customer')->where('id', $enquiry->customer_id)->first();

//         // Decode products_item JSON safely
//         $items = [];
//         if (!empty($enquiry->products_item)) {
//             $cleanJson = trim($enquiry->products_item, '"');
//             $cleanJson = stripslashes($cleanJson);
//             $items = json_decode($cleanJson, true);
//         }

//         $totalCft = 0;
//         $totalAmount = 0;
//         $totalCost = 0;

//         if (!empty($items) && is_array($items)) {
//             foreach ($items as &$item) {
//                 $productName = trim($item['product_name']);

//                 $product = DB::table('tbl_product')
//                     ->whereRaw('LOWER(product_name) LIKE ?', ['%' . strtolower($productName) . '%'])
//                     ->first();

//                 if ($product) {
//                     $productCft = floatval($product->product_cft);
//                     $quantity = (int) $item['quantity'];
//                     $totalProductCft = $productCft * $quantity;

//                     $rateDetails = $this->getCftRate($totalProductCft);

//                     $computedCost = $rateDetails['total_cost'];

//                     // Assign item data
//                     $item['product_cft'] = $productCft;
//                     $item['total_cft'] = $totalProductCft;
//                     $item['rate_type'] = ucfirst($rateDetails['rate_type']);
//                     $item['cft_rate'] = $rateDetails['cft_rate'];
//                     $item['cft_profit'] = $rateDetails['cft_profit'];
//                     $item['amount'] = $rateDetails['total_amount'];
//                     $item['total_cost'] = $computedCost;

//                     // Totals
//                     $totalCft += $totalProductCft;
//                     $totalAmount += $rateDetails['total_amount'];
//                     $totalCost += $computedCost;
//                 } else {
//                     $item['product_cft'] = 0;
//                     $item['total_cft'] = 0;
//                     $item['rate_type'] = '-';
//                     $item['cft_rate'] = 0;
//                     $item['cft_profit'] = 0;
//                     $item['amount'] = 0;
//                     $item['total_cost'] = 0;
//                 }
//             }
//         }

//         $cft_id = $this->getCftId($totalCft);
//         // dd($cft_id);

//         // ✅ Now handle KM calculation
//         $kmDistance = $enquiry->km_distance ?? 0;
//         $kmRateDetails = $this->getKmRate($kmDistance, $cft_id);

//         $enquiry->items = $items;
//         $enquiry->total_cft = round($totalCft, 2);
//         $enquiry->total_amount = round($totalAmount, 2);
//         $enquiry->grand_total_cost = round($totalCost, 2);

//         // ✅ Add KM details
//         $enquiry->km_distance = $kmDistance;
//         $enquiry->km_rate = $kmRateDetails['km_rate'];
//         // $enquiry->km_profit = $kmRateDetails['km_profit'];
//         $enquiry->km_rate_type = ucfirst($kmRateDetails['rate_type']);
//         $enquiry->total_km_cost = round($kmRateDetails['total_km_cost'], 2);

//         // ✅ Add KM cost to the overall grand total if applicable
//         $enquiry->grand_total_cost += $enquiry->total_km_cost;

//     $allPayments = Payment::where('enquiry_id', $id)
//     ->orderBy('id', 'desc')
//     ->get();

// $lastPayment = $allPayments->first();  
// $advancePaid = $allPayments->sum('amount');  
// $quotationAmount = $lastPayment->total_amount ?? $enquiry->total_amount;
//         // dd($enquiry_payemnt_details);
// return view(
//     'admin.enquiry.enquiry_summries',
//     compact(
//         'title', 'enquiry', 'customer',
//         'allPayments', 'lastPayment',
//         'advancePaid', 'quotationAmount'
//     )
// );

//     }


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

    $totalCft = 0;
    $totalAmount = 0;
    $totalCost = 0;

    if (is_array($items)) {
        foreach ($items as &$item) {

            $productName = trim($item['product_name'] ?? '');

            $product = DB::table('tbl_product')
                ->whereRaw('LOWER(product_name) LIKE ?', ['%' . strtolower($productName) . '%'])
                ->first();

            if (!$product) {
                $item = array_merge($item, [
                    'product_cft' => 0,
                    'total_cft' => 0,
                    'rate_type' => '-',
                    'cft_rate' => 0,
                    'cft_profit' => 0,
                    'amount' => 0,
                    'total_cost' => 0
                ]);
                continue;
            }

            $productCft = (float) $product->product_cft;
            $quantity = (int) ($item['quantity'] ?? 1);
            $totalProductCft = $productCft * $quantity;

            $rateDetails = $this->getCftRate($totalProductCft);

            $item['product_cft'] = $productCft;
            $item['total_cft'] = $totalProductCft;
            $item['rate_type'] = $rateDetails['rate_type'];
            $item['cft_rate'] = $rateDetails['cft_rate'];
            $item['cft_profit'] = $rateDetails['cft_profit'];
            $item['amount'] = $rateDetails['total_amount'];
            $item['total_cost'] = $rateDetails['total_cost'];

            $totalCft += $totalProductCft;
            $totalAmount += $rateDetails['total_amount'];
            $totalCost += $rateDetails['total_cost'];
        }
    }

    /* -------------------- KM Calculation -------------------- */
    $cftId = $this->getCftId($totalCft);
    $kmDistance = $enquiry->km_distance ?? 0;
    $kmRateDetails = $this->getKmRate($kmDistance, $cftId);

    /* -------------------- Enquiry Totals -------------------- */
    $enquiry->items = $items;
    $enquiry->total_cft = round($totalCft, 2);
    $enquiry->total_amount = round($totalAmount, 2);
    $enquiry->grand_total_cost = round($totalCost, 2);

    $enquiry->km_distance = $kmDistance;
    $enquiry->km_rate = $kmRateDetails['km_rate'];
    $enquiry->km_rate_type = $kmRateDetails['rate_type'];
    $enquiry->total_km_cost = round($kmRateDetails['total_km_cost'], 2);

    $enquiry->grand_total_cost += $enquiry->total_km_cost;

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
            'quotationAmount'
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



    public function get_service_enquiry(Request $request)
    {
        $title = 'Enquiry Service Details';

        $query = EnquiryserviceModel::select(
                'enquiry_service_tbl.*',
                'sub_category_tbl.sub_category_service'
            )
            ->join('sub_category_tbl', 'enquiry_service_tbl.service_name', '=', 'sub_category_tbl.sub_categoryname')
            ->where('sub_category_tbl.sub_category_service', 0);

        if ($request->filled('order_no')) {
            $query->where('enquiry_service_tbl.order_no', 'like', '%' . $request->order_no . '%');
        }

        if ($request->filled('created_date')) {
            $query->whereDate('enquiry_service_tbl.created_at', $request->created_date);
        }

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

        $query->orderBy('enquiry_service_tbl.id', 'desc');

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


    // private function getCftRate($product_cft)
    // {
    //     // Find the slab that includes the product CFT
    //     $rateInfo = CFTModel::whereRaw('CAST(from_cft AS UNSIGNED) <= ?', [$product_cft])
    //         ->whereRaw('CAST(to_cft AS UNSIGNED) >= ?', [$product_cft])
    //         ->first();


    //     // ✅ No slab found — return zeros
    //     if (!$rateInfo) {
    //         return [
    //             'cft_rate' => 0,
    //             'cft_profit' => 0,
    //             'total_cost' => 0,
    //             'total_amount' => 0,
    //             'rate_type' => 'N/A'
    //         ];
    //     }

    //     // Rate details
    //     $rateType = ($rateInfo->rate_type == 0) ? 'Fixed' : 'Per CFT';
    //     $rate = $rateInfo->cft_rate;
    //     $profit = $rateInfo->cft_profit;

    //     // Calculations
    //     if ($rateType === 'Fixed') {
    //         $totalAmount = $rate;
    //         $totalCost = $rate + $profit;
    //         // $totalCost = $rate;
    //     } else {
    //         $totalAmount = $rate * $product_cft;
    //         $totalCost = $totalAmount + $profit;
    //     }


    //     return [
    //         'cft_rate' => $rate,
    //         'cft_profit' => $profit,
    //         'total_cost' => $totalCost,
    //         'total_amount' => $totalAmount,
    //         'rate_type' => $rateType
    //     ];
    // }


    // private function getKmRate($km_distance)
    // {
    //     $rateInfo = DB::table('km_rate_tb')
    //         ->whereRaw('CAST(from_km AS DECIMAL(10,2)) <= ?', [$km_distance])
    //         ->whereRaw('CAST(to_km AS DECIMAL(10,2)) >= ?', [$km_distance])
    //         ->first();

    //     if (!$rateInfo) {
    //         return [
    //             'km_rate'   => 0,
    //             'km_profit' => 0,
    //             'total_km_cost' => 0,
    //             'rate_type' => 'N/A'
    //         ];
    //     }

    //     $rateType = ($rateInfo->rate_type == 0) ? 'fixed' : 'per km';
    //     $rate     = (float) $rateInfo->km_rate;
    //     $profit   = (float) $rateInfo->km_profit;

    //     // ✅ Calculate total cost
    //     if ($rateType === 'fixed') {
    //         $totalKmCost = $rate + $profit;
    //     } else {
    //         $totalKmCost = ($rate * $km_distance) + $profit;
    //     }

    //     return [
    //         'km_rate'       => $rate,
    //         'km_profit'     => $profit,
    //         'total_km_cost' => $totalKmCost,
    //         'rate_type'     => $rateType
    //     ];
    // }


    // private function getKmRate($km_distance, $cft_id)
    // {
    //     $rateInfo = DB::table('km_rate_tb')
    //         ->where('cft_id', $cft_id)
    //         ->whereRaw('CAST(from_km AS DECIMAL(10,2)) <= ?', [$km_distance])
    //         ->whereRaw('CAST(to_km AS DECIMAL(10,2)) >= ?', [$km_distance])
    //         ->first();

    //     if (!$rateInfo) {
    //         return [
    //             'km_rate'       => 0,
    //             'total_km_cost' => 0,
    //             'rate_type'     => 'N/A'
    //         ];
    //     }

    //     $rateType = ($rateInfo->rate_type == 0) ? 'fixed' : 'per km';
    //     $rate     = floatval($rateInfo->km_rate);

    //     // 🔥 NO KM PROFIT ANYMORE
    //     if ($rateType === 'fixed') {
    //         $totalKmCost = $rate;
    //     } else {
    //         $totalKmCost = $rate * $km_distance;
    //     }

    //     return [
    //         'km_rate'       => $rate,
    //         'total_km_cost' => $totalKmCost,
    //         'rate_type'     => $rateType
    //     ];
    // }


    private function getCftRate($product_cft)
{
    // Find the slab that includes the product CFT
    $rateInfo = CFTModel::whereRaw('CAST(from_cft AS UNSIGNED) <= ?', [$product_cft])
        ->whereRaw('CAST(to_cft AS UNSIGNED) >= ?', [$product_cft])
        ->first();

    // ❌ No slab found
    if (!$rateInfo) {
        return [
            'cft_rate'      => 0,
            'cft_profit'    => 0,
            'total_cost'    => 0,
            'total_amount'  => 0,
            'rate_type'     => 'N/A'
        ];
    }

    // Normalize rate_type (case-insensitive)
    $rateTypeRaw = strtolower(trim($rateInfo->rate_type));

    $rate   = (float) $rateInfo->cft_rate;
    $profit = (float) $rateInfo->cft_profit;

    // Calculations
    if ($rateTypeRaw === 'fixed') {

        // Fixed → rate + profit
        $totalAmount = $rate;
        $totalCost   = $rate + $profit;
        $rateType    = 'Fixed';

    } elseif ($rateTypeRaw === 'per cft' || $rateTypeRaw === 'Per CFT') {

        // Per CFT → (rate × CFT) + profit
        $totalAmount = $rate * $product_cft;
        $totalCost   = $totalAmount + $profit;
        $rateType    = 'Per CFT';

    } else {

        // Safety fallback
        return [
            'cft_rate'      => $rate,
            'cft_profit'    => $profit,
            'total_cost'    => 0,
            'total_amount'  => 0,
            'rate_type'     => 'Invalid Rate Type'
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
    // Round KM distance to nearest integer
    $km_distance = (int) ceil($km_distance);

    // Fetch rate row matching CFT ID + KM Range
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

    // rate_type: 0 = fixed, 1 = per box
    $rateType = ($rateInfo->rate_type == 0) ? 'fixed' : 'per km';

    $rate   = (float) $rateInfo->km_rate;
    $profit = (float) $rateInfo->km_profit;

    /** 
     * CORRECT LOGIC:
     * fixed      = total cost is km_rate only
     * per box    = total cost is km_rate + km_profit
     */
    if ($rateType === 'fixed') {
        $totalKmCost = $rate + $profit;
    } else {
        $totalKmCost = ($rate * $km_distance) + $profit;
    }

    return [
        'km_rate'       => $rate,
        'km_profit'     => $profit,
        'total_km_cost' => $totalKmCost,
        'rate_type'     => $rateType,
        'rate_profile'  => $rate + $profit
    ];
}


    private function getCftId($totalCft)
    {
        return DB::table('cft_rate_tbl')
            ->whereRaw('CAST(from_cft AS DECIMAL(10,2)) <= ?', [$totalCft])
            ->whereRaw('CAST(to_cft AS DECIMAL(10,2)) >= ?', [$totalCft])
            ->value('id'); // returns only id
    }


    // public function destroy($id){
    // $enquiry = DB::table('tbl_enquiry')->where('id', $id)->first();

    // if (!$enquiry) {
    //     return redirect()->back()->with('error', 'Enquiry not found.');
    // }
    // $customer = DB::table('tbl_customer')->where('id', $enquiry->customer_id)->first();
    // $customer = DB::table('tbl_customer')->where('id', $enquiry->customer_id)->first();
    // DB::table('tbl_enquiry')->where('id', $id)->delete();
    // if ($customer) {
    //     DB::table('tbl_customer')->where('id', $customer->id)->delete();
    // }
    // return redirect()->back()->with('success', 'Enquiry and customer deleted successfully.');

    // }


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

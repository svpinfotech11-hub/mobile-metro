<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Payment;
use App\Models\Category;
use App\Models\CFTModel;
use App\Models\SubCategory;
use App\Models\UserEnquiry;
use App\Models\UserPayment;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class UserEnquiryController extends Controller
{

      public function formss()
    {
        $categories = Category::with('subCategories')->get();
        // dd($categories);
        // $subCategories = SubCategory::where('category_id', $categories->id)->get();
        return view('pages.users.form', compact('categories'));
    }

//     public function store(Request $request)
// {
//     try {
//         $request->validate([
//             'name'  => 'required|string',
//             'email' => 'required|email|unique:users,email',
//             'phone' => 'required|string',
//             'category_id'     => 'required|integer',
//             'sub_category_id' => 'required|integer',
//         ]);

//         $user = User::firstOrCreate(
//             ['email' => $request->email],
//             [
//                 'name'        => $request->name,
//                 'phone'       => $request->phone,
//                 'status'      => 'active',
//                 'role'        => 'user',
//                 'is_verified' => true,
//             ]
//         );

//         $subCategory = SubCategory::findOrFail($request->sub_category_id);
//         $type = (int) $subCategory->sub_category_service;

//         // Validation rules based on type
//         $rules = [];
//         switch ($type) {
//             case 1:
//                 $rules = [
//                     'service_ids' => 'required|array|min:1',
//                     'service_ids.*' => 'integer',
//                     'product_subcategory_ids' => 'required|array|min:1',
//                     'product_subcategory_ids.*' => 'integer',
//                     'products' => 'required|array',
//                     'product_qty' => 'required|array',
//                     'product_qty.*' => 'required|integer|min:1',
//                     'pickup_location' => 'required|string',
//                     'drop_location' => 'required|string',
//                     'service_date' => 'required|date',
//                     'floor_number' => 'nullable|string',
//                     'lift_available' => 'nullable|in:yes,no',
//                 ];
//                 break;
//             case 0:
//                 $rules = [
//                     'service_name' => 'required|string',
//                     'service_location' => 'required|string',
//                     'service_date' => 'required|date',
//                     'service_description' => 'nullable|string',
//                 ];
//                 break;
//             case 2:
//                 $rules = [
//                     'pickup_location' => 'required|string',
//                     'drop_location' => 'required|string',
//                     'service_date' => 'required|date',
//                 ];
//                 break;
//             case 3:
//                 $rules = [
//                     'vehicle_number' => 'required|string',
//                     'service_date' => 'required|date',
//                 ];
//                 break;
//         }
//         $validated = $request->validate($rules);

//         // -----------------------------
//         // Initialize variables
//         // -----------------------------
//         $productsItem = [];
//         $kmDistance = 0;
//         $kmRate = ['rate_type' => null, 'km_rate' => 0, 'total_km_cost' => 0, 'km_profit' => 0];
//         $totalAmount = 0;
//         $totalProfit = 0;

//         if ($type === 1 && $request->filled('products')) {
//             $products = ProductModel::whereIn('product_id', $request->products)->get();

//             // Total CFT across all products
//             $totalCft = 0;
//             foreach ($products as $product) {
//                 $qty = $request->product_qty[$product->product_id] ?? 1;
//                 $totalCft += $product->product_cft * $qty;
//             }

//             $cftRateData = $this->getCftRate($totalCft);

//             // KM distance calculation
//             $kmDistance = $this->calculateDistanceFromGoogle($request->pickup_location, $request->drop_location);
//             if ($kmDistance <= 0) {
//                 $pickup = $this->getLatLngFromGoogle($request->pickup_location);
//                 $drop = $this->getLatLngFromGoogle($request->drop_location);
//                 if ($pickup['lat'] && $drop['lat']) {
//                     $kmDistance = $this->calculateDistanceForService($pickup['lat'],$pickup['lng'],$drop['lat'],$drop['lng']);
//                 }
//             }

//             // KM Rate based on CFT slab
//             if (!empty($cftRateData['cft_id'])) {
//                 $kmRate = $this->getKmRate($kmDistance, (int) $cftRateData['cft_id']);
//             }

//             // Per-product cost calculation
//             foreach ($products as $product) {
//                 $cftTotalCost = $cftRateData['total_cost'];     // includes profit
//                 $cftProfit    = $cftRateData['cft_profit'];

                
//                 $qty = $request->product_qty[$product->product_id] ?? 1;
//                 $productCft = $product->product_cft * $qty;

//                   if ($cftRateData['rate_type'] === 'FIXED') {

//                     // Distribute slab proportionally (DISPLAY ONLY)
//                     $productTotal  = ($productCft / $totalCft) * $cftRateData['total_amount'];
//                     $productProfit = ($productCft / $totalCft) * $cftRateData['cft_profit'];

//                 } else { // PER CFT

//                     $productTotal  = $productCft * $cftRateData['cft_rate'];
//                     $productProfit = $productCft * $cftRateData['cft_profit'];
//                 }

//                 $productsItem[] = [
//                     'product_id' => $product->product_id,
//                     'product_name' => $product->product_name,
//                     'product_subcat_id' => $product->product_subcategory_id,
//                     'quantity' => $qty,
//                     'service_id' => $product->service_id ?? null,
//                     'product_cft' => $product->product_cft,
//                     'total_cft' => $productCft,
//                     'rate_type' => $cftRateData['rate_type'],
//                     'cft_rate' => $cftRateData['cft_rate'],
//                     'total_cost' => $productTotal,
//                     'cft_profit' => $productProfit,
//                 ];

//                 $totalAmount += $productTotal;
//                 $totalProfit += $productProfit;
//             }

//             // Add KM cost & profit
//             $totalAmount += $kmRate['total_km_cost'] ?? 0;
//             $totalProfit += $kmRate['km_profit'] ?? 0;
//         }
//         $kmDistance = round($kmDistance);
//         // -----------------------------
//         // Save enquiry
//         // -----------------------------
//         $enquiry = UserEnquiry::create([
//             'user_id' => $user->id,
//             'category_id' => $request->category_id,
//             'sub_category_id' => $request->sub_category_id,
//             'service_ids' => $request->service_ids ?? null,
//             'product_subcategory_ids' => $request->product_subcategory_ids ?? null,
//             'service_id' => $request->service_id ?? null,
//             'service_name' => $request->service_name ?? null,
//             'service_date' => $request->service_date ?? null,
//             'service_location' => $request->service_location ?? null,
//             'pickup_location' => $request->pickup_location ?? null,
//             'drop_location' => $request->drop_location ?? null,
//             'floor_number' => $request->floor_number ?? null,
//             'lift_available' => $request->lift_available ?? null,
//             'vehicle_number' => $request->vehicle_number ?? null,
//             'service_description' => $request->service_description ?? null,
//             // 'km_distance' => round($kmDistance, 2),
//             'km_distance' => $kmDistance,
//             'km_rate' => $kmRate['km_rate'] ?? 0,
//             'km_cost' => $kmRate['total_km_cost'] ?? 0,
//             'km_rate_type' => $kmRate['rate_type'] ?? 'N/A',
//             'km_profit' => $kmRate['km_profit'] ?? 0,
//             'total_amount' => round($totalAmount, 2),
//             'products_item' => $productsItem,
//         ]);

//         if ($type === 1 && $request->filled('products')) {
//             $enquiry->services()->sync($request->service_ids);
//             $enquiry->productSubcategories()->sync($request->product_subcategory_ids);
//         }

//         // -----------------------------
//         // Payment handling
//         // -----------------------------
//         $paymentMethod = $request->payment_method;
//         $paidAmount = $enquiry->total_amount;

//         if ($paymentMethod === 'cash') {
//             UserPayment::create([
//                 'user_enquiry_id' => $enquiry->id,
//                 'amount' => $paidAmount,
//                 'method' => 'cash',
//                 'status' => 'paid',
//             ]);

//             return response()->json([
//                 'status' => 'success',
//                 'message' => 'Enquiry submitted successfully (Cash Payment)',
//                 'data' => $enquiry,
//             ]);
//         }

//         if (in_array($paymentMethod, ['upi','card'])) {
//             $razorpay = new \Razorpay\Api\Api(
//                 config('services.razorpay.key'),
//                 config('services.razorpay.secret')
//             );

//             $order = $razorpay->order->create([
//                 'amount' => $paidAmount * 100,
//                 'currency' => 'INR',
//                 'receipt' => 'ENQ-'.$enquiry->id,
//             ]);

//             return response()->json([
//                 'status' => 'payment_required',
//                 'order' => ['id'=>$order['id'],'amount'=>$order['amount']],
//                 'enquiry_id' => $enquiry->id,
//             ]);
//         }

//         return response()->json([
//             'status' => true,
//             'message' => 'Enquiry submitted successfully',
//             'data' => $enquiry,
//         ]);

//     } catch (ValidationException $e) {
//         return response()->json([
//             'status' => false,
//             'errors' => $e->errors()
//         ], 422);
//     }
// }
    public function store(Request $request)
    {
        try {
            // -----------------------------
            // Base validation
            // -----------------------------
            $request->validate([
                'name'  => 'required|string',
                'email' => 'required|email|unique:users,email',
                'phone' => 'required|string',
                'category_id'     => 'required|integer',
                'sub_category_id' => 'required|integer',
            ]);

            $user = User::firstOrCreate(
                ['email' => $request->email],
                [
                    'name'        => $request->name,
                    'phone'       => $request->phone,
                    'status'      => 'active',
                    'role'        => 'user',
                    'is_verified' => true,
                ]
            );

            $subCategory = SubCategory::findOrFail($request->sub_category_id);
            $type = (int) $subCategory->sub_category_service;

            // -----------------------------
            // Type-based validation
            // -----------------------------
               switch ($type) {
            case 1: // Product + KM type
                $rules = [
                    'service_ids' => 'required|array|min:1',
                    'service_ids.*' => 'integer',
                    'product_subcategory_ids' => 'required|array|min:1',
                    'product_subcategory_ids.*' => 'integer',
                    'products' => 'required|array',
                    'product_qty' => 'required|array',
                    'product_qty.*' => 'required|integer|min:1',
                    'pickup_location' => 'required|string',
                    'drop_location' => 'required|string',
                    'service_date' => 'required|date',
                    'floor_number' => 'nullable|string',
                    'lift_available' => 'nullable|in:yes,no',
                    'payment_method' => 'required|in:cash,upi,card',
                ];
                break;
            case 0: // General service
                $rules = [
                    'service_name' => 'required|string',
                    'service_location' => 'required|string',
                    'service_date' => 'required|date',
                    'service_description' => 'nullable|string',
                ];
                break;
            case 2: // Transport type
                $rules = [
                    'pickup_location' => 'required|string',
                    'drop_location' => 'required|string',
                    'service_date' => 'required|date',
                ];
                break;
            case 3: // Vehicle type
                $rules = [
                    'vehicle_number' => 'required|string',
                    'service_date' => 'required|date',
                ];
                break;
        }


            // -----------------------------
            // Init
            // -----------------------------
            $productsItem = [];
            $totalAmount  = 0;
            $kmRate       = ['km_rate'=>0,'km_profit'=>0,'total_km_cost'=>0,'rate_type'=>'N/A'];
            $kmDistance   = 0;

            // ======================================================
            // TYPE 1 : PRODUCT + KM BASED
            // ======================================================
            if ($type === 1) {

                $products = ProductModel::whereIn('product_id', $request->products)->get();

                // -----------------------------
                // Total CFT
                // -----------------------------
                $totalCft = 0;
                foreach ($products as $product) {
                    $qty = $request->product_qty[$product->product_id] ?? 1;
                    $totalCft += ($product->product_cft * $qty);
                }

                if ($totalCft <= 0) {
                    throw new \Exception('Total CFT cannot be zero');
                }

                // -----------------------------
                // CFT slab
                // -----------------------------
                $cftRateData = $this->getCftRate($totalCft);

                // -----------------------------
                // Distance calculation
                // -----------------------------
                $kmDistance = $this->calculateDistanceFromGoogle(
                    $request->pickup_location,
                    $request->drop_location
                );

                if ($kmDistance <= 0) {
                    $pickup = $this->getLatLngFromGoogle($request->pickup_location);
                    $drop   = $this->getLatLngFromGoogle($request->drop_location);

                    if ($pickup['lat'] && $drop['lat']) {
                        $kmDistance = $this->calculateDistanceForService(
                            $pickup['lat'],
                            $pickup['lng'],
                            $drop['lat'],
                            $drop['lng']
                        );
                    }
                }

                // IMPORTANT → round distance BEFORE pricing
                $kmDistance = (int) ceil($kmDistance);

                // -----------------------------
                // KM rate (based on CFT slab)
                // -----------------------------
                if (!empty($cftRateData['cft_id'])) {
                    $kmRate = $this->getKmRate($kmDistance, (int)$cftRateData['cft_id']);
                }

                // -----------------------------
                // Product breakup (DISPLAY ONLY)
                // -----------------------------
                foreach ($products as $product) {
                    $qty = $request->product_qty[$product->product_id] ?? 1;
                    $productCft = $product->product_cft * $qty;

                    if ($cftRateData['rate_type'] === 'FIXED') {
                        $productTotal  = ($productCft / $totalCft) * $cftRateData['total_amount'];
                        $productProfit = ($productCft / $totalCft) * $cftRateData['cft_profit'];
                    } else {
                        $productTotal  = $productCft * $cftRateData['cft_rate'];
                        $productProfit = $productCft * $cftRateData['cft_profit'];
                    }

                    $productsItem[] = [
                        'product_id'   => $product->product_id,
                        'product_name' => $product->product_name,
                        'product_subcat_id' => $product->product_subcategory_id,
                        'quantity'     => $qty,
                        'product_cft'  => $product->product_cft,
                        'total_cft'    => $productCft,
                        'rate_type'    => $cftRateData['rate_type'],
                        'cft_rate'     => $cftRateData['cft_rate'],
                        'total_cost'   => round($productTotal, 2),
                        'cft_profit'   => round($productProfit, 2),
                    ];
                }

                // -----------------------------
                // FINAL TOTAL (ONLY HERE)
                // -----------------------------
                $totalAmount = round(
                    $cftRateData['total_cost'] + ($kmRate['total_km_cost'] ?? 0),
                    2
                );
            }

            // -----------------------------
            // Save enquiry
            // -----------------------------
           $enquiry = UserEnquiry::create([
            'user_id' => $user->id,
            'category_id' => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
            'service_ids' => $request->service_ids ?? null,
            'product_subcategory_ids' => $request->product_subcategory_ids ?? null,
            'service_id' => $request->service_id ?? null,
            'service_name' => $request->service_name ?? null,
            'service_date' => $request->service_date ?? null,
            'service_location' => $request->service_location ?? null,
            'pickup_location' => $request->pickup_location ?? null,
            'drop_location' => $request->drop_location ?? null,
            'floor_number' => $request->floor_number ?? null,
            'lift_available' => $request->lift_available ?? null,
            'vehicle_number' => $request->vehicle_number ?? null,
            'service_description' => $request->service_description ?? null,
            'km_distance' => $kmDistance,
            'km_rate' => $kmRate['km_rate'] ?? 0,
            'km_cost' => $kmRate['total_km_cost'] ?? 0,
            'km_rate_type' => $kmRate['rate_type'] ?? 'N/A',
            'km_profit' => $kmRate['km_profit'] ?? 0,
            'total_amount' => round($totalAmount, 2),
            'products_item' => $productsItem,
        ]);

            if ($type === 1) {
                $enquiry->services()->sync($request->service_ids);
                $enquiry->productSubcategories()->sync($request->product_subcategory_ids);
            }

            // -----------------------------
            // Payment
            // -----------------------------
            if ($request->payment_method === 'cash') {
                UserPayment::create([
                    'user_enquiry_id' => $enquiry->id,
                    'amount' => $totalAmount,
                    'method' => 'cash',
                    'status' => 'paid',
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Enquiry submitted (Cash)',
                    'data' => $enquiry,
                ]);
            }

            if (in_array($request->payment_method, ['upi','card'])) {
                $razorpay = new \Razorpay\Api\Api(
                    config('services.razorpay.key'),
                    config('services.razorpay.secret')
                );

                $order = $razorpay->order->create([
                    'amount' => $totalAmount * 100,
                    'currency' => 'INR',
                    'receipt' => 'ENQ-'.$enquiry->id,
                ]);

                return response()->json([
                    'status' => 'payment_required',
                    'order' => [
                        'id' => $order['id'],
                        'amount' => $order['amount']
                    ],
                    'enquiry_id' => $enquiry->id,
                ]);
            }

        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }




    private function calculateDistance(string $pickup, string $drop): float
    {
        $googleDistance = $this->calculateDistanceFromGoogle($pickup, $drop);

        if ($googleDistance > 0) {
            return $googleDistance;
        }

        $pickupLatLng = $this->getLatLngFromGoogle($pickup);
        $dropLatLng   = $this->getLatLngFromGoogle($drop);

        if (!$pickupLatLng['lat'] || !$dropLatLng['lat']) {
            return 0;
        }

        return $this->calculateDistanceForService(
            $pickupLatLng['lat'],
            $pickupLatLng['lng'],
            $dropLatLng['lat'],
            $dropLatLng['lng']
        );
    }

    private function calculateDistanceForService($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371; // in kilometers

        $lat1 = deg2rad($lat1);
        $lng1 = deg2rad($lng1);
        $lat2 = deg2rad($lat2);
        $lng2 = deg2rad($lng2);

        $dLat = $lat2 - $lat1;
        $dLng = $lng2 - $lng1;

        $a = sin($dLat / 2) ** 2 +
            cos($lat1) * cos($lat2) * sin($dLng / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c; // distance in KM
    }

    private function calculateDistanceFromGoogle($pickup, $drop)
    {
        $apiKey = env('GOOGLE_MAPS_API_KEY');
        $pickupEncoded = urlencode($pickup);
        $dropEncoded = urlencode($drop);

        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins={$pickupEncoded}&destinations={$dropEncoded}&key={$apiKey}";
        $response = @file_get_contents($url);

        if ($response === false) {
            Log::error("Google Maps API call failed for URL: " . $url);
            return 0;
        }

        $data = json_decode($response, true);
        if (!empty($data['rows'][0]['elements'][0]['distance']['value'])) {
            return $data['rows'][0]['elements'][0]['distance']['value'] / 1000; // meters to km
        }

        return 0;
    }

    private function getCftRate(float $product_cft): array
    {
        $rateInfo = CFTModel::where('from_cft', '<=', $product_cft)
            ->where('to_cft', '>=', $product_cft)
            ->first();

        if (!$rateInfo) {
            return [
                'cft_id'        => null,
                'cft_rate'      => 0,
                'cft_profit'    => 0,
                'total_cost'    => 0,
                'total_amount'  => 0,
                'rate_type'     => 'N/A'
            ];
        }

        $rate     = (float) $rateInfo->cft_rate;
        $profit  = (float) $rateInfo->cft_profit;
        $rateRaw = (int) $rateInfo->rate_type;

        if ($rateRaw === 0) {
            $rateType    = 'FIXED';
            $totalAmount = $rate;
            $totalCost   = $rate + $profit;
        } elseif ($rateRaw === 1) {
            $rateType    = 'PER CFT';
            $totalAmount = $rate * $product_cft;
            $totalCost   = $totalAmount + $profit;
        } else {
            Log::error('INVALID CFT RATE TYPE', ['id' => $rateInfo->id]);
            return [
                'cft_id' => null,
                'cft_rate' => 0,
                'cft_profit' => 0,
                'total_cost' => 0,
                'total_amount' => 0,
                'rate_type' => 'INVALID'
            ];
        }

        return [
            'cft_id'        => $rateInfo->id,   // ✅ REAL CFT ID
            'cft_rate'      => $rate,
            'cft_profit'    => $profit,
            'total_cost'    => round($totalCost, 2),
            'total_amount'  => round($totalAmount, 2),
            'rate_type'     => $rateType
        ];
    }

    private function getKmRate(float $km_distance, int $cft_id): array
    {
        $km = (int) ceil($km_distance);

        $rateInfo = DB::table('km_rate_tb')
            ->where('cft_id', $cft_id)
            ->where('from_km', '<=', $km)
            ->where('to_km', '>=', $km)
            ->first();

        if (!$rateInfo) {
            Log::warning('KM SLAB NOT FOUND', compact('km', 'cft_id'));
            return [
                'km_rate'       => 0,
                'km_profit'     => 0,
                'total_km_cost' => 0,
                'rate_type'     => 'N/A'
            ];
        }

        $rate    = (float) $rateInfo->km_rate;
        $profit  = (float) $rateInfo->km_profit;
        $rateRaw = (int) $rateInfo->rate_type;

        if ($rateRaw === 0) {
            $rateType    = 'FIXED';
            $amount      = $rate;
        } elseif ($rateRaw === 1) {
            $rateType    = 'PER KM';
            $amount      = $rate * $km;
        } else {
            Log::error('INVALID KM RATE TYPE', ['id' => $rateInfo->id]);
            return [
                'km_rate' => 0,
                'km_profit' => 0,
                'total_km_cost' => 0,
                'rate_type' => 'INVALID'
            ];
        }

        return [
            'km_rate'       => $rate,
            'km_profit'     => $profit,
            'total_km_cost' => round($amount + $profit, 2),
            'rate_type'     => $rateType
        ];
    }

    public function paymentSuccess(Request $request)
    {
        $payment = Payment::where('razorpay_order_id', $request->razorpay_order_id)->firstOrFail();

        $payment->update([
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'status' => 'paid'
        ]);

        return response()->json(['status' => true]);
    }

    public function storea(Request $request)
    {
        UserPayment::create([
            'user_enquiry_id' => $request->enquiry_id,
            'amount'          => UserEnquiry::find($request->enquiry_id)->total_amount,
            'method'          => 'razorpay',
            'status'          => 'paid',
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_order_id'   => $request->razorpay_order_id,
        ]);

        return response()->json(['status' => true]);
    }

    public function index()
    {
        $enquiries = UserEnquiry::with([
                'category',
                'subCategory',
                'services',
                'productSubcategories',
                'products',
                'latestPayment'
            ])
            ->whereNull('service_name') // exclude rows where service_name is NOT null
            ->latest()
            ->get();

            // dd($enquiries);

        return view('admin.enquiries.index', compact('enquiries'));
    }
    public function servicesMethod()
    {
        $enquiries = UserEnquiry::with(['category', 'subCategory'])
            ->whereNotNull('service_name')
            ->latest()
            ->get();

        return view('admin.enquiries.services', compact('enquiries'));
    }


    public function destroy($id)
    {
        $enquiry = UserEnquiry::findOrFail($id);

        // 🔒 Optional safety: prevent delete if payment is paid
        if ($enquiry->latestPayment && $enquiry->latestPayment->status === 'paid') {
            return redirect()->back()->with('error', 'Paid enquiry cannot be deleted');
        }

        // ✅ Delete related payments first
        UserPayment::where('user_enquiry_id', $enquiry->id)->delete();

        // ✅ Detach products if pivot table exists
        if (method_exists($enquiry, 'products')) {
            $enquiry->products()->detach();
        }

        // ✅ Delete enquiry
        $enquiry->delete();

        return redirect()->back()->with('success', 'Enquiry deleted successfully');
    }
}

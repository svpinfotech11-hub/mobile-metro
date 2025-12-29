<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\CFTModel;
use App\Models\SubCategory;
use App\Models\UserEnquiry;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class UserEnquiryController extends Controller
{

    public function store(Request $request)
    {
        // dd($request->all());

        try {

            $baseRules = [
                'category_id'     => 'required|integer',
                'sub_category_id' => 'required|integer',
            ];

            $request->validate($baseRules);

            /* -----------------------------
           DETERMINE SERVICE TYPE
        ----------------------------- */
            $subCategory = SubCategory::findOrFail($request->sub_category_id);
            $type = (int) $subCategory->sub_category_service;

            /* -----------------------------
           DYNAMIC RULES
        ----------------------------- */
            $rules = [];

            switch ($type) {

                // ========= TYPE 1 (Packers & Movers)
                case 1:
                    $rules = [
                        'service_id'             => 'required|integer',
                        'product_subcategory_id' => 'required|integer',
                        'products'               => 'required|array|min:1',
                        'products.*'             => 'integer',
                        'pickup_location'        => 'required|string',
                        'drop_location'          => 'required|string',
                        'service_date'           => 'required|date',
                        'floor_number'           => 'nullable|string',
                        'lift_available'         => 'nullable|in:yes,no',
                    ];
                    break;

                // ========= TYPE 0 (Simple Service)
                case 0:
                    $rules = [
                        'service_name'     => 'required|string',
                        'service_location' => 'required|string',
                        'service_date'     => 'required|date',
                        'service_description' => 'nullable|string',
                    ];
                    break;

                // ========= TYPE 2
                case 2:
                    $rules = [
                        'pickup_location' => 'required|string',
                        'drop_location'   => 'required|string',
                        'service_date'    => 'required|date',
                    ];
                    break;

                // ========= TYPE 3 (Vehicle Based)
                case 3:
                    $rules = [
                        'vehicle_number'  => 'required|string',
                        'service_date'    => 'required|date',
                    ];
                    break;
            }

            /* -----------------------------
           VALIDATE FINAL RULES
        ----------------------------- */
            $validated = $request->validate($rules);

            /* -----------------------------
           SAVE ENQUIRY
        ----------------------------- */
            $enquiry = UserEnquiry::create([
                'category_id'       => $request->category_id,
                'sub_category_id'   => $request->sub_category_id,
                'service_id'        => $request->service_id ?? null,
                'service_name'      => $request->service_name ?? null,
                'service_date'      => $request->service_date ?? null,
                'service_location'      => $request->service_location ?? null,
                'pickup_location'   => $request->pickup_location ?? null,
                'drop_location'     => $request->drop_location ?? null,
                'floor_number'      => $request->floor_number ?? null,
                'lift_available'    => $request->lift_available ?? null,
                'vehicle_number'    => $request->vehicle_number ?? null,
                'service_description' => $request->service_description ?? null,
                'product_subcategory_id' => $request->product_subcategory_id ?? null, // <-- add this
            ]);

            /* -----------------------------
           SYNC PRODUCTS (TYPE 1)
        ----------------------------- */
            // if ($type === 1 && $request->filled('products')) {
            //     $enquiry->products()->sync($request->products);
            // }

            /* -----------------------------
            TYPE 1 CALCULATIONS (Packers & Movers)
            ----------------------------- */
            // if ($type === 1 && $request->filled('products')) {

            //     $products = ProductModel::whereIn('product_id', $request->products)->get();

            //     $totalCft = $products->sum('product_cft');

            //     $cftRate = $this->getCftRate($totalCft);

            //     // ❗ VERY IMPORTANT
            //     if (empty($cftRate['cft_id']) || $cftRate['cft_id'] <= 0) {
            //         throw ValidationException::withMessages([
            //             'products' => 'Pricing not available for selected product volume.'
            //         ]);
            //     }

            //     $kmDistance = $this->calculateDistanceFromGoogle(
            //         $request->pickup_location,
            //         $request->drop_location
            //     );

            //     if ($kmDistance <= 0) {
            //         $pickup = $this->getLatLngFromGoogle($request->pickup_location);
            //         $drop   = $this->getLatLngFromGoogle($request->drop_location);

            //         if ($pickup['lat'] && $drop['lat']) {
            //             $kmDistance = $this->calculateDistanceForService(
            //                 $pickup['lat'],
            //                 $pickup['lng'],
            //                 $drop['lat'],
            //                 $drop['lng']
            //             );
            //         }
            //     }

            //     // $kmRate = $this->getKmRate(
            //     //     $kmDistance,
            //     //     (int) $cftRate['cft_id']
            //     // );

            //     $kmRate = $this->getKmRate(
            //     $kmDistance,
            //     $cftRate['cft_id'] ?? null
            //     );

            //     $totalAmount =
            //         $cftRate['total_amount'] +
            //         $kmRate['total_km_cost'];

            //     $enquiry->update([
            //         'km_distance'  => round($kmDistance, 2),
            //         'km_rate'      => $kmRate['km_rate'],
            //         'km_cost'      => $kmRate['total_km_cost'],
            //         'km_profit'    => $kmRate['km_profit'],
            //         'total_amount' => round($totalAmount, 2),

            //         'products_item' => $products->map(fn($p) => [
            //             'product_id' => $p->product_id,
            //             'name'       => $p->product_name,
            //             'cft'        => $p->product_cft,
            //         ])->values(),
            //     ]);

            //     $enquiry->products()->sync($request->products);
            // }



            if ($type === 1 && $request->filled('products')) {

    $products = ProductModel::whereIn('product_id', $request->products)->get();

    $totalCft = $products->sum('product_cft');

    // 🔹 Try to get CFT slab (may be NULL for Domestic)
    $cftRate = $this->getCftRate($totalCft);

    // 🔹 Distance calculation
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

    /* -----------------------------
       KM PRICING (ONLY IF CFT SLAB EXISTS)
    ----------------------------- */
    $kmRate = [
        'km_rate' => 0,
        'total_km_cost' => 0,
        'km_profit' => 0
    ];

    if (!empty($cftRate['cft_id'])) {
        $kmRate = $this->getKmRate(
            $kmDistance,
            (int) $cftRate['cft_id']
        );
    }

    $totalAmount =
        ($cftRate['total_amount'] ?? 0) +
        ($kmRate['total_km_cost'] ?? 0);

    /* -----------------------------
       UPDATE ENQUIRY
    ----------------------------- */
    $enquiry->update([
        'km_distance'  => round($kmDistance, 2),
        'km_rate'      => $kmRate['km_rate'] ?? 0,
        'km_cost'      => $kmRate['total_km_cost'] ?? 0,
        'km_profit'    => $kmRate['km_profit'] ?? 0,
        'total_amount' => round($totalAmount, 2),

        'products_item' => $products->map(fn ($p) => [
            'product_id' => $p->product_id,
            'name'       => $p->product_name,
            'cft'        => $p->product_cft,
        ])->values(),
    ]);

    // 🔹 Pivot sync
    $enquiry->products()->sync($request->products);
}


            return response()->json([
                'status'  => true,
                'message' => 'Enquiry submitted successfully',
                'data'    => $enquiry
            ]);
        } catch (ValidationException $e) {

            return response()->json([
                'status' => false,
                'errors' => $e->errors()
            ], 422);
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

}

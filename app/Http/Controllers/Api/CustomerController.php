<?php

namespace App\Http\Controllers\Api;

use App\Services\OtpService;
use Illuminate\Http\Request;
use App\Models\CustomerModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;


class CustomerController extends Controller
{
    public function sendotp(Request $request, $mobile, otpService $otpService)
    {

        if (!preg_match('/^[0-9]{10}$/', $mobile)) {
            return response()->json(['status' => false, 'msg' => 'Invalid mobile number'], 422);
        }

        $customer = CustomerModel::where('mobile_no', $request->mobile_no)->first();

         if ($customer->is_registered ) {
               return response()->json(['status' => false, 'message' => 'Already registered. Please login.'], 409);
        }
        
        $otp = rand(100000, 999999);
        // dd($otp);
        $cacheotp12  =  Cache::put('otp_' . $mobile, $otp, now()->addMinutes(5));

        //   if (!$cacheotp12) {
        //         return response()->json([
        //             'status' => false,
        //             'msg'    => 'OTP has expired. Please request a new one.'
        //         ], 400);
        //     }

        // Send OTP via service
        $otpService->SendOtp($mobile, $otp);

        return response()->json([
            'status' => true,
            'msg' => 'OTP sent successfully',
            'otp' => $otp
        ], 200);
    }

    public function verifyotp(Request $request, $mobile)
    {

        $request->validate([
            'otp' => 'required|digits:6'
        ]);

        $cacheotp = Cache::get('otp_' . $mobile);
        if (!$cacheotp || $cacheotp != $request->otp) {
            return response()->json([
                'status' => false,
                'msg' => 'Invalid OTP'
            ], 400);
        }

        $customer = CustomerModel::firstorCreate(
            ['mobile_no' => $mobile],
            ['is_verified' => true]
        );
        $customer->update(['is_verified' => true]);

        Cache::forget('otp_' . $mobile);


        return response()->json([
            'status' => true,
            'msg' => 'OTP verified successfully',
            'customer_id' => $customer->id
        ], 200);
    }

    public function store(Request $request)
    {
    // dd($request->all());
        $request->validate([
            'mobile_no' => 'required|digits:10|exists:tbl_customer,mobile_no',
            'customer_name' => 'required|string',
            'email' => 'nullable|email',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'pincode' => 'nullable|string',
            'password' => 'nullable|string',
        ]);

        $customer = CustomerModel::where('mobile_no', $request->mobile_no)->first();

        if (!$customer || !$customer->is_verified) {
            return response()->json(['status' => false, 'message' => 'Please verify OTP first'], 403);
        }

         if ($customer->is_registered ) {
               return response()->json(['status' => false, 'message' => 'Already registered. Please login.'], 409);
        }

        $password =  Hash::make($request->password);

        $customer->update([
            'customer_name' => $request->customer_name,
            'email' => $request->email,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $request->pincode,
            'password' => $password,
            'is_registered' => true
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Registration successful',
            'data' => $customer
        ], 201);
    }

    public function get_customer(Request $request)
    {

        $customer = CustomerModel::where('id', $request->customer_id)->first();
        if (!$customer) {
            return response()->json(['status' => false, 'message' => 'Account not found', 'data' => []], 404);
        } else {
            return response()->json(['status' => true, 'message' => 'Customer Detail', 'data' => $customer], 200);
        }
    }

    //   public function login(Request $request, otpService $otpService) {
    //             $request->validate([
    //                 'mobile_no' => 'required|digits:10',
    //             ]);

    //             $customer = CustomerModel::where('mobile_no', $request->mobile_no)->first();

    //             if (!$customer) {
    //                 return response()->json(['status' => false, 'message' => 'Account not found'], 404);
    //             }

    //             if (!$customer->is_verified) {
    //                 return response()->json(['status' => false, 'message' => 'Account not verified'], 403);
    //             }

    //             // Call sendotp correctly
    //             $response = $this->sendotp($request, $request->mobile_no, $otpService);
    //             if ($response->getStatusCode() !== 200) {
    //                 return $response; // return error if OTP sending failed
    //             }

    //             return response()->json([
    //                 'status' => true,
    //                 'message' => 'OTP sent successfully for login!',
    //                 'data' => $customer
    //             ]);
    //         }


    public function login(Request $request, otpService $otpService)
    {
        $request->validate([
            'mobile_no' => 'required|digits:10',
        ]);

        // Check if customer exists
        $customer = CustomerModel::where('mobile_no', $request->mobile_no)->first();

        if ($customer && $customer->is_blocked) {
            return response()->json([
                'status' => false,
                'message' => 'Your account has been blocked. Please contact support.'
            ], 403); // 403 Forbidden
        }

        // 1️⃣ If no record found → force register first
        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => 'Mobile number not found. Please register first before login.'
            ], 404);
        }

        // 2️⃣ If not verified → block until verification
        if (!$customer->is_verified) {
            return response()->json([
                'status' => false,
                'message' => 'Account not verified. Please verify OTP before login.'
            ], 403);
        }

        // 3️⃣ If verified → send OTP for login
        $response = $this->sendotp($request, $request->mobile_no, $otpService);

        if ($response->getStatusCode() !== 200) {
            return $response; // handle OTP failure
        }

        // 4️⃣ Success response
        return response()->json([
            'status' => true,
            'message' => 'OTP sent successfully for login!',
            'data' => $customer
        ]);
    }



    public function getCustomerById($customer_id)
    {
        // Fetch customer by ID
        $customer = CustomerModel::select('id', 'customer_name as customer_name', 'email', 'pincode', 'city', 'state', 'mobile_no')
            ->where('id', $customer_id)
            ->first();

        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => 'Customer not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $customer
        ]);
    }
}

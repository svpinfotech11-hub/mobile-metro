<?php

namespace App\Http\Controllers\Api;

use Razorpay\Api\Api;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function initiate(Request $request)
    {
        $request->validate([
            'order_no' => 'required|string|exists:tbl_enquiry,order_no',
        ]);

        // Find enquiry
        $enquiry = DB::table('tbl_enquiry')->where('order_no', $request->order_no)->first();

        if (!$enquiry) {
            return response()->json([
                'status' => false,
                'message' => 'Enquiry not found.'
            ], 404);
        }

        // Calculate 10% amount
        $paymentAmount = round($enquiry->total_amount * 0.10, 2); // 10%

        // $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );
        $razorpayAmount = $paymentAmount * 100; // Razorpay expects paise
        // Create Razorpay order
        $razorpayOrder = $api->order->create([
            'receipt' => 'RCPT-' . strtoupper(Str::random(8)),
            'amount' => $razorpayAmount,
            'currency' => 'INR',
            'payment_capture' => 1
        ]);

        // QR Code link
        // $qr_code_url = "https://api.qrserver.com/v1/create-qr-code/?data={$razorpayOrder['id']}&size=250x250";

        // Insert record
        DB::table('tbl_payments')->insert([
            'enquiry_id' => $enquiry->id,
            'order_no' => $enquiry->order_no,
            'customer_id' => $enquiry->customer_id,
            'razorpay_order_id' => $razorpayOrder['id'],
            // 'razorpay_order' => $razorpayOrder->toArray(),
            'amount' => $paymentAmount,  // 10% stored
            'total_amount' => $enquiry->total_amount, // ⭐ important
            // 'qr_code_url' => $qr_code_url,
            'payment_status' => 'pending',
            'created_at' => now(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Payment initiated for 10% amount.',
            'data' => [
                'order_no' => $enquiry->order_no,
                'customer_id' => $enquiry->customer_id,
                'razorpay_order_id' => $razorpayOrder['id'],
                'amount' => $paymentAmount,   // 10%
                'total_amount' => $enquiry->total_amount,
                'currency' => 'INR',
                // 'qr_code_url' => $qr_code_url,
                'razorpay_key' => config('services.razorpay.key')
            ]
        ]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id'   => 'required|string',
            'razorpay_signature'  => 'required|string',
        ]);

        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );

        // STEP 1: Signature Verification
        try {
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id'   => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid payment signature'
            ], 400);
        }

        // STEP 2: Fetch Payment Details from Razorpay
        $paymentDetails = $api->payment->fetch($request->razorpay_payment_id);

        $paymentMethod = $paymentDetails->method ?? null;

        // Razorpay returns timestamp → convert to datetime
        $paymentTime = isset($paymentDetails->created_at)
            ? date('Y-m-d H:i:s', $paymentDetails->created_at)
            : now();

        // STEP 3: Fetch existing payment record from DB
        $paymentRecord = DB::table('tbl_payments')
            ->where('razorpay_order_id', $request->razorpay_order_id)
            ->first();

        if (!$paymentRecord) {
            return response()->json([
                'status' => false,
                'message' => 'Payment record not found'
            ], 404);
        }

        // Calculate remaining amount
        $paidAmount = $paymentRecord->amount;
        $totalAmount = $paymentRecord->total_amount;
        $remainingAmount = $totalAmount - $paidAmount;

        // STEP 4: Update final payment details
        DB::table('tbl_payments')
            ->where('razorpay_order_id', $request->razorpay_order_id)
            ->update([
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'payment_status'      => 'success',
                'payment_method'      => $paymentMethod,
                'payment_date'        => $paymentTime,
                'remaining_amount'    => $remainingAmount,
                'updated_at'          => now(),
            ]);

        // STEP 5: Response
        return response()->json([
            'status' => true,
            'message' => 'Payment verified successfully',
            'data' => [
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'payment_method'      => $paymentMethod,
                'payment_date'        => $paymentTime,
                'total_amount'        => $totalAmount,
                'paid_amount'         => $paidAmount,
                'remaining_amount'    => $remainingAmount
            ]
        ]);
    }


    // // STEP 3: Check Payment Status (Mobile calls this)
    public function status($order_no)
    {
        $payment = DB::table('tbl_payments')->where('order_no', $order_no)->first();

        if (!$payment) {
            return response()->json([
                'status' => false,
                'message' => 'Payment not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'order_no' => $payment->order_no,
                'amount' => $payment->amount,
                'payment_status' => $payment->payment_status,
                'razorpay_payment_id' => $payment->razorpay_payment_id,
                'qr_code_url' => $payment->qr_code_url,
            ]
        ]);
    }
}

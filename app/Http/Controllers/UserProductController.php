<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\ProductModel;
use App\Models\SubCategory;
use App\Models\ServiceModel;
use app\Services\OtpService;
use Illuminate\Http\Request;
use App\Models\ProductSubCategory;

class UserProductController extends Controller
{
    public function create()
    {
        return view('pages.users.create');
    }

    public function store(Request $request, OtpService $otpService)
    {
        $request->validate([
            'name'  => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|unique:users',
            'status' => 'required'
        ]);

        $otp = rand(100000, 999999);

        $user = User::create([
            'name'            => $request->name,
            'email'           => $request->email,
            'phone'           => $request->phone,
            'status'          => $request->status,
            'otp'             => $otp,
            'otp_expires_at'  => now()->addMinutes(5),
            'is_verified'     => false,
            'role'            => 'user'
        ]);

        $otpServiceRes = $otpService->sendOtpphone($user->phone, $otp);

        if (!$otpServiceRes['success']) {
            return back()->with('error', 'OTP could not be sent. Please try again.');
        }
        return redirect()->route('pages.users.otp', $user->id)->with('success', 'OTP Sent SuccessFully!');
    }

    public function showOtpForm($id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'otp' => '123456',
            'otp_expires_at' => now()->addMinutes(30),
        ]);

        return view('pages.users.otp', compact('user'));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'otp' => 'required|digits:6'
        ]);

        // $user = User::findOrFail($id);
        $user = User::findOrFail($request->user_id);


        if ($user->otp !== $request->otp) {
            return back()->with('error', 'Invalid OTP');
        }

        if (now()->gt($user->otp_expires_at)) {
            return back()->with('error', 'OTP expired');
        }

        $user->update([
            'is_verified'    => true,
            'otp'            => null,
            'otp_expires_at' => null
        ]);

        return redirect()
            ->route('pages.users.form')
            ->with('success', 'OTP verified successfully');
    }

    public function getSubCategories($categoryId)
    {
        $subCategories = SubCategory::where('category_id', $categoryId)->get();

        return response()->json($subCategories);
    }

    public function getServicessss(Request $request)
    {

        $services = ServiceModel::where('category_id', $request->category_id)
            ->where('subCategory_id', $request->sub_category_id)
            ->get(['id', 'service_name']);

        return response()->json($services);
    }

    // public function getProductSubCategories(Request $request)
    // {
    //     if (!$request->service_id) {
    //         return response()->json([]);
    //     }

    //     $subCategories = ProductSubCategory::where('service_id', $request->service_id)
    //         ->where('status', 1)
    //         ->get(['id', 'subcat_name']);

    //     return response()->json($subCategories);
    // }

    public function getProductSubCategories(Request $request)
    {
        if (!$request->filled('service_ids')) {
            return response()->json([]);
        }

        $subCategories = ProductSubCategory::whereIn(
                'service_id',
                (array) $request->service_ids
            )
            ->where('status', 1)
            ->select('id', 'subcat_name')
            ->distinct()
            ->get();

        return response()->json($subCategories);
    }


    // public function getProducts(Request $request){

    //     $products = ProductModel::where('service_id', $request->service_id)
    //     ->where('product_subcat_id', $request->product_subcat_id)
    //     ->where('status', 1)
    //     ->get([
    //         'product_id as id',
    //         'product_name as text', 
    //         'product_cft'
    //     ]);

    //         return response()->json($products);
    // }

    public function getProducts(Request $request)
    {
        if (!$request->filled('product_subcat_ids')) {
            return response()->json([]);
        }

        $products = ProductModel::whereIn(
                'product_subcat_id',
                (array) $request->product_subcat_ids
            )
            ->where('status', 1)
            ->select(
                'product_id as id',
                'product_name as text',
                'product_cft'
            )
            ->get();

        return response()->json($products);
    }


    public function getSubCategoryDetail($id)
    {
        return SubCategory::select('id', 'sub_category_service')
            ->where('id', $id)
            ->first();
    }

}

<?php

namespace App\Http\Controllers\Api;

use App\Models\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'business_name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'mobile_no' => 'required|digits:10|unique:vendors,mobile_no',
            'email' => 'nullable|email|max:255',
            'business_type' => 'required|array',
            'business_type.*' => 'string',
            'business_description' => 'nullable|string',
            'experience_years' => 'nullable|integer|min:0',
            'service_areas' => 'nullable|string',
        ]);
        // if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                // 'errors' => $validator->errors()
            ], 422);
        }

        // create vendor
        $vendor = Vendor::create([
            'full_name' => $request->full_name,
            'business_name' => $request->business_name,
            'address' => $request->address,
            'mobile_no' => $request->mobile_no,
            'email' => $request->email,
            'business_type' => json_encode($request->business_type),
            'business_description' => $request->business_description,
            'experience_years' => $request->experience_years,
            'service_areas' => $request->service_areas,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Vendor registered successfully!',
            'data' => $vendor
        ], 201);
    }

        public function get_vendors(Request $request)
    {
        $query = Vendor::query();

        // Filter by full_name
        if ($request->filled('full_name')) {
            $query->where('full_name', 'like', '%' . $request->full_name . '%');
        }

        // Filter by email
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        // Filter by mobile number
        if ($request->filled('mobile_no')) {
            $query->where('mobile_no', 'like', '%' . $request->mobile_no . '%');
        }

        // Filter by created_at
        if ($request->filled('created_at')) {
            $query->whereDate('created_at', $request->created_at);
        }

        // ⭐ Correct: apply ordering + keep filters in pagination
        $records = $query->orderByDesc('id')->paginate(10)->appends($request->query());

        return view('admin.vendors.get-vendor', compact('records'));
    }


    public function destroy($id){
        $record = Vendor::findOrFail($id);
        $record->delete();
        return redirect()->back()->with('success', 'Record Deleted SuccessFully!');
    }
}

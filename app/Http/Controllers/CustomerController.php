<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerModel;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    // public function get_customer(Request $request){
    //     $title ='CUSTOMER LIST';
    //     // $customer = CustomerModel::all();
    //     $query = CustomerModel::query();
        
    //     if($request->filled('customer_name')){
    //         $query = $query->where('customer_name', 'like', '%'.$request->customer_name. '%');
    //     }
    //      $query->orderBy('tbl_customer.id', 'desc');
    //     $customer = $query->paginate(10)->appends($request->query());
    //     // dd($customer);
    //     return view('admin.customer_list',compact('customer','title'));
    // }

    public function get_customer(Request $request)
    {
        $title = 'CUSTOMER LIST';

        $query = CustomerModel::query();

        // Filter by customer name
        if ($request->filled('customer_name')) {
            $query->where('customer_name', 'like', '%' . $request->customer_name . '%');
        }

        // ⭐ Filter by email
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

            // ⭐ Filter by mobile number
        if ($request->filled('mobile_no')) {
            $query->where('mobile_no', 'like', '%' . $request->mobile_no . '%');
        }

         // ⭐ Filter by created_at date
        if ($request->filled('created_at')) {
            $query->whereDate('created_at', $request->created_at);
        }

        // Order
        $query->orderBy('tbl_customer.id', 'desc');

        // Pagination with query parameters preserved
        $customer = $query->paginate(10)->appends($request->query());

        return view('admin.customer_list', compact('customer', 'title'));
    }


     public function destroy($id)
    {
        $customer = DB::table('tbl_customer')->where('id', $id)->first();
        if ($customer) {
            DB::table('tbl_customer')->where('id', $customer->id)->delete();
        }
        return redirect()->back()->with('success', 'Customer deleted successfully.');
    }

    public function block($id)
    {
        DB::table('tbl_customer')->where('id', $id)->update(['is_blocked' => true]);
        return redirect()->back()->with('success', 'User has been blocked.');
    }

    public function unblock($id)
    {
        DB::table('tbl_customer')->where('id', $id)->update(['is_blocked' => false]);
        return redirect()->back()->with('success', 'User has been unblocked.');
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CustomerModel;
use App\Models\EnquiryModel;
use App\Models\ProductModel;
use App\Models\ServiceModel;
use App\Models\Subcategory;


class AdminDashboardController extends Controller
{
    // public function index(){
    //   return view('admin.dashboard') ;
    // }
    public function index() {
    // Fetch counts from database
    $totalEnquiries     = EnquiryModel::count();
    $totalCustomers     = CustomerModel::count();
    $totalProducts      = ProductModel::count();
    $totalServices      = ServiceModel::count();
    $totalCategories    = Category::count();
    $totalSubcategories = Subcategory::count();

    // Pass counts to dashboard view
    return view('admin.dashboard', compact(
        'totalEnquiries', 
        'totalCustomers', 
        'totalProducts', 
        'totalServices', 
        'totalCategories', 
        'totalSubcategories'
    ));
}
}




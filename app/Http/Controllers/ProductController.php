<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProductModel;
use App\Models\ServiceModel;
use App\Models\CFTModel;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     $title = 'ADD INVENTORY';
    //     $product_list_tit = 'INVENTORY WISE SERVICE LIST';
    //     $all_product = DB::table('tbl_product as p')
    //                  ->join('tbl_services as s', 'p.service_id', '=', 's.id')
    //                  ->select(
    //                     // 'p.product_name',
    //                     // 'p.product_cft',
    //                     // 'p.id',
    //                     's.service_name',
    //                     'p.service_id',
    //                       DB::raw('COUNT(p.product_id) as total_product')
    //                  )
                   
    //                   ->groupBy('p.service_id', 's.service_name')
    //                  ->get();
    //  $services = ServiceModel::select('id', 'service_name')
    //         ->groupBy('id', 'service_name') 
    //         ->get();

    //     return view('admin.product.add-product',compact('all_product','title','product_list_tit','services'));
    // }

    public function create()
    {
        $title = 'ADD INVENTORY';
        $product_list_tit = 'INVENTORY WISE SERVICE LIST';

        // $all_product = DB::table('tbl_product as p')
        //     ->join('tbl_services as s', 'p.service_id', '=', 's.id')
        //     ->join('sub_category_tbl as sc', 's.subCategory_id', '=', 'sc.id') // join subcategory
        //     ->select(
        //         's.service_name',
        //         'sc.sub_categoryname', // fetch subcategory
        //         'p.service_id',
        //         DB::raw('COUNT(p.product_id) as total_product')
        //     )
        //     ->groupBy('p.service_id', 's.service_name', 'sc.sub_categoryname')
        //     ->get();

        $all_product = DB::table('tbl_product as p')
        ->join('tbl_services as s', 'p.service_id', '=', 's.id')
        ->join('sub_category_tbl as sc', 's.subCategory_id', '=', 'sc.id') 
        ->join('tbl_product_subcategory as psc', 'p.product_subcat_id', '=', 'psc.id') 
        ->select(
            's.service_name',
            'sc.sub_categoryname as service_subcategory',
            'psc.subcat_name as product_subcategory',
            'p.service_id',
            DB::raw('COUNT(p.product_id) as total_product')
        )
        ->groupBy(
            'p.service_id',
            's.service_name',
            'sc.sub_categoryname',
            'psc.subcat_name'
        )
        ->get();

        // dd($all_product);

        // $services = ServiceModel::select('id', 'service_name')
        //     ->groupBy('id', 'service_name') 
        //     ->get();
        $services = DB::table('tbl_services as s')
        ->join('sub_category_tbl as sc', 's.subCategory_id', '=', 'sc.id')
        ->select(
            's.id',
            's.service_name',
            'sc.sub_categoryname'
        )
        // ->orderBy('sc.sub_categoryname')
        // ->orderBy('s.service_name')
        ->get();


        return view('admin.product.add-product', compact('all_product','title','product_list_tit','services'));
        }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //        'service_id' => 'required',
    //        'product_name' => 'required',
    //        'product_cft' =>'required'
    //     ]);

    //     $data = $request->all();
    //     ProductModel::create($data);
    //     return redirect()->back()->with('success','PRODUCT ADDED SUCCESSFULLY!');
    // }

    public function store(Request $request)
    {
        $request->validate([
            'service_id'        => 'required|exists:tbl_services,id',
            'product_subcat_id' => 'required|exists:tbl_product_subcategory,id',
            'product_name'      => 'required|string|max:255',
            'product_cft'       => 'required|numeric'
        ]);

        ProductModel::create([
            'service_id'        => $request->service_id,
            'product_subcat_id' => $request->product_subcat_id,
            'product_name'      => $request->product_name,
            'product_cft'       => $request->product_cft,
        ]);

        return back()->with('success','PRODUCT ADDED SUCCESSFULLY!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         $product_list_tit = 'Inventory LIST';
         $products =   DB::table('tbl_product as p')
                        ->join('tbl_services as s', 'p.service_id', '=', 's.id')
                        ->join('tbl_product_subcategory as psc', 'p.product_subcat_id', '=', 'psc.id')                      
                        ->select(
                        'p.product_name',
                        'p.product_cft',
                        'p.product_id',
                        'p.service_id',
                        's.service_name',
                        'psc.subcat_name as product_subcategory'
                        )
                        ->where('p.service_id',$id)
                        ->get();
       $services = ServiceModel::all();
       
  // Add rate info per product
    foreach ($products as $p) {
        $rateData = $this->getCftRate($p->product_cft);
        $p->cft_rate = $rateData['cft_rate'];
        $p->cft_profit = $rateData['cft_profit'];
        $p->rate_type = $rateData['rate_type'];
        $p->total_cost = $rateData['total_cost'];
    }

        return view('admin.product.service_wise_product',compact('products','product_list_tit','services'));            
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
    public function update(Request $request, $product_id)
    {
       $request->validate([
            'service_id'=>'required',
            'product_name'=>'required',
            'product_cft'=>'required | numeric',
            
            ]);
            
            // dd($request->all());
            
        $product = ProductModel::findOrFail($product_id);
        $product->service_id = $request->input('service_id');
        $product->product_name = $request->input('product_name');
        $product->product_cft = $request->input('product_cft');
        
        $product->save();
        return redirect()->back()->with('success', 'Product updated successfully!');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = ProductModel::findOrFail($id);
        $data->delete();
        return redirect()->back()->with('success','PRODUCT DELETED!');
    }
    
  private function getCftRate($product_cft)
{
    $rateInfo = CFTModel::where('from_cft', '<=', $product_cft)
        ->where('to_cft', '>=', $product_cft)
        ->first();

    if (!$rateInfo) {
        return [
            'cft_rate' => 0,
            'cft_profit' => 0,
            'total_cost' => 0,
            'rate_type' => '-'
        ];
    }

    $rate = $rateInfo->cft_rate;
    $profit = $rateInfo->cft_profit;
    $rateType = $rateInfo->rate_type == 0 ? 'fixed' : 'per box';

    // ✅ Different calculation for each type
    if ($rateInfo->rate_type == 0) {
        $totalCost = $rate + $profit;
    } else {
        $totalCost = ($rate * $product_cft) + $profit;
    }

    return [
        'cft_rate' => $rate,
        'cft_profit' => $profit,
        'total_cost' => $totalCost,
        'rate_type' => $rateType
    ];
}


public function total(){
    return view('total-cft');
}


public function getSubcategory($service_id)
{
    // 1. Get service row
    $service = DB::table('tbl_services')
        ->where('id', $service_id)
        ->first();

    if(!$service){
        return response()->json(['subcategory' => null]);
    }

    // 2. Get subcategory based on service.subCategory_id
    $subcategory = DB::table('sub_category_tbl')
        ->where('id', $service->subCategory_id)
        ->value('sub_categoryname');  // returns string

    return response()->json(['subcategory' => $subcategory]);
}


}

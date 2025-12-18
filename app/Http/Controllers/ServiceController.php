<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ServiceModel;
use App\Models\Category;
use App\Models\SubCategory;

class ServiceController extends Controller
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
    public function create()
    {
        $title = 'ADD SERVICES';
        $service_list_tit = 'SERVICES LIST';
        $all_category = Category::all();     
        $all_service = $all_service = DB::table('tbl_services as s')
                    ->join('category_tbl as c', 's.category_id', '=', 'c.id')
                    ->join('sub_category_tbl as sc', 's.subcategory_id', '=', 'sc.id')
                    ->select(
                        's.subcategory_id',
                        'sc.sub_categoryname',
                        'c.name',
                        DB::raw('count(s.id) as total_service')
                    )
                    ->groupBy('sc.sub_categoryname','s.subcategory_id', 'c.name')
                    ->get();

// dd($all_service);
        return view('admin.service.add-service',compact('all_service','all_category','service_list_tit','title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        // $request->validate([
        //     'category_id'     => 'required',
        //     'subCategory_id' => 'required',
        //     'service_name'    => 'required'
        // ]);
         $request->validate([
        'category_id' => 'required|exists:category_tbl,id',
        'subCategory_id' => 'required|exists:sub_category_tbl,id',
        'service_name' => [
            'required',
            // unique per category + subcategory
            function($attribute, $value, $fail) use ($request) {
                $exists = DB::table('tbl_services')
                    ->where('category_id', $request->category_id)
                    ->where('subCategory_id', $request->subCategory_id)
                    ->where('service_name', $value)
                    ->exists();

                if ($exists) {
                    $fail('The service name already exists for this category and subcategory.');
                }
            }
        ]
    ]);
        
        $data = $request->all();
        $data['created_at'] = now();
        
        
        //  if($request->hasfile('service_banner_image')){
        //      $image = $request->file('service_banner_image');
        //      //creqte_unique_name
        //        $extension = $image->getClientOriginalExtension();  
        //      $filename = md5(uniqid().time()).'.'.$extension;
        //      $detinationPath = base_path('admin_assets/service_banner_image');
        //      if(!file_exists($detinationPath)){
        //          mkdir($detinationPath , 0777 , true);
        //      }
             
        //      $image->move($detinationPath ,$filename );
             
        //      $service_banner_image = $filename;
             
        //     //  dd($icon_image);
        //  }else{
        //    $service_banner_image = 'No Image Add';  
        //  }
         
        //   if($request->hasfile('service_icon_image')){
        //      $image = $request->file('service_icon_image');
        //      //creqte_unique_name
        //       $extension = $image->getClientOriginalExtension();  
        //      $filename = md5(uniqid().time()).'.'.$extension;
        //      $detinationPath = base_path('admin_assets/service_icon_image');
        //      if(!file_exists($detinationPath)){
        //          mkdir($detinationPath , 0777 , true);
        //      }
             
        //      $image->move($detinationPath ,$filename );
             
        //      $service_icon_image = $filename;
             
        //     //  dd($icon_image);
        //  }else{
        //   $service_icon_image = 'No Image Add';  
        //  }
        
        //  $data['service_icon_image'] = $service_icon_image;
        //  $data['service_banner_image'] = $service_banner_image;
        ServiceModel::create($data);
        return redirect()->back()->with('success','SERVICE ADDED SUCCESSFULLY!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         $service_list_tit = 'SERVICES LIST';
        $all_service = $all_service = DB::table('tbl_services as s')
                    ->join('category_tbl as c', 's.category_id', '=', 'c.id')
                    ->join('sub_category_tbl as sc', 's.subCategory_id', '=', 'sc.id')
                    ->select(
                        's.id',
                        's.category_id',
                        's.service_name',
                        's.service_desc',
                        's.service_banner_image',
                        's.service_icon_image',
                        's.subCategory_id',
                        's.status',
                        'c.name',
                        'sc.sub_categoryname'
                    )
                    ->where('s.subCategory_id',$id)
                    ->get();
                    // dd($all_service);

       $all_category = Category::all();   
        return view('admin.service.subcategory_wise_service',compact('all_service','service_list_tit','all_category'));
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
        $request->validate([
            
            'subCategory_id'=>'required',
            'category_id'=>'required',
            'service_name'=>'required',
            
        ]);
        
         if($request->hasfile('service_banner_image')){
             $image = $request->file('service_banner_image');
             //creqte_unique_name
               $extension = $image->getClientOriginalExtension();  
             $filename = md5(uniqid().time()).'.'.$extension;
             $detinationPath = base_path('admin_assets/service_banner_image');
             if(!file_exists($detinationPath)){
                 mkdir($detinationPath , 0777 , true);
             }
             
             $image->move($detinationPath ,$filename );
             
             $service_banner_image = $filename;
             
            //  dd($icon_image);
         }else{
           $service_banner_image = $request->service_banner_image;  
         }
         
        //   if($request->hasfile('service_icon_image')){
        //      $image = $request->file('service_icon_image');
        //      //creqte_unique_name
        //       $extension = $image->getClientOriginalExtension();  
        //      $filename = md5(uniqid().time()).'.'.$extension;
        //      $detinationPath = base_path('admin_assets/service_icon_image');
        //      if(!file_exists($detinationPath)){
        //          mkdir($detinationPath , 0777 , true);
        //      }
             
        //      $image->move($detinationPath ,$filename );
             
        //      $service_icon_image = $filename;
             
        //     //  dd($icon_image);
        //  }else{
        //   $service_icon_image = $request->service_banner_image;  
        //  }
         
            
        $service = ServiceModel::findOrfail($id);
        $service->subCategory_id = $request->subCategory_id;
        $service->category_id = $request->category_id;
        $service->service_name = $request->service_name;
        $service->service_desc = $request->service_desc;
        // $service->service_icon_image = $service_icon_image ;
        $service->service_banner_image = $service_banner_image;
        
        $service->save();
        return redirect()->route('app.admin-services.create')->with('success','Services Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $data = ServiceModel::findorfail($id);
       $data->delete();
       return redirect()->back()->with('success','SERVICES DATA DELETED!');
    }

   public function getSubcategories($id)
    {
        $subcategories = SubCategory::where('category_id', $id)->get();

        return response()->json($subcategories);
    }




}

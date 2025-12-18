<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\ServiceModel;
use App\Models\ProductModel;
use App\Models\BannerModel;
use Illuminate\Http\Request;

class MainApiController extends Controller
{
    public function get_category(){

        $category_data = Category::all();

        if($category_data->isEmpty()){
         return response()->json(['status'=>false,  'msg'=>'No categories found' , 'data' => []],400);
        }else{
         return response()->json(['status'=>true, 'msg'=>'Categories retrieved successfully' , 'data'=>$category_data],200);
        }
        
    }

    public function get_subcategory($category_id){
        // dd($category_id);
        $subcategory = DB::table('sub_category_tbl as sc')
        ->join('category_tbl as c', 'c.id' , '=' ,'sc.category_id')
        ->select(
            'sc.category_id',
            'sc.id',
            'sc.sub_category_service',
            'sc.sub_categoryname',
            'c.name as category_name',
            'sc.sub_category_desc',
            'sc.sub_icon_image',
            'sc.sub_banner_image',
        )
        ->where('sc.category_id',$category_id)
        ->get();

        if($subcategory->isEmpty()){
          return response()->json(['status'=>false , 'msg'=>'Sub Category Not Found','data'=>[]],400);
        }else{
             return response()->json(['status'=>true , 'msg'=>'Sub Categories retrieved successfully','data'=>$subcategory],200);
        }

    }

    // public function get_service($subCategory_id){
    //     $service_data = DB::table('tbl_services as sc')
    //     ->select('*')
    //     ->where('subCategory_id', $subCategory_id) 
    //     ->get();

    //   if($service_data->isEmpty()){
    //     return response()->json(['status'=>false,'msg'=>'Service Not Found','data'=>[]],400);
    //   }else{
    //     return response()->json(['status'=>true,'msg'=>'Service retrieved successfully','data'=>$service_data],200);
    //   }

    // }
    
        public function get_service($subCategory_id)
        {
            $services = ServiceModel::withCount('products')
                ->where('subCategory_id', $subCategory_id)
                ->having('products_count', '>', 0)
                ->get();
        
            return response()->json([
                'status' => true,
                'msg' => 'Service retrieved successfully',
                'data' => $services
            ]);
        }



    public function get_product($service_id){

       $product_data = DB::table('tbl_product')
        ->select('*')
        ->where('service_id', $service_id)
        ->get();
        
        if($product_data->isEmpty()){
            return response()->json(['status'=>false , 'msg'=>'Product Not Found' , 'data'=>$product_data],400);
        }else{
            return response()->json(['status'=>true , 'msg'=>' Product retrieved successfully' , 'data'=> $product_data],200);
        }

    }

    public function get_banner(){

        $banner_data = BannerModel::all();
        if($banner_data->isEmpty()){
            return response()->json(['status'=>false , 'msg' => 'Banner Not Found' , 'data'=>[]],400);
        }else{
            return response()->json(['status'=>true , 'msg'=>'Banner retrieved successfully' , 'data'=>$banner_data],200);
        }
    }

    
}

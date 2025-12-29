<?php

namespace App\Http\Controllers;

use App\Models\ServiceModel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\ProductSubCategory;
use Illuminate\Support\Facades\DB;

class ProductSubCategoryController extends Controller
{
    // public function index()
    // {
    //     $all_product = DB::table('tbl_product_subcategory')
    //         ->join('tbl_services', 'tbl_services.id', '=', 'tbl_product_subcategory.service_id')
    //         ->select(
    //             'tbl_product_subcategory.id',
    //             'tbl_product_subcategory.subcat_name',
    //             'tbl_services.service_name',
    //         )
    //         ->orderBy('tbl_product_subcategory.id', 'DESC')
    //         ->get();

    //     return view('admin.product_subcategory.index', compact('all_product'));
    // }
    public function index()
    {
        $all_product = DB::table('tbl_product_subcategory')
            ->join('tbl_services', 'tbl_services.id', '=', 'tbl_product_subcategory.service_id')
            ->join('tbl_category', 'tbl_category.id', '=', 'tbl_services.category_id')
            ->select(
                'tbl_product_subcategory.id',
                'tbl_product_subcategory.subcat_name',
                'tbl_services.service_name',
                'tbl_category.category_name'
            )
            ->orderBy('tbl_product_subcategory.id', 'DESC')
            ->get();

        return view('admin.product_subcategory.index', compact('all_product'));
    }


    public function create(Request $request)
    {
        $search = $request->input('search');
        $all_product = DB::table('tbl_product_subcategory')
            ->join('tbl_services', 'tbl_services.id', '=', 'tbl_product_subcategory.service_id')
            ->join('sub_category_tbl', 'sub_category_tbl.id', '=', 'tbl_services.subCategory_id')
            ->join('category_tbl', 'category_tbl.id', '=', 'tbl_services.category_id')
            ->select(
                'tbl_product_subcategory.id',
                'tbl_product_subcategory.subcat_name',
                'tbl_services.service_name',
                'sub_category_tbl.sub_categoryname as sub_category',
                'category_tbl.name'
            )
              ->when($search, function ($query, $search) {
            $query->where('tbl_services.service_name', 'LIKE', "%{$search}%");
        })
            ->orderBy('tbl_product_subcategory.id', 'DESC')
            ->get();

        // dd($all_product);

        $services = DB::table('tbl_services as s')
            ->join('sub_category_tbl as sc', 's.subCategory_id', '=', 'sc.id')
            ->select(
                's.id',
                's.service_name',
                'sc.sub_categoryname'
            )
            ->get();
        // dd($services);
        $productSubcategories = DB::table('tbl_product_subcategory')->get();
        return view('admin.product_subcategory.create', compact('services', 'all_product', 'productSubcategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:tbl_services,id',
            'subcat_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tbl_product_subcategory')->where(function ($query) use ($request) {
                    return $query->where('service_id', $request->service_id);
                }),
            ],
            'status' => 'required'
        ]);

        ProductSubCategory::create($request->all());

        return redirect()->route('admin.product_subcategory.create')
            ->with('success', 'Subcategory Created Successfully');
    }


    public function edit($id)
    {
        $subcategory = ProductSubCategory::findOrFail($id);
        // $services = ServiceModel::all();
        $services = DB::table('tbl_services as s')
            ->join('sub_category_tbl as sc', 's.subCategory_id', '=', 'sc.id')
            ->select(
                's.id',
                's.service_name',
                'sc.sub_categoryname'
            )
            ->get();
        return view('admin.product_subcategory.edit', compact('subcategory', 'services'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'service_id' => 'required|exists:tbl_services,id',
            'subcat_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tbl_product_subcategory')
                    ->where(function ($query) use ($request) {
                        return $query->where('service_id', $request->service_id);
                    })
                    ->ignore($id), // ignore current row
            ],
            'status' => 'required'
        ]);

        $subcategory = ProductSubCategory::findOrFail($id);

        $subcategory->update([
            'service_id' => $request->service_id,
            'subcat_name' => $request->subcat_name,
            'status' => $request->status
        ]);

        return redirect()->route('admin.product_subcategory.create')
            ->with('success', 'Subcategory Updated Successfully');
    }


    public function destroy($id)
    {
        ProductSubCategory::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Subcategory Deleted');
    }
}

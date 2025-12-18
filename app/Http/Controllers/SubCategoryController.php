<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubCategoryController extends Controller
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
        $data['title'] = 'ADD SUB CATEGORY';
        $data['subcategory_list_tit'] = 'SUB CATEGORY LIST';
        $data['categories_all'] = Category::all();
        $data['sub_categories_all'] = DB::table('sub_category_tbl as sc')
            ->join('category_tbl as c', 'c.id', '=', 'sc.category_id')
            ->select(
                'sc.category_id',
                'c.name',
                DB::raw('COUNT(sc.id) as total_subcategories')
            )
            ->groupBy('sc.category_id', 'c.name')
            ->get();

        return view('admin.category.add_subCategory', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id'       => 'required',
            'sub_categoryname'  => 'required|string|max:255',
        ]);

        /* ---------------------------------------------------
       1. Upload Banner Image (sub_banner_image)
    --------------------------------------------------- */
        $bannerImageName = null;

        if ($request->hasFile('sub_banner_image')) {

            $image = $request->file('sub_banner_image');
            $extension = $image->getClientOriginalExtension();

            $bannerImageName = md5(uniqid() . time()) . '.' . $extension;
            $destinationPath = 'admin_assets/subcategories';

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            $image->move($destinationPath, $bannerImageName);
        }


        /* ---------------------------------------------------
       2. Upload Icon Image (sub_icon_image)
    --------------------------------------------------- */
        $iconImageName = null;

        if ($request->hasFile('sub_icon_image')) {

            $image = $request->file('sub_icon_image');
            $extension = $image->getClientOriginalExtension();

            $iconImageName = md5(uniqid() . time()) . '.' . $extension;
            $destinationPath = 'admin_assets/subcategoriesIconImg';

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            $image->move($destinationPath, $iconImageName);
        }


        /* ---------------------------------------------------
       3. Create New SubCategory
    --------------------------------------------------- */
        SubCategory::create([
            'category_id'            => $request->category_id,
            'sub_categoryname'       => $request->sub_categoryname,
            'sub_category_desc'      => $request->sub_category_desc,
            'sub_banner_image'       => $bannerImageName,
            'sub_icon_image'         => $iconImageName,
            'sub_category_service'   => $request->sub_category_service ?? 0,
        ]);

        return redirect()->back()->with('success', 'Sub Category Created successfully!');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['title'] = 'SUB CATEGORY LIST';
        $data['sub_categories_details'] = DB::table('sub_category_tbl as sc')
            ->join('category_tbl as c', 'c.id', '=', 'sc.category_id')
            ->select(
                'sc.*',
                'c.name'
            )
            ->WHERE('category_id', $id)
            ->get();

        return view('admin.category.view_subCategory_list', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $title = 'Update Sub Category';
        $subcategor_details = SubCategory::findorFail($id);
        $categories_all = Category::all();
        return view('admin.category.edit_subCategory', compact('subcategor_details', 'categories_all', 'title'));
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
        $service = SubCategory::findOrFail($id);

        // Validation
        $request->validate([
            'category_id'        => 'required',
            'sub_categoryname'   => 'required',
            'sub_banner_image'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'sub_icon_image'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Update fields
        $service->category_id           = $request->category_id;
        $service->sub_categoryname      = $request->sub_categoryname;
        $service->sub_category_desc     = $request->sub_category_desc;
        $service->sub_category_service  = $request->sub_category_service ?? 0;

        /* ----------------------------------------------------
       1. Update Banner Image (sub_banner_image)
    ---------------------------------------------------- */
        if ($request->hasFile('sub_banner_image')) {

            // Delete old banner image
            if ($service->sub_banner_image) {
                $oldBannerPath = 'admin_assets/subcategories/' . $service->sub_banner_image;
                if (file_exists($oldBannerPath)) {
                    unlink($oldBannerPath);
                }
            }

            $image = $request->file('sub_banner_image');
            $extension = $image->getClientOriginalExtension();
            $bannerFileName = md5(uniqid() . time()) . '.' . $extension;
            $destinationPath = 'admin_assets/subcategoriesIconImg';
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            $image->move($destinationPath, $bannerFileName);

            $service->sub_banner_image = $bannerFileName;
        }

        /* ----------------------------------------------------
       2. Update Icon Image (sub_icon_image)
    ---------------------------------------------------- */
        if ($request->hasFile('sub_icon_image')) {

            // Delete old icon image
            if ($service->sub_icon_image) {
                $oldIconPath = public_path('admin_assets/subcategoriesIconImg/' . $service->sub_icon_image);
                if (file_exists($oldIconPath)) {
                    unlink($oldIconPath);
                }
            }

            $image = $request->file('sub_icon_image');
            $extension = $image->getClientOriginalExtension();
            $iconFileName = md5(uniqid() . time()) . '.' . $extension;

            $iconPath = public_path('admin_assets/subcategoriesIconImg');

            if (!file_exists($iconPath)) {
                mkdir($iconPath, 0777, true);
            }

            $image->move($iconPath, $iconFileName);

            $service->sub_icon_image = $iconFileName;
        }

        // Save the updated data
        $service->save();

        return redirect()->back()->with('success', 'Subcategory updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   public function destroy($id)
{
    $data = SubCategory::findOrFail($id);

    /* ----------------------------------------
       1. Delete Banner Image if available
    ---------------------------------------- */
    if (!empty($data->sub_banner_image)) {

        $bannerPath = 'admin_assets/subcategories/' . $data->sub_banner_image;

        if (file_exists($bannerPath)) {
            unlink($bannerPath);
        }
    }

    /* ----------------------------------------
       2. Delete Icon Image if available
    ---------------------------------------- */
    if (!empty($data->sub_icon_image)) {

        $iconPath = 'admin_assets/subcategoriesIconImg/' . $data->sub_icon_image;

        if (file_exists($iconPath)) {
            unlink($iconPath);
        }
    }

    /* ----------------------------------------
       3. Delete Database Record
    ---------------------------------------- */
    $data->delete();

    return redirect()->back()
        ->with('success', 'Sub Category deleted successfully!');
}

}

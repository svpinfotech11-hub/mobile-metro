<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
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
        $data['title'] = 'ADD CATEGORY';
        $data['category_list_tit'] = 'CATEGORY LIST';
        $data['categories_all'] = Category::all();
        return view('admin.category.add_category', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'category_name' => 'required|string|max:255|unique:category_tbl,name',
        ]);

        $icon_image = 'no Image'; // Default value if no image uploaded

        if ($request->hasFile('icon_image')) {
            $image = $request->file('icon_image');
            $extension = $image->getClientOriginalExtension();
            $filename = md5(uniqid() . time()) . '.' . $extension;

            $destinationPath = 'admin_assets/category_icon_img'; // ensure it's under public

            // Create folder if not exists
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            // Move new image
            $image->move($destinationPath, $filename);

            // Assign filename to variable to save in DB
            $icon_image = $filename;
        }

        // Save category in DB
        category::create([
            'name' => $request->category_name,
            'icon_image' => $icon_image, // now this is saved
        ]);

        return redirect()->route('app.admin-category.create')->with('success', 'Category created successfully!');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {}

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
            'name' => 'required|string|max:255',
        ]);

        $category = Category::findOrFail($id);

        /* ----------------------------------------------------
        ICON IMAGE UPLOAD
    ---------------------------------------------------- */
        if ($request->hasFile('icon_image')) {

            // Delete old image if exists
            $oldPath = 'admin_assets/category_icon_img/' . $category->icon_image;
            if ($category->icon_image && file_exists($oldPath)) {
                unlink($oldPath);
            }

            $image = $request->file('icon_image');
            $extension = $image->getClientOriginalExtension();
            $filename = md5(uniqid() . time()) . '.' . $extension;

            $destinationPath = 'admin_assets/category_icon_img';

            // Create folder if not exists
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            // Move new image
            $image->move($destinationPath, $filename);

            // Save new image name
            $category->icon_image = $filename;
        } else {
            // Keep existing image (DON’T replace with input text)
            $category->icon_image = $category->icon_image;
        }

        /* ----------------------------------------------------
        UPDATE CATEGORY NAME
    ---------------------------------------------------- */
        $category->name = $request->name;
        $category->save();

        return redirect()->back()->with('success', 'Category updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // Delete old image if exists
        $oldPath = 'admin_assets/category_icon_img/' . $category->icon_image;
        if ($category->icon_image && file_exists($oldPath)) {
            unlink($oldPath);
        }
        $category->delete();

        return redirect()->back()
            ->with('success', 'Category deleted successfully!');
    }
}

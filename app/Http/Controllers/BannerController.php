<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BannerModel;

class BannerController extends Controller
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
        $data['title'] = 'ADD BANNER';
        $data['banner_list_tit'] = 'BANNER LIST';
        $data['banner_data'] = BannerModel::all();
        return view('admin.banner.add_banner', $data);
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
            'title'       => 'required',
            'image'       => 'required',
            'description' => 'required|string',
        ]);

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            // Generate unique name
            $filename = md5(uniqid() . time()) . '.' . $image->getClientOriginalExtension();

            // Ensure folder exists
            $destinationPath = base_path('admin_assets/banners');
            // $destinationPath = base_path('admin_assets/banners');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            // Move file
            $image->move($destinationPath, $filename);

            // Save relative path (for DB / frontend usage)
            $data['image'] =  $filename;
        }


        BannerModel::create($data);


        return redirect()->back()->with('success', 'Banner created successfully!');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data  = BannerModel::findOrFail($id); // finds the record by id (404 if not found)
        $data->delete(); // deletes the record from database
        return redirect()->back()->with('success', 'BANNER DELETED SUCCESSFULLY!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\CFTModel;
use App\Models\KMRateModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KMRateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['title'] = 'KM RATE LIST';
        $rates = KMRateModel::all();
        $cft = CFTModel::all();
        return view('admin.km_rate.km_rate_list', compact('rates', 'data', 'cft'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $rates = KMRateModel::with('cft')->get();
        $rates = KMRateModel::with('cft')
            ->get()
            ->filter(fn($item) => $item->cft !== null)
            ->groupBy(fn($item) => $item->cft->from_cft . ' - ' . $item->cft->to_cft);

        //  dd($rates);
        $cft = CFTModel::all();

        // Merge all data into one associative array
        $data = [
            'title' => 'ADD KM RATE',
            'cft' => $cft,
            'rates' => $rates,
        ];

        // Pass the $data array to the view
        return view('admin.km_rate.add_km_rate', $data);
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
    //         'from_km.*'   => 'required|numeric',
    //         'to_km.*'     => 'required|numeric',
    //         'km_rate.*'       => 'required|numeric',
    //         'rate_type.*'  => 'required|string'

    //     ]);

    //     foreach ($request->from_km as $key => $value) {
    //         KMRateModel::create([
    //             'from_km' => $value,
    //             'to_km'   => $request->to_km[$key],
    //             'km_rate' => $request->km_rate[$key],
    //             // 'km_profit' => $request->km_profit[$key],
    //             'rate_type' => $request->rate_type[$key],
    //             'cft_id' => $request->cft_id[$key]
    //         ]);
    //     }

    //     return redirect()->back()->with('success', 'Rates added successfully!');
    // }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'from_km.*'  => 'required|numeric',
    //         'to_km.*'    => 'required|numeric|gte:from_km.*',
    //         'km_rate.*'  => 'required|numeric',
    //         'rate_type.*' => 'required|string',
    //         'cft_id.*'   => 'required|integer'
    //     ]);

    //     foreach ($request->from_km as $key => $value) {

    //         $km_profit = $request->from_km[$key];
    //         $fromKm = $request->from_km[$key];
    //         $toKm   = $request->to_km[$key];
    //         $cftId  = $request->cft_id[$key];

    //         // 🔍 CHECK FOR OVERLAPPING RANGE
    //         $overlap = DB::table('km_rate_tb')
    //             ->where('cft_id', $cftId)
    //             ->where(function ($query) use ($fromKm, $toKm) {
    //                 $query->whereBetween('from_km', [$fromKm, $toKm])
    //                     ->orWhereBetween('to_km', [$fromKm, $toKm])
    //                     ->orWhere(function ($q) use ($fromKm, $toKm) {
    //                         $q->where('from_km', '<=', $fromKm)
    //                             ->where('to_km', '>=', $toKm);
    //                     });
    //             })
    //             ->exists();

    //         if ($overlap) {
    //             return redirect()->back()
    //                 ->with('error', "❌ Duplicate or overlapping KM range ($fromKm - $toKm) for CFT ID $cftId cannot be added.");
    //         }

    //         // 🌟 Insert row only if no overlap
    //         KMRateModel::create([
    //             'from_km'  => $fromKm,
    //             'km_profit' => $km_profit,
    //             'to_km'    => $toKm,
    //             'km_rate'  => $request->km_rate[$key],
    //             'rate_type' => $request->rate_type[$key],
    //             'cft_id'   => $cftId
    //         ]);
    //     }

    //     return redirect()->back()->with('success', 'KM Rates added successfully!');
    // }
    public function store(Request $request)
    {
        $request->validate([
            'from_km.*'  => 'required|numeric',
            'to_km.*'    => 'required|numeric',
            'km_rate.*'  => 'required|numeric',
            'rate_type.*' => 'required|string',
            'cft_id.*'   => 'required|integer'
        ]);

        foreach ($request->from_km as $key => $value) {

            $fromKm = $request->from_km[$key];
            $toKm   = $request->to_km[$key];
            $cftId  = $request->cft_id[$key];
            $km_profit = $request->km_profit[$key];

            // ❌ TO KM must be >= FROM KM
            if ($toKm < $fromKm) {
                return back()->with('error', "❌ TO KM ($toKm) cannot be less than FROM KM ($fromKm).");
            }

            // 🔍 CHECK FOR OVERLAPPING RANGE
            $overlap = DB::table('km_rate_tb')
                ->where('cft_id', $cftId)
                ->where(function ($q) use ($fromKm, $toKm) {
                    $q->where('from_km', '<=', $toKm)
                        ->where('to_km', '>=', $fromKm);
                })
                ->exists();


            if ($overlap) {
                return back()->with('error', "❌ Duplicate or overlapping KM range ($fromKm - $toKm) for CFT ID $cftId.");
            }

            // 🌟 Insert row
            KMRateModel::create([
                'from_km'  => $fromKm,
                'to_km'    => $toKm,
                'km_profit' => $km_profit,
                'km_rate'  => $request->km_rate[$key],
                'rate_type' => $request->rate_type[$key],
                'cft_id'   => $cftId
            ]);
        }

        return back()->with('success', 'KM Rates added successfully!');
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
        // Get the KM Rate row by ID
        $kmRate = KMRateModel::findOrFail($id);

        // Get all CFT records
        $cft = CFTModel::all();

        // Define the CFT ID (from the KM rate)
        $cftId = $kmRate->cft_id;

        // Pass KM rate, all CFTs, and cftId to the view
        return view('admin.km_rate.edit_km_rate', compact('kmRate', 'cft', 'cftId'));
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
        foreach ($request->row_id as $index => $rowId) {

            KMRateModel::where('id', $rowId)->update([
                'cft_id'     => $request->cft_id[$index],
                'from_km'    => $request->from_km[$index],
                'to_km'      => $request->to_km[$index],
                'km_rate'    => $request->km_rate[$index],
                'km_profit'  => $request->km_profit[$index],
                'rate_type'  => $request->rate_type[$index],
            ]);
        }

        return redirect()->back()
            ->with('success', 'KM Rate Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        KMRateModel::findorfail($id)->delete();
        return redirect()->back()->with('success', 'KM Rate deleted successfully!');
    }


    public function showKmDetails($cftId)
    {
        // Fetch the CFT and its associated KM rates
        $cft = CFTModel::findOrFail($cftId);  // Use cftId, not id
        $kmRates = KMRateModel::where('cft_id', $cftId)->get();  // Get KM rates for this CFT

        // Return the view with CFT and KM rates
        return view('admin.km_rate.km_details', compact('kmRates', 'cft'));
    }
}

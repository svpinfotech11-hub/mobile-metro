<?php

namespace App\Http\Controllers;

use App\Models\CFTModel;

use Illuminate\Http\Request;

class CFTController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['title'] = 'CFT RATE LIST';
        $rates = CFTModel::all();
        return view('admin.cft.cft_rate_list', compact('rates', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['title'] = 'ADD CFT RATE';
        $rates = CFTModel::all();

        $data = [
            'title' => 'ADD KM RATE',
            'rates' => $rates,
        ];

        return view('admin.cft.add_cft_rate', $data);
    }


    public function store(Request $request)
    {
        $request->validate([
            'from_cft.*'   => 'required|numeric',
            'to_cft.*'     => 'required|numeric|gte:from_cft.*', // to_cft must be >= from_cft
            'cft_rate.*'   => 'required|numeric',
            'rate_type.*'  => 'required|string'
        ]);


        foreach ($request->from_cft as $key => $from) {
            $to = $request->to_cft[$key];
            $from = (float) $request->from_cft[$key];
            $to   = (float) $request->to_cft[$key];
            $overlap = CFTModel::where('from_cft', '<=', $to)
                ->where('to_cft', '>=', $from)
                ->exists();


            if ($overlap) {
                return redirect()->back()->withErrors([
                    "from_cft.$key" => "The range $from - $to overlaps with an existing range. Enter Other Range"
                ])->withInput();
            }

            // Insert
            CFTModel::create([
                'from_cft'   => $from,
                'to_cft'     => $to,
                'cft_rate'   => $request->cft_rate[$key],
                'rate_type'  => $request->rate_type[$key],
                'cft_profit' => $request->cft_profit[$key] ?? 0
            ]);
        }

        return redirect()->back()->with('success', 'Rates added successfully!');
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
        $title = 'Edit CFT Rate';
        $cftRate = CFTModel::findOrFail($id); // group multiple rows if you have a group logic

        return view('admin.cft.edit', compact('title', 'cftRate'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'from_cft.*'   => 'required|numeric',
    //         'to_cft.*'     => 'required|numeric',
    //         'cft_rate.*'   => 'required|numeric',
    //         'rate_type.*'  => 'required|string',
    //     ]);

    //     foreach ($request->from_cft as $key => $from) {
    //         $from = (int) $from;
    //         $to = (int) $request->to_cft[$key];

    //         if ($from >= $to) {
    //             return redirect()->back()->withErrors([
    //                 "from_cft.$key" => "FROM CFT must be less than TO CFT"
    //             ])->withInput();
    //         }

    //         $overlap = CFTModel::where('from_cft', '<=', $to)
    //             ->where('to_cft', '>=', $from)
    //             ->exists();

    //         if ($overlap) {
    //             return redirect()->back()->withErrors([
    //                 "from_cft.$key" => "The range $from - $to overlaps with an existing range. Enter Other Range"
    //             ])->withInput();
    //         }

    //         CFTModel::create([
    //             'from_cft'   => $from,
    //             'to_cft'     => $to,
    //             'cft_rate'   => (int) $request->cft_rate[$key],
    //             'rate_type'  => $request->rate_type[$key],
    //             'cft_profit' => (int) ($request->cft_profit[$key] ?? 0)
    //         ]);
    //     }

    //     return redirect()
    //         ->back()
    //         ->with('success', 'CFT Rates updated successfully!');
    // }

    public function update(Request $request, $id)
    {
        $request->validate([
            'from_cft'   => 'required|numeric',
            'to_cft'     => 'required|numeric',
            'cft_rate'   => 'required|numeric',
            'rate_type'  => 'required|in:0,1',
            'cft_profit' => 'nullable|numeric',
        ]);

        $from = (int) $request->from_cft;
        $to   = (int) $request->to_cft;

        if ($from >= $to) {
            return back()->withErrors([
                'from_cft' => 'FROM CFT must be less than TO CFT'
            ])->withInput();
        }

        // ✅ OVERLAP CHECK (exclude current row)
        $overlap = CFTModel::where('id', '!=', $id)
            ->where(function ($q) use ($from, $to) {
                $q->whereBetween('from_cft', [$from, $to])
                    ->orWhereBetween('to_cft', [$from, $to])
                    ->orWhere(function ($q2) use ($from, $to) {
                        $q2->where('from_cft', '<=', $from)
                            ->where('to_cft', '>=', $to);
                    });
            })
            ->exists();

        if ($overlap) {
            return back()->withErrors([
                'from_cft' => "The range $from - $to overlaps with an existing range."
            ])->withInput();
        }

        CFTModel::where('id', $id)->update([
            'from_cft'   => $from,
            'to_cft'     => $to,
            'cft_rate'   => (float) $request->cft_rate,
            'rate_type'  => (int) $request->rate_type,
            'cft_profit' => (float) ($request->cft_profit ?? 0),
        ]);

        return back()->with('success', 'CFT Rate updated successfully!');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        CFTModel::findorfail($id)->delete();
        return redirect()->back()->with('success', 'Rate deleted successfully!');
    }
}

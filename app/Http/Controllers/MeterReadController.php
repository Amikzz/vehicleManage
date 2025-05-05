<?php

namespace App\Http\Controllers;

use App\Models\MeterRead;
use Illuminate\Http\Request;

class MeterReadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $meterReads = MeterRead::orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $vehicles = \App\Models\Vehicle::all();

        return view('meterread', [
            'readings' => $meterReads,
            'vehicles' => $vehicles,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'vehicle_id' => 'required|exists:vehicles,id',
                'date' => 'required|date',
                'mileage' => 'required|integer|min:0',
            ]);

            MeterRead::create([
                'vehicle_id' => $request->vehicle_id,
                'date' => $request->date,
                'mileage' => $request->mileage,
            ]);

            return redirect()->back()->with('success', 'Meter read added successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create meter read: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MeterRead $meterRead)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MeterRead $meterRead)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MeterRead $meterRead)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MeterRead $meterRead)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Maintainance;
use Illuminate\Http\Request;

class MaintainanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $maintainances = Maintainance::all();
        $vehicles = \App\Models\Vehicle::all();
        return view('maintainance', compact('maintainances', 'vehicles'));
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
                'cost' => 'required|numeric|min:0',
                'done_by' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
            ]);

            Maintainance::create([
                'vehicle_id' => $request->vehicle_id,
                'date' => $request->date,
                'mileage' => $request->mileage,
                'cost' => $request->cost,
                'done_by' => $request->done_by,
                'description' => $request->description,
            ]);

            return redirect()->back()->with('success', 'Maintenance record added successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create maintainance record: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Maintainance $maintainance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Maintainance $maintainance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Maintainance $maintainance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Maintainance $maintainance)
    {
        //
    }
}

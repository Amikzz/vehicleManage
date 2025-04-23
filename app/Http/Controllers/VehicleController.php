<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Show all the vehicles ordered by type
        $vehicles = Vehicle::orderBy('status', 'asc')->get();
        return view('vehicles', compact('vehicles'));
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
            //validate the request
            $request->validate([
                'make' => 'required|string|max:255',
                'model' => 'required|string|max:255',
                'year' => 'required|integer|min:1900|max:' . date('Y'),
                'color' => 'required|string|max:255',
                'license_plate' => 'required|string|max:10|unique:vehicles,license_plate',
                'type' => 'required|string|max:255',
                'status' => 'required',
            ]);

            //store vehicle
            $vehicle = new Vehicle();
            $vehicle->make = $request->make;
            $vehicle->model = $request->model;
            $vehicle->year = $request->year;
            $vehicle->color = $request->color;
            $vehicle->license_plate = $request->license_plate;
            $vehicle->type = $request->type;
            $vehicle->save();
            return redirect()->route('vehicles.index')->with('success', 'Vehicle created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('vehicles.index')->with('error', 'Failed to create vehicle: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        try {
            //validate the request
            $request->validate([
                'make' => 'required|string|max:255',
                'model' => 'required|string|max:255',
                'year' => 'required|integer|min:1900|max:' . date('Y'),
                'color' => 'required|string|max:255',
                'license_plate' => 'required|string|max:10|unique:vehicles,license_plate,' . $vehicle->id,
                'type' => 'required|string|max:255',
                'status' => 'required',
            ]);

            //update vehicle
            $vehicle->make = $request->make;
            $vehicle->model = $request->model;
            $vehicle->year = $request->year;
            $vehicle->color = $request->color;
            $vehicle->license_plate = $request->license_plate;
            $vehicle->type = $request->type;
            $vehicle->status = $request->status;
            $vehicle->save();
            return redirect()->route('vehicles.index')->with('success', 'Vehicle updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('vehicles.index')->with('error', 'Failed to update vehicle: ' . $e->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        //delete the vehicle
        try {
            $vehicle->delete();
            return redirect()->route('vehicles.index')->with('success', 'Vehicle deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('vehicles.index')->with('error', 'Failed to delete vehicle: ' . $e->getMessage());
        }
    }
}

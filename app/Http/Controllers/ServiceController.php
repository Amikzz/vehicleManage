<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Show all the services, sorted by service_date in descending order
        $services = Service::orderBy('service_date', 'desc')->get();
        $vehicles = Vehicle::all();

        return view('service', compact('services', 'vehicles'));
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
                'vehicle_id' => 'required|exists:vehicles,id',
                'service_type' => 'required|string|max:255',
                'service_date' => 'required|date',
                'service_location' => 'required|string|max:255',
                'service_cost' => 'required|numeric|min:0',
                'mileage' => 'required|integer|min:0',
                'service_notes' => 'nullable|string|max:1000',
                'done_by' => 'nullable|string|max:255',
            ]);

            //next mileage added 5000 to the mileage
            $next_service_mileage = $request->mileage + 5000;

            //next service date added 3 months to the service date
            $next_service_date = date('Y-m-d', strtotime($request->service_date . ' +3 months'));

            //store service
            $service = new Service();
            $service->vehicle_id = $request->vehicle_id;
            $service->service_type = $request->service_type;
            $service->service_date = $request->service_date;
            $service->service_location = $request->service_location;
            $service->service_cost = $request->service_cost;
            $service->mileage = $request->mileage;
            $service->next_service_mileage = $next_service_mileage;
            $service->next_service_date = $next_service_date;
            $service->service_notes = $request->service_notes;
            $service->done_by = $request->done_by;
            $service->save();

            return redirect()->route('services.index')->with('success', 'Service created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('services.index')->with('error', 'Failed to create service: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        //
    }
}

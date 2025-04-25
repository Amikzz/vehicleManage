<?php

namespace App\Http\Controllers;

use App\Models\Maintainance;
use App\Models\Service;
use App\Models\Vehicle;
use Carbon\Carbon;

class Dashboard extends Controller
{
    public function index()
    {
        $totalVehicles = Vehicle::count();

        $dueServices = Service::whereDate('next_service_date', '<=', Carbon::now()->addMonth())->get();

        $totalServiceCost = Service::whereDate('service_date', '>=', Carbon::now()->subDays(30))
            ->sum('service_cost');

        $totalMaintenanceCost = (Maintainance::whereDate('date', '>=', Carbon::now()->subDays(30))
                ->sum('cost')) + $totalServiceCost;

        // Cost vs Date (Last 30 days)
        $costByDate = Service::whereDate('service_date', '>=', Carbon::now()->subDays(30))
            ->selectRaw('DATE(service_date) as date, SUM(service_cost) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $dates = $costByDate->pluck('date')->map(fn($date) => Carbon::parse($date)->format('M d'))->toArray();
        $costs = $costByDate->pluck('total')->toArray();

        // Service Cost vs Vehicle
        $costByVehicle = Service::with('vehicle')
            ->selectRaw('vehicle_id, SUM(service_cost) as total')
            ->groupBy('vehicle_id')
            ->get();

        $vehicleLabels = $costByVehicle->map(fn($item) => $item->vehicle->license_plate)->toArray();
        $vehicleCosts = $costByVehicle->pluck('total')->toArray();

        // Maintenance Cost vs Vehicle
        $maintainCostByVehicle = Maintainance::with('vehicle')
            ->selectRaw('vehicle_id, SUM(cost) as total')
            ->groupBy('vehicle_id')
            ->get();

        $maintenanceLabels = $maintainCostByVehicle->map(fn($item) => $item->vehicle->license_plate)->toArray();
        $maintenanceCosts = $maintainCostByVehicle->pluck('total')->toArray();

        // Combined Service + Maintenance Cost vs Vehicle
        $combinedCostsByVehicle = [];

        foreach ($vehicleLabels as $index => $label) {
            $maintenanceIndex = array_search($label, $maintenanceLabels);
            $combinedCost = $vehicleCosts[$index] + ($maintenanceIndex !== false ? $maintenanceCosts[$maintenanceIndex] : 0);
            $combinedCostsByVehicle[$label] = $combinedCost;
        }

        $combinedLabels = array_keys($combinedCostsByVehicle);
        $combinedCosts = array_values($combinedCostsByVehicle);

        return view('dashboard', compact(
            'totalVehicles',
            'dueServices',
            'totalServiceCost',
            'totalMaintenanceCost',
            'dates',
            'costs',
            'vehicleLabels',
            'vehicleCosts',
            'maintenanceLabels',
            'maintenanceCosts',
            'combinedLabels',
            'combinedCosts'
        ));
    }
}

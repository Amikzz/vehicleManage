<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-semibold mb-6">Vehicle Management Dashboard</h3>

                    <!-- Summary Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-blue-500 text-white rounded-lg p-6">
                            <h4 class="text-xl font-semibold">Total Vehicles in Fleet</h4>
                            <p class="text-3xl font-bold">{{ $totalVehicles }}</p>
                        </div>

                        <div x-data="{ showModal: false }">
                            <div class="bg-yellow-500 text-white rounded-lg p-6 cursor-pointer" @click="showModal = true">
                                <h4 class="text-xl font-semibold">Vehicles with Services Due</h4>
                                <p class="text-3xl font-bold">{{ $dueServices->count() }}</p>
                            </div>

                            <!-- Modal -->
                            <div
                                x-show="showModal"
                                x-cloak
                                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                                @click.outside="showModal = false"
                            >
                                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-2xl w-full shadow-xl">
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Vehicles with Services Coming up or Due</h3>
                                        <button
                                            @click="showModal = false"
                                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-all duration-200 rounded-full w-8 h-8 flex items-center justify-center"
                                            aria-label="Close modal"
                                            title="Close"
                                        >
                                            <span class="text-2xl font-bold">&times;</span>
                                        </button>
                                    </div>
                                    <ul id="dueServiceList" class="text-gray-700 dark:text-gray-100 space-y-2 max-h-80 overflow-y-auto">
                                        @foreach ($dueServices as $service)
                                            <li>{{ $service->vehicle->license_plate }} - {{ $service->next_service_mileage }}</li>
                                        @endforeach
                                    </ul>
                                    <div class="mt-6 text-right">
                                        <button onclick="downloadDueServicesPDF()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Download PDF</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-green-500 text-white rounded-lg p-6">
                            <h4 class="text-xl font-semibold">Total Service Cost (Last 30 Days)</h4>
                            <p class="text-3xl font-bold">Rs. {{ number_format($totalServiceCost, 2) }}</p>
                        </div>
                    </div>

                    <br>

                    <div class="bg-purple-500 text-white rounded-lg p-6">
                        <h4 class="text-xl font-semibold">Total Maintenance Cost (Last 30 days)</h4>
                        <p class="text-3xl font-bold">Rs. {{ number_format($totalMaintenanceCost, 2) }}</p>
                    </div>

                    <!-- Charts -->
                    <div class="mt-8">
                        <h4 class="text-xl font-semibold">Analytics</h4>
                        <div class="mt-12 grid grid-cols-1 gap-8">

                            <!-- Service Cost vs Date -->
                            <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow-lg w-full h-[400px]">
                                <h4 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Service Cost vs Date (Last 30 Days)</h4>
                                <canvas id="costDateChart" class="w-full h-full"></canvas>
                            </div>

                            <!-- Service Cost per Vehicle -->
                            <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow-lg w-full h-[400px]">
                                <h4 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Service Cost per Vehicle</h4>
                                <canvas id="costVehicleChart" class="w-full h-full"></canvas>
                            </div>

                            <!-- Maintenance Cost per Vehicle -->
                            <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow-lg w-full h-[400px]">
                                <h4 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Maintenance Cost per Vehicle</h4>
                                <canvas id="maintenanceVehicleChart" class="w-full h-full"></canvas>
                            </div>

                            <!-- Total Cost (Service + Maintenance) per Vehicle -->
                            <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow-lg w-full h-[400px]">
                                <h4 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Total Cost (Service + Maintenance) per Vehicle</h4>
                                <canvas id="totalCostVehicleChart" class="w-full h-full"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                    <script>
                        const costDateCtx = document.getElementById('costDateChart').getContext('2d');
                        new Chart(costDateCtx, {
                            type: 'line',
                            data: {
                                labels: @json($dates),
                                datasets: [{
                                    label: 'Service Cost (Rs.)',
                                    data: @json($costs),
                                    borderColor: 'rgb(59, 130, 246)',
                                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                                    fill: true,
                                    tension: 0.4,
                                }]
                            },
                            options: {
                                responsive: true,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            callback: value => 'Rs. ' + value
                                        }
                                    }
                                }
                            }
                        });

                        // Service Cost per Vehicle
                        const costVehicleCtx = document.getElementById('costVehicleChart').getContext('2d');
                        new Chart(costVehicleCtx, {
                            type: 'bar',
                            data: {
                                labels: @json($vehicleLabels),
                                datasets: [{
                                    label: 'Service Cost (Rs.)',
                                    data: @json($vehicleCosts),
                                    backgroundColor: 'rgb(34, 197, 94)',
                                }]
                            },
                            options: {
                                responsive: true,
                                indexAxis: 'y',
                                scales: {
                                    x: {
                                        beginAtZero: true,
                                        ticks: {
                                            callback: value => 'Rs. ' + value
                                        }
                                    }
                                }
                            }
                        });

                        // ✅ Fixed: Maintenance Cost per Vehicle
                        const maintenanceVehicleCtx = document.getElementById('maintenanceVehicleChart').getContext('2d');
                        new Chart(maintenanceVehicleCtx, {
                            type: 'bar',
                            data: {
                                labels: @json($maintenanceLabels),
                                datasets: [{
                                    label: 'Maintenance Cost (Rs.)',
                                    data: @json($maintenanceCosts),
                                    backgroundColor: 'rgb(249, 115, 22)',
                                }]
                            },
                            options: {
                                responsive: true,
                                indexAxis: 'y',
                                scales: {
                                    x: {
                                        beginAtZero: true,
                                        ticks: {
                                            callback: value => 'Rs. ' + value
                                        }
                                    }
                                }
                            }
                        });

                        // ✅ Fixed: Combined Cost per Vehicle
                        const totalCostVehicleCtx = document.getElementById('totalCostVehicleChart').getContext('2d');
                        new Chart(totalCostVehicleCtx, {
                            type: 'bar',
                            data: {
                                labels: @json($combinedLabels),
                                datasets: [{
                                    label: 'Total Cost (Rs.)',
                                    data: @json($combinedCosts),
                                    backgroundColor: 'rgb(168, 85, 247)',
                                }]
                            },
                            options: {
                                responsive: true,
                                indexAxis: 'y',
                                scales: {
                                    x: {
                                        beginAtZero: true,
                                        ticks: {
                                            callback: value => 'Rs. ' + value
                                        }
                                    }
                                }
                            }
                        });
                    </script>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
    <script>
        function downloadDueServicesPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            doc.setFontSize(14);
            doc.text("Vehicles with Services Due", 10, 10);

            const items = Array.from(document.querySelectorAll('#dueServiceList li')).map((el, index) => {
                return `${index + 1}. ${el.innerText}`;
            });

            doc.setFontSize(12);
            items.forEach((item, index) => {
                doc.text(item, 10, 20 + (index * 8));
            });

            doc.save("vehicles_with_services_due.pdf");
        }
    </script>
</x-app-layout>

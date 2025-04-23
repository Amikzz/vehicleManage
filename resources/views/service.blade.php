<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Service Records') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if (session('success'))
                        <div class="mb-4 p-4 text-green-800 bg-green-100 border border-green-300 rounded-md dark:text-green-200 dark:bg-green-900 dark:border-green-800">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 p-4 text-red-800 bg-red-100 border border-red-300 rounded-md dark:text-red-200 dark:bg-red-900 dark:border-red-800">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Filter Form -->
                    <form id="filterForm" class="mb-6 flex gap-6 items-center">
                        <div class="flex items-center gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Vehicle</label>
                                <select id="vehicleFilter" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                    <option value="">All Vehicles</option>
                                    @foreach ($vehicles as $vehicle)
                                        <option value="{{ $vehicle->license_plate }}">{{ $vehicle->license_plate }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service Date</label>
                                <input type="date" id="serviceDateFilter" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Next Service Date</label>
                                <input type="date" id="nextServiceDateFilter" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                            </div>
                        </div>

                        <!-- Clear Filters Button -->
                        <button type="button" id="clearFiltersBtn" class="mt-4 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 px-4 py-2 rounded hover:bg-gray-300">
                            Clear Filters
                        </button>
                    </form>

                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Service Records</h1>
                        <button onclick="document.getElementById('addServiceModal').classList.remove('hidden')"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow">
                            + Add New Record
                        </button>
                    </div>

                    <div class="overflow-x-auto bg-white dark:bg-gray-900 shadow rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">#</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Vehicle</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Service Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Service Mileage</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Next Service Mileage</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Next Service Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Cost</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Actions</th>

                            </tr>
                            </thead>
                            <tbody id="serviceRecords" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($services as $index => $service)
                                <tr data-vehicle="{{ $service->vehicle->license_plate }}"
                                    data-service-date="{{ $service->service_date }}"
                                    data-next-service-date="{{ $service->next_service_date }}">
                                    <td class="px-6 py-4">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">{{ $service->vehicle->license_plate }}</td>
                                    <td class="px-6 py-4">{{ $service->service_date }}</td>
                                    <td class="px-6 py-4">{{ $service->mileage }}</td>
                                    <td class="px-6 py-4">{{ $service->next_service_mileage }}</td>
                                    <td class="px-6 py-4">{{ $service->next_service_date }}</td>
                                    <td class="px-6 py-4">Rs. {{ number_format($service->service_cost, 2) }}</td>
                                    <td class="px-6 py-4">
                                        <button
                                            onclick="showServiceDetailsModal({{ json_encode($service) }})"
                                            class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                            View
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No service records found.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- View Service Details Modal -->
                    <div id="viewServiceModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
                        <div class="w-[500px] bg-white dark:bg-gray-900 rounded-2xl shadow-2xl p-6">
                            <h2 class="text-lg font-semibold mb-4 text-center text-green-500 dark:text-green-500">Service Details</h2>
                            <div class="space-y-2 text-sm text-gray-700 dark:text-gray-200">
                                <p><strong class="text-green-500">Vehicle:</strong> <span id="viewVehicle"></span></p>
                                <p><strong class="text-green-500">Service Date:</strong> <span id="viewServiceDate"></span></p>
                                <p><strong class="text-green-500">Service Type:</strong> <span id="viewServiceType"></span></p>
                                <p><strong class="text-green-500">Service Mileage:</strong> <span id="viewMileage"></span></p>
                                <p><strong class="text-green-500">Next Service Mileage:</strong> <span id="viewNextMileage"></span></p>
                                <p><strong class="text-green-500">Next Service Date:</strong> <span id="viewNextDate"></span></p>
                                <p><strong class="text-green-500">Location:</strong> <span id="viewLocation"></span></p>
                                <p><strong class="text-green-500">Cost (LKR):</strong> Rs. <span id="viewCost"></span></p>
                                <p><strong class="text-green-500">Service Notes:</strong> <span id="viewNotes"></span></p>
                            </div>
                            <div class="flex justify-end mt-6">
                                <button onclick="document.getElementById('viewServiceModal').classList.add('hidden')"
                                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>


                    <!-- Add Service Record Modal -->
                    <div id="addServiceModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
                        <div class="w-[500px] bg-white dark:bg-gray-900 rounded-2xl shadow-2xl p-6 transform transition-all scale-95">
                            <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100 text-center">Add New Service Record</h2>
                            <form action="{{ route('services.store') }}" method="POST">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Vehicle</label>
                                        <select name="vehicle_id" required class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                            @foreach ($vehicles as $vehicle)
                                                <option value="{{ $vehicle->id }}">{{ $vehicle->license_plate }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service Date</label>
                                        <input type="date" name="service_date" required class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service Type</label>
                                        <select name="service_type" required class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                            <option value="Full Service">Full Service</option>
                                            <option value="Partial Service">Partial Service</option>
                                            <option value="Wheel Alignment">Wheel Alignment</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service Mileage</label>
                                        <input type="number" name="mileage" required class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service Location</label>
                                        <input type="text" name="service_location" required class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cost (LKR)</label>
                                        <input type="number" name="service_cost" step="0.01" required class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Done by</label>
                                        <input type="text" name="done_by" required class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service Notes</label>
                                        <textarea name="service_notes" rows="3" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-white text-sm"></textarea>
                                    </div>
                                </div>
                                <div class="flex justify-end gap-3 mt-6">
                                    <button type="button" onclick="document.getElementById('addServiceModal').classList.add('hidden')" class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm rounded hover:bg-gray-300">
                                        Cancel
                                    </button>
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                                        Save
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const vehicleFilter = document.getElementById('vehicleFilter');
            const serviceDateFilter = document.getElementById('serviceDateFilter');
            const nextServiceDateFilter = document.getElementById('nextServiceDateFilter');
            const serviceRecords = document.querySelectorAll('#serviceRecords tr');
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');

            // Function to filter the rows
            function filterRows() {
                const vehicleValue = vehicleFilter.value.toLowerCase();
                const serviceDateValue = serviceDateFilter.value;
                const nextServiceDateValue = nextServiceDateFilter.value;

                serviceRecords.forEach(row => {
                    const vehicle = row.getAttribute('data-vehicle').toLowerCase();
                    const serviceDate = row.getAttribute('data-service-date');
                    const nextServiceDate = row.getAttribute('data-next-service-date');

                    const matchesVehicle = vehicle.includes(vehicleValue);
                    const matchesServiceDate = serviceDate.includes(serviceDateValue);
                    const matchesNextServiceDate = nextServiceDate.includes(nextServiceDateValue);

                    // Show/hide the row based on the filter criteria
                    if (matchesVehicle && matchesServiceDate && matchesNextServiceDate) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            // Add event listeners to filter inputs
            vehicleFilter.addEventListener('input', filterRows);
            serviceDateFilter.addEventListener('change', filterRows);
            nextServiceDateFilter.addEventListener('change', filterRows);

            // Clear filters function
            clearFiltersBtn.addEventListener('click', function() {
                vehicleFilter.value = '';
                serviceDateFilter.value = '';
                nextServiceDateFilter.value = '';

                filterRows();  // Reset to show all rows
            });
        });
    </script>

    <script>
        function showServiceDetailsModal(service) {
            document.getElementById('viewVehicle').textContent = service.vehicle.license_plate;
            document.getElementById('viewServiceDate').textContent = service.service_date;
            document.getElementById('viewServiceType').textContent = service.service_type || '-';
            document.getElementById('viewMileage').textContent = service.mileage;
            document.getElementById('viewNextMileage').textContent = service.next_service_mileage;
            document.getElementById('viewNextDate').textContent = service.next_service_date;
            document.getElementById('viewLocation').textContent = service.service_location || '-';
            document.getElementById('viewCost').textContent = parseFloat(service.service_cost).toFixed(2);
            document.getElementById('viewNotes').textContent = service.service_notes || 'N/A';

            document.getElementById('viewServiceModal').classList.remove('hidden');
        }
    </script>
</x-app-layout>

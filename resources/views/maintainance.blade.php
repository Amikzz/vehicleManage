<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Maintainance Records') }}
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
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Maintainance Date</label>
                                <input type="date" id="serviceDateFilter" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                            </div>
                        </div>

                        <!-- Clear Filters Button -->
                        <button type="button" id="clearFiltersBtn" class="mt-4 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 px-4 py-2 rounded hover:bg-gray-300">
                            Clear Filters
                        </button>
                    </form>

                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Maintainance Records</h1>
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Maintainance Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Cost</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Description</th>

                            </tr>
                            </thead>
                            <tbody id="serviceRecords" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($maintainances as $index => $maintainace)
                                <tr class="record-row"
                                    data-vehicle="{{ $maintainace->vehicle->license_plate }}"
                                    data-date="{{ $maintainace->date }}">
                                    <td class="px-6 py-4">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">{{ $maintainace->vehicle->license_plate}}</td>
                                    <td class="px-6 py-4">{{ $maintainace->date }}</td>
                                    <td class="px-6 py-4">Rs. {{ number_format($maintainace->cost, 2) }}</td>
                                    <td class="px-6 py-4">{{ $maintainace->description }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No maintainance records found.</td>
                                </tr>
                            @endforelse
                            </tbody>

                        </table>
                    </div>

                    <!-- Add Maintenance Record Modal -->
                    <div id="addServiceModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
                        <div class="bg-white dark:bg-gray-900 w-full max-w-2xl p-6 rounded-lg shadow-lg relative">
                            <!-- Close Button -->
                            <button onclick="document.getElementById('addServiceModal').classList.add('hidden')"
                                    class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white">
                                &times;
                            </button>

                            <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">Add Maintenance Record</h2>

                            <form action="{{route('maintainances.store')}}" method="POST">
                                @csrf

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Vehicle Select -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Vehicle</label>
                                        <select name="vehicle_id" required
                                                class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                            <option value="">Select Vehicle</option>
                                            @foreach ($vehicles as $vehicle)
                                                <option value="{{ $vehicle->id }}">{{ $vehicle->license_plate }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Date -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date</label>
                                        <input type="date" name="date" required
                                               class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                    </div>

                                    <!-- Mileage -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mileage</label>
                                        <input type="number" name="mileage" required
                                               class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                    </div>

                                    <!-- Cost -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service Cost (Rs.)</label>
                                        <input type="number" name="cost" step="0.01" required
                                               class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                    </div>

                                    <!-- Done by -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Done by</label>
                                        <input type="text" name="done_by" step="0.01" required
                                               class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                    </div>

                                    <!-- Description -->
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                                        <textarea name="description" rows="3"
                                                  class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-white text-sm"
                                                  placeholder="Enter maintenance details..."></textarea>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="flex justify-end gap-3 mt-6">
                                    <button type="button" onclick="document.getElementById('addServiceModal').classList.add('hidden')" class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm rounded hover:bg-gray-300">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow">
                                        Save Record
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
        document.addEventListener('DOMContentLoaded', () => {
            const vehicleFilter = document.getElementById('vehicleFilter');
            const serviceDateFilter = document.getElementById('serviceDateFilter');
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');

            function filterRecords() {
                const vehicleValue = vehicleFilter.value.trim().toLowerCase();
                const dateValue = serviceDateFilter.value.trim();

                document.querySelectorAll('.record-row').forEach(row => {
                    const rowVehicle = row.dataset.vehicle.trim().toLowerCase();
                    const rowDate = row.dataset.date.trim();

                    const matchesVehicle = !vehicleValue || rowVehicle === vehicleValue;
                    const matchesDate = !dateValue || rowDate === dateValue;

                    if (matchesVehicle && matchesDate) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            // Add event listeners for both filters
            vehicleFilter.addEventListener('change', filterRecords);
            serviceDateFilter.addEventListener('change', filterRecords);

            // Clear button resets the filters and shows all
            clearFiltersBtn.addEventListener('click', () => {
                vehicleFilter.value = '';
                serviceDateFilter.value = '';
                filterRecords(); // Show all
            });
        });
    </script>

</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Meter Readings') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Card --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                {{-- Filters --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 items-end">
                    <div>
                        <label for="vehicleFilter" class="block text-sm font-medium text-gray-700">Vehicle</label>
                        <div class="flex gap-2">
                            <select id="vehicleFilter" class="mt-1 block w-full rounded-md shadow-sm border-gray-300">
                                <option value="">All Vehicles</option>
                                @foreach ($vehicles as $vehicle)
                                    <option value="{{ $vehicle->license_plate }}">{{ $vehicle->license_plate }}</option>
                                @endforeach
                            </select>
                            <button
                                id="clearFilter"
                                class="mt-1 px-3 py-2 text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-md shadow-sm border border-gray-300"
                            >
                                Clear
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Add Button --}}
                <div class="mb-4 text-right">
                    <x-primary-button x-data @click="$dispatch('open-modal', 'add-reading-modal')">
                        + Add New Reading
                    </x-primary-button>
                </div>

                {{-- Readings Table --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mileage (km)</th>
                        </tr>
                        </thead>
                        <tbody id="readingsTableBody" class="bg-white divide-y divide-gray-200">
                        @forelse ($readings as $reading)
                            <tr data-vehicle="{{ $reading->vehicle->license_plate }}">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $reading->vehicle->license_plate }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ \Carbon\Carbon::parse($reading->date)->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $reading->mileage }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">No readings found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    {{-- Modal --}}
    <x-modal name="add-reading-modal" focusable>
        <form method="POST" action="{{ route('meterreads.store') }}" class="p-6">
            @csrf

            <h2 class="text-lg font-medium text-gray-900 mb-4">Add Meter Reading</h2>

            {{-- Vehicle --}}
            <div class="mb-4">
                <label for="vehicle_id_modal" class="block text-sm font-medium text-gray-700">Vehicle</label>
                <select name="vehicle_id" id="vehicle_id_modal" class="mt-1 block w-full rounded-md shadow-sm border-gray-300" required>
                    @foreach ($vehicles as $vehicle)
                        <option value="{{ $vehicle->id }}">{{$vehicle->license_plate}}</option>
                    @endforeach
                </select>
            </div>

            {{-- Date --}}
            <div class="mb-4">
                <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                <input type="date" name="date" id="date" class="mt-1 block w-full rounded-md shadow-sm border-gray-300" required>
            </div>

            {{-- Mileage --}}
            <div class="mb-4">
                <label for="mileage" class="block text-sm font-medium text-gray-700">Mileage (km)</label>
                <input type="number" name="mileage" id="mileage" class="mt-1 block w-full rounded-md shadow-sm border-gray-300" required min="0">
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button @click="$dispatch('close')">
                    Cancel
                </x-secondary-button>

                <x-primary-button class="ml-3">
                    Save
                </x-primary-button>
            </div>
        </form>
    </x-modal>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const vehicleFilter = document.getElementById('vehicleFilter');
            const clearButton = document.getElementById('clearFilter');
            const readingRows = document.querySelectorAll('#readingsTableBody tr');

            // Filter function
            function filterRows() {
                const filterValue = vehicleFilter.value.toLowerCase();

                readingRows.forEach(row => {
                    const vehicle = row.getAttribute('data-vehicle').toLowerCase();

                    if (vehicle.includes(filterValue) || filterValue === '') {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            // Event listeners
            vehicleFilter.addEventListener('input', filterRows);

            clearButton.addEventListener('click', function (e) {
                e.preventDefault();
                vehicleFilter.value = '';
                filterRows();
            });
        });
    </script>


</x-app-layout>

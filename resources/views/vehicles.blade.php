<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Vehicle List') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Vehicle List</h1>
                        <button onclick="document.getElementById('addVehicleModal').classList.remove('hidden')"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow">
                            + Add New Vehicle
                        </button>
                    </div>

                    <div class="overflow-x-auto bg-white dark:bg-gray-900 shadow rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">#</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Make</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Model</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Year</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">License Plate</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Actions</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($vehicles as $index => $vehicle)
                                <tr>
                                    <td class="px-6 py-4">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">{{ $vehicle->make }}</td>
                                    <td class="px-6 py-4">{{ $vehicle->model }}</td>
                                    <td class="px-6 py-4">{{ $vehicle->year }}</td>
                                    <td class="px-6 py-4">{{ $vehicle->license_plate }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full
                                            {{
                                                $vehicle->status === 'Available' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                                ($vehicle->status === 'in_service' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                                                'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200')
                                            }}">
                                            {{ ucfirst($vehicle->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 flex gap-2">
                                        <button type="button" onclick="openEditModal({{ $vehicle->id }}, '{{ $vehicle->make }}', '{{ $vehicle->model }}', '{{ $vehicle->year }}', '{{ $vehicle->license_plate }}', '{{ $vehicle->color }}', '{{ $vehicle->type }}', '{{ $vehicle->status }}')"
                                                class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm px-3 py-1 rounded">
                                            Edit
                                        </button>
                                        <form action="{{ route('vehicles.destroy', $vehicle->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this vehicle?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-sm px-3 py-1 rounded">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No vehicles found.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Modal -->
                    <div id="addVehicleModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
                        <div class="w-[500px] bg-white dark:bg-gray-900 rounded-2xl shadow-2xl p-6 transform transition-all scale-95">
                            <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100 text-center">Add New Vehicle</h2>
                            <form action="{{route('vehicles.store')}}" method="POST">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Make</label>
                                        <input type="text" name="make" required class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Model</label>
                                        <input type="text" name="model" required class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Year</label>
                                        <input type="number" name="year" required class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">License Plate</label>
                                        <input type="text" name="license_plate" required class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Color</label>
                                        <input type="text" name="color" required class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                                        <select name="type" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-white text-sm" required>
                                            <option value="car">Car</option>
                                            <option value="van">Van</option>
                                            <option value="motor_bike">Motor Bike</option>
                                            <option value="truck">Truck</option>
                                            <option value="bus">Bus</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                        <select name="status" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-white text-sm" required>
                                            <option value="available">Available</option>
                                            <option value="in_service">In Service</option>
                                            <option value="maintenance">Maintenance</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="flex justify-end gap-3 mt-6">
                                    <button type="button"
                                            onclick="document.getElementById('addVehicleModal').classList.add('hidden')"
                                            class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm rounded hover:bg-gray-300">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                            class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                                        Save
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Edit Vehicle Modal -->
                    <div id="editVehicleModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
                        <div class="w-[500px] bg-white dark:bg-gray-900 rounded-2xl shadow-2xl p-6 transform transition-all scale-95">
                            <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100 text-center">Edit Vehicle</h2>
                            <form action="{{route('vehicles.update', ':vehicle_id')}}" method="POST" id="editVehicleForm">
                                @csrf
                                @method('PUT')
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Make</label>
                                        <input type="text" name="make" required id="editMake" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Model</label>
                                        <input type="text" name="model" required id="editModel" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Year</label>
                                        <input type="number" name="year" required id="editYear" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">License Plate</label>
                                        <input type="text" name="license_plate" required id="editLicensePlate" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Color</label>
                                        <input type="text" name="color" required id="editColor" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                                        <select name="type" id="editType" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-white text-sm" required>
                                            <option value="car">Car</option>
                                            <option value="van">Van</option>
                                            <option value="motor_bike">Motor Bike</option>
                                            <option value="truck">Truck</option>
                                            <option value="bus">Bus</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                        <select name="status" id="editStatus" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-white text-sm" required>
                                            <option value="available">Available</option>
                                            <option value="in_service">In Service</option>
                                            <option value="maintenance">Maintenance</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="flex justify-end gap-3 mt-6">
                                    <button type="button" onclick="document.getElementById('editVehicleModal').classList.add('hidden')" class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm rounded hover:bg-gray-300">
                                        Cancel
                                    </button>
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                                        Save
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <script>
                        function openEditModal(id, make, model, year, licensePlate, color, type, status) {
                            // Set the form action to include the vehicle ID for updating
                            document.getElementById('editVehicleForm').action = "{{ route('vehicles.update', ':vehicle_id') }}".replace(':vehicle_id', id);

                            // Set the values in the form fields
                            document.getElementById('editMake').value = make;
                            document.getElementById('editModel').value = model;
                            document.getElementById('editYear').value = year;
                            document.getElementById('editLicensePlate').value = licensePlate;
                            document.getElementById('editColor').value = color;
                            document.getElementById('editType').value = type;
                            document.getElementById('editStatus').value = status;

                            // Show the modal
                            document.getElementById('editVehicleModal').classList.remove('hidden');
                        }
                    </script>
                    <script>
                        // Close modal when clicking outside of it
                        window.onclick = function(event) {
                            const addVehicleModal = document.getElementById('addVehicleModal');
                            const editVehicleModal = document.getElementById('editVehicleModal');
                            if (event.target === addVehicleModal || event.target === editVehicleModal) {
                                addVehicleModal.classList.add('hidden');
                                editVehicleModal.classList.add('hidden');
                            }
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

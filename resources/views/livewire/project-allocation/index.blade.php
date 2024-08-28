<div>
    <div>
        <section class="mt-10">
            <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                <div class="mt-20 bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">

                    <!-- Success Message -->
                    @if (session()->has('message'))
                    <div class="bg-green-500 text-white p-4 rounded shadow-md mb-6">
                        {{ session('message') }}
                    </div>
                    @endif

                    <div class="flex items-center justify-between p-4 flex-wrap gap-4">
                        <!-- Left Section: Search and Filters -->
                        <div class="flex space-x-3 items-center">
                            <!-- Search Input -->
                            <div class="relative w-48"> <!-- Adjusted width -->
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg aria-hidden="true" class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
                                </div>

                                <input wire:model.live.debounce="search" type="text" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-400 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-9 py-1.5" placeholder="Search">
                            </div>

                            <!-- Start Date Picker -->
                            <div class="mb-4">
                                <label for="employee-select" class="block text-sm font-medium text-gray-700 dark:text-gray-400">From Date</label>
                                <input type="date" wire:model.live="startDate" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-400 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full py-1.5" placeholder="Start Date">
                            </div>

                            <!-- End Date Picker -->
                            <div class="mb-4">
                                <label for="employee-select" class="block text-sm font-medium text-gray-700 dark:text-gray-400">To Date</label>
                                <input type="date" wire:model.live="endDate" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-400 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full py-1.5" placeholder="End Date">
                            </div>


                            <!-- Project Select Dropdown -->
                            <div class="mb-4">
                                <label for="project-select" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Select Project</label>
                                @if($projects->isNotEmpty())
                                <select wire:model.live="selectedProject" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-400 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full py-1.5">
                                    <option value="">Select Project</option>
                                    @foreach($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                                @else
                                <div class="flex flex-col items-center justify-center mt-4">
                                    <img src="{{ asset('images/no-data.png') }}" alt="No Data" class="h-24 w-24">
                                    <p class="text-gray-500 dark:text-gray-400 mt-2">No projects available</p>
                                </div>
                                @endif
                            </div>

<!-- Employee Select Dropdown -->
<div class="mb-4">
    <label for="employee-select" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Select Employee</label>
    <select id="employee-select" wire:model.live="selectedEmployee" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-400 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full py-1.5 mt-1">
        <option value="">Select Employee</option>
        @foreach($employees as $employee)
            <option value="{{ $employee->id }}">{{ $employee->name }}</option>
        @endforeach
    </select>
</div>

                            <!-- Search Button -->
                            <button wire:click="searchWithFilters" class="px-3 py-1.5 bg-blue-500 dark:bg-blue-600 text-white dark:text-gray-100 rounded-lg hover:bg-blue-600 dark:hover:bg-blue-700">
                                <svg aria-hidden="true" class="w-4 h-4 inline-block" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </button>

                        </div>

                        <button wire:click="exportToExcel" class="  btn bg-green-500 text-white hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 px-4 py-2 rounded flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            Export
                        </button>
                        <a href="{{ route('employee.project-allocations.create') }}" class="px-4 py-2 bg-blue-500 dark:bg-blue-600 text-white dark:text-gray-100 rounded-lg hover:bg-blue-600 dark:hover:bg-blue-700">Create</a>



                    </div>


                    <div id="main" class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 dark:text-gray-100 uppercase bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th wire:click="sortBy('project_id')" class="cursor-pointer px-4 py-2">Project</th>
                                    <th wire:click="sortBy('employee_id')" class="cursor-pointer px-4 py-2">Employee</th>
                                    <th wire:click="sortBy('role_id')" class="cursor-pointer px-4 py-2">Role</th>
                                    <th wire:click="sortBy('start_date')" class="cursor-pointer px-4 py-2">Start Date</th>
                                    <th wire:click="sortBy('end_date')" class="cursor-pointer px-4 py-2">End Date</th>
                                    <th wire:click="sortBy('allocated_by')" class="cursor-pointer px-4 py-2">Allocated By</th>
                                    <th scope="col" class="px-4 py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allocations as $allocation)
                                <tr class="border-b dark:border-gray-700">
                                    <td class="px-4 py-2">{{ $allocation->project->name }}</td>
                                    <td class="px-4 py-2">{{ $allocation->employee->name }}</td>
                                    <td class="px-4 py-2">{{ $allocation->role->name }}</td>
                                    <td class="px-4 py-2">{{ $allocation->start_date }}</td>
                                    <td class="px-4 py-2">{{ $allocation->end_date }}</td>
                                    <td class="px-4 py-2">{{ $allocation->allocatedBy->name }}</td>
                                    <td class="px-4 py-2 flex items-center">
                                        <a href="{{ route('employee.project-allocations.edit', $allocation->id) }}" class="px-3 py-1 mr-2 bg-gray-500 dark:bg-gray-600 text-white dark:text-gray-100 rounded hover:bg-gray-600 dark:hover:bg-gray-700">Edit</a>
                                        <button onclick="confirm('Are you sure you want to delete this allocation?') || event.stopImmediatePropagation()" wire:click="delete({{ $allocation->id }})" class="px-3 py-1 bg-red-500 dark:bg-red-600 text-white dark:text-gray-100 rounded hover:bg-red-600 dark:hover:bg-red-700">Delete</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="py-4 px-3">
                        {{ $allocations->links() }}
                    </div>


                    <div class="flex space-x-6 items-center"> <!-- Adjusted spacing -->
                        <!-- Per Page Selector -->
                        <div class="flex items-center space-x-2">
                            <label class="text-sm font-medium text-gray-900 dark:text-gray-200">Per Page :</label>
                            <select wire:model.live="perPage" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-400 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block py-1.5">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

</div>
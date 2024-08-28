<div>

    <div>
        <section class="mt-10 pl-40 ml">
            <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                <div class="mt-20 bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">

                    <!-- Success Message -->
                    @if (session()->has('message'))
                    <div class="bg-green-500 text-white p-4 rounded shadow-md mb-6">
                        {{ session('message') }}
                    </div>
                    @endif

                    <h2 class="text-base text-left font-semibold leading-tight text-gray-700">
                        Timesheet Report
                        <hr class="mt-2">
                    </h2>
                    <p class="mt-1 text-xs leading-6 text-gray-600"></p>
                    <div id="tbl-dashboard" class="tbl-dashboard text-sm my-5 flex gap-8">
                        <div class="bg-gray-200 text-center p-3" style="width: 400px;">
                            <p>Available (You)</p>
                            <p id="available_you" class="dashboad-content mt-3">{{ $availableTime }}</p>
                        </div>
                        <div class="bg-gray-200 text-center p-3" style="width: 400px;">
                            <p>Logged (You)</p>
                            <p id="logged_you" class="dashboad-content mt-3">{{ $loggedTime }}</p>
                        </div>
                        <div class="bg-gray-200 text-center p-3" style="width: 400px;">
                            <p>Deviation (You)</p>
                            <p id="deviation_you" class="dashboad-content mt-3 {{ $deviation < 0 ? 'text-red-500' : '' }}">
                                {{ $deviation }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 flex-wrap gap-4">

                        <!-- Left Section: Search and Filters -->
                        <div class="flex space-x-3 items-center text-sm">
                            <!-- Search Input -->
                            <div class="relative w-48">
                                <!-- Adjusted width -->
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-xs">
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
                                <select id="project-select" wire:model.live="selectedProject" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-400 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full py-1.5 mt-1">
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
                                    <option value="">All</option>
                                    @foreach($reportingEmployees as $reportingEmployee)
                                    <option value="{{ $reportingEmployee->id }}">{{ $reportingEmployee->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="relative w-20">   
                                <input wire:model.live.debounce="taskSearch" type="text" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-400 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full  py-1.5" placeholder="TaskId">
                            </div>
                            <!-- Search Button -->
                            <button wire:click="searchWithFilters" class="px-3 py-1.5 bg-blue-500 dark:bg-blue-600 text-white dark:text-gray-100 rounded-lg hover:bg-blue-600 dark:hover:bg-blue-700">
                                <svg aria-hidden="true" class="w-4 h-4 inline-block" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        <div class="flex space-x-3 justify-end">
                        <button wire:click="exportToExcel" class="btn bg-green-500 text-white hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 px-4 py-2 rounded flex items-center justify-end">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                           
                        </button>


                        
                            <a href="{{ route('employee.time-entries') }}" class="px-4 py-2 bg-blue-500 dark:bg-blue-600 text-white dark:text-gray-100 rounded-lg hover:bg-blue-600 dark:hover:bg-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>

                            </a>

                        </div>
                    </div>

                    <!-- Main Content -->





                    <div id="main" class="overflow-x-auto">
                        @if($timesheets->isEmpty())
                        <!-- Show if the table is empty -->
                        <div class="flex flex-col items-center justify-center py-10">
                            <img src="{{ asset('images/no-data.png') }}" alt="No Data" class="h-32 w-32">
                            <p class="text-gray-500 dark:text-gray-400 mt-2">No timesheets found</p>
                        </div>
                        @else
                        <!-- Table Section -->
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
    <thead class="text-xs text-gray-700 dark:text-gray-100 uppercase bg-gray-50 dark:bg-gray-700">
        <tr>
            <th wire:click="sortBy('project_id')" class="cursor-pointer px-4 py-2">Project</th>
            {{-- Display Employee column conditionally --}}
            @if(Auth::guard('employee')->id() !== $timesheets->first()->employee_id || $selectedEmployee)
                <th wire:click="sortBy('employee_id')" class="cursor-pointer px-4 py-2">Employee</th>
            @endif
            <th wire:click="sortBy('date')" class="cursor-pointer px-4 py-2">Date</th>
            <th wire:click="sortBy('taskid')" class="cursor-pointer px-4 py-2">Task Id</th>
            <th wire:click="sortBy('comment')" class="cursor-pointer px-4 py-2">Comment</th>
            <th wire:click="sortBy('time')" class="cursor-pointer px-4 py-2">Time</th>
            <th scope="col" class="px-4 py-2">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($timesheets as $timesheet)
            <tr class="border-b dark:border-gray-700">
                <td class="px-4 py-2">{{ $timesheet->project->name }}</td>
                {{-- Display employee name only if the timesheet does not belong to the logged-in user or if a specific employee is selected --}}
                @if(Auth::guard('employee')->id() !== $timesheet->employee_id || $selectedEmployee)
                    <td class="px-4 py-2">{{ $timesheet->employee->name }}</td>
                @endif
                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($timesheet->date)->format('d M Y') }}</td>
                <td class="px-4 py-2">{{ $timesheet->taskid ?: '' }}</td>
                <td class="px-4 py-2">{{ $timesheet->comment }}</td>
                <td class="px-4 py-2">{{ $timesheet->time }}</td>
                                    <td class="px-4 py-2 flex items-center">
                                        <a href="{{ route('employee.timesheet.edit',$timesheet->id) }}" class="px-3 py-1 mr-2 text-gray-500 dark:text-gray-100 rounded hover:text-gray-600 dark:hover:bg-gray-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                            </svg>
                                        </a>
                                        <button onclick="confirm('Are you sure you want to delete this timesheet?') || event.stopImmediatePropagation()" wire:click="delete({{ $timesheet->id }})" class="px-3 py-1  text-red-500 dark:text-gray-100 rounded hover:text-red-600 dark:hover:text-red-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                          
                            @if(Auth::guard('employee')->id() !== $timesheets->first()->employee_id )
                            <tfoot>
                                <tr class="bg-gray-100 dark:bg-gray-800">
                                    <td colspan="5" class="px-4 py-2 text-right font-semibold">Total Time:</td>
                                    <td colspan="2" class="px-4 py-2 font-semibold">
                                        {{ $loggedTime }}
                                    </td>
                                </tr>
                            </tfoot>
                            <tfoot>
                                <tr class="bg-gray-100 dark:bg-gray-800">
                                    <td colspan="5" class="px-4 py-2 text-right font-semibold">Available:</td>
                                    <td colspan="2" class="px-4 py-2 font-semibold">
                                        {{ $availableTime }}
                                    </td>
                                </tr>
                            </tfoot>
                            <tfoot>
                                <tr class="bg-gray-100 dark:bg-gray-800">
                                    <td colspan="5" class="px-4 py-2 text-right font-semibold">Logged</td>
                                    <td colspan="2" class="px-4 py-2 font-semibold">
                                        {{ $loggedTime }}
                                    </td>
                                </tr>
                            </tfoot>
                            <tfoot>
                                <tr class="bg-gray-100 dark:bg-gray-800">
                                    <td colspan="5" class="px-4 py-2 text-right font-semibold">Deviation</td>
                                    <td colspan="2" class="px-4 py-2 font-semibold {{ $deviation < 0 ? 'text-red-500' : '' }}">
                                        {{ $deviation }}
                                    </td>
                                </tr>
                            </tfoot>
                            @else
                            <tfoot>
                                <tr class="bg-gray-100 dark:bg-gray-800">
                                    <td colspan="4" class="px-4 py-2 text-right font-semibold">Total Time:</td>
                                    <td colspan="2" class="px-4 py-2 font-semibold">
                                        {{ $loggedTime }}
                                    </td>
                                </tr>
                            </tfoot>
                            <tfoot>
                                <tr class="bg-gray-100 dark:bg-gray-800">
                                    <td colspan="4" class="px-4 py-2 text-right font-semibold">Available:</td>
                                    <td colspan="2" class="px-4 py-2 font-semibold">
                                        {{ $availableTime }}
                                    </td>
                                </tr>
                            </tfoot>
                            <tfoot>
                                <tr class="bg-gray-100 dark:bg-gray-800">
                                    <td colspan="4" class="px-4 py-2 text-right font-semibold">Logged</td>
                                    <td colspan="2" class="px-4 py-2 font-semibold">
                                        {{ $loggedTime }}
                                    </td>
                                </tr>
                            </tfoot>
                            <tfoot>
                                <tr class="bg-gray-100 dark:bg-gray-800">
                                    <td colspan="4" class="px-4 py-2 text-right font-semibold">Deviation</td>
                                    <td colspan="2" class="px-4 py-2 font-semibold {{ $deviation < 0 ? 'text-red-500' : '' }}">
                                        {{ $deviation }}
                                    </td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                        <!-- Pagination -->
                        {{ $timesheets->links() }}
                        @endif
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
        <div class="flex items-center justify-center h-screen">
    

</div>
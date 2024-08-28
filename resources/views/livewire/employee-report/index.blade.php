<div>
    <div>
        <section class="mt-10 pl-40 ml">
            <div class="mx-auto lg:w-[1400px]">
                <div class="mt-20 bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <h2 class="text-base text-left font-semibold leading-tight text-gray-700">
                        Employee Report
                        <hr class="mt-2">
                    </h2>
                    <div class="flex flex-wrap p-6 gap-5">
                        <!-- Each card will take 1/7th of the row's width -->
                        <div class="w-1/7 bg-gray-200 dark:bg-gray-800 shadow-lg rounded-md flex items-center justify-between p-3 border-b-4 border-gray-400 dark:border-gray-600 text-blue-800 font-medium group">
                            <div class="flex justify-center items-center w-14 h-14 bg-gray-400 rounded-full transition-all duration-300 transform group-hover:rotate-12">
                            <span class="text-white text-lg">{{$projectHours}}</span>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl"></p>
                                <p>Project Hours</p>
                            </div>
                        </div>

                        <div class="w-1/7 bg-gray-200 dark:bg-gray-800 shadow-lg rounded-md flex items-center justify-between p-3 border-b-4 border-gray-400 dark:border-gray-600 text-blue-800 font-medium group">
                            <div class="flex justify-center items-center w-14 h-14 bg-gray-400 rounded-full transition-all duration-300 transform group-hover:rotate-12">
                            <span class="text-white text-lg">{{$beachHours}}</span>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl"></p>
                                <p>Bench Hour</p>
                            </div>
                        </div>

                        <div class="w-1/7 bg-gray-200 dark:bg-gray-800 shadow-lg rounded-md flex items-center justify-between p-3 border-b-4 border-gray-400 dark:border-gray-600 text-blue-800 font-medium group">
                            <div class="flex justify-center items-center w-14 h-14 bg-gray-400 rounded-full transition-all duration-300 transform group-hover:rotate-12">
                            <span class="text-white text-lg">{{$trainingHours}}</span>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl"></p>
                                <p>Training Hours</p>
                            </div>
                        </div>

                        <div class="w-1/7 bg-gray-200 dark:bg-gray-800 shadow-lg rounded-md flex items-center justify-between p-3 border-b-4 border-gray-400 dark:border-gray-600 text-blue-800 font-medium group">
                            <div class="flex justify-center items-center w-14 h-14 bg-gray-400 rounded-full transition-all duration-300 transform group-hover:rotate-12">
                            <span class="text-white text-lg">{{$learningHours}}</span>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl"></p>
                                <p>Learning Hours</p>
                            </div>
                        </div>

                        <div class="w-1/7 bg-gray-200 dark:bg-gray-800 shadow-lg rounded-md flex items-center justify-between p-3 border-b-4 border-gray-400 dark:border-gray-600 text-blue-800 font-medium group">
                            <div class="flex justify-center items-center w-14 h-14 bg-gray-400 rounded-full transition-all duration-300 transform group-hover:rotate-12">
                            <span class="text-white text-lg">{{$leaveDays}}</span>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl"></p>
                                <p>Leave Days</p>
                            </div>
                        </div>

                        <div class="w-1/7 bg-gray-200 dark:bg-gray-800 shadow-lg rounded-md flex items-center justify-between p-3 border-b-4 border-gray-400 dark:border-gray-600 text-blue-800 font-medium group">
                            <div class="flex justify-center items-center w-14 h-14 bg-gray-400 rounded-full transition-all duration-300 transform group-hover:rotate-12">
                                <span class="text-white text-lg">{{$totalTime}}</span>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl"></p>
                                <p>Total Hours</p>
                            </div>
                        </div>

                        <div class="w-1/7 bg-gray-200 dark:bg-gray-800 shadow-lg rounded-md flex items-center justify-between p-3 border-b-4 border-gray-400 dark:border-gray-600 text-blue-800 font-medium group">
                            <div class="flex justify-center items-center w-14 h-14 bg-gray-400 rounded-full transition-all duration-300 transform group-hover:rotate-12">
                                <span class=" {{ $deviation < 0 ? 'text-red-500' : 'text-white text-lg' }}">{{$deviation}}</span>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl"></p>
                                <p>Deviation</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between p-4 flex-wrap gap-4">

                        <!-- Left Section: Search and Filters -->
                        <div class="flex space-x-3 items-center">
                            <!-- Search Input -->
                            <div class="relative w-48">
                                <!-- Adjusted width -->
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg aria-hidden="true" class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input wire:model.live.debounce="search" type="text" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-400 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-9 py-1.5" placeholder="Search">
                            </div>

                            <!-- Start Date Picker -->

                            <div class="mb-4">
    <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-400">From Date</label>
    <input 
        type="date" 
        wire:model.live="startDate" 
        class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-400 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full py-1.5" 
        placeholder="Start Date" 
        id="start_date"
        min="{{ $startDate }}" 
    >
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
                            <!-- Search Button -->
                            <button wire:click="searchWithFilters" class="px-3 py-1.5 bg-blue-500 dark:bg-blue-600 text-white dark:text-gray-100 rounded-lg hover:bg-blue-600 dark:hover:bg-blue-700">
                                <svg aria-hidden="true" class="w-4 h-4 inline-block" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 dark:text-gray-100 uppercase bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th>Employee</th>
                                    <th>Reporting Manager</th>
                                    <th>Project hours</th>
                                    <th>Bench hours</th>
                                    <th>Leave days</th>
                                    <th>Training hours</th>
                                    <th>Learning hours</th>
                                    <th>Total hours</th>
                                    <th>Deviation</th>
                                </tr>
                            </thead>
                            <tbody>
        <tr class="border-b dark:border-gray-700">
        @if($selectedEmployeeDetails)
    
    <td class="px-4 py-2">{{  $selectedEmployeeDetails->name }}</td>
            <td>{{ $selectedEmployeeDetails->reportingManager->name ?? 'N/A' }}</td>
@else
            <td class="px-4 py-2">{{ $employee->name }}</td>
            <td>{{ $employee->reportingManager ? $employee->reportingManager->name : '' }}</td>
            @endif
            <td>{{ $projectHours }}</td>
            <td>{{ $beachHours }}</td>
            <td>{{ $leaveDays }}</td>
            <td>{{ $trainingHours }}</td>
            <td>{{ $learningHours }}</td>
            <td>{{ $totalTime }}</td>
            <td class="{{ $deviation < 0 ? 'text-red-500' : '' }}">{{ $deviation }}</td>
        </tr>
        <tr></tr>
    </tbody>
    <tfoot class="bg-gray-200 dark:bg-gray-700">
        <tr>
            <td class="px-4 py-2 font-semibold"></td>
            <td class="px-4 py-2 font-semibold">Total</td>
            <td class="font-semibold">{{ $projectHours }}</td>
            <td class="font-semibold">{{ $beachHours }}</td>
            <td class="font-semibold">{{ $leaveDays }}</td>
            <td class="font-semibold">{{ $trainingHours }}</td>
            <td class="font-semibold">{{ $learningHours }}</td>
            <td class="font-semibold">{{ $totalTime }}</td>
            <td class="font-semibold {{ $deviation < 0 ? 'text-red-500' : '' }}">{{ $deviation }}</td>
        </tr>
    </tfoot>
                        </table>


                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
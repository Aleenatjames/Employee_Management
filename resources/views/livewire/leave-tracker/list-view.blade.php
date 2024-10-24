<div>

    <div class="flex justify-between items-center mb-4 mt-10 bg-white dark:bg-gray-600 px-10 py-5">
        <!-- Centered Date Display and Navigation -->

        <!-- Previous Week/Month Arrow -->
        <div class="mb-4 justify-start ml-2">
            <select id="employee-select" wire:model.live="selectedEmployee" wire:change="loadReportingEmployees" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-400 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full py-1.5 mt-4">
                <option value="">Select Employee</option>
                @if(!empty($reportingEmployees))
                @foreach($reportingEmployees as $reportingEmployee)
                <option value="{{ $reportingEmployee->id }}">{{ $reportingEmployee->name }}</option>
                @endforeach
                @endif
            </select>
        </div>

        <div class="flex-grow flex items-center justify-center">
            <!-- Previous Year Button -->
            <button wire:click="previousPeriod" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
            </button>

            <!-- Year Range Display -->
            <span class="mx-4 text-gray-500 dark:text-gray-400 justify-center text-lg ">
                {{ $yearRange }}
            </span>

            <!-- Next Year Button -->
            <button wire:click="nextPeriod" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
            </button>
        </div>
        <div class="mb-4 justify-end">
            <a href="{{route('employee.leave.apply')}}" type="button" class="bg-yellow-500 px-4 text-white py-2 rounded border-separate">Apply Leave</a>

        </div>

    </div>
    <div class="flex flex-col lg:flex-row justify-between h-auto dark:bg-gray-200 m-10">

        <div class="flex-1 mx-2 mb-6 lg:mb-0 shadow-lg bg-white dark:bg-gray-800 min-h-80 flex flex-col">
            <h4 class="text-l font-semibold mt-4 mb-2 text-center w-full truncate" title="Training Period">Casual Leave</h4>

            <div class="flex-1 flex flex-col justify-center items-center">
                <!-- SVG Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="0.7" stroke="#80bc42" class="size-20 mb-6">

                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    <text x="50%" y="50%" text-anchor="middle" dy=".7em" font-size="8" fill="green" font-weight="10">!</text>
                </svg>

                <table class=" text-center">
                    <tr>
                        <td class="text-gray-600 px-2">Available:</td>
                        <td class="text-gray-600"> {{ $leaveData['Casual Leave']['allocated'] ?? '0' }}</td>
                    </tr>
                    <tr>
                        <td class="text-gray-600">Booked:</td>
                        <td class="text-red-500"> {{ $leaveData['Casual Leave']['used'] ?? '0' }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="flex-1 mx-2 mb-6 lg:mb-0 shadow-lg bg-white dark:bg-gray-800 min-h-80 flex flex-col">
            <h4 class="text-l font-semibold mt-4 mb-2 text-center w-full truncate" title="Work From Home">Compensatory Off</h4>

            <!-- Middle content: WF text and Available/Booked table -->
            <div class="flex-1 flex flex-col justify-center items-center">
                <!-- WF Text in the middle -->
                <h2 class=" text-5xl mb-10" style="color: #96bc42;">CO</h2>

                <table class=" text-center">
                    <tr>
                        <td class="text-gray-600 px-2">Available:</td>
                        <td class="text-gray-600"> {{ $leaveData['Compensatory Off']['allocated'] ?? '0' }}</td>
                    </tr>
                    <tr>
                        <td class="text-gray-600">Booked:</td>
                        <td class="text-red-500"> {{ $leaveData['Compensatory Off']['used'] ?? '0' }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="flex-1 mx-2 mb-6 lg:mb-0 shadow-lg bg-white dark:bg-gray-800 min-h-80 flex flex-col">
            <h4 class="text-l font-semibold mt-4 mb-2 text-center w-full truncate" title="Training Period">LOP</h4>

            <div class="flex-1 flex flex-col justify-center items-center">
                <!-- SVG Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="0.7" stroke="#ab4afc" class="size-20 mb-6">

                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    <text x="50%" y="50%" text-anchor="middle" dy=".7em" font-size="8" fill="green" font-weight="10">!</text>
                </svg>

                <table class="text-center">
                    <tr>
                        <td class="text-gray-600">Available:</td>
                        <td class="text-gray-600"> {{ $leaveData['Loss Of Pay']['allocated'] ?? '0' }}</td>
                    </tr>
                    <tr>
                        <td class="text-gray-600">Booked:</td>
                        <td class="text-red-500"> {{ $leaveData['Loss Of Pay']['allocated'] ?? '0' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="flex-1 mx-2 mb-6 lg:mb-0 shadow-lg bg-white dark:bg-gray-800 min-h-80 flex flex-col">
            <h4 class="text-l font-semibold mt-4 mb-2 text-center w-full truncate" title="Training Period">Restricted/Optional Holidays</h4>

            <div class="flex-1 flex flex-col justify-center items-center">
                <!-- SVG Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="0.7" stroke="#64d5fd" class="size-20 mb-6">

                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    <text x="50%" y="50%" text-anchor="middle" dy=".7em" font-size="8" fill="green" font-weight="10">!</text>
                </svg>

                <table class=" text-center">
                    <tr>
                        <td class="text-gray-600 px-2">Available:</td>
                        <td class="text-gray-600"> {{ $leaveData['Restricted Holiday']['allocated'] ?? '0' }}</td>
                    </tr>
                    <tr>
                        <td class="text-gray-600">Booked:</td>
                        <td class="text-red-500"> {{ $leaveData['Restricted Holiday']['used'] ?? '0' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="flex-1 mx-2 mb-6 lg:mb-0 shadow-lg bg-white dark:bg-gray-800 min-h-80 flex flex-col">
            <h4 class="text-l font-semibold mt-4 mb-2 text-center w-full truncate" title="Training Period">Sick Leave</h4>

            <div class="flex-1 flex flex-col justify-center items-center">
                <!-- SVG Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="0.7" stroke="#f2d613" class="size-20 mb-6">

                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    <text x="50%" y="50%" text-anchor="middle" dy=".7em" font-size="8" fill="green" font-weight="10">!</text>
                </svg>

                <table class=" text-center">
                    <tr>
                        <td class="text-gray-600 px-2">Available:</td>
                        <td class="text-gray-600"> {{ $leaveData['Sick Leave']['allocated'] ?? '0' }}</td>
                    </tr>
                    <tr>
                        <td class="text-gray-600">Booked:</td>
                        <td class="text-red-500"> {{ $leaveData['Sick Leave']['used'] ?? '0' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="flex-1 mx-2 mb-6 lg:mb-0 shadow-lg bg-white dark:bg-gray-800 min-h-80 flex flex-col">
            <h4 class="text-l font-semibold mt-4 mb-2 text-center w-full truncate" title="Training Period">Training Period</h4>

            <div class="flex-1 flex flex-col justify-center items-center">
                <!-- SVG Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="0.7" stroke="#e05654" class="size-20 mb-6">

                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    <text x="50%" y="50%" text-anchor="middle" dy=".7em" font-size="8" fill="green" font-weight="10">!</text>
                </svg>

                <table class=" text-center">
                    <tr>
                        <td class="text-gray-600 px-2">Available:</td>
                        <td class="text-gray-600"> {{ $leaveData['Training Period Leave']['allocated'] ?? '0' }}</td>
                    </tr>
                    <tr>
                        <td class="text-gray-600">Booked:</td>
                        <td class="text-red-500"> {{ $leaveData['Training Period Leave']['used'] ?? '0' }}</td>
                    </tr>
                </table>
            </div>
        </div>


        <div class="flex-1 mx-2 mb-6 lg:mb-0 shadow-lg bg-white dark:bg-gray-800 min-h-80 flex flex-col">
            <h4 class="text-l font-semibold mt-4 mb-2 text-center w-full truncate" title="Work From Home">Work From Home</h4>

            <!-- Middle content: WF text and Available/Booked table -->
            <div class="flex-1 flex flex-col justify-center items-center">
                <!-- WF Text in the middle -->
                <h2 class="text-blue-300 text-5xl mb-10">WF</h2>

                <table class=" text-center">
                    <tr>
                        <td class="text-gray-600 px-2">Available:</td>
                        <td class="text-gray-600"> {{ $leaveData['Work From Home']['allocated'] ?? '0' }}</td>
                    </tr>
                    <tr>
                        <td class="text-gray-600">Booked:</td>
                        <td class="text-red-500"> {{ $leaveData['Work From Home']['used'] ?? '0' }}</td>
                    </tr>
                </table>
            </div>
        </div>

    </div>

    <div x-data="{ dropdownOpen: false }" class="flex flex-col lg:flex-row justify-between my-20 h-auto dark:bg-gray-200 m-10">
        <div class="flex-1 mx-2 mb-6 lg:mb-0 shadow-lg bg-white dark:bg-gray-800 min-h-80">
            <div class="border-b border-gray-200 flex justify-between items-center">
                <!-- Dropdown Toggle Button -->
                <div class="relative">
                    <button @click="dropdownOpen = !dropdownOpen" class="font-bold text-lg p-4 bg-white dark:bg-gray-800 flex items-center focus:outline-none">
                        <h1>{{ $selectedOption }}</h1>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 ml-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown Content -->
                    <div x-show="dropdownOpen" @click.away="dropdownOpen = false" class="absolute z-10 mt-2 w-48 bg-white dark:bg-gray-800 shadow-lg rounded-md py-1" x-cloak>
                        <div @click="dropdownOpen = false" wire:click="setOption('All Leave and Holidays')" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                            All Leave and Holidays
                        </div>
                        <div @click="dropdownOpen = false" wire:click="setOption('Only Leave')" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                            Only Leave
                        </div>
                        <div @click="dropdownOpen = false" wire:click="setOption('Only Holidays')" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                            Only Holidays
                        </div>
                    </div>
                </div>
                <div class="flex bg-gray-200 m-4 text-sm rounded dark:bg-gray-600">
                    <!-- 'Upcoming' Button -->
                    <button wire:click="filter('upcoming')" class=" py-2 px-4 hover:text-blue-600 transition duration-200 {{ $filtering == 'upcoming' ? 'text-blue-600' : 'text-gray-400' }}">
                        Upcoming
                    </button>

                    <!-- 'History' Button -->
                    <button wire:click="filter('history')" class=" py-2 px-4 hover:text-blue-600 transition duration-200 {{ $filtering == 'history' ? 'text-blue-600' : 'text-gray-400' }}">
                        History
                    </button>
                </div>
            </div>
            <!-- Table for Holidays and Leaves -->
            <table class="min-w-full divide-y divide-gray-200 dark:bg-gray-800">
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800">
                    <!-- Conditional Display based on Selected Option -->
                    @if($selectedOption === 'All Leave and Holidays' || $selectedOption === 'Only Holidays')
                    <!-- Holidays Section -->
                    @foreach($holidays as $holiday)
                    <tr>
                        <td class="px-4 py-6">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-blue-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                                </svg>
                                <span class="ml-2">{{ \Carbon\Carbon::parse($holiday->date)->format('j-M-D') }}</span>
                            </div>
                        </td>
                        <td class=" py-3">{{ $holiday->reason }}</td>
                        <td></td>
                        <td class="px-4 py-3"></td> <!-- Empty status cell for holidays -->
                    </tr>
                    @endforeach
                    @endif

                    @if($selectedOption === 'All Leave and Holidays' || $selectedOption === 'Only Leave')
                    <!-- Leaves Section -->
                    @foreach($leaveApplications as $leave)
                    <tr>
                        <td class="px-4 py-6">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-green-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                                </svg>
                                @php
                                $from_date_carbon=Carbon\Carbon::parse($leave->from_date);
                                @endphp
                                @if($from_date_carbon->equalTo($leave->to_date))
                                <span class="ml-2">{{ \Carbon\Carbon::parse($leave->to_date)->format('j-M-D') }}</span>
                                @else
                                <span class="ml-2">{{ \Carbon\Carbon::parse($leave->from_date)->format('j-M-D') }}</span>&nbsp;&nbsp;to
                                <span class="ml-2">{{ \Carbon\Carbon::parse($leave->to_date)->format('j-M-D') }}</span>
                                @endif
                            </div>
                        </td>
                        <td class=" py-3 flex items-center">
                            <div class="
                                        {{ $leave->leaveType->name === 'Casual Leave' ? 'border-l-4 border-green-600' :
                                        ($leave->leaveType->name === 'Sick Leave' ? 'border-l-4 border-yellow-500' : 
                                        ($leave->leaveType->name === 'Restricted Holiday' ? 'border-l-4 border-blue-500' : '')) }}
                                        h-4" style="height: 30px;">
                            </div>
                            <div class="flex-grow pl-2">
                                <div>{{ $leave->leaveType->name }}</div>
                                <div class="text-gray-400 text-sm">

                                    {{$leave->no_of_days}}&nbsp;Day(s)
                                </div>
                            </div>
                        </td>
                        <td>
                            {{$leave->reason}}
                        </td>
                        <td class="px-4 py-3 text-gray-600">
                            <div class="flex items-center">
                                @if($leave->status == 'pending')
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" stroke="#faba59" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M6 3h12v3H6zM6 18h12v3H6zM12 9l6 6H6z" />
                                    <path d="M6 6l6 6 6-6" />
                                    <path d="M12 12v6" />
                                </svg>
                                @elseif($leave->status == 'fully approved')
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" stroke="#4CAF50" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <polyline points="20 6 9 17 4 12" />
                                    <polyline points="16 6 9 13 7 11" />
                                </svg>
                                @elseif($leave->status =='partial approved')
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" stroke="#4CAF50" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                                @elseif($leave->status == 'rejected')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#F44336" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                                @endif

                                {{ $leave->status }}
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
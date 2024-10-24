<div class="bg-gray-100 dark:bg-gray-700">
    <div class="flex flex-col lg:flex-row justify-between my-5 mx-3 dark:bg-gray-200 mt-20 text-gray-500 ">
        <div class="flex lg:mb-0 shadow-lg bg-white dark:bg-gray-800 w-full h-14 items- p-4 rounded justify-between items-center">
            <a wire:click="previousPeriod" href="{{ route('employee.leave.application') }}"
                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 flex items-">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#FBC02D" class="size-7 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
                <h1 class="text-xl">Leave</h1>
            </a>

            <div class="flex items- ml-auto">
                <div class="mb-4 flex">
                    @if($leaveApplication->status == 'pending')
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" stroke="#faba59" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 3h12v3H6zM6 18h12v3H6zM12 9l6 6H6z" />
                        <path d="M6 6l6 6 6-6" />
                        <path d="M12 12v6" />
                    </svg>
                    @elseif($leaveApplication->status == 'fully approved')
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" stroke="#4CAF50" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12" />
                        <polyline points="16 6 9 13 7 11" />
                    </svg>
                    @elseif($leaveApplication->status =='partial approved')
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" stroke="#4CAF50" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12" />
                    </svg>
                    @elseif($leaveApplication->status == 'rejected')
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#F44336" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>

                    @endif
                    Approval
                    {{$leaveApplication->status}}
                    <div x-data="{ open: false }">
                        <!-- View Button -->
                        <a href="#" @click="open = true" class="bg-yellow-500 px-2 text-white py-1 rounded border-separate text-sm mx-3">View</a>

                        <!-- Modal Background Overlay -->
                        <div x-show="open" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-end " style="display: none;">
                            <!-- Modal Box (full height based on content) -->
                            <div @click.away="open = false" class="bg-white w-full sm:w-1/3 h-full p-6 shadow-lg overflow-y-auto relative mt-28 dark:bg-gray-700">
                                <!-- Close Button -->
                                <button @click="open = false" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                </button>

                                <!-- Modal Content -->
                                <div class="text-center p-4 border-b dark:bg-gray-800">
                                    <h2 class="text-xl font-bold mb-4 text-left py-4">Approval Timeline</h2>
                                    @if(session()->has('message'))
                                    <div class="bg-green-500 text-white p-4 rounded shadow-md mb-6">
                                        {{session('message')}}
                                    </div>
                                    @endif
                                    @if(session()->has('error'))
                                    <div class="bg-red-500 text-white p-4 rounded shadow-md mb-6">
                                        {{session('error')}}
                                    </div>
                                    @endif

                                    <!-- Employee Name and Submission Date -->
                                    <p class="text-left py-4">
                                        <strong>{{ $leaveApplication->employee->id }} - {{ $leaveApplication->employee->name }}</strong>
                                        request has been sent for approval
                                        <br>
                                        <span class="text-sm text-gray-400">
                                            {{ $leaveApplication->created_at->format('F j, Y, g:i a') }}
                                        </span>
                                    </p>

                                    <!-- Reporting Manager -->
                                    <p class="text-left py-4">
                                        <strong>{{ $leaveApplication->status }}</strong>
                                    </p>

                                    <!-- Reporting Manager's Section -->
                                    <div class="mx-7 px-5 border-l">
                                        <div class="text-left py-4">
                                            <span>{{ $leaveApplication->employee->manager->id ?? '' }} - {{ $leaveApplication->employee->manager->name ?? 'Not assigned' }}</span>
                                            <span class="block text-gray-400">{{ $leaveApplication->employee->manager->email ?? '' }}</span>
                                        </div>

                                        @if (!$leaveApplication->manager_approved && auth()->guard('employee')->user()->id === $leaveApplication->employee->manager->id)
                                        <!-- Reporting Manager's Comment Section -->
                                        <div class="text-left py-4 ">
                                            <label for="manager_comment" class="block text-gray-700">Comments (optional):</label>
                                            <textarea id="manager_comment" wire:model.defer="managerComment"
                                                class="dark:bg-gray-700 mt-1 block w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-yellow-500"
                                                rows="3" placeholder="Add a comment...">
                                            </textarea>
                                        </div>

                                        <!-- Reporting Manager's Approval and Rejection Buttons -->
                                        <div class="flex space-x-2 py-4">
                                            <button wire:click="approveLeave('manager')" class="bg-green-500 text-white py-2 px-4 rounded-sm hover:bg-green-600">
                                                Approve
                                            </button>
                                            <button wire:click="rejectLeave('manager')" class="bg-red-500 text-white py-2 px-4 rounded-md hover:bg-red-600">
                                                Reject
                                            </button>
                                        </div>
                                        @endif
                                    </div>

                                    <!-- Reporting Manager's Manager Section -->
                                    @if( $leaveApplication->employee->manager->manager && !$leaveApplication->manager_manager_approved)
                                    <div class="mx-7 px-5 border-l mt-4">
                                        <div class="text-left py-4">
                                            <span>{{ $leaveApplication->employee->manager->manager->id ?? '' }} - {{ $leaveApplication->employee->manager->manager->name ?? 'Not assigned' }}</span>
                                            <span class="block text-gray-400">{{ $leaveApplication->employee->manager->manager->email ?? '' }}</span>
                                        </div>

                                        @if (auth()->guard('employee')->user()->id === $leaveApplication->employee->manager->manager->id)
                                        <!-- Reporting Manager's Manager's Comment Section -->
                                        <div class="text-left py-4">
                                            <label for="manager_manager_comment" class="block text-gray-700">Comments (optional):</label>
                                            <textarea id="manager_manager_comment" wire:model.defer="managerManagerComment"
                                                class="mt-1 block w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-yellow-500"
                                                rows="3" placeholder="Add a comment..."></textarea>
                                        </div>

                                        <!-- Reporting Manager's Manager's Approval and Rejection Buttons -->
                                        <div class="flex space-x-2 py-4">
                                            <button wire:click="approveLeave('manager_manager')" class="bg-green-500 text-white py-2 px-4 rounded-sm hover:bg-green-600">
                                                Approve
                                            </button>
                                            <button wire:click="rejectLeave('manager_manager')" class="bg-red-500 text-white py-2 px-4 rounded-md hover:bg-red-600">
                                                Reject
                                            </button>
                                        </div>
                                        @endif
                                    </div>
                                    @endif

                                </div>
                                <!-- Leave Application Details -->
                                <div class="p-4  pb-10">
                                    <div class="text-gray-600 font-bold text-left">
                                        <span class="text-gray-600">{{ $leaveApplication->lastupdated->name ?? 'Not available' }}</span>
                                        <span class="text-gray-600">{{ $leaveApplication->status }}</span>
                                    </div>
                                    <div class="text-left py-2">
                                        @foreach($leaveApprovals as $approval)
                                        @if($approval->comment)
                                        <p><strong>Comment:</strong> {{ $approval->comment }}</p>
                                        @endif
                                        @endforeach
                                        <span class="block text-gray-400">{{ $leaveApplication->updated_at->format('d M Y, h:i A') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <a wire:click="previousPeriod" href="{{ route('employee.leave.application') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#D32F2F" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
    <div class="flex flex-col lg:flex-row mx-3 dark:bg-gray-200 ">
        <div class="mb-6 lg:mb-0 shadow-lg bg-white dark:bg-gray-800 w-full h-auto p-4 rounded pb-20">
            <div class="font-bold text-lg mb-4 ">
                <h1>Leave Application Details</h1>
            </div>

            <!-- Employee Name -->
            <div class="mt-4 flex items-center text-gray-500 border-b">
                <label class="text-lg mr-4 w-1/3">Employee Name:</label>
                <p class="block w-1/3 p-2 text-lg dark:bg-gray-700 dark:text-white">{{ $leaveApplication->employee->name }}</p>
            </div>

            <!-- Leave Type -->
            <div class="mt-4 flex items-center text-gray-500 border-b">
                <label class="text-lg mr-4 w-1/3">Leave Type:</label>
                <p class="block w-1/3 p-2 text-lg dark:bg-gray-700 dark:text-white">{{ $leaveApplication->leaveType->name }}</p>
            </div>

            <!-- Date Range -->
            <div class="mt-4 flex items-center text-gray-500 border-b ">
                <label class="text-lg mr-4 w-1/3">Date:</label>
                <p class="block w-1/3 p-2 text-lg dark:bg-gray-700 dark:text-white">
                    {{ \Carbon\Carbon::parse($leaveApplication->from_date)->format('d-M-Y') }} - {{ \Carbon\Carbon::parse($leaveApplication->to_date)->format('d-M-Y') }}
                </p>
            </div>
            <div class="mt-4 flex items-center text-gray-500 border-b">
                <label class="text-sm mr-4 w-1/3"></label>
                <div class="block w-1/3 p-2 text-sm dark:bg-gray-700 dark:text-white">

                    <!-- Gray background box with date and duration heading -->
                    <div class="h-14 bg-gray-200 flex items-center justify-between px-4 py-2 rounded-sm dark:bg-gray-600">
                        <h2 class="text-sm font-bold">Date</h2>
                        <h2 class="text-sm font-bold">Duration</h2>
                    </div>

                    <div class="mt-2">
                        @foreach($leaveApplication->leaveApplicationDates as $leaveDate)
                        <div class="flex justify-between px-4 py-2">
                            <!-- Date -->
                            <p class="text-gray-500">
                                {{ \Carbon\Carbon::parse($leaveDate->date)->format('d-M-Y') }}
                            </p>
                            <!-- Duration (Full Day, First Half, Second Half) -->
                            <p class="text-gray-500">
                                @if($leaveDate->duration == '0')
                                1 Day(s)
                                @elseif($leaveDate->duration == '1')
                                0.5 Day(s)
                                @elseif($leaveDate->duration == '2')
                                0.5 Day(s)
                                @endif
                            </p>
                        </div>
                        @endforeach
                    </div>
                    <!-- Total Days in Gray Background -->
                    <div class="h-14 bg-gray-200 flex items-center justify-between px-4 py-2 rounded-sm mt-4 dark:bg-gray-600">
                        <h2 class="text-sm font-bold">Total Days</h2>
                        <p class="text-sm font-bold">{{ $leaveApplication->no_of_days }} Days</p>
                    </div>
                </div>
            </div>
            <!-- Number of Days -->
            <div class="mt-4 flex items-center text-gray-500 border-b">
                <label class="text-lg mr-4 w-1/3">Number of Days:</label>
                <p class="block w-1/3 p-2 text-lg dark:bg-gray-700 dark:text-white">{{ $leaveApplication->no_of_days }}</p>
            </div>

            <!-- Reason for Leave -->
            <div class="mt-4 flex items-center text-gray-500 border-b">
                <label class="text-lg mr-4 w-1/3">Reason:</label>
                <p class="block w-1/3 p-2 text-lg dark:bg-gray-700 dark:text-white">{{ $leaveApplication->reason }}</p>
            </div>

            <!-- Leave Status -->
            <div class="mt-4 flex items-center text-gray-500 border-b">
                <label class="text-lg mr-4 w-1/3">Status:</label>
                <p class="block w-1/3 p-2 text-lg dark:bg-gray-700 dark:text-white">{{ $leaveApplication->status }}</p>
            </div>

            <!-- Back Button -->
            <div class="mt-4">
                <a href="{{ route('employee.leave.application') }}" class="bg-yellow-500 text-white p-2 rounded hover:bg-yellow-600 focus:outline-none text-lg">
                    Back to Leave List
                </a>
            </div>
        </div>
    </div>

</div>
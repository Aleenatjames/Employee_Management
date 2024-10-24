<div class="bg-gray-100">
    <div class="flex flex-col lg:flex-row justify-between my-5 mx-3 dark:bg-gray-200 mt-20 text-gray-500">
        <div class="flex lg:mb-0 shadow-lg bg-white dark:bg-gray-800 w-full h-14 items- p-4 rounded justify-between items-center">
            <a wire:click="previousPeriod" href="{{ route('employee.leave.list') }}"
                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 flex items-">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#FBC02D" class="size-7 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
                <h1 class="text-xl">Apply Leave</h1>
            </a>
            <div class="flex items- ml-auto">
                <a wire:click="previousPeriod" href="{{ route('employee.leave.list') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#D32F2F" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
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

    <form wire:submit.prevent="submit"> <!-- Wire the form to the submit function -->
        <div class="flex flex-col lg:flex-row mx-3 dark:bg-gray-200 ">
            <div class="mb-6 lg:mb-0 shadow-lg bg-white dark:bg-gray-800 w-full h-auto p-4 rounded pb-20">
                <div class="font-bold text-lg mb-4 ">
                    <h1>Leave Application</h1>
                </div>

           <!-- Select Employee Row -->
           <div class="mt-8 flex items-center text-gray-500">
                    <label class="text-lg mr-4 w-1/3">Select Employee</label>
                    <select wire:model.live="employee" class="block w-1/3 p-2 text-lg dark:bg-gray-700 dark:text-white placeholder-gray-400"
                        name="employee" id="employee" style="border:none; border-bottom: 1px solid gray;">
                        <option value="" selected>-- Select Employee --</option>
                        <!-- Add employee options dynamically from the database -->
                        @foreach($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                        @endforeach
                    </select>
                    @error('employee') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Leave Type Row -->
                <div class="mt-8 flex items-center text-gray-500">
                    <label class="text-lg mr-4 w-1/3">Leave Type</label>
                    <select wire:model.live="leave_type" class="block w-1/3 p-2 text-lg dark:bg-gray-700 dark:text-white placeholder-gray-400"
                        name="leave_type" id="leave_type" style="border:none; border-bottom: 1px solid gray;">
                        <option value="" disabled selected>-- Select Leave Type --</option>
                        <!-- Add leave type options dynamically from the database -->
                        @foreach($leave_types as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                    @error('leave_type') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>
            <!-- Date Row -->
<div class="mt-8 flex items-center text-gray-500">
    <label class="text-lg mr-4 w-1/3">Date</label>
    <input type="date" wire:model.live="date_from" class="block w-1/6 p-2 text-lg dark:bg-gray-700 dark:text-white placeholder-gray-400"
           name="date_from" id="date_from" style="border:none; border-bottom: 1px solid gray;">
    <input type="date" wire:model.live="date_to" class="block w-1/6 p-2 text-lg dark:bg-gray-700 dark:text-white placeholder-gray-400"
           name="date_to" id="date_to" style="border:none; border-bottom: 1px solid gray;">
    @error('date_from') <span class="text-red-500">{{ $message }}</span> @enderror
    @error('date_to') <span class="text-red-500">{{ $message }}</span> @enderror
</div>

<!-- Selected Dates Display -->
@if(!empty($selectedDates))
    <div class="mt-8 flex items-center text-gray-500">
        <label class="text-lg mr-4 w-1/3"></label>
        <div class="mt-4 block w-1/3 p-2 border border-gray-300 rounded bg-gray-50 dark:bg-gray-700">
            <h2 class="text-lg font-bold">Confirm Dates</h2>
            <div>
                @foreach($selectedDates as $date)
                    <div class="flex space-x-9 p-5">
                        <span>{{ $date }}</span>

                        <!-- Check if the day is marked as a weekend -->
                        @if(isset($dayType[$date]) && $dayType[$date] === 'weekend')
                            <span class="text-gray-500">Weekend</span>
                        @else
                            <!-- Show Full Day / Half Day options for non-weekend days -->
                            <select wire:model.live="dayType.{{ $date }}">
                                <option value="0">Full Day</option>
                                <option value="1">First Half</option>
                                <option value="2">Second Half</option>
                            </select>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Total Days Calculation -->
            <div class="mt-4">
                <h3 class="text-lg font-semibold">Total Leave Days: {{ $totalDays }}</h3>
            </div>
        </div>
    </div>
@endif


                <!-- Reason Textarea -->
                <div class="mt-8 flex items-center text-gray-500">
                    <label class="block mb-1 text-lg w-1/3">Reason</label>
                    <textarea wire:model="reason" class="block w-1/3 p-2 text-lg dark:bg-gray-700 dark:text-white placeholder-gray-400"
                        name="reason" id="reason" rows="4" placeholder="Enter the reason for your leave..."
                        style="border:none; border-bottom: 1px solid gray;"></textarea>
                    @error('reason') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row mx-3 dark:bg-gray-200 mt-5">
            <div class="lg:mb-0 shadow-lg bg-white dark:bg-gray-800 w-full h-auto p-4 rounded">
                <!-- Submit Button -->
                <div class="mt-4">
                    <button type="submit" class="bg-yellow-500 text-white p-2 rounded hover:bg-yellow-600 focus:outline-none text-lg">
                        Submit
                    </button>
                    <a type="submit" class="bg-white-500 text-gray-500 border-yellow-200 border-1 p-2 rounded hover:bg-yellow-600  text-lg">
                        cancel
</a>
                </div>
            </div>
        </div>
    </form>
</div>

</div>
<div class="">
    @php
    use Carbon\Carbon;

    @endphp

    <div class="flex flex-col lg:flex-row justify-between my-16 mx-3 h-auto dark:bg-gray-200">
        <div class="flex-1 mx-2 mb-6 lg:mb-0 shadow-lg bg-white dark:bg-gray-800 min-h-96">
            <div class="border-b border-gray-200">
                <h1 class="font-bold text-lg p-4 ">Birthday</h1>

            </div>
            <div class="grid justify-center align-middle">
                <img src="{{ asset('images/birthday.png') }}" class="w-60 h-60 flex justify-center align-middle">

            </div>
        </div>
        <div class="flex-1 mx-2 mb-6 lg:mb-0 shadow-lg bg-white dark:bg-gray-800 min-h-96">
            <div class="border-b border-gray-200  ">
                <h1 class="font-bold text-lg p-4">New Hires</h1>
            </div>

            <div class="p-4">
                @if (!is_null($newHires) && !$newHires->isEmpty())
                <ul>
                    @foreach ($newHires as $hire)
                    <li class="p-2 border-b border-gray-200 dark:border-gray-700">
                        <p><strong>Name:</strong> {{ $hire->name }}</p>
                        <p><strong>Email:</strong> {{ $hire->email }}</p>
                        <p><strong>Hired On:</strong> {{ $hire->created_at->format('M d, Y') }}</p>
                    </li>
                    @endforeach
                </ul>
                @else
                <div class="grid justify-center align-middle">
                    <img src="{{ asset('images/nohires.png') }}" class="w-40 h-40 flex justify-center align-middle">
                    <p class="text-gray-600 dark:text-gray-400 flex justify-center">No New Joinees in past 15 days.</p>
                </div>
                @endif

            </div>
        </div>

        <div class="flex-1 mx-2 mb-6 lg:mb-0 shadow-lg bg-white dark:bg-gray-800 min-h-96 ">
            <div class="border-b border-gray-200">
                <h1 class="font-bold text-lg p-4">Upcoming Holidays</h1>
            </div>
            <div class="overflow-y-auto" style="max-height: 300px;">
                <ul>
                    @foreach($holidays as $holiday)
                    <li class="border-b border-gray-100 p-3">
                        <div class="flex justify-between">
                            <!-- Left side: Holiday Reason -->
                            <div>
                                <span>{{ $holiday->reason }}</span>
                                @if($holiday->is_restricted === 'yes')
                                <br><span class="text-sm text-gray-500">(Restricted Holiday)</span>
                                @endif
                            </div>

                            <!-- Right side: Date -->
                            <div class="text-right">
                                <span>{{ \Carbon\Carbon::parse($holiday->date)->format('j-M') }}</span> <!-- Date -->
                                <br>
                                <span class="text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($holiday->date)->format('l') }} <!-- Day of the week -->
                                </span>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

    </div>
    <div class="flex flex-col lg:flex-row justify-between my-16 mx-3 h-auto dark:bg-gray-200">
        <div class="flex-1 mx-2 mb-6 lg:mb-0 shadow-lg bg-white dark:bg-gray-800 min-h-96">
            <div class="border-b border-gray-200">
                <h1 class="font-bold text-lg p-4 ">Quick Links</h1>
            </div>
            <div class="grid justify-center align-middle">
                <img src="{{ asset('images/fav.png') }}" class="w-60 h-60 flex justify-center align-middle">
                <p class="text-gray-600 dark:text-gray-400 flex justify-center">No quick links</p>

            </div>
        </div>
        <div class="flex-1 mx-2 mb-6 lg:mb-0 shadow-lg bg-white dark:bg-gray-800 min-h-96">
            <div class="border-b border-gray-200 flex items-center justify-between">
                <!-- Attendance Title -->
                <h1 class="font-bold text-lg p-4">Attendance</h1>

                <!-- Day View and Weekly View Icons -->
                <div class="flex items-center p-4 space-x-4">
                    <div class="cursor-pointer" id="dayViewButton" onclick="toggleView('day')">
                        <svg id="dayViewIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="gray" class="size-7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                        </svg>
                    </div>

                    <!-- Weekly View Button -->
                    <div class="cursor-pointer" id="weekViewButton" onclick="toggleView('week')">
                        <svg id="weekViewIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="gray" class="size-7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Day View Content -->
            <div id="dayViewContent" class="view-content mx-3">
                <div class="p-">
                    <!-- Include Check-In Button with Timer -->
                    <div class="inline-flex items-center p-">
                        <button id="checkButton" class="text-sm font-bold text-white mt-2 mr-2" style="background-color: #27cda5; padding: 5px; min-width: 150px;" onclick="toggleCheckInStatus()">
                            <!-- Left Side: SVG Icon -->
                            <div class="flex items-center mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>
                            <!-- Right Side: Check-in/Check-out and Timer -->
                            <div class="flex flex-col items-start">
                                <span id="checkStatusText">Check-in</span>
                                <span id="buttonTimer" class="text-md font-light text-white mt-1">00:00:00</span>
                            </div>
                        </button>
                    </div>
                    <div class="flex justify-center items-center mt-4">
                        <span id="centralTimer" class="font-12 text-5xl mt-1 text-gray-600 dark:text-white">00:00:00 Hrs</span>
                    </div>
                    <div class="flex justify-center text-lg text-gray-500 mt-2">
                        <span>{{ \Carbon\Carbon::now()->format('j M Y') }}</span> <!-- Date -->
                    </div>


                    <div class="border-b-2 border-gray-100 mt-10 dark:border-gray-600"></div>

                    @php
                    // Check if today is in weekDates (assumes you're working with today's date)
                    $isToday = \Carbon\Carbon::now()->isToday();

                    // Get the last check-in time if available
                    $lastCheckIn = !empty($checkInTimes) ? end($checkInTimes) : null;

                    // Parse the last check-in time using Carbon
                    $checkInTime = $lastCheckIn ? \Carbon\Carbon::parse($lastCheckIn) : null;

                    // Check if the check-in time was before 9 AM
                    $checkInBefore9AM = $checkInTime && $checkInTime->lt(\Carbon\Carbon::parse('09:00'));

                    // Initialize flag for check-out before 9 AM
                    $checkOutBefore9AM = false;

                    // Loop through check-out times and check if any were before 9 AM
                    foreach ($checkOutTimes as $checkOut) {
                    if (\Carbon\Carbon::parse($checkOut)->lt(\Carbon\Carbon::parse('09:00'))) {
                    $checkOutBefore9AM = true;
                    break;
                    }
                    }

                    // Initialize flag for any check-in before 9 AM
                    $anyCheckInBefore9AM = false;

                    // Loop through check-in times and check if any were before 9 AM
                    foreach ($checkInTimes as $checkIn) {
                    if (\Carbon\Carbon::parse($checkIn)->lt(\Carbon\Carbon::parse('09:00'))) {
                    $anyCheckInBefore9AM = true;
                    break;
                    }
                    }
                    // Default width for the moving progress (in percentage)
                    $progressWidth = 0;

                    // Define the workday start (9 AM) and end (6 PM) as Carbon instances
                    $workdayStart = \Carbon\Carbon::parse('09:00');
                    $workdayEnd = \Carbon\Carbon::parse('18:00');

                    // Ensure the check-in and check-out times are properly parsed as Carbon instances
                    $checkInTime = !empty($checkInTimes) ? \Carbon\Carbon::parse(end($checkInTimes)) : null;
                    $checkOutTime = !empty($checkOutTimes) ? \Carbon\Carbon::parse(end($checkOutTimes)) : null;

                    // If the employee has checked in, calculate the progress
                    if ($checkInTime) {
                    // Calculate the total workday duration in seconds
                    $totalWorkdaySeconds = $workdayEnd->diffInSeconds($workdayStart);

                    // Use check-out time if available, otherwise use the current time
                    $currentTime = \Carbon\Carbon::now();
                    $endTime = $checkOutTime ? $checkOutTime : $currentTime;

                    // Calculate the elapsed time in seconds since the workday started (9 AM)
                    $elapsedSeconds = $endTime->diffInSeconds($workdayStart);

                    // Calculate the percentage of the workday that has elapsed
                    $progressWidth = min(100, ($elapsedSeconds / $totalWorkdaySeconds) * 100);
                    }
                    @endphp

                    <div class="progress-container">
                        <div class="relative" style="position: relative; height: 30px;">
                            <!-- Dots indicating different conditions -->
                            <div class="absolute" style="left: 0; top: 50%; transform: translateY(-50%); height: 10px; width: 10px; border-radius: 50%; background-color: {{ $anyCheckInBefore9AM ? '#b2d7ad' : ($checkOutBefore9AM ? '#e53e3e' : '#ccc') }};"></div>
                            <div class="absolute" style="left: 15px; top: 50%; transform: translateY(-50%); height: 5px; width: 5px; border-radius: 50%; background-color: {{ $anyCheckInBefore9AM ? '#b2d7ad' : '#ccc' }};"></div>
                            <div class="absolute" style="left: 25px; top: 50%; transform: translateY(-50%); height: 10px; width: 10px; border-radius: 50%; background-color: {{ $anyCheckInBefore9AM ? '#b2d7ad' : ($checkOutBefore9AM ? '#e53e3e' : '#ccc') }};"></div>

                            <!-- Progress bar (gray background) -->
                            <div class="progress cust-bar" style="position: absolute; left: 45px; top: 50%; transform: translateY(-50%); width: 456px; height: 1px; background-color: #c7c8c9;">
                                <div class="moving-progress" style="width: {{ $progressWidth }}%; background-color: green; height: 1px; position: absolute; top: 0; animation: progress-bar-stripes 2s linear infinite;"></div>
                            </div>
                            @php
                            $lastCheckIn = end($checkInTimes);
                            $lastCheckOut = end($checkOutTimes);
                            $endTime = \Carbon\Carbon::parse('18:00'); // Assuming 6 PM as the end time
                            $lastCheckInAfter6PM =NULL;
                            $lastCheckOutAfter6PM = NULL;
                            $flag = false;
                            if( $lastCheckIn){
                            $lastCheckInAfter6PM = $lastCheckIn && \Carbon\Carbon::parse($lastCheckIn)->gt($endTime);
                            }
                            if( $lastCheckOut){
                            $lastCheckOutAfter6PM = $lastCheckOut && \Carbon\Carbon::parse($lastCheckOut)->gt($endTime);
                            }
                            if($lastCheckOutAfter6PM ||$lastCheckInAfter6PM ){
                            if ($lastCheckOutAfter6PM){
                            $parsedCheckOut = \Carbon\Carbon::parse($lastCheckOut);
                            }else{
                            $parsedCheckOut = $endTime;
                            }
                            if ($lastCheckInAfter6PM){
                            $parsedCheckIn = \Carbon\Carbon::parse($lastCheckIn);
                            }else{
                            $parsedCheckIn = $endTime;
                            }


                            $flag = $parsedCheckOut->gte($parsedCheckIn);

                            }
                            @endphp

                            <!-- First two dots -->
                            <div class="absolute" style="left: 515px; top: 50%; transform: translateY(-50%); height: 10px; width: 10px; border-radius: 50%; background-color: {{ $lastCheckInAfter6PM  || $lastCheckOutAfter6PM  ? '#b2d7ad' :  '#ccc' }};"></div>
                            <div class="absolute" style="left: 530px; top: 50%; transform: translateY(-50%); height: 5px; width: 5px; border-radius: 50%; background-color: {{ $lastCheckInAfter6PM  || $lastCheckOutAfter6PM  ? '#b2d7ad' :  '#ccc'  }};"></div>

                            <!-- Third dot, red if last check-out is after 6 PM, otherwise gray -->
                            <div class="absolute" style="left: 540px; top: 50%; transform: translateY(-50%); height: 10px; width: 10px; border-radius: 50%; background-color: {{ $lastCheckInAfter6PM != NULL || $lastCheckOutAfter6PM !=NULL ? ($flag ? '#e53e3e' : '#b2d7ad') : '#ccc' }};"></div>

                            <!-- Timing line below the moving line -->
                            <div class="absolute" style="left: 45px; top: 52%; transform: translateY(-50%); width: 450px; height: 1px; background-color: #c7c8c9;"></div>
                        </div>
                        <div class="flex justify-center align-middle w-full">
                            <div class="border-b-2 border-gray-200 dark:border-gray-600 w-full" style="width: 450px;"></div>
                        </div>

                        <!-- Flex container for the text -->
                        <div class="flex justify-between mx-12">
                            <div class="text-left text-gray-500">09 AM</div>
                            <div class="text-center text-gray-500"> Regular Shift</div>
                            <div class="text-right text-gray-500">06 PM</div>
                        </div>
                    </div>




                </div>
            </div>

            <!-- Weekly View Content -->
            <div id="weeklyViewContent" class="view-content hidden">
                <div class="p-4">
                    <table class="table table-hover atlist" id="ZPAtt_dashboard_weekCont">
                        <tbody>
                            @foreach ($weekDates as $date)
                            @php

                            try {
                            // Format date from "D j" to "Y-m-d"
                            $formattedDate = Carbon::createFromFormat('D j', $date)->format('Y-m-d');
                            $dayOfWeek = Carbon::parse($formattedDate)->dayOfWeek;

                            } catch (\Exception $e) {
                            $formattedDate = null; // Handle invalid date format
                            $dayOfWeek = null; // Handle invalid day
                            }
                            $isWeekend = \Carbon\Carbon::parse($date)->isWeekend();
                            // Initialize variables
                            $attendanceForDate = null;
                            $isAbsent = false;
                            $firstInTimeDisplay = 'N/A';
                            $lastOutTimeDisplay = 'N/A';

                            $formattedDate = Carbon::parse($date)->format('Y-m-d');
                            // Check if attendance data is available
                            if ($attendance && $formattedDate) {
                            // Find the attendance record for the given date
                            $attendanceForDate = $attendance->where('date', $formattedDate)->first();


                            // Check if the attendance status is 'aa' (Absent)
                            if ($attendanceForDate) {
                            $isAbsent = $attendanceForDate->status === 'aa';
                            $firstInTimeEntry = $attendanceForDate->attendanceEntries->firstWhere('entry_type', 1);
                            $lastOutTimeEntry = $attendanceForDate->attendanceEntries->where('entry_type', 0)->sortByDesc('entry_time')->first();

                            // Format check-in and check-out times
                            $firstInTimeDisplay = $firstInTimeEntry
                            ? Carbon::parse($attendanceForDate->date . ' ' . $firstInTimeEntry->entry_time)->format('h:i A')
                            : 'N/A';

                            $lastOutTimeDisplay = $lastOutTimeEntry
                            ? Carbon::parse($attendanceForDate->date . ' ' . $lastOutTimeEntry->entry_time)->format('h:i A')
                            : 'N/A';
                            }
                            }

                            // Check if the current date is today
                            $isToday = $formattedDate === now()->format('Y-m-d');
                            $holidayForDate = $holidays->firstWhere('date', $formattedDate);
                            @endphp

                            <tr class="border-b border-gray-200">
                                <!-- Show the date -->
                                <td width="75" class="{{ $isToday ? 'bg-gray-200' : 'bg-gray-50 dark:bg-gray-600' }} p-2">
                                    <div>
                                        <span class="{{ $isToday ? 'text-black font-bold' : 'text-black' }}">
                                            {{ \Carbon\Carbon::parse($date)->format('D j') }} <!-- Format as 'day month' -->
                                        </span>

                                    </div>
                                </td>


                                <td colspan="4" style="width: 300px;">
                                    <div class="lingr">
                                        @if ($dayOfWeek ==!$isWeekend)
                                        <!-- Weekend -->
                                        <div class="weekend-line">
                                            <div class="relative timeline-container" style="height: 2px; width: 450px; background-color: #ffe5a3;">
                                                <span class="absolute dark:bg-blue-200 bg-white" style="top: -10px; left: 50%; transform: translateX(-50%); font-size: 12px; font-weight: normal; color: #666; padding: 3px 6px;">
                                                    Weekend
                                                </span>
                                            </div>
                                        </div>
                                        @elseif ($holidayForDate)
                                        <!-- Display holiday message -->
                                        <div class="weekend-line">
                                            <div class="relative timeline-container" style="height: 2px; width: 450px; background-color: #9cc6e5;">
                                                <span class="absolute dark:bg-blue-200 bg-white" style="top: -10px; left: 50%; transform: translateX(-50%); font-size: 12px; font-weight: normal; color: #666; padding: 3px 6px;">
                                                    {{ $holidayForDate->reason }}
                                                </span>
                                            </div>
                                        </div>
                                        @else
                                        <!-- Attendance Data -->
                                        @if ($attendanceForDate)
                                        @if ($isAbsent && !$isToday) <!-- Do not show absent status for today -->
                                        <!-- Show "Absent" status with red line -->
                                        <div class="absent-line">
                                            <div class="relative timeline-container" style="height: 2px; width: 450px; background-color: #ffcccc;">
                                                <span class="absolute dark:bg-red-200 bg-white" style="top: -10px; left: 50%; transform: translateX(-50%); font-size: 12px; color: black; padding: 3px 6px;">
                                                    Absent
                                                </span>
                                            </div>
                                        </div>
                                        @else
                                        <!-- Show Check-in and Check-out Times -->
                                        <div class="flex justify-center">
                                            <span class="px-2">{{ $firstInTimeDisplay }}</span> -
                                            <span class="px-2">{{ $lastOutTimeDisplay }}</span>
                                        </div>
                                        @endif
                                        @else
                                        <!-- No attendance for this date -->
                                        <div class="flex justify-center">
                                            <span></span>
                                        </div>
                                        @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>


        </div>

        <div class="flex-1 mx-2 mb-6 lg:mb-0 shadow-lg bg-white dark:bg-gray-800 min-h-96">
            <div class="border-b border-gray-200">
                <h1 class="font-bold text-lg p-4">Favourites</h1>
            </div>
            <div class="grid justify-center align-middle">
                <img src="{{ asset('images/cases.png') }}" class="w-60 h-60 flex justify-center align-middle">
                <p class="text-gray-600 dark:text-gray-400 flex justify-center">No Favorites found.</p>

            </div>
        </div>

    </div>
    <div class="flex flex-col lg:flex-row justify-between my-16 mx-3 h-auto dark:bg-gray-200">
        <div class="flex-1 mx-2 mb-6 lg:mb-0 shadow-lg bg-white dark:bg-gray-800 min-h-96">
            <div class="border-b border-gray-200">
                <h1 class="font-bold text-lg p-4 ">My Timesheets</h1>
            </div>
            <div class="overflow-y-auto" style="max-height: 300px;">
                <div
                    class="text-center font-bold text-base m-1">Timesheet for the week
                </div>
                <!-- Table Section -->
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 border-separate border-spacing-y-2">
                    <thead class="text-xs text-gray-700 dark:text-gray-100 uppercase bg-gray-50 dark:bg-gray-700 ">
                        <tr>
                            <th class="cursor-pointer px-4 py-2  border-gray-300">Date</th>
                            <th class="cursor-pointer px-4 py-2  border-gray-300">Project</th>

                            <th class="cursor-pointer px-4 py-2  border-gray-300">Task Id</th>
                            <th class="cursor-pointer px-4 py-2  border-gray-300" style="width: 40%;">Comment</th>
                            <th class="cursor-pointer px-4 py-2  border-gray-300">Hours</th>
                            <th class="cursor-pointer px-4 py-2  border-gray-300">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($weekDates as $date)
                        @php
                        try {
                            // Format date from "D j" to "Y-m-d"
                            $formattedDate = Carbon::createFromFormat('D j', $date)->format('Y-m-d');
                            $dayOfWeek = Carbon::parse($formattedDate)->dayOfWeek;

                            } catch (\Exception $e) {
                            $formattedDate = null; // Handle invalid date format
                            $dayOfWeek = null; // Handle invalid day
                            }

    $isWeekend = \Carbon\Carbon::parse($date)->isWeekend(); // Check if the date is a weekend

    // Ensure $timesheets is not null before filtering
    $todayTimesheets = $timesheets ? $timesheets->filter(function($timesheet) use ($formattedDate) {
        $timesheetDate = Carbon::parse($timesheet->date)->format('Y-m-d');
        return $timesheetDate === $formattedDate;
    }) : collect(); // Use an empty collection if $timesheets is null
    $formattedDate = Carbon::parse($date)->format('Y-m-d');
    $holidayForDate = $holidays->firstWhere('date', $formattedDate);
      @endphp

                        @forelse($todayTimesheets as $timesheet)
                        <tr class="border-b border-gray-300">
                            <td class="border-b border-gray-300"> {{\Carbon\Carbon::parse($date)->format('d j')}} </td>
                            <td class="border-b border-gray-300">{{ $timesheet->project->name }}</td>
                            <td class="border-b border-gray-300">{{ $timesheet->taskid ?? '' }}</td>
                            <td class="border-b border-gray-300">
                                @php
                                $commentWords = explode(' ', $timesheet->comment);
                                $limitedComment = implode(' ', array_slice($commentWords, 0, 4));
                                if (count($commentWords) > 5) {
                                $limitedComment .= '...';
                                }
                                @endphp
                                {{ $limitedComment }}
                            </td>
                            <td class="border-b border-gray-300">{{ $timesheet->time }}</td>
                            <td class="border-b border-gray-300">
                                <a href="{{ route('employee.timesheet.edit', $timesheet->id) }}" class="text-gray-500 dark:text-gray-100 rounded hover:text-gray-600 dark:hover:bg-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zM16.862 4.487L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr class="border-b border-gray-300">
                        <td class="border-b border-gray-300">{{ \Carbon\Carbon::parse($date)->format('D j') }}</td> <!-- Format date as 'D j' -->
                        <td colspan="4" class="text-center border-b border-gray-300 
                         {{ $isWeekend ? 'text-yellow-500 font-bold' : ($holidayForDate ? 'text-[#9cc6e5] font-bold' : '') }}">
            @if ($isWeekend)
                - Weekend -
            @elseif ($holidayForDate)
                - {{ $holidayForDate->reason }} -
               
            @else
                
            @endif
        </td>
                            <td colspan="4" class="text-center border-b border-gray-300"></td>
                        </tr>
                        @endforelse
                        @endforeach
                    </tbody>
                </table>


            </div>

        </div>
        <div class="flex-1 mx-2 mb-6 lg:mb-0 shadow-lg bg-white dark:bg-gray-800 min-h-96">
            <div class="border-b border-gray-200">
                <h1 class="font-bold text-lg p-4">Reports</h1>
            </div>
            <div class="overflow-x-auto" style="max-height: 310px;">
            <table class="text-sm mt-2">
                <thead>
                    <tr class="flex space-x-1 text-sm  ">
                        <th class="w-20 h-12 flex items-center justify-center"></th>
                        <th class="bg-gray-200 w-28 h-12 flex items-center justify-center dark:bg-gray-900 text-center">Logged Hours</th>
                        <th class="bg-gray-200 w-28 h-12 flex items-center justify-center dark:bg-gray-900">Project Hours</th>
                        <th class="bg-gray-200 w-28 h-12 flex items-center justify-center dark:bg-gray-900">Bench Hours</th>
                        <th class="bg-gray-200 w-28 h-12 flex items-center justify-center dark:bg-gray-900">Training Hours</th>
                        <th class="bg-gray-200 w-28 h-12 flex items-center justify-center dark:bg-gray-900">Learning Hours</th>
                        <th class="bg-gray-200 w-28 h-12 flex items-center justify-center dark:bg-gray-900">Leave Days</th>
                        <th class="bg-gray-200 w-24 h-12 flex items-center justify-center dark:bg-gray-900">Deviation</th>
                    </tr>
                </thead>
                <tbody class="grid space-y-4 mt-3">
                    <tr class="flex space-x-1 text-sm">
                        <td class="bg-gray-200 w-20 h-12 flex items-center justify-center text-center dark:bg-gray-900">This Week</td>
                        <td class="bg-gray-100  w-28 h-12 flex  items-center justify-center text-center dark:bg-gray-700">{{$weeklyTotalHours}}</td>
                        <td class="bg-gray-100 w-28 h-12 flex  items-center justify-center text-center dark:bg-gray-700">{{$weeklyProjectHours}}</td>
                        <td class="bg-gray-100 w-28 h-12 flex  items-center justify-center text-center dark:bg-gray-700">{{ $weeklyBenchHours }}</td>
                        <td class="bg-gray-100 w-28 h-12 flex  items-center justify-center text-center dark:bg-gray-700">{{ $weeklyTrainingHours }}</td>
                        <td class="bg-gray-100 w-28 h-12 flex  items-center justify-center text-center dark:bg-gray-700">{{ $weeklyLearningHours }}</td>
                        <td class="bg-gray-100 w-28 h-12 flex  items-center justify-center text-center dark:bg-gray-700">{{ $weeklyLeaveDays }}</td>
                        <td class="bg-gray-100 w-24 h-12 flex items-center justify-center text-center dark:bg-gray-700 
            @if($weeklyDeviation < 0) text-red-600 @elseif($weeklyDeviation > 0) text-green-600 @else text-gray-600 @endif">
                            {{ $weeklyDeviation }}
                        </td>
                    </tr>
                    <tr class="flex space-x-1 text-sm">
                        <td class="bg-gray-200 w-20 h-12 flex items-center justify-center text-center dark:bg-gray-900">Last Week</td>
                        <td class="bg-gray-100 w-28 h-12 flex items-center justify-center text-center dark:bg-gray-700">{{$lastWeekTotalHours}}</td>
                        <td class="bg-gray-100 w-28 h-12 flex items-center justify-center text-center dark:bg-gray-700">{{$lastWeekProjectHours}}</td>
                        <td class="bg-gray-100 w-28 h-12 flex items-center justify-center text-center dark:bg-gray-700">{{ $lastWeekBenchHours }}</td>
                        <td class="bg-gray-100 w-28 h-12 flex items-center justify-center text-center dark:bg-gray-700">{{ $lastWeekTrainingHours }}</td>
                        <td class="bg-gray-100 w-28 h-12 flex items-center justify-center text-center dark:bg-gray-700">{{ $lastWeekLearningHours }}</td>
                        <td class="bg-gray-100 w-28 h-12 flex items-center justify-center text-center dark:bg-gray-700">{{ $lastWeekLeaveDays }}</td>
                        <td class="bg-gray-100 w-24 h-12 flex items-center justify-center text-center dark:bg-gray-700
            @if($lastWeekDeviation < 0) text-red-600 @elseif($lastWeekDeviation > 0) text-green-600 @else text-gray-600 @endif">
                            {{ $lastWeekDeviation }}
                        </td>
                    </tr>
                    <tr class="flex space-x-1 text-sm">
                        <td class="bg-gray-200 w-20 h-12 flex items-center justify-center text-center  dark:bg-gray-900">This Month</td>
                        <td class="bg-gray-100 w-28 h-12 flex items-center justify-center text-center dark:bg-gray-700">{{$monthlyTotalHours}}</td>
                        <td class="bg-gray-100 w-28 h-12 flex items-center justify-center text-center dark:bg-gray-700">{{ $weeklyProjectHours }}</td>
                        <td class="bg-gray-100 w-28 h-12 flex items-center justify-center text-center dark:bg-gray-700">{{ $monthlyBenchHours }}</td>
                        <td class="bg-gray-100 w-28 h-12 flex items-center justify-center text-center dark:bg-gray-700">{{ $monthlyTrainingHours }}</td>
                        <td class="bg-gray-100 w-28 h-12 flex items-center justify-center text-center dark:bg-gray-700">{{ $weeklyLearningHours }}</td>
                        <td class="bg-gray-100 w-28 h-12 flex items-center justify-center text-center dark:bg-gray-700">{{ $monthlyLeaveDays }}</td>
                        <td class="bg-gray-100 w-24 h-12 flex items-center justify-center text-center dark:bg-gray-700
            @if($monthlyDeviation < 0) text-red-600 @elseif($monthlyDeviation > 0) text-green-600 @else text-gray-600 @endif">
                            {{ $monthlyDeviation }}
                        </td>
                    </tr>
                    <tr class="flex space-x-1 text-sm">
                        <td class="bg-gray-200  w-20 h-12 flex items-center justify-center text-center  dark:bg-gray-900">Last Month</td>
                        <td class="bg-gray-100 w-28 h-12 flex items-center justify-center text-center dark:bg-gray-700">{{$lastMonthTotalHours}}</td>
                        <td class="bg-gray-100 w-28 h-12 flex items-center justify-center text-center dark:bg-gray-700">{{ $lastMonthProjectHours}}</td>
                        <td class="bg-gray-100 w-28 h-12 flex items-center justify-center text-center dark:bg-gray-700">{{ $lastMonthBenchHours }}</td>
                        <td class="bg-gray-100 w-28 h-12 flex items-center justify-center text-center dark:bg-gray-700">{{ $lastMonthTrainingHours }}</td>
                        <td class="bg-gray-100 w-28 h-12 flex items-center justify-center text-center dark:bg-gray-700">{{ $lastMonthLearningHours }}</td>
                        <td class="bg-gray-100 w-28 h-12 flex items-center justify-center text-center dark:bg-gray-700">{{ $lastMonthLeaveDays }}</td>
                        <td class="bg-gray-100 w-24 h-12 flex items-center justify-center text-center dark:bg-gray-700
            @if($lastMonthDeviation < 0) text-red-500 @elseif($lastMonthDeviation > 0) text-green-500 @else text-gray-500 @endif">
                            {{ $lastMonthDeviation }}
                        </td>
                    </tr>
                </tbody>

            </table>
           

        </div>

        </div>

    </div>
    <div class="flex flex-col lg:flex-row justify-between my-16 mx-3 h-auto dark:bg-gray-200">
        <div class="flex-1 mx-2 mb-6 lg:mb-0 shadow-lg bg-white dark:bg-gray-800 min-h-96">
            <div class="border-b border-gray-200">
                <h1 class="font-bold text-lg p-4">Project Allocations</h1>
            </div>
            <div id="main" class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 dark:text-gray-100 uppercase bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th wire:click="sortBy('name')" class="cursor-pointer px-4 py-2">Name</th>
                            <th wire:click="sortBy('description')" class="cursor-pointer px-4 py-2">Description</th>
                            <th wire:click="sortBy('status')" class="cursor-pointer px-4 py-2">Status</th>
                            <th wire:click="sortBy('start_date')" class="cursor-pointer px-4 py-2">Start Date</th>
                            <th wire:click="sortBy('end_date')" class="cursor-pointer px-4 py-2">End Date</th>
                            <th wire:click="sortBy('pm')" class="cursor-pointer px-4 py-2">Project Manager</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($projects as $project)
                        <tr class="border-b dark:border-gray-700">
                            <td class="px-4 py-2">{{ $project->name }}</td>
                            <td class="px-4 py-2">{{ $project->description }}</td>
                            <td class="px-4 py-2">{{ $project->status }}</td>
                            <td class="px-4 py-2">{{ $project->start_date }}</td>
                            <td class="px-4 py-2">{{ $project->end_date }}</td>
                            <td class="px-4 py-2">{{ $project->projectManager->name }}</td>


                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4">


            </div>

        </div>
        <div class="flex-1 mx-2 mb-6 lg:mb-0 shadow-lg bg-white dark:bg-gray-800 min-h-96">
            <div class="border-b border-gray-200">
                <h1 class="font-bold text-lg p-4">Cases</h1>
            </div>
            <div class="grid justify-center align-middle">
                <img src="{{ asset('images/case.png') }}" class="w-50 h-60 flex justify-center align-middle">
                <p class="text-gray-600 dark:text-gray-400 flex justify-center">No record found</p>
            </div>
            <div class="p-4">


            </div>

        </div>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function toggleView(view) {
        // Get SVG elements
        const dayIcon = document.getElementById('dayViewIcon');
        const weekIcon = document.getElementById('weekViewIcon');

        // Get view content containers
        const dayViewContent = document.getElementById('dayViewContent');
        const weeklyViewContent = document.getElementById('weeklyViewContent');

        // Reset colors and hide both contents
        dayIcon.setAttribute('stroke', 'gray');
        weekIcon.setAttribute('stroke', 'gray');
        dayViewContent.classList.add('hidden');
        weeklyViewContent.classList.add('hidden');

        // Change color based on selected view and show the corresponding content
        if (view === 'day') {
            dayIcon.setAttribute('stroke', '#e6a03f'); // Yellow for selected
            dayViewContent.classList.remove('hidden');
        } else if (view === 'week') {
            weekIcon.setAttribute('stroke', '#e6a03f'); // Yellow for selected
            weeklyViewContent.classList.remove('hidden');
        }
    }

    // Set default view to 'day' when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        toggleView('day'); // Set default to day view
    });
    let isCheckedIn = false;
    let timerInterval = null;
    let totalSeconds = 0;
    let lastCheckInDate = null;
    const employeeId = "{{ Auth::guard('employee')->user()->id }}"; // Get employee ID dynamically

    $(document).ready(function() {
        const storedIsCheckedIn = localStorage.getItem(`isCheckedIn_${employeeId}`);
        const storedTotalSeconds = parseInt(localStorage.getItem(`totalSeconds_${employeeId}`)) || 0;
        const storedLastCheckInDate = localStorage.getItem(`lastCheckInDate_${employeeId}`);

        if (storedIsCheckedIn !== null) {
            isCheckedIn = JSON.parse(storedIsCheckedIn);
        }
        totalSeconds = storedTotalSeconds;
        lastCheckInDate = storedLastCheckInDate || new Date().toDateString();

        updateButtonState();
        updateTimer(); // Ensure the timer shows the correct value on load
        updateCurrentTime(); // Update the current time display immediately

        if (isCheckedIn) {
            startTimer();
        }

        // Poll server for current status every 10 seconds
        setInterval(checkServerStatus, 10000);

        // Update current time every second
        setInterval(updateCurrentTime, 1000);

        // Listen for localStorage changes
        window.addEventListener('storage', function(event) {
            if (event.key === `isCheckedIn_${employeeId}`) {
                isCheckedIn = JSON.parse(event.newValue);
                updateButtonState();
                updateTimer();
                if (isCheckedIn) {
                    startTimer();
                } else {
                    stopTimer();
                }
            } else if (event.key === `totalSeconds_${employeeId}`) {
                totalSeconds = parseInt(event.newValue) || 0;
                updateTimer();
            } else if (event.key === `currentTime`) { // Listen for current time updates
                const currentTime = event.newValue;
                $('#buttonTimer').text(currentTime); // Update the current time display
            }
        });
    });

    function toggleCheckInStatus() {
        isCheckedIn = !isCheckedIn;
        updateButtonState();

        $.ajax({
            url: '/toggle-check-in-status',
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: JSON.stringify({
                isCheckedIn: isCheckedIn
            }),
            success: function(data) {
                if (data.success) {
                    if (isCheckedIn) {
                        startTimer(); // Start the timer on check-in
                    } else {
                        stopTimer(); // Stop the timer on check-out
                    }
                    localStorage.setItem(`isCheckedIn_${employeeId}`, JSON.stringify(isCheckedIn));
                    localStorage.setItem(`totalSeconds_${employeeId}`, totalSeconds);
                } else {
                    console.error('Failed to update check-in status');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', status, error);
            }
        });
    }

    function updateButtonState() {
        const button = $('#checkButton');
        const buttonText = isCheckedIn ? 'Check-out' : 'Check-in';
        button.html(`
        <div class="flex items-center">
            <div class="mr-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8 text-white">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
            <div class="flex flex-col items-center">
                <span id="checkStatusText">${buttonText}</span>
                <span id="buttonTimer" class="text-md font-light text-white mt-1">00:00:00</span>
            </div>
        </div>
    `);
        const newColor = isCheckedIn ? '#e95b6d' : '#27cda5';
        button.css('background-color', newColor);
    }

    function startTimer() {
        if (timerInterval) {
            clearInterval(timerInterval);
        }
        const startTime = Date.now() - (totalSeconds * 1000);
        timerInterval = setInterval(function() {
            totalSeconds = Math.floor((Date.now() - startTime) / 1000);
            updateTimer();
        }, 1000);
    }

    function stopTimer() {
        if (timerInterval) {
            clearInterval(timerInterval);
        }
        localStorage.setItem(`totalSeconds_${employeeId}`, totalSeconds);
        localStorage.setItem(`lastCheckInDate_${employeeId}`, lastCheckInDate);
    }

    function updateTimer() {
        const currentDate = new Date().toDateString();
        if (lastCheckInDate && lastCheckInDate !== currentDate) {
            totalSeconds = 0; // Reset the timer if the day has changed
            lastCheckInDate = currentDate;
            localStorage.setItem(`lastCheckInDate_${employeeId}`, lastCheckInDate);
        }
        const hours = Math.floor(totalSeconds / 3600);
        const minutes = Math.floor((totalSeconds % 3600) / 60);
        const seconds = totalSeconds % 60;

        const formattedTime = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')} Hrs`;
        $('#centralTimer').text(formattedTime); // Update central timer

        localStorage.setItem(`totalSeconds_${employeeId}`, totalSeconds);
    }

    function updateCurrentTime() {
        const now = new Date();
        let hours = now.getHours();
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');

        // Determine AM or PM
        const ampm = hours >= 12 ? 'PM' : 'AM';

        // Convert to 12-hour format and pad with zero if necessary
        hours = hours % 12;
        hours = hours ? String(hours).padStart(2, '0') : '12'; // the hour '0' should be '12'

        const currentTime = `${hours}:${minutes}:${seconds} ${ampm}`;
        $('#buttonTimer').text(currentTime); // Update button timer

        // Save current time to local storage for other tabs/windows
        localStorage.setItem('currentTime', currentTime);
    }

    function checkServerStatus() {
        $.ajax({
            url: '/get-check-in-status',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.isCheckedIn !== isCheckedIn) {
                    isCheckedIn = data.isCheckedIn;
                    updateButtonState();
                    if (isCheckedIn) {
                        startTimer();
                    } else {
                        stopTimer(); // Stop the timer but keep totalSeconds
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Failed to retrieve check-in status:', status, error);
            }
        });
    }
</script>
<div>

    <div class="flex justify-between items-center mb-4 mt-10 bg-gray-100 dark:bg-gray-700 l">
        <!-- Centered Date Display and Navigation -->

        <!-- Previous Week/Month Arrow -->
        <div class="mb-4 justify-start  ">
            <select id="employee-select" wire:model="selectedEmployee" wire:change="loadAttendanceData" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-400 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full py-1.5 mt-4">
                <option value="">Select Employee</option>
                @if(!empty($reportingEmployees))
                @foreach($reportingEmployees as $reportingEmployee)
                <option value="{{ $reportingEmployee->id }}">{{ $reportingEmployee->name }}</option>
                @endforeach
                @endif
            </select>
        </div>
        <div class="flex-grow flex items-center justify-center ">
            <button wire:click="previousPeriod" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
            </button>

            <!-- Week/Month Display -->
            <span class="mx-4  text-gray-500 dark:text-gray-400 justify-center ">
                @if($viewMode === 'weekly')
                {{ \Carbon\Carbon::now()->startOfWeek(\Carbon\Carbon::SUNDAY)->addWeeks($weekOffset)->format('M d, Y') }} -
                {{ \Carbon\Carbon::now()->endOfWeek(\Carbon\Carbon::SATURDAY)->addWeeks($weekOffset)->format('M d, Y') }}
                @else
                {{ \Carbon\Carbon::now()->startOfMonth()->addMonths($monthOffset)->format('M Y') }}
                @endif
            </span>

            <!-- Next Week/Month Arrow -->
            <button wire:click="nextPeriod" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
            </button>
        </div>

        <!-- View Mode Selection on the Right -->
        <div class="flex space-x-2 ml-10"> <!-- Reduced space between buttons -->
            <!-- Weekly View Button -->
            <div class="flex space-x-2 ml-10">
                <div class="flex ml-10">
                    <div class="flex border border-gray-700 rounded h-8">
                        <!-- Weekly View Button -->
                        <div wire:click="setViewMode('weekly')" class="cursor-pointer p-1 {{ $viewMode === 'weekly' ? 'bg-gray-500' : 'bg-white' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-5 {{ $viewMode === 'weekly' ? 'text-white' : 'text-gray-500' }}">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                            </svg>
                        </div>

                        <!-- Monthly View Button -->
                        <div wire:click="setViewMode('monthly')" class="cursor-pointer p-1 {{ $viewMode === 'monthly' ? 'bg-gray-500' : 'bg-white' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-5 {{ $viewMode === 'monthly' ? 'text-white' : 'text-gray-500' }}">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 
                                 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                            </svg>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Dropdown Menu for Export and Audit History -->
            <div x-data="{ open: false }" class="relative inline-block text-left">
                <div>
                    <button @click="open = !open" type="button" class="inline-flex justify-center  border-gray-500 shadow-sm px-4 py-2  text-xs font-medium text-gray-500 hover:bg-gray-100 focus:outline-none" id="menu-button" aria-expanded="true" aria-haspopup="true">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                        </svg>
                    </button>
                </div>
                <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                    <div class="py-1" role="none">
                        <!-- Export to Excel Option -->
                        <a wire:click="exportToExcel" href="#" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 " role="menuitem" tabindex="-1" id="menu-item-0">
                            <div class="flex gap-2 text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                                </svg>
                                Export to Excel
                            </div>
                        </a>
                        <!-- Audit History Option -->
                        <a wire:click="auditHistory" href="{{route('employee.attendance.history')}}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1" id="menu-item-1">
                            <div class="flex space-x-2 text-gray-500 gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                Audit History
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
    @if (session()->has('success'))
    <div class="bg-green-500 text-white p-4 rounded shadow-md mb-6">
        {{ session('success') }}
    </div>
    @endif
    <table class="pr-10 text-sm text-left text-gray-500 dark:text-gray-400 w-full">
        <thead class="text-xs text-gray-700 dark:text-gray-100 uppercase bg-gray-50 dark:bg-gray-700">
            <tr>
                <th class="cursor-pointer px-4 py-2">Date</th>
                <th class="cursor-pointer px-4 py-2">Check In</th>
                <th class="cursor-pointer px-4 py-2">Timeline</th>
                <th class="cursor-pointer px-4 py-2">Check Out</th>
                <th class="cursor-pointer px-4 py-2">Total Hours</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendanceData as $data)
            @php
            $isAuthenticatedEmployee = auth()->guard('employee')->check();

            // Detect if the day is a weekend (Saturday or Sunday)
            $isWeekend = \Carbon\Carbon::parse($data['date'])->isWeekend();
            $checkInTimes = $data['check_in_times'];
            $checkOutTimes = $data['check_out_times'];

            // If weekend and no check-in/check-out times, apply yellow background
            $applyYellowBackground = $isWeekend && (empty($checkInTimes) || empty($checkOutTimes));

            // Define start and end times (9 AM - 6 PM)
            $startTime = Carbon\Carbon::createFromTime(9, 0); // 9 AM
            $endTime = Carbon\Carbon::createFromTime(18, 0); // 6 PM
            $totalMinutes = $startTime->diffInMinutes($endTime);

            // Process markers for check-ins and check-outs
            $markers = [];
            foreach ($checkInTimes as $time) {
            $time = Carbon\Carbon::parse($time);
            $minutesFromStart = $startTime->diffInMinutes($time);
            $position = min(($minutesFromStart / $totalMinutes) * 100, 100); // Ensure no marker exceeds 100%
            $markers[] = ['position' => $position, 'type' => 'check-in'];
            }
            foreach ($checkOutTimes as $time) {
            $time = Carbon\Carbon::parse($time);
            $minutesFromStart = $startTime->diffInMinutes($time);
            $position = ($minutesFromStart > $totalMinutes) ? 100 : min(($minutesFromStart / $totalMinutes) * 100, 100); // Handle after 6 PM
            $markers[] = ['position' => $position, 'type' => 'check-out', 'isAfter6PM' => ($time->gt($endTime))];
            }

            // Sort the markers by position (time of day)
            usort($markers, fn($a, $b) => $a['position'] <=> $b['position']);
                @endphp

                <tr class="h-20 " style=" width: 1200px">
                    <td class="px-4 py-1">{{ \Carbon\Carbon::parse($data['date'])->format('D, d') }}</td>
                    <td class="px-4 py-1">{{ $data['firstInTime'] ? $data['firstInTime']->format('H:i') : '-' }}</td>
                    <td class="pl-2 pr-1 py-1">
                        @php
                        $isToday = \Carbon\Carbon::parse($data['date'])->isToday();
                        $lastCheckIn = !empty($checkInTimes) ? end($checkInTimes) : null;
                        $checkInTime = $lastCheckIn ? \Carbon\Carbon::parse($lastCheckIn) : null;
                        $checkInBefore9AM = $checkInTime && $checkInTime->lt(\Carbon\Carbon::parse('09:00'));

                        $checkOutBefore9AM = false;
                        foreach ($checkOutTimes as $checkOut) {
                        if (\Carbon\Carbon::parse($checkOut)->lt(\Carbon\Carbon::parse('09:00'))) {
                        $checkOutBefore9AM = true;
                        break;
                        }
                        }

                        $anyCheckInBefore9AM = false;
                        foreach ($checkInTimes as $checkIn) {
                        if (\Carbon\Carbon::parse($checkIn)->lt(\Carbon\Carbon::parse('09:00'))) {
                        $anyCheckInBefore9AM = true;
                        break;
                        }
                        }
                        @endphp

                        <div class="relative">
                            <!-- First dot: Green if check-in is before 9 AM, otherwise gray -->
                            <div class="absolute" style="left: -20px; top: -4px; height: 10px; width: 10px; border-radius: 50%; background-color: {{  $anyCheckInBefore9AM ? '#b2d7ad' : ($checkOutBefore9AM ? '#e53e3e' : '#ccc') }};"></div>

                            <!-- Second dot: Red if any check-out is before 9 AM, otherwise gray -->
                            <div class="absolute" style="left: -27px; top: 0px; height: 5px; width: 5px; border-radius: 50%; background-color: {{ $anyCheckInBefore9AM ? '#b2d7ad' : '#ccc' }};"></div>

                            <!-- Third dot: Green if any check-in is before 9 AM, otherwise red if any check-out is before 9 AM, otherwise gray -->
                            <div class="absolute" style="left: -40px; top: -4px; height: 10px; width: 10px; border-radius: 50%; background-color: {{ $anyCheckInBefore9AM ? '#b2d7ad' : ($checkOutBefore9AM ? '#e53e3e' : '#ccc') }};"></div>
                        </div>
                        @php
                        $isHoliday = isset($data['holiday']);
                        $holidayType = $isHoliday ? ($data['holiday']['type'] === 'restricted' ? 'Restricted Holiday' : 'Public Holiday') : null;
                        $holidayReason = $isHoliday && isset($data['holiday']['reason']) ? $data['holiday']['reason'] : null;
                        $isFutureDate = \Carbon\Carbon::parse($data['date'])->isAfter(\Carbon\Carbon::today());
                        $isWeekend = \Carbon\Carbon::parse($data['date'])->isWeekend();
                        @endphp

                        <div class="relative timeline-container" style="height: 2px; width: 1240px; background-color: 
                            @if ($isHoliday)
                                #9cc6e5
                            @elseif ($isWeekend)
                                #ffe5a3
                            @elseif ($isFutureDate)
                                #e2e8f0
                            @elseif ($isToday)
                                #e2e8f0
                            @else
                                {{ ($data['status'] == 'aa' || $data['status'] == 'ap' || $data['status'] == 'pa') ? '#e53e3e' : '#e2e8f0' }}
                            @endif
                        ;">

                            <!-- Display status or labels based on conditions -->
                            @if ($isHoliday)
                            @php
                            // Determine if the holiday is restricted and format the label accordingly
                            $holidayLabel = $holidayType;
                            if ($data['holiday']['is_restricted'] === 'yes') {
                            $holidayLabel = "{$holidayReason} (Restricted)";
                            } elseif ($holidayReason) {
                            $holidayLabel = $holidayReason;
                            }
                            @endphp
                            <span class="absolute" style="top: -10px; left: 50%; transform: translateX(-50%); font-size: 12px; font-weight: normal; color: #666; padding: 3px 6px; background-color: #FFF; border: 1px solid #9cc6e5; border-radius: 5px;">
                                {{ $holidayLabel }}
                            </span>


                            @elseif ($isToday||$data['status'] == 'pp')
                            @foreach($markers as $marker)
                            @php
                            $markerStyle = "left: {$marker['position']}%; top: -2px; height: 6px; width: 6px; border-radius: 50%;";
                            $markerColor = ($marker['type'] === 'check-in') ? '#b2d7ad' : ($marker['isAfter6PM'] ? '#ff6347' : '#e53e3e');

                            if ($marker['type'] === 'check-in') {
                            $lastCheckInPosition = $marker['position'];
                            }

                            if ($lastCheckInPosition !== null && $marker['type'] === 'check-out') {
                            $lineColor = ($data['status'] == 'aa' || $data['status'] == 'ap' || $data['status'] == 'pa') ? '#b2d7ad' : '#b2d7ad';
                            $lineStyle = "position: absolute; top: 0; left: {$lastCheckInPosition}%; width: " . ($marker['position'] - $lastCheckInPosition) . "%; height: 2px; background-color: {$lineColor};";
                            echo "<div class='absolute' style='{$lineStyle}'></div>";
                            $lastCheckInPosition = null;
                            }
                            @endphp
                            <div class="absolute" style="{{ $markerStyle }} background-color: {{ $markerColor }};"></div>
                            @endforeach
                            @elseif ($isWeekend)
                            <span class="absolute dark:bg-blue-200 bg-white" style="top: -10px; left: 50%; transform: translateX(-50%); font-size: 12px; font-weight: normal; color: #666; padding: 3px 6px; border: 1px solid #ffe5a3; border-radius: 5px;">
                                Weekend
                            </span>
                            @elseif (!$isFutureDate)
                            @if($data['status'] == 'aa')
                            <span class="absolute" style="top: -10px; left: 50%; transform: translateX(-50%); font-size: 12px; font-weight: normal; color: #666; padding: 3px 6px; background-color: #FFF; border: 1px solid #e53e3e; border-radius: 5px;">
                                Absent
                            </span>
                            @elseif($data['status'] == 'ap' || $data['status'] == 'pa')
                            <span class="absolute" style="top: -10px; left: 50%; transform: translateX(-50%); font-size: 12px; font-weight: normal; color: #666; padding: 3px 6px; background-color: #FFF; border: 1px solid #e53e3e; border-radius: 5px;">
                                0.5 Present, 0.5 Absent
                            </span>
                            @endif
                            @endif

                            <!-- Green dynamic line that grows based on current time -->
                            @if ($isToday)
                            <div id="dynamic-green-line-{{ $loop->index }}" class="absolute" style="left: 0; top: 0; height: 2px; background-color: #b2d7ad;"></div>
                            @endif

                            <!-- Add two larger gray dots after the line -->
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

                            Log::info('asdfg' .$checkIn.' '.$checkOut.' '.$flag);
                            }



                            @endphp

                            <!-- First two dots -->
                            <div class="absolute" style="left: 101%; top: -4px; height: 10px; width: 10px; border-radius: 50%; background-color: {{ $lastCheckInAfter6PM  || $lastCheckOutAfter6PM  ? '#b2d7ad' :  '#ccc' }};"></div>
                            <div class="absolute" style="left: 102.2%; height: 5px; width: 5px; border-radius: 50%; background-color: {{ $lastCheckInAfter6PM  || $lastCheckOutAfter6PM  ? '#b2d7ad' :  '#ccc'  }};"></div>

                            <!-- Third dot, red if last check-out is after 6 PM, otherwise gray -->
                            <div class="absolute" style="left: 103%; top: -4px; height: 10px; width: 10px; border-radius: 50%; background-color: {{ $lastCheckInAfter6PM != NULL || $lastCheckOutAfter6PM !=NULL ? ($flag ? '#e53e3e' : '#b2d7ad') : '#ccc' }};"></div>
                        </div>
                    </td>

                    <td class="px-4 py-2">{{ $data['lastOutTime'] ? $data['lastOutTime']->format('H:i') : '-' }}</td>
                    <td class="px-4 py-2">{{ $data['totalHours'] }}</td>
                </tr>

                @endforeach


        </tbody>
    </table>

    <!-- Time scale aligned under the timeline -->
    <div class="relative mt-4" style="width: 1200px; margin-left: 180px;">
        <div class="flex justify-between text-xs text-gray-700 dark:text-gray-100">
            <div class="text-center" style="width: calc(100% / 9);">9 AM</div>
            <div class="text-center" style="width: calc(100% / 9);">10 AM</div>
            <div class="text-center" style="width: calc(100% / 9);">11 AM</div>
            <div class="text-center" style="width: calc(100% / 9);">12 PM</div>
            <div class="text-center" style="width: calc(100% / 9);">1 PM</div>
            <div class="text-center" style="width: calc(100% / 9);">2 PM</div>
            <div class="text-center" style="width: calc(100% / 9);">3 PM</div>
            <div class="text-center" style="width: calc(100% / 9);">4 PM</div>
            <div class="text-center" style="width: calc(100% / 9);">5 PM</div>
            <div class="text-center" style="width: calc(100% / 9);">6 PM</div>
        </div>
    </div>

    <!-- Fixed footer -->
    <div class="fixed bottom-5 left-60 w-[calc(100%-10rem)] bg-gray-100 dark:bg-gray-800 p-4 text-sm text-gray-500 dark:text-gray-400shadow-md flex items-center">
        <!-- Tabs for Days and Hours aligned to the left -->
        <div class="flex space-x-4">
            <!-- Adjusted padding to move tabs to the left -->
            <button id="days-tab" class="px-4 py-2 text-gray-500 dark:text-gray-400 focus:outline-none" onclick="showContent('days')">Days</button>
            <button id="hours-tab" class="px-4 py-2 text-gray-500 dark:text-gray-400 focus:outline-none" onclick="showContent('hours')">Hours</button>
        </div>

        <!-- Days Content -->
        <div id="days-content" class="flex space-x-4 justify-left ml-5">


            <div style="border-left: 4px solid rgb(82, 189, 82); max-width: 150px; height: 30px; padding-left: 5px; display: flex; flex-direction: column; align-items: flex-start; justify-content: center;">
                <span>Payable Days</span>
                <span>{{ $totalPayableDays }}</span>
            </div>
            <div style="border-left: 4px solid rgb(185, 108, 185); max-width: 150px; height: 30px; padding-left: 5px; display: flex; flex-direction: column; align-items: flex-start; justify-content: center;">
                <span>Present Days</span>
                <span>{{ $totalPresentDays }}</span>
            </div>
            <div style="border-left: 4px solid orange; max-width: 150px; height: 30px; padding-left: 5px; display: flex; align-items: center;">
                <span>Paid Leave</span>
            </div>
            <div style="border-left: 4px solid rgb(207, 207, 67); max-width: 150px; height: 30px; padding-left: 5px; display: flex; flex-direction: column; align-items: flex-start; justify-content: center;">
                <span>Weekends</span>
                <span>{{ $totalWeekendDays }}</span>
            </div>
            <div style="border-left: 4px solid rgb(55, 124, 202); max-width: 150px; height: 30px; padding-left: 5px; display: flex; flex-direction: column; align-items: flex-start; justify-content: center;">
                <span>Holidays</span>
                <span>{{ $totalHolidayDays }}</span>
            </div>
            <div style="border-left: 4px solid red; max-width: 150px; height: 30px; padding-left: 5px; display: flex; flex-direction: column; align-items: flex-start; justify-content: center;">
                <span>Absent</span>
                <span>{{ $totalAbsentDays }}</span>
            </div>
        </div>

        <!-- Hours Content (Initially Hidden) -->
        <div id="hours-content" class="hidden flex space-x-4 ml-5">
            <div style="border-left: 4px solid green; max-width: 150px; height: 30px; padding-left: 5px; display: flex; flex-direction: column; align-items: flex-start; justify-content: center;">
                <span>Total Hours</span>
                <span>{{ $totalHours }}</span>
            </div>

            <div style="border-left: 4px solid rgb(82, 189, 82); max-width: 150px; height: 30px; padding-left: 5px; display: flex; flex-direction: column; align-items: flex-start; justify-content: center;">
                <span>Payable Hours</span>
                <span>{{ $totalPayableHours }}</span>
            </div>
            <div style="border-left: 4px solid rgb(185, 108, 185); max-width: 150px; height: 30px; padding-left: 5px; display: flex; flex-direction: column; align-items: flex-start; justify-content: center;">
                <span>Present Hours</span>
                <span>{{ $totalPresentHours }}</span>
            </div>
            <div style="border-left: 4px solid orange; max-width: 150px; height: 30px; padding-left: 5px; display: flex; align-items: center;">
                <span>Paid Leave</span>
            </div>
            <div style="border-left: 4px solid rgb(207, 207, 67); max-width: 150px; height: 30px; padding-left: 5px; display: flex; flex-direction: column; align-items: flex-start; justify-content: center;">
                <span>Weekends</span>
                <span>{{ $totalWeekendHours }}</span>
            </div>
            <div style="border-left: 4px solid rgb(55, 124, 202); max-width: 150px; height: 30px; padding-left: 5px; display: flex; flex-direction: column; align-items: flex-start; justify-content: center;">
                <span>Holidays</span>
                <span>{{ $totalHolidayHours }}</span>
            </div>
            <div style="border-left: 4px solid red; max-width: 150px; height: 30px; padding-left: 5px; display: flex; flex-direction: column; align-items: flex-start; justify-content: center;">
                <span>Absent</span>
                <span>{{ $totalAbsentHours }}</span>
            </div>
        </div>

        <!-- Right-aligned Regular Shift information -->
        <div class="ml-auto flex items-center space-x-2 mr-20">
            <div style="border-left: 3px solid gray; padding-left: 10px;">
                <span>Regular Shift</span><br>
                <span>9:00-6:00</span>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function showContent(type) {
                const daysContent = document.getElementById('days-content');
                const hoursContent = document.getElementById('hours-content');
                const daysTab = document.getElementById('days-tab');
                const hoursTab = document.getElementById('hours-tab');

                if (!daysContent || !hoursContent || !daysTab || !hoursTab) {
                    console.error('One or more elements not found.');
                    return;
                }

                // Hide both contents initially
                daysContent.classList.add('hidden');
                hoursContent.classList.add('hidden');

                // Remove active styles from both buttons
                daysTab.classList.remove('bg-gray-300', 'text-blue-500');
                hoursTab.classList.remove('bg-gray-300', 'text-blue-500');

                // Show the selected content and add active styles to the clicked tab
                if (type === 'days') {
                    daysContent.classList.remove('hidden');
                    daysTab.classList.add('bg-gray-300', 'text-blue-500');
                } else if (type === 'hours') {
                    hoursContent.classList.remove('hidden');
                    hoursTab.classList.add('bg-gray-300', 'text-blue-500');
                }
            }

            // Set the initial view to 'Days'
            showContent('days');

            // Attach event listeners to tabs
            document.getElementById('days-tab').addEventListener('click', function() {
                showContent('days');
            });
            document.getElementById('hours-tab').addEventListener('click', function() {
                showContent('hours');
            });

            // Re-apply the script after each Livewire update
            Livewire.hook('message.processed', () => {
                showContent('days');
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startTime = '09:00'; // 9 AM
            const endTime = '18:00'; // 6 PM

            // Pass PHP data to JavaScript
            const attendanceData = @json($attendanceData);

            function updateGreenLine(index, checkInTime, checkOutTime, startTime, endTime) {
                const currentTime = new Date();
                const start = new Date();
                start.setHours(startTime.split(':')[0], startTime.split(':')[1], 0);

                const end = new Date();
                end.setHours(endTime.split(':')[0], endTime.split(':')[1], 0);

                const checkIn = new Date(checkInTime);
                const checkOut = checkOutTime ? new Date(checkOutTime) : end;

                if (checkIn && currentTime >= checkIn) {
                    const totalDuration = checkOut.getTime() - checkIn.getTime();
                    const elapsed = Math.min(currentTime.getTime() - checkIn.getTime(), totalDuration);
                    const percentage = (elapsed / totalDuration) * 100;

                    const greenLine = document.getElementById(`dynamic-green-line-${index}`);
                    if (greenLine) {
                        greenLine.style.width = percentage + '%';
                        greenLine.style.left = `${checkInPosition}%`;
                    }
                } else {
                    const greenLine = document.getElementById(`dynamic-green-line-${index}`);
                    if (greenLine) {
                        greenLine.style.width = '0%';
                    }
                }
            }

            attendanceData.forEach((data, index) => {
                const checkInTime = data.check_in_times[0] || '';
                const checkOutTime = data.check_out_times.length ? data.check_out_times[data.check_out_times.length - 1] : '';
                updateGreenLine(index, checkInTime, checkOutTime, startTime, endTime);
                setInterval(() => {
                    updateGreenLine(index, checkInTime, checkOutTime, startTime, endTime);
                }, 60000); // Update every minute
            });
        });
    </script>

</div>
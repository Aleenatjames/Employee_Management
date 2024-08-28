<div>

    <div class="flex justify-between items-center mb-4 mt-10">
        <!-- Centered Date Display and Navigation -->
        <div class="flex-grow flex items-center justify-center">
            <!-- Previous Week/Month Arrow -->
            <button wire:click="previousPeriod" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
            </button>

            <!-- Week/Month Display -->
            <span class="mx-4 text-gray-700 dark:text-gray-300 justify-center">
                @if($viewMode === 'weekly')
                {{ \Carbon\Carbon::now()->startOfWeek()->addWeeks($weekOffset)->format('M d, Y') }} -
                {{ \Carbon\Carbon::now()->endOfWeek()->addWeeks($weekOffset)->format('M d, Y') }}
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
        <div class="flex space-x-2"> <!-- Reduced space between buttons -->
            <!-- Weekly View Button -->
            <button wire:click="setViewMode('weekly')"
                class="px-2 py-1 text-xs font-medium rounded border border-gray-500 
                   focus:outline-none 
                   {{ $viewMode === 'weekly' ? 'bg-gray-500 text-white' : 'bg-white text-gray-500 hover:bg-gray-100' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 inline"> <!-- Adjusted icon size -->
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>
                Weekly View
            </button>

            <!-- Monthly View Button -->
            <button wire:click="setViewMode('monthly')"
                class="px-2 py-1 text-xs font-medium rounded border border-gray-500 
                   focus:outline-none 
                   {{ $viewMode === 'monthly' ? 'bg-gray-500 text-white' : 'bg-white text-gray-500 hover:bg-gray-100' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 inline"> <!-- Adjusted icon size -->
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                </svg>
                Monthly View
            </button>
        </div>

    </div>

    <div id="main" class="overflow-x-auto mt-10">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 dark:text-gray-100 uppercase bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="cursor-pointer px-4 py-2">Date</th>
                    <th class="cursor-pointer px-4 py-2">First In</th>
                    <th class="cursor-pointer px-4 py-2">Last Out</th>
                    <th class="cursor-pointer px-4 py-2">Total Hours</th>
                    <th class="cursor-pointer px-4 py-2">Payable Hours</th>
                    <th class="cursor-pointer px-4 py-2">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendanceData as $data)
                <tr class="border-b dark:border-gray-700">
                    <td class="px-4 py-2">{{ $data['date'] }}</td>
                    <td class="px-4 py-2">{{ $data['firstInTime'] ? $data['firstInTime']->format('H:i:s') : '-' }}</td>
                    <td class="px-4 py-2">{{ $data['lastOutTime'] ? $data['lastOutTime']->format('H:i:s') : '-' }}</td>
                    <td class="px-4 py-2">{{ $data['totalHours'] }}</td>
                    <td class="px-4 py-2">{{ $data['payableHours'] }}</td>

                    <td class="px-4 py-2 flex items-center">
                        @if($data['holiday'])
                        <!-- Blue dot for holidays -->
                        <span class="inline-block w-2 h-2 rounded-full mr-2 bg-blue-500"></span>
                        @elseif(strpos($data['status'], 'Absent, Present') !== false)
                        <!-- Dot with half red and half green -->
                        <span class="inline-block w-2 h-2 rounded-full mr-2 bg-split"></span>
                        @elseif(strpos($data['status'], 'Weekend') !== false)
                        <!-- Yellow dot for weekends -->
                        <span class="inline-block w-2 h-2 rounded-full mr-2 bg-yellow-300"></span>
                        @else
                        <!-- Single colored dot based on status -->
                        <span class="inline-block w-2 h-2 rounded-full mr-2
                        @if($data['status'] === 'Present') bg-green-500
                        @elseif($data['status'] === 'Absent') bg-red-500
                        @else  @endif">
                        </span>
                        @endif
                        {{ $data['status'] }}
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    
<!-- Fixed footer -->
<div class="fixed bottom-5 left-60 w-[calc(100%-10rem)] bg-gray-100 dark:bg-gray-800 p-4 text-sm text-gray-700 dark:text-gray-300 shadow-md flex items-center">
    <!-- Tabs for Days and Hours aligned to the left -->
    <div class="flex space-x-4">
        <!-- Adjusted padding to move tabs to the left -->
        <button id="days-tab" class="px-4 py-2 text-gray-700 dark:text-gray-300 focus:outline-none" onclick="showContent('days')">Days</button>
        <button id="hours-tab" class="px-4 py-2 text-gray-700 dark:text-gray-300 focus:outline-none" onclick="showContent('hours')">Hours</button>
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

</div>

    
 
<script>
    document.addEventListener('DOMContentLoaded', function () {
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




</div>
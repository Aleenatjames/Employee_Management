<div>

    <div class="bg-gray-200 text-gray-600 py-4 text-center flex items-center">
        <a wire:click="previousPeriod" href="{{route('employee.attendance')}}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>
        </a>
        <h1 class="text-xl">Audit History</h1>
    </div>

    <div class="ml-24 my-10">
        <div class="mb-4 flex items-center">
            <label class="block text-gray-600 text-sm font-bold mb-2 dark:text-gray-400" for="ZPAtt_Audit_FilDate">
                Audit history for date
            </label>
            <div class="btnCal">
                <input type="date" wire:model.live="date" id="ZPAtt_Audit_FilDate" class="shadow appearance-none rounded w-full py-2 px-3 ml-5 text-gray-700 leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-700 border border-gray-300 dark:border-gray-600 dark:text-white">
            </div>
        </div>

        <div id="attendance-entries">
            @if(count($attendancePairs) > 0)
            <div class="aclog_cont">
                <div class="aclog_detl mb-4">
                    <div class="cur_day mb-2">
                        <span class="font-semibold text-gray-700 dark:text-gray-100">{{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}</span>
                    </div>

                    @foreach($attendancePairs as $pair)
                    <div class="event add mb-2 flex items-center space-x-10">
                        <span class="tline text-gray-500 text-sm">{{ \Carbon\Carbon::parse($pair['checkIn']->entry_time ?? $pair['checkIn']->created_at)->format('h:i A') }}</span>
                        <div class="flex items-center mt-1">
                            <a href="#" class="text-blue-500 hover:underline">{{ $pair['checkOut']->attendance->employee->name}}</a>
                            <span class="ml-2 text-gray-600">checked in</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 ml-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 20.25h12m-7.5-3v3m3-3v3m-10.125-3h17.25c.621 0 1.125-.504 1.125-1.125V4.875c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125Z" />
                            </svg>
                        </div>
                    </div>
                    <div class="event add mb-2 flex items-center space-x-10">
                        <span class="tline text-gray-500 text-sm">{{ \Carbon\Carbon::parse($pair['checkOut']->entry_time ?? $pair['checkOut']->created_at)->format('h:i A') }}</span>
                        <div class="flex items-center mt-1">
                            <a href="#" class="text-blue-500 hover:underline">{{ $pair['checkOut']->attendance->employee->name }}</a>
                            <span class="ml-2 text-gray-600">checked out</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 ml-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 20.25h12m-7.5-3v3m3-3v3m-10.125-3h17.25c.621 0 1.125-.504 1.125-1.125V4.875c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125Z" />
                            </svg>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div id="ZPAtt_Audit_NoData">
                <div class="flex items-center justify-center p-4   dark:bg-gray-800 w-96 dark:text-gray-100">
                    <i class="CR-nofeeds text-gray-400 mr-2"></i>
                    <h5 class="text-gray-600">No audit history to show here</h5>
                </div>
            </div>
            @endif

            @foreach($attendancePairs as $pair)
            @if($pair['checkIn']->modifiedBy || $pair['checkOut']->modifiedBy)
            <div class="cur_day mb-2">
                <span class="font-semibold text-gray-700 dark:text-gray-100">
                    @if($pair['checkIn']->updated_at && $pair['checkIn']->created_at != $pair['checkIn']->updated_at)
                    {{ $pair['checkIn']->updated_at->format('d-m-Y') }}
                    @elseif($pair['checkOut']->updated_at && $pair['checkOut']->created_at != $pair['checkOut']->updated_at)
                    {{ $pair['checkOut']->updated_at->format('d-m-Y') }}
                    @else
                    No modifications
                    @endif
                </span>
            </div>

            <div class="audit-entry mb-4 p-4 bg-white shadow-sm rounded-lg ml-20 w-96 dark:bg-gray-800">
                <div class="flex items-center">
                    <a href="#" onclick="ZPeople.getUserPage('79183000000096107')" class="text-blue-500 hover:underline">
                        {{ $pair['checkIn']->modifiedBy->name ?? $pair['checkOut']->modifiedBy->name ?? 'Unknown' }}
                    </a>
                    <span class="mx-1 text-gray-600">edited entries on behalf of</span>
                    <a href="#" onclick="ZPeople.getUserPage('79183000018685821')" class="text-blue-500 hover:underline">
                        {{ auth()->guard('employee')->user()->name }}
                    </a>
                    <i class="IC-system dgry S12 ML5 ml-2"></i>
                </div>

                <div class="att_lgentry flex flex-col mt-4">
                    <div class="att_lgentryrw flex mb-2 dark:bg-gray-500">
                        <div class="att_pnchdt POI flex-1 bg-gray-100 p-2 rounded">
                            <span class="text-gray-600">Previous entry</span>
                        </div>
                        <div class="att_pnchdt POI flex-1 bg-gray-100 p-2 rounded dark:bg-gray-500">
                            <span class="text-gray-600">New entry</span>
                        </div>
                    </div>

                    <!-- Check-In Section -->
                    @if($pair['checkIn']->modifiedBy)
                    <div class="att_lgentryrw flex">
                        <!-- From (Check-in created_at time) -->
                        <div class="att_pnchdt in flex-1 bg-gray-100 p-2 rounded flex flex-col items-start border-l-4 border-green-500 dark:bg-gray-500">
                            <span class="S12 text-gray-500">From</span>
                            <span><b>{{ $pair['checkIn']->created_at->format('d-m-Y') }}</b></span>
                            <span><b>{{ $pair['checkIn']->created_at->format('h:i A') }}</b></span>
                        </div>

                        <!-- To (Check-in entry_time if modified, otherwise use created_at) -->
                        <div class="att_pnchdt in flex-1 bg-gray-100 p-2 rounded flex flex-col items-start dark:bg-gray-500">
                            <span class="S12 text-gray-500">To</span>
                            <span><b>{{ $pair['checkIn']->created_at->format('d-m-Y') }}</b></span>
                            <span><b>{{ \Carbon\Carbon::parse($pair['checkIn']->entry_time ?? $pair['checkIn']->created_at)->format('h:i A') }}</b></span>
                        </div>
                    </div>
                    @endif

                    <!-- Check-Out Section -->
                    @if($pair['checkOut']->modifiedBy)
                    <div class="att_lgentryrw flex mt-2">
                        <!-- From (Check-out created_at time) -->
                        <div class="att_pnchdt in flex-1 bg-gray-100 p-2 rounded flex flex-col items-start border-l-4 border-green-500 dark:bg-gray-500">
                            <span class="S12 text-gray-500">From</span>
                            <span><b>{{ $pair['checkOut']->created_at->format('d-m-Y') }}</b></span>
                            <span><b>{{ $pair['checkOut']->created_at->format('h:i A') }}</b></span>
                        </div>

                        <!-- To (Check-out entry_time if modified, otherwise use created_at) -->
                        <div class="att_pnchdt in flex-1 bg-gray-100 p-2 rounded flex flex-col items-start dark:bg-gray-500">
                            <span class="S12 text-gray-500">To</span>
                            <span><b>{{ $pair['checkOut']->created_at->format('d-m-Y') }}</b></span>
                            <span><b>{{ \Carbon\Carbon::parse($pair['checkOut']->entry_time ?? $pair['checkOut']->created_at)->format('h:i A') }}</b></span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
            @endforeach






        </div>

    </div>
</div>
<!-- <div class="fix_day mb-4">
<div style="opacity: 1;"></div>
</div>

<div id="ZPAtt_Audit_NoData" class="hidden text-center">
<div class="flex flex-col items-center justify-center opacity-60">
    <i class="fas fa-exclamation-circle text-gray-400 text-5xl mb-2"></i>
    <h5 class="text-gray-600">No audit history to show here</h5>
</div>
</div>

<div class="aclog_cont mb-4" id="ZPAtt_Audit_ActivityBody_30082024">
<div class="aclog_detl mb-4">
    <div class="cur_day font-bold mb-2">
        <span id="ZPAtt_Audit_Date">30-08-2024</span>
    </div>
    <div class="event flex items-center mb-2">
        <span class="mr-2">12:31 PM</span>
        <div>
            <a href="#" onclick="ZPeople.getUserPage('79183000000096107')" class="text-blue-500">Santosh Abraham</a>
            edited entries on behalf of 
            <a href="#" onclick="ZPeople.getUserPage('79183000018685821')" class="text-blue-500">Aleena T James</a>
            <i class="fas fa-cog text-gray-500 ml-2"></i>
        </div>
    </div>
    <div class="att_lgentry flex flex-col space-y-2">
        <div class="att_lgentryrw flex space-x-2">
            <div class="att_pnchdt POI flex-1 bg-gray-100 p-2 rounded">Details 1</div>
            <div class="att_pnchdt POI flex-1 bg-gray-100 p-2 rounded">Details 2</div>
        </div>
        <div class="att_lgentryrw flex space-x-2">
            <div class="att_pnchdt in flex-1 bg-gray-100 p-2 rounded flex flex-col items-start">
                <span class="text-sm text-gray-500">From</span>
                <span><b>01-08-2024</b></span>
                <span><b>09:45 AM</b></span>
                <div class="arwto flex items-center">
                    <i class="fas fa-arrow-right text-gray-500"></i>
                </div>
            </div>
            <div class="att_pnchdt in flex-1 bg-gray-100 p-2 rounded flex flex-col items-start">
                <span class="text-sm text-gray-500">From</span>
                <span><b>01-08-2024</b></span>
                <span><b>09:30 AM</b></span>
            </div>
        </div>
    </div>
</div>
</div> -->


</div>
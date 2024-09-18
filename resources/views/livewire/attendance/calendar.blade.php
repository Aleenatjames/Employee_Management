<div>
  <div class="Cal-container dark:bg-gray-900 dark:text-gray-100">
    <div class="Cal-card bg-white dark:bg-slate-800 ">
      <header class="Cal-header dark:bg-slate-800 flex h-12 justify-center">

        <div class="flex items-center space-x-2">
          <button wire:click="previousMonth" class="Cal-header-button text-gray-900 dark:text-gray-100">
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
              <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
            </svg>
          </button>
          <h1 class="Cal-header-title">
            <time datetime="{{ $currentDate->format('Y-m') }}" class="text-gray-900 dark:text-gray-300">{{ $monthYear }}</time>
          </h1>
          <button wire:click="nextMonth" class="Cal-header-button text-gray-900 dark:text-gray-100">
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
              <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
            </svg>
          </button>
        </div>
      </header>
      <div class="Cal-grid-wrapper dark:bg-slate-800">
        <div class="Cal-grid dark:bg-slate-800 grid grid-cols-7 gap-1">
          <!-- Header for days of the week -->
          <div class="Cal-day-header text-gray-700 dark:text-gray-300 dark:bg-slate-800">M</div>
          <div class="Cal-day-header text-gray-700 dark:text-gray-300 dark:bg-slate-800">T</div>
          <div class="Cal-day-header text-gray-700 dark:text-gray-300 dark:bg-slate-800">W</div>
          <div class="Cal-day-header text-gray-700 dark:text-gray-300 dark:bg-slate-800">T</div>
          <div class="Cal-day-header text-gray-700 dark:text-gray-300 dark:bg-slate-800">F</div>
          <div class="Cal-day-header text-gray-700 dark:text-gray-300 dark:bg-slate-800">S</div>
          <div class="Cal-day-header text-gray-700 dark:text-gray-300 dark:bg-slate-800">S</div>
          <!-- Calendar days -->
          @foreach($days as $week)
    @foreach($week as $day)
        @if($day['currentMonth'])
            @php
                $date = $currentDate->copy()->day($day['day'])->format('Y-m-d');
                $isWeekend = $currentDate->copy()->day($day['day'])->isWeekend(); // Check if it's Saturday or Sunday
            @endphp
            <div class="Cal-day-cell border border-gray-300 dark:border-gray-600 {{ $isWeekend ? 'bg-yellow-200' : '' }} dark:bg-gray-600" style="height: 120px; position: relative;">
                <div style="display: flex; flex-direction: column; align-items: left; justify-content: start; height: 100%;">
                    <span class="day-number text-gray-700 dark:text-gray-100">{{ $day['day'] ?? '' }}</span><br>

                    @if($attendances->has($date) && $attendances[$date]->status != 'na')
                        @php
                            $presentTag = '';
                            $absentTag = '';
                            $tagColor = 'green';

                            if ($attendances[$date]->status == "pp") {
                                $presentTag = 'Present';
                            } elseif ($attendances[$date]->status == "pa") {
                                $presentTag = '0.5 Present';
                                $absentTag = '0.5 Absent';
                            } elseif ($attendances[$date]->status == "ap") {
                                $absentTag = '0.5 Absent';
                                $presentTag = '0.5 Present';
                            } elseif ($attendances[$date]->status == "aa") {
                                $absentTag = 'Absent';
                                $tagColor = 'red';
                            }
                        @endphp

                        @if($attendances[$date]->status == 'pa')
                            @if ($presentTag)
                                <div class="Cal-tag Cal-tag-green ">
                                    {{ $presentTag }}
                                </div>
                            @endif
                            @if ($absentTag)
                                <div class="Cal-tag Cal-tag-red ">
                                    {{ $absentTag }}
                                </div>
                            @endif
                        @elseif($attendances[$date]->status == 'ap')
                            @if ($absentTag)
                                <div class="Cal-tag Cal-tag-red ">
                                    {{ $absentTag }}
                                </div>
                            @endif
                            @if ($presentTag)
                                <div class="Cal-tag Cal-tag-green ">
                                    {{ $presentTag }}
                                </div>
                            @endif
                        @elseif($attendances[$date]->status == 'pp' || $attendances[$date]->status == 'aa')
                            @if ($presentTag)
                                <div class="Cal-tag Cal-tag-green ">
                                    {{ $presentTag }}
                                </div>
                            @endif
                            @if ($absentTag)
                                <div class="Cal-tag Cal-tag-red ">
                                    {{ $absentTag }}
                                </div>
                            @endif
                        @endif
                    @endif

                    @if ($holidays->has($date))
    @php
        $tagColor = 'blue';
        $tagText = $holidays[$date]->name;

        // Append "(restricted)" if the holiday is restricted
        if($holidays[$date]->is_restricted == 'yes') {
            $tagText .= " (restricted)";
        }
    @endphp

    <div class="Cal-tag Cal-tag-{{ $tagColor }} ">
        {{ $tagText }}
    </div>
@endif

                </div>
            </div>
        @else
            <div class="Cal-day-cell empty-cell border border-gray-300 dark:border-gray-600" style="height: 120px;"></div>
        @endif
    @endforeach
@endforeach

        </div>
      </div>
    </div>
  </div>
</div>
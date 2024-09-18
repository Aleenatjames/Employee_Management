@if($data['attendanceId'])
<td class="px-4 py-2">
    <div x-data="{ open: false, attendanceId: @js($data['attendanceId']) }">
        <button @click="open = true" class="px-3 py-1 mr-2 text-gray-500 dark:text-gray-100 rounded hover:text-gray-600">
            <!-- SVG icon for edit -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>
        </button>

        <!-- Modal -->
        <div x-show="open" @click.away="open = false" class="fixed inset-0 flex items-center justify-center z-50">
            <div class="bg-white p-4 border border-gray-300 rounded shadow-lg w-1/3">
                <h2 class="text-lg font-semibold mb-2">Edit Status</h2>
                <form method="POST" action="{{ route('employee.attendance.update', $data['attendanceId']) }}">
                    @csrf
                    @method('post')

                    <div class="flex justify-between">
                        <div class="w-1/2 pr-2">
                            <h3 class="text-sm font-medium">Check-In Times</h3>
                            <ul>
                                @forelse($data['check_in_times'] as $index => $checkInTime)
                                <li class="flex items-center mb-2">
                                    <input type="time" name="check_in_times[{{ $index }}]" value="{{ $checkInTime }}" class="form-input mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </li>
                                @empty
                                <li class="flex items-center mb-2">
                                    <input type="time" name="check_in_times[0]" value="" class="form-input mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </li>
                                @endforelse
                            </ul>
                        </div>

                        <div class="w-1/2 pl-2">
                            <h3 class="text-sm font-medium">Check-Out Times</h3>
                            <ul>
                                @forelse($data['check_out_times'] as $index => $checkOutTime)
                                <li class="flex items-center mb-2">
                                    <input type="time" name="check_out_times[{{ $index }}]" value="{{ $checkOutTime }}" class="form-input mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </li>
                                @empty
                                <li class="flex items-center mb-2">
                                    <input type="time" name="check_out_times[0]" value="" class="form-input mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>


                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Save</button>
                        <button @click="open = false" type="button" class="ml-2 px-4 py-2 bg-gray-500 text-white rounded">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        @else
        <button class="px-3 py-1 mr-2 text-gray-500 dark:text-gray-100 rounded hover:text-gray-600">
            <!-- SVG icon placeholder if attendanceId is not available -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                <!-- Add SVG path here if needed -->
            </svg>
        </button>
        @endif
    </div>
</td>
<div>
    <div class="flex justify-center items-start -screen dark:bg-gray-900 py-8">
        <div class="relative flex flex-col min-w-0 break-words dark:bg-gray-800 w-full max-w-2xl shadow-lg rounded">
            <div class="rounded-t">
                <div class="flex flex-wrap items-center px-4 py-2">
                    <div class="relative w-full max-w-full flex-grow flex-1">
                        <h3 class="font-semibold text-base text-gray-900 dark:text-gray-50">
                         
                        </h3>
                    </div>
                    <div class="relative w-full max-w-full flex-grow flex-1 text-right">
                        <a href="{{ route('employee.leave.index') }}" class="bg-blue-500 dark:bg-gray-100 text-white dark:text-gray-800 text-xs font-bold px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150">Back to List</a>
                    </div>
                </div>

                @if(session()->has('message'))
                <div class="bg-green-500 text-white p-4 rounded shadow-md mb-6">
                    {{session('message')}}
                </div>
                @endif
                
                <!-- Store or Update Form -->
                <form wire:submit.prevent="store" class="px-4 py-6">
                    
                    <!-- incremental_leave_count Input -->
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-100">Code</label>
                        <div class="flex items-center mt-2">
                            <input type="text" wire:model="code" value="{{ old('code', $code) }}" id="code" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-100 rounded-md p-2" />
                        </div>
                        @error('code')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>

                    <!-- Leave Name Input -->
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-100">Leave Name</label>
                        <div class="flex items-center mt-2">
                            <input type="text" wire:model="name" value="{{ old('name', $name) }}" id="name" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-100 rounded-md p-2" />
                        </div>
                        @error('name')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>

                    <!-- Is Payable Radio Buttons -->
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-100">Is Payable</label>
                        <div class="flex items-center mt-2">
                            <input type="radio" wire:model="is_payable" value="1" class="mr-2" {{ old('is_payable', $is_payable) == 1 ? 'checked' : '' }} />
                            <span class="text-gray-800 dark:text-gray-200">Yes</span>
                            <input type="radio" wire:model="is_payable" value="0" class="ml-4 mr-2" {{ old('is_payable', $is_payable) == 0 ? 'checked' : '' }} />
                            <span class="text-gray-800 dark:text-gray-200">No</span>
                        </div>
                        @error('is_payable')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>

                    <!-- Is Carry Over Radio Buttons -->
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-100">Is Carry Over</label>
                        <div class="flex items-center mt-2">
                            <input type="radio" wire:model="is_carry_over" value="1" class="mr-2" {{ old('is_carry_over', $is_carry_over) == 1 ? 'checked' : '' }} />
                            <span class="text-gray-800 dark:text-gray-200">Yes</span>
                            <input type="radio" wire:model="is_carry_over" value="0" class="ml-4 mr-2" {{ old('is_carry_over', $is_carry_over) == 0 ? 'checked' : '' }} />
                            <span class="text-gray-800 dark:text-gray-200">No</span>
                        </div>
                        @error('is_carry_over')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>

                    <!-- Incremental Type Select -->
                    <div class="mb-4">
                        <label for="incremental_type" class="block text-gray-700 dark:text-gray-100">Select Incremental Type</label>
                        <select wire:model="incremental_type" id="incremental_type" class="mr-1 block w-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-200 rounded-md p-2 mt-2">
                            <option value="y" {{ old('incremental_type', $incremental_type) == 'y' ? 'selected' : '' }}>Yearly</option>
                            <option value="mon" {{ old('incremental_type', $incremental_type) == 'mon' ? 'selected' : '' }}>Monthly</option>
                            <option value="qua" {{ old('incremental_type', $incremental_type) == 'qua' ? 'selected' : '' }}>Quarterly</option>
                            <option value="h" {{ old('incremental_type', $incremental_type) == 'h' ? 'selected' : '' }}>Hourly</option>
                        </select>
                        @error('incremental_type')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-4">
                        <label for="application_timing" class="block text-gray-700 dark:text-gray-100">Select Application Timing</label>
                        <select wire:model="application_timing" id="application_timing" class="mr-1 block w-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-200 rounded-md p-2 mt-2">
                            <option value="any" {{ old('application_timing', $application_timing) == 'Any' ? 'selected' : '' }}>Any</option>
                            <option value="before" {{ old('application_timing', $application_timing) == 'Before' ? 'selected' : '' }}>Before</option>
                            <option value="after" {{ old('application_timing', $application_timing) == 'After' ? 'selected' : '' }}>After</option>
                         
                        </select>
                        @error('application_timing')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-4">
                        <h2 class="text-lg font-semibold">Edit Division Leave Types</h2>

                        @if(!empty($divisionLeaveTypes))
                            @foreach($divisionLeaveTypes as $index => $divisionLeaveType)
                                <div class="mb-4 border p-4 rounded-md flex items-center justify-between">
                                    <div>
                                        <p><strong>Division Name:</strong> {{ $divisionLeaveType['division_name'] ?? 'No Division' }}</p>
                                    </div>

                                    <div class="flex space-x-4">
                                        <div class="mb-4">
                                            <label class="block text-gray-700 dark:text-gray-100">Incremental Leave Count</label>
                                            <input type="text" 
                                                wire:model.defer="divisionLeaveTypes.{{ $index }}.incremental_count" 
                                                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-100 rounded-md p-2" />
                                            @error('divisionLeaveTypes.' . $index . '.incremental_count')
                                                <span class="text-red-500 text-xs">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p>No Division Leave Types available for this Leave Type.</p>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded">
                           update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

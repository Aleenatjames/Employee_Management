<div>
    <div class="flex justify-center items-start -screen dark:bg-gray-900 py-8">
        <div class="relative flex flex-col min-w-0 break-words dark:bg-gray-800 w-full max-w-2xl shadow-lg rounded">
            <div class="rounded-t">
                <div class="flex flex-wrap items-center px-4 py-2">
                    <div class="relative w-full max-w-full flex-grow flex-1">
                        <h3 class="font-semibold text-base text-gray-900 dark:text-gray-50">Create Leave Type</h3>
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
                <form wire:submit.prevent="store" class="px-4 py-6">
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-100">Code</label>
                        <div class="flex items-center mt-2">
                            <input type="text" wire:model="code" id="code" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-100 rounded-md p-2" />

                        </div>
                        @error('code')<span class="text-red-500 text-xs">{{$message}}</span>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-100">Leave Name</label>
                        <div class="flex items-center mt-2">
                            <input type="text" wire:model="name" id="name" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-100 rounded-md p-2" />

                        </div>
                        @error('name')<span class="text-red-500 text-xs">{{$message}}</span>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-100">Is Payable</label>
                        <div class="flex items-center mt-2">
                            <input type="radio" wire:model="is_payable" value="1" class="mr-2" />
                            <span class="text-gray-800 dark:text-gray-200">Yes</span>
                            <input type="radio" wire:model="is_payable" value="0" class="ml-4 mr-2" />
                            <span class="text-gray-800 dark:text-gray-200">No</span>
                        </div>
                        @error('is_payable')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-100">Is Carry Over</label>
                        <div class="flex items-center mt-2">
                            <input type="radio" wire:model="is_carry_over" value="1" class="mr-2" />
                            <span class="text-gray-800 dark:text-gray-200">Yes</span>
                            <input type="radio" wire:model="is_carry_over" value="0" class="ml-4 mr-2" />
                            <span class="text-gray-800 dark:text-gray-200">No</span>
                        </div>
                        @error('is_carry_over')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="incremental_type" class="block text-gray-700 dark:text-gray-100">Select incremental type</label>
                        <select wire:model="incremental_type" id="incremental_type" class="mr-1 block w-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-200 rounded-md p-2 mt-2">
                            <option value="y">Yearly</option>
                            <option value="mon">Monthly</option>
                            <option value="qua">Quarterly</option>
                            <option value="h">Hourly</option>
                        </select>
                        @error('incremental_type')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="application_timing" class="block text-gray-700 dark:text-gray-100">Select Application Timing</label>
                        <select wire:model="application_timing" id="application_timing" class="mr-1 block w-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-200 rounded-md p-2 mt-2">
                            <option value="any">Any</option>
                            <option value="before">Before</option>
                            <option value="after">After</option>
                        </select>
                        @error('application_timing') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <select wire:model="parent_id" id="parent_id" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-100 rounded-md p-2">
                        <option value="">Select Parent Division</option>
                        @foreach ($existingParents as $parent)
                        <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                        @endforeach
                    </select>
                    @error('parent_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror


                    <div class="mt-6">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white dark:bg-gray-100 dark:text-gray-800 dark:hover:text-gray-700 py-2 px-4 rounded">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="flex justify-center items-start dark:bg-gray-900 py-1">
        <div class="relative flex flex-col min-w-0 break-words dark:bg-gray-800 w-full max-w-2xl shadow-lg rounded">
            <div class="rounded-t">
                <div class="flex flex-wrap items-center px-4 py-2">
                    <div class="relative w-full max-w-full flex-grow flex-1">
                        <h3 class="font-semibold text-base text-gray-900 dark:text-gray-50">Assign Leave Type to Division</h3>
                    </div>
                   
                </div>
                <form wire:submit.prevent="storeDivisionLeaveType" class="px-4 py-6">
                    <!-- Leave Type Select -->
                    <div class="mb-4">
                        <label for="leave_type_id" class="block text-gray-700 dark:text-gray-100">Select Leave Type</label>
                        <select wire:model.live="leave_type_id" class="block w-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-100 rounded-md p-2">
                            <option value="">Select Leave Type</option>
                            @foreach ($leaveTypes as $leaveType)
                            <option value="{{ $leaveType->id }}">{{ $leaveType->name }}</option>
                            @endforeach
                        </select>
                        @error('leave_type_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Child Division Select (Only visible if there are child divisions) -->
                    @if($childDivisions->isNotEmpty())
                    <div class="mb-4">
                        <label for="child_id" class="block text-gray-700 dark:text-gray-100">Select Child Division</label>
                        <select wire:model="child_id" class="block w-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-100 rounded-md p-2">
                            <option value="">Select Child Division</option>
                            @foreach ($childDivisions as $child)
                            <option value="{{ $child->id }}">{{ $child->name }}</option>
                            @endforeach
                        </select>
                        @error('child_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    @endif

                    <!-- Incremental Count Input -->
                    <div class="mb-4">
                        <label for="incremental_count" class="block text-gray-700 dark:text-gray-100">Incremental Count</label>
                        <input type="text" wire:model="incremental_count" class="block w-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-100 rounded-md p-2" />
                        @error('incremental_count') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded">Save</button>
                    </div>
                </form>

            </div>
        </div>
    </div>


</div>
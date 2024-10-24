<div>
    <div class="flex justify-center items-start min-h-screen bg-gray-100 dark:bg-gray-900 py-8">
        <div class="relative flex flex-col min-w-0 break-words bg-gray-50 dark:bg-gray-800 w-full max-w-2xl shadow-lg rounded">
            <div class="rounded-t mb-0 px-0 border-0">
                <div class="flex flex-wrap items-center px-4 py-2">
                    <div class="relative w-full max-w-full flex-grow flex-1">
                        <h3 class="font-semibold text-base text-gray-900 dark:text-gray-50">Assign Employee Category</h3>
                    </div>
                    <div class="relative w-full max-w-full flex-grow flex-1 text-right">
                        <a href="{{ route('employee.projects.index') }}" class="bg-blue-500 dark:bg-gray-100 text-white dark:text-gray-800 text-xs font-bold uppercase px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150">Back to List</a>
                    </div>
                </div>

                @if (session()->has('message'))
                <div class="bg-green-500 text-white p-4 rounded shadow-md mb-6">
                    {{ session('message') }}
                </div>
                @endif
                @if (session()->has('error'))
                <div class="bg-red-500 text-white p-4 rounded shadow-md mb-6">
                    {{ session('error') }}
                </div>
                @endif

                <form wire:submit.prevent="store" class="px-4 py-6">
                    <!-- Parent Division Creation/Selection -->
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-100">Create New or Select Existing Parent Division</label>
                        <div class="flex items-center mt-2">
                            <input type="radio" wire:model.live="create_new_parent" value="1" class="mr-2">
                            <span class="text-gray-800 dark:text-gray-200">Create New Parent</span>
                            <input type="radio" wire:model.live="create_new_parent" value="0" class="ml-4 mr-2">
                            <span class="text-gray-800 dark:text-gray-200">Select Existing Parent</span>
                        </div>
                    </div>

                    @if ($create_new_parent)
                    <!-- Create New Parent -->
                    <div class="mb-4">
                        <label for="parent_name" class="block text-gray-700 dark:text-gray-100">Parent Division Name</label>
                        <input type="text" wire:model="parent_name" id="parent_name" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-100 rounded-md p-2" />
                        @error('parent_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    @else
                    <!-- Select Existing Parent -->
                    <div class="mb-4">
                        <label for="parent_id" class="block text-gray-700 dark:text-gray-100">Select Parent Division</label>
                        <select wire:model.live="parent_id" id="parent_id" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-100 rounded-md p-2">
                            <option value="">Select Parent Division</option>
                            @foreach ($existingParents as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                            @endforeach
                        </select>
                        @error('parent_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    @endif

                  
                    <!-- Create New Child -->
                    <div class="mb-4">
                        <label for="child_name" class="block text-gray-700 dark:text-gray-100">Child Division Name</label>
                        <input type="text" wire:model="child_name" id="child_name" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-100 rounded-md p-2" />
                        @error('child_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white dark:bg-gray-100 dark:text-gray-800 dark:hover:text-gray-700 py-2 px-4 rounded">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
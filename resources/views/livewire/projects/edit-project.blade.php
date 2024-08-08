<div>
   <div class="flex justify-center items-start min-h-screen bg-gray-100 dark:bg-gray-900 py-8">
        <div class="relative flex flex-col min-w-0 break-words bg-gray-50 dark:bg-gray-800 w-full max-w-2xl shadow-lg rounded">
            <div class="rounded-t mb-0 px-0 border-0">
                <div class="flex flex-wrap items-center px-4 py-2">
                    <div class="relative w-full max-w-full flex-grow flex-1">
                        <h3 class="font-semibold text-base text-gray-900 dark:text-gray-50">Edit Project</h3>
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

                <form wire:submit.prevent="save" class="px-4 py-6">
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 dark:text-gray-100">Project Name</label>
                        <input type="text" id="name" wire:model="name" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-100" />
                        @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-gray-700 dark:text-gray-100">Description</label>
                        <textarea id="description" wire:model="description" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-100"></textarea>
                        @error('description') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="status" class="block text-gray-700 dark:text-gray-100">Status</label>
                        <select id="status" wire:model="status" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-100">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        @error('status') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="start_date" class="block text-gray-700 dark:text-gray-100">Start Date</label>
                        <input type="date" id="start_date" wire:model="start_date" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-100" />
                        @error('start_date') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="end_date" class="block text-gray-700 dark:text-gray-100">End Date</label>
                        <input type="date" id="end_date" wire:model="end_date" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-100" />
                        @error('end_date') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="pm" class="block text-gray-700 dark:text-gray-100">Assigned To</label>
                        <select id="pm" wire:model="pm" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-100">
                            <option value="">Select an employee</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                        @error('pm') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white dark:bg-gray-100 dark:text-gray-800 dark:hover:text-gray-700 py-2 px-4 rounded">Update Project</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</div>

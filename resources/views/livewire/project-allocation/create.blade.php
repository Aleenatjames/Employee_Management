<div>
    <div class="flex justify-center items-start min-h-screen bg-gray-100 dark:bg-gray-900 py-8">
        <div class="relative flex flex-col min-w-0 break-words bg-gray-50 dark:bg-gray-800 w-full max-w-2xl shadow-lg rounded">
            <div class="rounded-t mb-0 px-0 border-0">
                <div class="flex flex-wrap items-center px-4 py-2">
                    <div class="relative w-full max-w-full flex-grow flex-1">
                        <h3 class="font-semibold text-base text-gray-900 dark:text-gray-50">Create Project Allocation</h3>
                    </div>
                    <div class="relative w-full max-w-full flex-grow flex-1 text-right">
                        <a href="{{ route('employee.project-allocations.index') }}" class="bg-blue-500 dark:bg-gray-100 text-white dark:text-gray-800 text-xs font-bold uppercase px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150">
                            Back to List
                        </a>
                    </div>
                </div>

                @if (session()->has('message'))
                    <div class="bg-green-500 text-white p-4 rounded shadow-md mb-6">
                        {{ session('message') }}
                    </div>
                @endif

                <form wire:submit.prevent="submit" class="px-4 py-6">
                    <div class="mb-4">
                        <label for="project_id" class="block text-gray-700 dark:text-gray-100">Project</label>
                        <select id="project_id" wire:model="project_id" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-100">
                            <option value="">Select a Project</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                        @error('project_id') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="employee_id" class="block text-gray-700 dark:text-gray-100">Employee</label>
                        <select id="employee_id" wire:model="employee_id" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-100">
                            <option value="">Select an Employee</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                        @error('employee_id') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="role_id" class="block text-gray-700 dark:text-gray-100">Role</label>
                        <select id="role_id" wire:model="role_id" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-100">
                            <option value="">Select a Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role_id') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="allocated_by" class="block text-gray-700 dark:text-gray-100">Allocated By</label>
                        <select id="allocated_by" wire:model="allocated_by" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-100">
                            <option value="">Select Allocator</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                        @error('allocated_by') <span class="text-red-500">{{ $message }}</span> @enderror
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

                    <div class="mt-6">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white dark:bg-gray-100 dark:text-gray-800 dark:hover:text-gray-700 py-2 px-4 rounded">
                            Create Allocation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

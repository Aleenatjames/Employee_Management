<div>
<div>
    <section class="mt-10">
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
            <div class="mt-20 bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                <div class="flex items-center justify-between p-4">
                    <h3 class="font-semibold text-base text-gray-900 dark:text-gray-50">Edit Project Allocation</h3>
                    <a href="{{ route('employee.projects.index') }}" class="bg-blue-500 text-white dark:bg-gray-100 text-xs font-bold uppercase px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150">Back to List</a>
                </div>

                @if (session()->has('message'))
                    <div class="bg-green-500 text-white p-4 rounded shadow-md mb-6">
                        {{ session('message') }}
                    </div>
                @endif

                <form wire:submit.prevent="update" class="px-4 py-6">
                    <div class="mb-4">
                        <label for="project_id" class="block text-gray-700 dark:text-gray-100">Project</label>
                        <select id="project_id" wire:model="project_id" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-100">
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                        @error('project_id') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="employee_id" class="block text-gray-700 dark:text-gray-100">Employee</label>
                        <select id="employee_id" wire:model="employee_id" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-100">
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                        @error('employee_id') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="role_id" class="block text-gray-700 dark:text-gray-100">Role</label>
                        <select id="role_id" wire:model="role_id" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-100">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role_id') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="allocated_by" class="block text-gray-700 dark:text-gray-100">Allocated By</label>
                        <select id="allocated_by" wire:model="allocated_by" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-100">
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
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white dark:bg-gray-100 dark:text-gray-800 dark:hover:text-gray-700 py-2 px-4 rounded">Update Allocation</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

</div>

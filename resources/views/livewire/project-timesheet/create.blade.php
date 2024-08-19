<div>
    <div class="h-full ml-14 mt-14 mb-10 md:ml-64">
        <div class="mx-auto max-w-screen-lg px-4 lg:px-12 mt-20">
            <div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg p-6">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-6">Timesheet</h2>

                <!-- Success Message -->
                @if (session()->has('message'))
                <div class="bg-green-500 text-white p-4 rounded shadow-md mb-6">
                    {{ session('message') }}
                </div>
                @endif

                <form wire:submit.prevent="submitTimesheet">
                    <div class="gap-4 mb-4">
                        <!-- Project ID -->
                        <div class="w-60">
                            <label for="project_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Project</label>
                            <select wire:model="project_id" id="project_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                                <option value="">Select Project</option>
                                @if($projects->isNotEmpty())
                                @foreach($projects as $project)
                                <option value="{{ $project->id }}">
                                    @if($project->group)
                                    {{ $project->group->name }} - {{ $project->name }}
                                    @else
                                    {{ $project->name }}
                                    @endif
                                </option>
                                @endforeach
                                @endif

                                @if($projectGroups->isNotEmpty())
                                @foreach($projectGroups as $projectGroup)
                                @foreach($projectGroup->projects as $project)
                                <option value="group-{{ $project->id }}">
                                    {{ $projectGroup->name }} - {{ $project->name }}
                                </option>
                                @endforeach
                                @endforeach
                                @endif

                                <!-- Individual Projects -->

                            </select>
                            @error('project_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <br>
                    <!-- Date -->
                    <div class="mb-4 w-56">
                        <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Date</label>
                        <input type="date" wire:model="date" id="date" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" max="{{ \Carbon\Carbon::today()->toDateString() }}">
                        @error('date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <br>
                    <!-- Time -->
                    <div class="gap-4 mb-4">
                        <label for="time" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Time Spent</label>
                        <div class="md:col-span-3 flex items-center space-x-2 w-60">
                            <div class="w-1/3">
                                <input type="number" wire:model="time_hours" id="time" placeholder="Hrs" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                            </div>
                            <div class="w-1/3">
                                <input type="number" wire:model="time_minutes" id="time_minutes" placeholder="Min" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                            </div>
                            <div class="w-1/3">
                                <input type="number" wire:model="time_seconds" id="time_seconds" placeholder="Sec" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                            </div>
                        </div>
                        @error('time') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <br>
                    <div class="mb-4 w-56">
                        <label for="is_taskid" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Is Task ID?</label>
                        <select wire:model="is_taskid" id="is_taskid" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                    <br>
                    <!-- Task ID (conditionally shown) -->

                    <div class="mb-4 w-56">
                        <label for="taskid" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Task ID</label>
                        <input type="text" wire:model="taskid" id="taskid" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                        @error('taskid') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <br>
                    <!-- Comment -->
                    <div class="mb-4">
                        <label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Comment</label>
                        <textarea wire:model="comment" id="comment" rows="6" class="mt-1 w-1/2 block py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"></textarea>
                        @error('comment') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <br>
                    <!-- Submit Button -->
                    <div class="text-left">
                        <button type="submit" class="px-4 py-2 bg-blue-500 dark:bg-blue-600 text-white rounded-lg hover:bg-blue-600 dark:hover:bg-blue-700">Submit Timesheet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
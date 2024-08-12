@extends('layouts.employee_dashboard')

@section('title', 'Project-Group-Create')

@section('sidebar')
    @parent

@endsection

@section('content')
       
<div class="h-full ml-14 mt-14 mb-10 md:ml-64">
<div class="h-full ml-14 mt-14 mb-10 md:ml-64">
    <div class="mx-auto max-w-screen-lg px-4 lg:px-12">
        <div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg p-6">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-6">Employee Timesheet</h2>

            <!-- Success Message -->
            @if (session()->has('message'))
                <div class="bg-green-500 text-white p-4 rounded shadow-md mb-6">
                    {{ session('message') }}
                </div>
            @endif

            <form wire:submit.prevent="submitTimesheet">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <!-- Employee ID -->

                    <!-- Project ID -->
                    <div>
                        <label for="project_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Project ID</label>
                        <select wire:model="project_id" id="project_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                            <option value="">Select Project</option>
                           
                        </select>
                        
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <!-- Date -->
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Date</label>
                        <input type="date" wire:model="date" id="date" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                       
                    </div>

                    <!-- Time -->
                    <div>
                        <label for="time" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Time (Hours)</label>
                        <input type="number" wire:model="time" id="time" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                       
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <!-- Task ID -->
                    <div>
                        <label for="task_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Task ID</label>
                        <select wire:model="task_id" id="task_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                            <option value="">Select Task</option>
                            
                        </select>
                        
                    </div>

                    <!-- Sub-task ID -->
                    <div>
                        <label for="subtask_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Sub-task ID</label>
                        <select wire:model="subtask_id" id="subtask_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                            <option value="">Select Sub-task</option>
                            
                        </select>
                        
                    </div>
                </div>

                <!-- Comment -->
                <div class="mb-4">
                    <label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Comment</label>
                    <textarea wire:model="comment" id="comment" rows="3" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"></textarea>
                    @error('comment') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Submit Button -->
                <div class="text-right">
                    <button type="submit" class="px-4 py-2 bg-blue-500 dark:bg-blue-600 text-white rounded-lg hover:bg-blue-600 dark:hover:bg-blue-700">Submit Timesheet</button>
                </div>
            </form>
        </div>
    </div>
</div>


</div> 


@endsection
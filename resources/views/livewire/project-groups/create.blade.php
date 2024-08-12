<div>
<div class="flex justify-center items-start min-h-screen bg-gray-100 dark:bg-gray-900 py-8">
    <div class="relative flex flex-col min-w-0 break-words bg-gray-50 dark:bg-gray-800 w-full max-w-2xl shadow-lg rounded">
        <div class="rounded-t mb-0 px-0 border-0">
            <div class="flex flex-wrap items-center px-4 py-2">
                <div class="relative w-full max-w-full flex-grow flex-1">
                    <h3 class="font-semibold text-base text-gray-900 dark:text-gray-50">Create Project Group</h3>
                </div>
            </div>

            @if (session()->has('message'))
                <div class="bg-green-500 text-white p-4 rounded shadow-md mb-6">
                    {{ session('message') }}
                </div>
            @endif

            <form wire:submit.prevent="save" class="px-4 py-6">
                <div class="mb-4">
                    <label for="code" class="block text-gray-700 dark:text-gray-100">Code</label>
                    <input type="text" id="code" wire:model="code" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-100" />
                    @error('code') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 dark:text-gray-100">Name</label>
                    <input type="text" id="name" wire:model="name" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-100" />
                    @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="isProject" class="block text-gray-700 dark:text-gray-100">Is Project?</label>
                    <input type="checkbox" id="isProject" wire:model="isProject" class="mt-1 block" />
                    @error('isProject') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="mt-6">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white dark:bg-gray-100 dark:text-gray-800 dark:hover:text-gray-700 py-2 px-4 rounded">Create Project Group</button>
                </div>
            </form>
        </div>
    </div>
</div>

</div>

<div>
<div class="h-full ml-14 mt-14 mb-10 md:ml-64">
    <section class="mt-10">
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
            <div class=" mt-20 bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden ">
                
                <!-- Success Message -->
                @if (session()->has('message'))
                    <div class="bg-green-500 text-white p-4 rounded shadow-md mb-6">
                        {{ session('message') }}
                    </div>
                @endif

                <div class="flex items-center justify-between p-4">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-200">Edit Project Group</h2>
                </div>

                <form wire:submit.prevent="update" class="p-4">
                    <div class="mb-4">
                        <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Code</label>
                        <input type="text" id="code" wire:model="code" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                        @error('code') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Name</label>
                        <input type="text" id="name" wire:model="name" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                        @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="isProject" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Is Project?</label>
                        <select id="isProject" wire:model="isProject" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                        @error('isProject') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-blue-500 dark:bg-blue-600 text-white dark:text-gray-100 rounded-lg hover:bg-blue-600 dark:hover:bg-blue-700">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

</div>

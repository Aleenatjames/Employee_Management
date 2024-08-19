<div>
    <div class="flex justify-center items-start min-h-screen bg-gray-100 dark:bg-gray-900 py-8">
        <div class="relative flex flex-col min-w-0 break-words bg-gray-50 dark:bg-gray-800 w-full max-w-2xl shadow-lg rounded">
            <div class="rounded-t mb-0 px-0 border-0">
                <div class="flex flex-wrap items-center px-4 py-2">
                    <div class="relative w-full max-w-full flex-grow flex-1">
                        <h3 class="font-semibold text-base text-gray-900 dark:text-gray-50">Create Holiday</h3>
                    </div>
                    <div class="relative w-full max-w-full flex-grow flex-1 text-right">
                        <a href="{{route('holiday.index')}}" class="bg-blue-500 dark:bg-gray-100 text-white dark:text-gray-800 text-xs font-bold uppercase px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150">
                            Back to List
                        </a>
                    </div>
                </div>

                @if (session()->has('message'))
                <div class="bg-green-500 text-white p-4 rounded shadow-md mb-6">
                    {{ session('message') }}
                </div>
                @endif

                <form wire:submit.prevent="createHoliday" class="px-4 py-6">
                    <div class="mb-4">
                        <label for="type" class="block text-sm font-medium text-gray-700">Date</label>
                        <input type="date" id="date" wire:model="date" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-100" />
                        @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="type" class="block text-sm font-medium text-gray-700">Reason</label>
                        <input type="text" id="reason" wire:model="reason" class="mt-1 block w-full p-2 border border-gray-300 rounded">
                        @error('reason') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                        <select id="type" wire:model="type" class="mt-1 block w-full p-2 border border-gray-300 rounded">
                            <option value="">Select Type</option>
                            <option value="public">Public</option>
                            <option value="company">Company</option>
                        </select>

                        @error('type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="is_restricted" class="block text-sm font-medium text-gray-700">Is Restricted?</label>
                        <select id="is_restricted" wire:model="is_restricted" class="mt-1 block w-full p-2 border border-gray-300 rounded">
                            <option value="">Select Type</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                        @error('is_restricted') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white dark:bg-gray-100 dark:text-gray-800 dark:hover:text-gray-700 py-2 px-4 rounded">
                            Create Holiday
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
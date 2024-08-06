<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Department / Edit') }}
            </h2>
            <a href="{{ route('department.index') }}" class="bg-slate-700 rounded py-2 my-2 px-3 text-white">Back</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('department.update', $department->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-2 gap-6">
                            <!-- Name Field -->
                            <div class="col-span-2">
                                <label for="name" class="text-lg font-medium">Department Name</label>
                                <div class="mt-2">
                                    <input value="{{ old('name', $department->name) }}" type="text" id="name" name="name" class="w-full border-gray-300 shadow-sm rounded-lg" placeholder="Enter Department Name">
                                    @error('name')
                                    <p class="invalid-feedback text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Head Field -->
                            <div class="col-span-2">
                                <label for="head" class="text-lg font-medium">Head</label>
                                <div class="mt-2">
                                    <select id="head" name="head" class="w-full border-gray-300 shadow-sm rounded-lg">
                                        <option value="">Select Head (Optional)</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}" {{ $employee->id == old('head', $department->head) ? 'selected' : '' }}>
                                                {{ $employee->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('head')
                                    <p class="invalid-feedback text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Parent Department Field -->
                            <div class="col-span-2">
                                <label for="parent_id" class="text-lg font-medium">Parent Department (Optional)</label>
                                <div class="mt-2">
                                    <select id="parent_id" name="parent_id" class="w-full border-gray-300 shadow-sm rounded-lg">
                                        <option value="">Select Parent Department (Optional)</option>
                                        @foreach($departments as $dept)
                                            <option value="{{ $dept->id }}" {{ $dept->id == old('parent_id', $department->parent_id) ? 'selected' : '' }}>
                                                {{ $dept->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('parent_id')
                                    <p class="invalid-feedback text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-span-2">
                                <button type="submit" class="bg-slate-700 rounded py-3 my-2 px-5 text-white w-full">Update Department</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Employee / Create') }}
            </h2>
            <a href="{{ route('employees.index') }}" class="bg-slate-700 rounded py-2 my-2 px-3 text-white">Back</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('employees.store') }}" method="post">
                        @csrf
                        <div class="grid grid-cols-2 gap-6">
                            <!-- Name Field -->
                            <div class="col-span-1">
                                <label for="name" class="text-lg font-medium">Name</label>
                                <div class="mt-2">
                                    <input value="{{ old('name') }}" type="text" id="name" name="name" class="w-full border-gray-300 shadow-sm rounded-lg" placeholder="Enter Name">
                                    @error('name')
                                    <p class="invalid-feedback text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Email Field -->
                            <div class="col-span-2">
                                <label for="email" class="text-lg font-medium">Email</label>
                                <div class="mt-2">
                                    <input value="{{ old('email') }}" type="email" id="email" name="email" class="w-full border-gray-300 shadow-sm rounded-lg" placeholder="Enter Email">
                                    @error('email')
                                    <p class="invalid-feedback text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Password Field -->
                            <div class="col-span-1">
                                <label for="password" class="text-lg font-medium">Password</label>
                                <div class="mt-2">
                                    <input value="{{ old('password') }}" type="password" id="password" name="password" class="w-full border-gray-300 shadow-sm rounded-lg" placeholder="Enter Password">
                                    @error('password')
                                    <p class="invalid-feedback text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Designation Field -->
                            <div class="col-span-1">
                                <label for="designation" class="text-lg font-medium">Designation</label>
                                <div class="mt-2">
                                    <input value="{{ old('designation') }}" type="text" id="designation" name="designation" class="w-full border-gray-300 shadow-sm rounded-lg" placeholder="Enter Designation">
                                    @error('designation')
                                    <p class="invalid-feedback text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-span-1">
                                <label for="name" class="text-lg font-medium">Reporting Manager</label>
                                <div class="mt-2">
                                <select id="rn" name="rm" class="w-full border-gray-300 shadow-sm rounded-lg">
                                        <option value="">Reporting manager</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}" {{ old('rm') == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('rm')
                                    <p class="invalid-feedback text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Departments Checkboxes -->
                            <div class="col-span-2">
                                <label class="text-lg font-medium">Departments</label>
                                <div class="mt-2 space-y-2">
                                    @foreach($departments as $department)
                                        <div>
                                            <input 
                                                type="checkbox" 
                                                id="department-{{ $department->id }}" 
                                                name="departments[]" 
                                                value="{{ $department->id }}" 
                                                {{ in_array($department->id, old('departments', [])) ? 'checked' : '' }}
                                            >
                                            <label for="department-{{ $department->id }}" class="ml-2">
                                                {{ $department->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                    @error('departments')
                                    <p class="invalid-feedback text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Role Dropdown -->
                            <div class="col-span-2">
                                <label for="role" class="text-lg font-medium">Role</label>
                                <div class="mt-2">
                                    <select id="role" name="role" class="w-full border-gray-300 shadow-sm rounded-lg">
                                        <option value="">Select Role</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                                {{ ucfirst($role->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                    <p class="invalid-feedback text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-span-2">
                                <button class="bg-slate-700 rounded py-3 my-2 px-5 text-white w-full">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

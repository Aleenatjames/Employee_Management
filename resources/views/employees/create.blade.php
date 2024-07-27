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
                    <form action="{{ route('employees.store') }}" method="post" enctype="multipart/form-data">
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
                            <div class="col-span-">
                                <label for="password" class="text-lg font-medium">Password</label>
                                <div class="mt-2">
                                    <input value="{{ old('password') }}" type="text" id="password" name="password" class="w-full border-gray-300 shadow-sm rounded-lg" placeholder="Enter Password">
                                    @error('password')
                                    <p class="invalid-feedback text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Company Dropdown -->
                            <div class="col-span-1">
                                <label for="company_id" class="text-lg font-medium">Company</label>
                                <div class="mt-2">
                                    <select id="company_id" name="company_id" class="w-full border-gray-300 shadow-sm rounded-lg">
                                        <option value="">Select Company</option>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('company_id')
                                    <p class="invalid-feedback text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-span-3">
                                <button class="bg-slate-700 rounded py-3 my-2 px-5 text-white w-full">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

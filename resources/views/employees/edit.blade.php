<x-app-layout>
    <x-slot name="header">
    <div class="flex justify-between" >
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Employee / Edit') }}
        </h2>
        <a href="{{route('employees.index')}}" class="bg-slate-700 rounded py-2 my-2 px-3 text-white">Back</a>
        </div>
    </x-slot>

    <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <form action="{{ route('employees.update',$employees->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-3 gap-6">
                        <!-- Name Field -->
                        <div class="col-span-1">
                            <label for="name" class="text-lg font-medium">Name</label>
                            <div class="mt-2">
                                <input value="{{ old('name',$employees -> name) }}" type="text" id="name" name="name" class="w-full border-gray-300 shadow-sm rounded-lg" placeholder="Enter Name">
                                @error('name')
                                <p class="invalid-feedback text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Email Field -->
                        <div class="col-span-1">
                            <label for="email" class="text-lg font-medium">Email</label>
                            <div class="mt-2">
                                <input value="{{ old('email',$employees -> email) }}" type="email" id="email" name="email" class="w-full border-gray-300 shadow-sm rounded-lg" placeholder="Enter Email">
                                @error('email')
                                <p class="invalid-feedback text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div class="col-span-1">
                            <label for="password" class="text-lg font-medium">Password</label>
                            <div class="mt-2">
                                <input value="{{ old('password') }}" type="text" id="password" name="password" class="w-full border-gray-300 shadow-sm rounded-lg" placeholder="Enter Password">
                                @error('password')
                                <p class="invalid-feedback text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    
                        <!-- Phone Number Field -->
                        <div class="col-span-1">
                            <label for="phone" class="text-lg font-medium">Phone Number</label>
                            <div class="mt-2">
                                <input value="{{ old('phone',$employee_details -> mobile_no) }}" type="text" id="phone" name="phone" class="w-full border-gray-300 shadow-sm rounded-lg" placeholder="Enter Phone Number">
                                @error('phone')
                                <p class="invalid-feedback text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Address Field -->
                        <div class="col-span-1">
                            <label for="address" class="text-lg font-medium">Address</label>
                            <div class="mt-2">
                                <input value="{{ old('address , $employees -> address') }}" type="text" id="address" name="address" class="w-full border-gray-300 shadow-sm rounded-lg" placeholder="Enter Address">
                                @error('address')
                                <p class="invalid-feedback text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Date of Birth and Blood Group Fields -->
                        <div class="col-span-1 grid grid-cols-2 gap-4">
                            <div>
                                <label for="dob" class="text-lg font-medium">DOB</label>
                                <div class="mt-2">
                                    <input value="{{ old('dob,$employees -> dob') }}" type="date" id="dob" name="dob" class="w-full border-gray-300 shadow-sm rounded-lg">
                                    @error('dob')
                                    <p class="invalid-feedback text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <label for="blood_group" class="text-lg font-medium">Blood Group</label>
                                <div class="mt-2">
                                    <input value="{{ old('blood_group,$employees -> blood_group') }}" type="text" id="blood_group" name="blood_group" class="w-full border-gray-300 shadow-sm rounded-lg" placeholder="Blood Group">
                                    @error('blood_group')
                                    <p class="invalid-feedback text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Profile Picture Field -->
                        <div class="col-span-1">
                            <label for="profile_picture" class="text-lg font-medium">Profile Picture</label>
                            <div class="mt-2">
                                <input type="file" id="profile_picture" name="profile_picture" class="w-full border-gray-300 shadow-sm rounded-lg">
                                @error('profile_picture')
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
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Employee / Edit') }}
            </h2>
            <a href="{{ route('employees.index') }}" class="bg-slate-700 rounded py-2 my-2 px-3 text-white">Back</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('employees.update', $employee->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-2 gap-6">
                            <!-- Name Field -->
                            <div class="col-span-1">
                                <label for="name" class="text-lg font-medium">Name</label>
                                <div class="mt-2">
                                    <input value="{{ old('name', $employee->name) }}" type="text" id="name" name="name" class="w-full border-gray-300 shadow-sm rounded-lg" placeholder="Enter Name">
                                    @error('name')
                                    <p class="invalid-feedback text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Email Field -->
                            <div class="col-span-2">
                                <label for="email" class="text-lg font-medium">Email</label>
                                <div class="mt-2">
                                    <input value="{{ old('email', $employee->email) }}" type="email" id="email" name="email" class="w-full border-gray-300 shadow-sm rounded-lg" placeholder="Enter Email">
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
                                    <input value="{{ old('designation', $employee->designation) }}" type="text" id="designation" name="designation" class="w-full border-gray-300 shadow-sm rounded-lg" placeholder="Enter Designation">
                                    @error('designation')
                                    <p class="invalid-feedback text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-span-1">
    <label for="rm" class="text-lg font-medium">Reporting Manager</label>
    <div class="mt-2">
        <select id="rm" name="rm" class="w-full border-gray-300 shadow-sm rounded-lg">
            <option value="">Select Reporting Manager</option>
            @foreach($employees as $employeeOption) <!-- Changed variable name to avoid conflict -->
                <option value="{{ $employeeOption->id }}" 
                    {{ old('rm', $employee->reporting_manager) == $employeeOption->id ? 'selected' : '' }}>
                    {{ $employeeOption->name }}
                </option>
            @endforeach
        </select>
        @error('rm')
        <p class="invalid-feedback text-red-400">{{ $message }}</p>
        @enderror
    </div>
</div>
                         
                           
<!-- Parent, Child Division, and Add Button in the same line -->
<div class="col-span-2 grid grid-cols-3 gap-6">
    <!-- Parent Division Dropdown -->
    <div class="col-span-1">
        <label for="parent_division" class="text-lg font-medium">Parent Division</label>
        <div class="mt-2">
            <select id="parent_division" class="w-full border-gray-300 shadow-sm rounded-lg">
                <option value="">Select Parent Division</option>
                @foreach($parentDivisions as $parent)
                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Child Division Dropdown -->
    <div class="col-span-1">
        <label for="child_division" class="text-lg font-medium">Child Division</label>
        <div class="mt-2">
            <select id="child_division" class="w-full border-gray-300 shadow-sm rounded-lg">
                <option value="">Select Child Division</option>
            </select>
            <input type="hidden" name="divisions" id="divisions-input">
        </div>
    </div>

    <!-- Add Button -->
    <div class="col-span-1 flex items-end">
        <button id="add-division" class="px-5 py-2 bg-blue-600 text-white rounded-md">Add</button>
    </div>
</div>


<!-- Selected Divisions Display Area -->
<div class="mt-4">
    <h3 class="text-lg font-medium">Selected Divisions:</h3>
    <ul id="selected-divisions" class="list-none mt-2">
    
    @foreach($employeeDivisions as $division)
    <li class="flex justify-between items-center py-2 px-4 bg-gray-100 rounded-lg mt-2" data-id="{{ $division->id }}">
        <span>{{ $division->parent_name }} > {{ $division->child_name }}</span>
        <button class="remove-division bg-red-500 text-white rounded-lg px-2" data-id="{{ $division->id }}">X</button>
    </li>
@endforeach

    </ul>
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
                                                {{ in_array($department->id, old('departments', $employee->departments->pluck('id')->toArray())) ? 'checked' : '' }}
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
                <option value="{{ $role->name }}" {{ old('role', $employee->roles->first()->name ?? '') == $role->name ? 'selected' : '' }}>
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
                                <button class="bg-slate-700 rounded py-3 my-2 px-5 text-white w-full">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    // Load child divisions when parent is selected
    $('#parent_division').on('change', function() {
        let parentId = $(this).val();
        if (parentId) {
            $.ajax({
                url: "/get-child-divisions/" + parentId,  // Adjust your route here
                type: "GET",
                success: function(data) {
                    $('#child_division').empty();  // Clear previous child divisions
                    $('#child_division').append('<option value="">Select Child Division</option>');
                    
                    $.each(data, function(key, value) {
                        $('#child_division').append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                }
            });
        } else {
            $('#child_division').empty();  // Clear if no parent selected
            $('#child_division').append('<option value="">Select Child Division</option>');
        }
    });

    // Handle add division button click
    $('#add-division').on('click', function(e) {
        e.preventDefault();
        
        // Get selected parent and child division values
        let parentDivision = $('#parent_division option:selected').text();
        let parentDivisionId = $('#parent_division').val();
        let childDivision = $('#child_division option:selected').text();
        let childDivisionId = $('#child_division').val();
        
        // Ensure both parent and child are selected
        if (parentDivisionId && childDivisionId) {
            // Append selected division to the list
            $('#selected-divisions').append(`
                <li class="flex justify-between items-center py-2 px-4 bg-gray-100 rounded-lg mt-2" data-parent-id="${parentDivisionId}" data-child-id="${childDivisionId}">
                    <span>${parentDivision} > ${childDivision}</span>
                    <button class="remove-division bg-red-500 text-white rounded-lg px-2">X</button>
                </li>
            `);
            updateHiddenInput(); 
            // Clear the dropdowns
            $('#parent_division').val('');
            $('#child_division').empty().append('<option value="">Select Child Division</option>');
        } else {
            alert("Please select both Parent and Child divisions.");
        }
    });
    function updateHiddenInput() {
        let selectedDivisions = [];
        $('#selected-divisions li').each(function() {
            let parentId = $(this).data('parent-id');
            let childId = $(this).data('child-id');
            selectedDivisions.push(`${parentId},${childId}`); // Assuming you want to store as "parentId,childId"
        });
        $('#divisions-input').val(selectedDivisions.join('|')); // Use '|' as a separator
    }

    // Handle remove division click
    $(document).on('click', '.remove-division', function() {
        $(this).closest('li').remove();  // Remove the selected division from the list
        updateHiddenInput(); // Update the hidden input after removing
    });
    // Handle remove division click
    $(document).on('click', '.remove-division', function() {
        $(this).closest('li').remove();  // Remove the selected division from the list
    });
});

</script>
</x-app-layout>

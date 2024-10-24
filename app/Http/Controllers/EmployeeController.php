<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Department;
use App\Models\DivisionChild;
use App\Models\DivisionLeaveType;
use App\Models\DivisionParent;
use App\Models\Employee;
use App\Models\EmployeeDetails;
use App\Models\EmployeeDivision;
use App\Models\LeaveAllocation;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('departments', 'roles')->paginate(10);

        return view('employees.list', [
            'employees' => $employees,

        ]);
    }

    public function create()
    {
        $departments = Department::all();
        $roles = Role::all();
        $employees = Employee::whereNull('reporting_manager')->get();
        $parentDivisions = DivisionParent::all();
        $leaveTypes = LeaveType::all(); 

        // Optionally, you can fetch all child divisions to pass initially, though it may be better to fetch them dynamically
        $childDivisions = DivisionChild::all();

        return view('employees.create', compact('departments', 'roles', 'employees', 'parentDivisions', 'childDivisions','leaveTypes'));
    }
    public function getChildDivisions($parent_id)
    {      
        $childDivisions = DivisionChild::where('parent_id', $parent_id)->get(); // Assuming `parent_id` is the foreign key for the parent division
        return response()->json($childDivisions);
    }

    public function store(Request $request)
    {
        // Validate the request data
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'password' => 'required|string|min:8',
            'designation' => 'nullable|string|max:255',
            'rm' => 'nullable|string|max:255',
            'departments' => 'nullable|array',
            'role' => 'required',
            
        ]);

        Log::info('Creating employee with data:', $data);

        try {
            // Create the employee record
            $employee = new Employee();
            $employee->name = $data['name'];
            $employee->email = $data['email'];
            $employee->password = bcrypt($data['password']); // Hash the password
            $employee->designation = $data['designation'];
            $employee->reporting_manager = $data['rm'];
            $employee->status = 'active';
            $employee->save(); // Save employee to get the ID

             // Process divisions
             $divisionsInput = $request->input('divisions'); // This will be like "parentId1,childId1|parentId2,childId2"
             if($divisionsInput){
             Log::info($divisionsInput);
             $divisionsInput = $request->input('divisions'); // This will be like "parentId1,childId1|parentId2,childId2"
            
                 $divisions = explode('|', $divisionsInput);
                 foreach ($divisions as $division) {
                     list($parentId, $childId) = explode(',', $division);
                     // Save to the EmployeeDivision table
                     $employeeDivision = new EmployeeDivision();
                     $employeeDivision->employee_id = $employee->id;
                     $employeeDivision->parent_id = $parentId;
                     $employeeDivision->child_id = $childId;
                     $employeeDivision->save(); // Save each division entry
                 }
             }
        

            Log::info('Employee created successfully:', ['id' => $employee->id]);

            // Associate the employee with departments
            if (!empty($request->departments)) {
                $employee->departments()->sync($request->input('departments', []));
                Log::info('Departments synced for employee:', ['departments' => $request->departments]);
            }

            // Assign the "staff" role to the employee
            $employee->assignRole($data['role']); // Assign role based on the input
            Log::info('Role assigned to employee:', ['role' => $data['role']]);

           
         // Now handle leave allocations based on division
$leaveTypes = LeaveType::all(); // Get all leave types

foreach ($leaveTypes as $leaveType) {
    // If applicable_division is null, allocate from the Division Leave Type table
    if (is_null($leaveType->applicable_division)) {
        // Fetch the incremental count for all employees
        $divisionLeaveType = DivisionLeaveType::where('leave_type', $leaveType->id)->first();
        $allocatedDays = $divisionLeaveType ? $divisionLeaveType->incremental_count : 0;

        LeaveAllocation::create([
            'employee_id' => $employee->id, // Ensure this field is populated
            'leave_type' => $leaveType->id, // Ensure leave type ID is provided
            'allocated_days' => $allocatedDays, // Ensure allocated days is provided
            'used' => 0, // Set default used to 0
        ]);
    } else {
        // For specific divisions, check if the employee belongs to that division
        $divisionLeaveType = DivisionLeaveType::where('leave_type', $leaveType->id)
            ->where('child_id', $leaveType->applicable_division) // Match division
            ->first();

        // Check if the employee belongs to the applicable division
        $employeeDivisions = EmployeeDivision::where('employee_id', $employee->id)
            ->pluck('child_id')->toArray();

        if ($divisionLeaveType && in_array($leaveType->applicable_division, $employeeDivisions)) {
            // Allocate leave based on the division's incremental count
            LeaveAllocation::create([
                'employee_id' => $employee->id, // Ensure this field is populated
                'leave_type' => $leaveType->id, // Ensure leave type ID is provided
                'allocated_days' => $divisionLeaveType->incremental_count, // Ensure allocated days is provided
                'used' => 0, // Set default used to 0
            ]);
        }
    }
}

         // Redirect with a success message
         return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating employee:', ['exception' => $e->getMessage()]);
            return redirect()->back()->withErrors('An error occurred while creating the employee. Please try again.');
        }
    }


    public function edit($id)
    {
        // Fetch the employee by ID or fail if not found
        $employee = Employee::findOrFail($id);
        $employees = Employee::whereNull('reporting_manager')->get();

        // Fetch all departments for the dropdown list
        $departments = Department::all();
        $parentDivisions = DivisionParent::all();

       
        $employeeDivisions = DB::table('employee_divisions')
    ->join('division_parents as parent', 'employee_divisions.parent_id', '=', 'parent.id')
    ->join('division_children as child', 'employee_divisions.child_id', '=', 'child.id')
    ->where('employee_divisions.employee_id', $id)  // Use $id for the employee ID
    ->select('parent.name as parent_name', 'child.name as child_name', 'parent.id as parent_id', 'child.id as child_id', 'employee_divisions.id')  // Ensure the employee_divisions.id is selected for deletion
    ->get();

    

        $roles = Role::all();
        // Pass the employee and departments to the view
        return view('employees.edit', [
            'employee' => $employee,
            'departments' => $departments,
            'roles' => $roles,
            'employees' => $employees,
            'parentDivisions'=>$parentDivisions,
            'employeeDivisions'=>$employeeDivisions
        ]);
    }

    public function update(Request $request, $id)
    {
      
        // Find the employee by ID or fail if not found
        $employee = Employee::findOrFail($id);
    
        // Validate the request data
        $validateData = $request->validate([
            'name' => 'required|unique:employees,name,' . $id,
            'email' => 'required|email|unique:employees,email,' . $id,
            'password' => 'nullable|min:8',
            'designation' => 'required',
            'rm' => 'required|nullable',
            'departments' => 'array|exists:departments,id',
            'divisions' => 'nullable|string',  // Added division validation
            'role' => 'required|string'
        ]);
    
        // Update employee details
        $employee->name = $request->input('name');
        $employee->email = $request->input('email');
    
        // Update password if provided
        if ($request->filled('password')) {
            $employee->password = bcrypt($request->input('password'));
        }
    
        $employee->designation = $request->input('designation');
        $employee->reporting_manager = $request->input('rm');
        $employee->save(); // Save the employee data
    
          // Process divisions
          $divisionsInput = $request->input('divisions'); // This will be like "parentId1,childId1|parentId2,childId2"
          if($divisionsInput){
          Log::info($divisionsInput);
          $divisionsInput = $request->input('divisions'); // This will be like "parentId1,childId1|parentId2,childId2"
         
              $divisions = explode('|', $divisionsInput);
              foreach ($divisions as $division) {
                  list($parentId, $childId) = explode(',', $division);
                  // Save to the EmployeeDivision table
                  $employeeDivision = new EmployeeDivision();
                  $employeeDivision->employee_id = $employee->id;
                  $employeeDivision->parent_id = $parentId;
                  $employeeDivision->child_id = $childId;
                  $employeeDivision->save(); // Save each division entry
              }
          }
    

    
        // Sync departments (assuming you have a many-to-many relationship)
        $employee->departments()->sync($request->input('departments', []));
    
        // Ensure the role is associated with the correct guard
        $role = Role::where('name', $request->input('role'))
            ->where('guard_name', 'employee')
            ->firstOrFail();
    
        $employee->syncRoles([$role]);
     // Handle leave allocations based on division
$leaveTypes = LeaveType::all(); // Get all leave types

foreach ($leaveTypes as $leaveType) {
    // Get all division leave types for this leave type
    $divisionLeaveTypes = DivisionLeaveType::where('leave_type', $leaveType->id)->get();

    foreach ($divisionLeaveTypes as $divisionLeaveType) {
        // Check if the leave allocation already exists for this employee and leave type
        $existingAllocation = LeaveAllocation::where('employee_id', $employee->id)
            ->where('leave_type', $leaveType->id)
            ->exists(); // Check only for employee and leave type

        // If no allocation exists, proceed to allocate
        if (!$existingAllocation) {
            // If applicable_division is null (leave type applies to all employees)
            if (is_null($leaveType->applicable_division)) {
                // Allocate leave for the employee
                LeaveAllocation::create([
                    'employee_id' => $employee->id,
                    'leave_type' => $leaveType->id,
                    'allocated_days' => $divisionLeaveType->incremental_count, // Get incremental count from division leave type
                    'used' => 0, // Default value for used days
                ]);
            } else {
                // For specific divisions, check if the employee belongs to that division
                $employeeDivisions = EmployeeDivision::where('employee_id', $employee->id)
                    ->pluck('child_id')->toArray(); // Get all division child IDs of the employee
            
                // Check if the employee belongs to the correct division and if a division leave type exists
                if (in_array($divisionLeaveType->child_id, $employeeDivisions)) { 
                    // Check if an allocation for this leave type already exists for the employee
                    $existingAllocation = LeaveAllocation::where('employee_id', $employee->id)
                        ->where('leave_type', $leaveType->id)
                        ->first();
            
                    if ($existingAllocation) {
                        // If an allocation exists, update the allocated days by adding the incremental count
                        $existingAllocation->allocated_days += $divisionLeaveType->incremental_count;
                        $existingAllocation->save(); // Save the updated allocation
                    } else {
                        // If no allocation exists, create a new allocation
                        LeaveAllocation::create([
                            'employee_id' => $employee->id,
                            'leave_type' => $leaveType->id,
                            'allocated_days' => $divisionLeaveType->incremental_count, // Get incremental count from division leave type
                            'used' => 0, // Default value for used days
                        ]);
                    }
                }
            }
            
        }
    }
}


    
        return redirect()->route('employees.index')->with('success', 'Employee updated successfully');
    }
    
    
    public function destroydivision($id)
{
    // Find the employee division by its ID and delete it
    $employeeDivision = DB::table('employee_divisions')->where('id', $id)->delete();

    if ($employeeDivision) {
        return response()->json(['success' => 'Division deleted successfully.']);
    } else {
        return response()->json(['error' => 'Failed to delete division.'], 500);
    }
}

    public function toggleStatus($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->status = $employee->status === 'active' ? 'inactive' : 'active';
        $employee->save();

        return redirect()->route('employees.index')->with('message', 'Employee status updated successfully.');
    }


    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }

    public function login()
    {
        return view('employee.login');
    }

    public function dashboard()
    {
        return view('employee.dashboard');
    }
    public function logout(Request $request)
    {
        Auth::guard('employee')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('employee.login');
    }
}

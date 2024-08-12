<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{
    public function index(){
        $employees = Employee::with('departments', 'roles')->paginate(10);
        return view('employees.list',[
            'employees'=>$employees ,
           
        ]);
    }

    public function create()
    {
        $departments = Department::all();
        $roles = Role::all();
        return view('employees.create', compact('departments','roles'));
    }

    public function store(Request $request)
    {
        // Validate the request data
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'password' => 'required|string|min:8',
            'designation' => 'nullable|string|max:255',
            'departments' => 'nullable|array',
            'role' => 'required'
        ]);
    
        Log::info('Creating employee with data:', $data);
    
        try {
            // Create the employee record
            $employee = new Employee();
            $employee->name = $data['name'];
            $employee->email = $data['email'];
            $employee->password = bcrypt($data['password']); // Hash the password
            $employee->designation = $data['designation'];
            $employee->status = 'active';
            $employee->save(); // Save employee to get the ID
    
            Log::info('Employee created successfully:', ['id' => $employee->id]);
    
            // Associate the employee with departments
            if (!empty($request->departments)) {
                $employee->departments()->sync($request->input('departments', []));
                Log::info('Departments synced for employee:', ['departments' => $request->departments]);
            }
    
            // Assign the "staff" role to the employee
            $employee->assignRole($data['role']); // Assign role based on the input
        Log::info('Role assigned to employee:', ['role' => $data['role']]);
    
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

    // Fetch all departments for the dropdown list
    $departments = Department::all();

    $roles = Role::all();
    // Pass the employee and departments to the view
    return view('employees.edit', [
        'employee' => $employee,
        'departments' => $departments,
        'roles'=>$roles
    ]);
}

    public function update(Request $request, $id)
{
    $employee = Employee::findOrFail($id);

    $validateData = $request->validate([
        'name' => 'required|unique:employees,name,' . $id,
        'email' => 'required|email|unique:employees,email,' . $id,
        'password' => 'nullable|min:8',
        'designation' => 'required',
        'departments' => 'array|exists:departments,id',
        'role' => 'required|string'
    ]);

    $employee->name = $request->input('name');
    $employee->email = $request->input('email');
    
    if ($request->filled('password')) {
        $employee->password = bcrypt($request->input('password'));
    }

    $employee->designation = $request->input('designation');
    $employee->save();

    // Sync departments (assuming you have a many-to-many relationship)
    $employee->departments()->sync($request->input('departments', []));

    // Ensure the role is associated with the correct guard
    $role = Role::where('name', $request->input('role'))
    ->where('guard_name', 'employee')
    ->firstOrFail();

$employee->syncRoles([$role]);

    return redirect()->route('employees.index')->with('success', 'Employee updated successfully');
}
    public function toggleStatus($id)
{
    $employee = Employee::findOrFail($id);
    $employee->status = $employee->status === 'active' ? 'inactive' : 'active';
    $employee->save();

    return redirect()->route('employees.index')->with('message', 'Employee status updated successfully.');
}

      
        public function destroy($id){
            $employee = Employee::findOrFail($id);
            $employee->delete();
            return redirect()->route('employees.index')->with('success','Employee deleted successfully.');
           
        }

        public function login(){
            return view('employee.login');
        }

        public function dashboard(){
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

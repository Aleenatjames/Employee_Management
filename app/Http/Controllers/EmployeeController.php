<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{
    public function index(){
        $employees = Employee::with('company')->paginate(25);
        return view('employees.list',[
            'employees'=>$employees ,
           
        ]);
    }

    public function create()
    {
        $companies = Company::all();
        return view('employees.create', compact('companies'));
    }

     public function store(Request $request)
        {
            Log::debug('Received request to store option:', ['request' => $request->all()]);
            // Validate the request data
            $validateData = $request->validate([
                'name' => 'required|string|max:255|unique:employees',
                'email' => 'required|email|max:255|unique:employees',
                'password' => 'required|string|min:8',
               
            ]);
        
            DB::beginTransaction();
        
            try {

                // Create the employee record
                $employee = new Employee();
                $employee->name = $request->name;
                $employee->email = $request->email;
                $employee->password = bcrypt($request->password); // Hash the password
                $employee->company_id = $request->company_id;
                $employee->save(); // Save employee to get the ID
        
                Log::info('Employee created successfully', ['employee' => $employee]);
        
                // Create the department with the newly created employee as the head
                $department = new Department();
                $department->name = 'Directors'; 
                $department->company_id = $request->company_id;
                $department->head = $employee->id; 
                $department->save(); 
        
                Log::info('Department created successfully', ['department' => $department]);
        
                // Update employee with department ID
                $employee->department_id = $department->id;
                $employee->save();

                $roleName = 'Director'; // Change this to the default role you want to assign

               // Retrieve the existing role by name
                  $role = Role::where('name', $roleName)->first();

               if (!$role) {
                // If role does not exist, create a new role
                 $role = Role::create(['name' => $roleName]);
                }

        // Assign the retrieved or newly created role to the employee
        $employee->assignRole($role->name);

        Log::info('Role assigned successfully', ['employee_id' => $employee->id, 'role_id' => $role->id]);

                // Commit the transaction
                DB::commit();
        
                // Redirect with success message
                return redirect()->route('employees.index')->with('success', 'Employee added successfully');
        
            } catch (\Exception $e) {
                // Rollback the transaction if something goes wrong
                DB::rollBack();
                
                Log::error('Failed to add employee', [
                    'error_message' => $e->getMessage(),
                    'error_trace' => $e->getTraceAsString(),
                    'request_data' => $request->all(), // Log the request data
                ]);
        
                return redirect()->route('employees.create')->withInput()->withErrors(['error' => 'Failed to add employee. Please try again.']);
            }
        }
        

    public function edit($id){
        $employees=Employee::findOrFail($id);
      
        return view('employees.edit',[
            'employees' => $employees,
           
        ]);
        
    }

    public function update($id,Request $request){
        $employees=Employee::findOrFail($id);
        $validateData = $request->validate([
            'name' => 'required|unique:employees,name,'.$id.',id'
        ]);

        if ($validateData) {
            $employees->name=$request->name;
            $employees->save();
            return redirect()->route('employees.index')->with('success', 'Employee updated successfully');
        } else {
            return redirect()->route('employees.edit',$id)->withInput()->withError($validateData);
        }
    }
    

   
        public function destroy($id){
            $employee = Employee::findOrFail($id);
            $employee->delete();
            return redirect()->route('employees.index')->with('success','Employee deleted successfully.');
           
        }
}

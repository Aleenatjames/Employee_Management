<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DepartmentController extends Controller
{
    public function index(){
        $departments = Department::with(['parentDepartment', 'departmentHead'])->paginate(10);
        return view('department.list', compact('departments'));
    }

    public function create(){
        $employees = Employee::all(); // Fetch all employees to select as the head
        $departments = Department::all(); // Fetch all departments to select as the parent department
        return view('department.create', compact('employees', 'departments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'head' => 'nullable|exists:employees,id',
            'parent_id' => 'nullable|exists:departments,id',
        ]);

        DB::beginTransaction();

        try {
            // Create the department record
            $department = new Department();
            $department->name = $data['name'];
            $department->head = $data['head'] ?? null; // Default to null if not provided
            $department->parent_id = $data['parent_id'] ?? null; // Default to null if not provided
            $department->save();

            // If the head is set, ensure the employee is not already a head of another department
            if ($department->head) {
                $employee = Employee::find($department->head);
               
            }

            DB::commit();

            Log::info('Department created successfully', ['department' => $department]);

            return redirect()->route('department.index')->with('success', 'Department created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create department', ['error' => $e->getMessage()]);

            return redirect()->back()->withErrors(['error' => 'Failed to create department. Please try again.']);
        }
    }

    public function edit($id){
        $department = Department::findOrFail($id);
    $employees = Employee::all(); // or use appropriate scope/filter
    $departments = Department::where('id', '!=', $id)->get(); // Exclude the current department

    return view('department.edit', compact('department', 'employees', 'departments'));
}

public function update(Request $request, $id)
{
    // Find the department by ID or fail if not found
    $department = Department::findOrFail($id);

    // Validate the incoming request data
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'head' => 'nullable|exists:employees,id', // Ensure the head exists in employees table
        'parent_id' => 'nullable|exists:departments,id', // Ensure the parent department exists
    ]);

    // Update department details
    $department->name = $validatedData['name'];
    $department->head = $validatedData['head']; // Assuming this is the employee ID
    $department->parent_id = $validatedData['parent_id'];
    $department->save();

    // Redirect with a success message
    return redirect()->route('department.index')->with('success', 'Department updated successfully');
}
public function toggleStatus($id)
{
    $department = Department::findOrFail($id);
    $department->status = $department->status === 'active' ? 'inactive' : 'active';
    $department->save();

    return redirect()->route('department.index')->with('message', 'Department status updated successfully.');
}

   
        public function destroy($id){
            $department = department::findOrFail($id);
            $department->delete();
            return redirect()->route('department.index')->with('success','department deleted successfully.');
           
        }
        
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PermissionController extends Controller 
{
   
    public function index(){
        $permissions=Permission::orderBy('created_at','DESC')->paginate(25);
        return view('permissions.list',[
            'permissions'=>$permissions ,
        ]);
    }

    public function create(){
        return view('permissions.create');
    }

    public function store(Request $request){
        $validateData = $request->validate([
            'name' => 'required|unique:permissions',
            'guard_name' => 'employee'
        ]);

        if ($validateData) {
            Permission::create(['name'=>$request->name,
            'guard_name'=>'employee'
        ]);
            return redirect()->route('permissions.index')->with('success', 'Permission added successfully');
        } else {
            return redirect()->route('permissions.create')->withInput();
        }
    }

    public function edit($id){
        $permissions=Permission::findOrFail($id);
        return view('permissions.edit',[
            'permissions' => $permissions 
        ]);
        
    }

    public function update($id,Request $request){
        $permissions=Permission::findOrFail($id);
        $validateData = $request->validate([
            'name' => 'required|unique:permissions,name,'.$id.',id'
        ]);

        if ($validateData) {
            $permissions->name=$request->name;
            $permissions->save();
            return redirect()->route('permissions.index')->with('success', 'Permission updated successfully');
        } else {
            return redirect()->route('permissions.edit',$id)->withInput()->withError($validateData);
        }
    }
    

   
        public function destroy($id){
            $permission = Permission::findOrFail($id);
            $permission->delete();
            return redirect()->route('permissions.index')->with('success','Permission deleted successfully.');
           
        }
        
    
}

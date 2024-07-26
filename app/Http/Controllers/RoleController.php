<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware():array
    {
        return[
            new Middleware('permission:view roles',only: ['index']),
            new Middleware('permission:edit roles',only: ['edit']),
            new Middleware('permission:create roles',only: ['create']),
            new Middleware('permission:delete roles',only: ['destroy']),
        ];
    }
    public function index(){
        $roles=Role::orderBy('created_at','DESC')->paginate(10);
        return view('roles.list',[
            'roles'=>$roles ,
        ]);
    }

    public function create(){
        $permissions=Permission::orderBy('name','ASC')->get();
        return view('roles.create',[
            'permissions'=>$permissions,
        ]);
    }

    public function store(Request $request){
        $validateData = $request->validate([
            'name' => 'required|unique:roles',
        ]);

        if ($validateData) {
            $role=Role::create(['name'=>$request->name]);
           if(!empty($request->permission)){
            foreach($request->permission as $name){
                $role->givePermissionTo($name);
            }
           }
           return redirect()->route('roles.index')->with('success', 'Role added successfully');
        } else {
            return redirect()->route('roles.create')->withInput();
        }
        }

    public function edit($id){
        $role=Role::findOrFail($id);
        $hasPermissions=$role->permissions->pluck('name');
        $permissions=Permission::orderBy('name','ASC')->get();
        
        return view('roles.edit',[
            'permissions' => $permissions,
            'hasPermissions'=>$hasPermissions ,
            'role'=>$role,
        ]);
        
        
    }

    public function update($id,Request $request){
        $role=Role::findOrFail($id);
        $validateData = $request->validate([
            'name' => 'required|unique:roles,name,'.$id.',id'
        ]);

        if ($validateData) {
            $role->name=$request->name;
            $role->save();
            if(!empty($request->permission)){
                $role->syncPermissions($request->permission);
            }else{
                $role->syncPermissions([]);
            }
            return redirect()->route('roles.index')->with('success', 'Permission updated successfully');
        } else {
            return redirect()->route('roles.edit',$id)->withInput()->withError($validateData);
        }
    }
    

   
        public function destroy($id){
            $role = Role::findOrFail($id);
            $role->delete();
            return redirect()->route('roles.index')->with('success','Role deleted successfully.');
           
        }
        
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PermissionController extends Controller implements HasMiddleware
{
    public static function middleware():array
    {
        return[
            new Middleware('permission:view permissions',only: ['index']),
            new Middleware('permission:edit permissions',only: ['edit']),
            new Middleware('permission:create permissions',only: ['create']),
            new Middleware('permission:delete permissions',only: ['destroy']),
        ];
    }
   
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
        ]);

        if ($validateData) {
            Permission::create(['name'=>$request->name]);
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

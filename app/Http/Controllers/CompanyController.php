<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(){
        $companies=Company::orderBy('created_at','DESC')->paginate(25);
        return view('company.list',[
            'companies'=>$companies ,
        ]);
    }

    public function create(){
        return view('company.create');
    }

    public function store(Request $request){
        $validateData = $request->validate([
            'name' => 'required',
            'location' => 'required',
        ]);

        if ($validateData) {
            Company::create([
                'name'=>$request->name,
                'location'=>$request->location,
        ]);
            return redirect()->route('company.index')->with('success', 'Company added successfully');
        } else {
            return redirect()->route('company.create')->withInput();
        }
    }

    public function edit($id){
        $company=Company::findOrFail($id);
        return view('company.edit',[
            'company' => $company 
        ]);
        
    }

    public function update($id,Request $request){
        $company=Company::findOrFail($id);
        $validateData = $request->validate([
            'name' => 'required|unique:company,name,'.$id.',id',
            'location' => 'required',
        ]);

        if ($validateData) {
            $company->name=$request->name;
            $company->location=$request->location;
            $company->save();
            return redirect()->route('company.index')->with('success', 'Company updated successfully');
        } else {
            return redirect()->route('company.edit',$id)->withInput()->withError($validateData);
        }
    }
    

   
        public function destroy($id){
            $company = Company::findOrFail($id);
            $company->delete();
            return redirect()->route('company.index')->with('success','Company deleted successfully.');
           
        }
        
}

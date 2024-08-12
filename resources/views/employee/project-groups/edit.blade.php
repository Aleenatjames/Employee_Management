@extends('layouts.employee_dashboard')

@section('title', 'Project-Group-Edit')

@section('sidebar')
    @parent
 
   
@endsection

@section('content')
       
<div class="h-full ml-14 mt-14 mb-10 md:ml-64">   
    @livewire('project-groups.edit',['groupId' => $groupId])
</div> 

@endsection
@extends('layouts.employee_dashboard')

@section('title', 'Dashboard')

@section('sidebar')
    @parent
 
   
@endsection

@section('content')
       
<div class="h-full ml-14 mt-14 mb-10 md:ml-64">
    
 @livewire('project-allocation.edit',['allocationId' => $allocationId])
</div> 


@endsection
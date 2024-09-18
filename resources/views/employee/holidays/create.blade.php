@extends('layouts.employee_dashboard')

@section('title', 'Holiday Create')

@section('sidebar')
    @parent
 
   
@endsection

@section('content')
       
<div class="h-full ml-14 mt-14 mb-10 md:ml-64">
    
 @livewire('holiday.create')
</div> 


@endsection
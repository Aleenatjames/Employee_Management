@extends('layouts.employee_dashboard')

@section('title', 'Attendance History')

@section('sidebar')
@parent
@livewireStyles

@endsection

@section('content')

<div class="h-full  mt- mb-10  w-full">
  
    <div class="h-full mt-14 mb-10 md:ml-64">
    
    @livewire('attendance.history')
   </div> 
           
@endsection
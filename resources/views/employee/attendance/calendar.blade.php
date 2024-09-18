@extends('layouts.employee_dashboard')

@section('title', 'Attendance Calendar')

@section('sidebar')
@parent
@livewireStyles

@endsection

@section('content')

<div class="h-full  mt- mb-10  w-full">
  
    <div class="h-full mt-14 mb-10 md:ml-64">
    
    @livewire('attendance.calendar')
   </div> 
           
@endsection
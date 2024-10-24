@extends('layouts.employee_dashboard')

@section('title', 'Leave View')

@section('sidebar')
    @parent
 
   
@endsection

@section('content')
       
<div class="h-full ml-14 mt-14 mb-10 md:ml-64 bg-gray-100 dark:bg-gray-700">
    
 @livewire('leave-tracker.apply')
</div> 


@endsection
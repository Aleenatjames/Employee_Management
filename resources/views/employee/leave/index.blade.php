@extends('layouts.employee_dashboard')

@section('title', 'Leave Division')

@section('sidebar')
    @parent
 
   
@endsection

@section('content')
       
<div class="h-full ml-14 mt-14 mb-10 md:ml-64 ">
    
 @livewire('leave-tracker.index')
</div> 


@endsection
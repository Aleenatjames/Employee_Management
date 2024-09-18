@extends('layouts.employee_dashboard')

@section('title', 'Leave Tracker-List')

@section('sidebar')
@parent


@endsection

@section('content')

<div class="h-full ml-100 mt- mb-10 md:ml-64">
    <section class="mt-10 mx-20 ">
       
            <div class="mt-20 bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
              
                <!-- Success Message -->
                @if (session()->has('error'))
                <div class="bg-green-500 text-white p-4 rounded shadow-md mb-6">
                    {{ session('error') }}
                </div>
                @endif
                @livewireScripts
                @livewire('leave-tracker.list-view')
            

@endsection
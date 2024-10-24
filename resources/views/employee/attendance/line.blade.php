@extends('layouts.employee_dashboard')

@section('title', 'Attendance-View')

@section('sidebar')
@parent


@endsection

@section('content')

<div class="px-10 mt- mb- md:ml-64">    
            <div class="mt-16">
                @include('elements.check-button')
                <!-- Success Message -->
                @if (session()->has('error'))
                <div class="bg-green-500 text-white p-4 rounded shadow-md mb-6">
                    {{ session('error') }}
                </div>
                @endif
                @livewireScripts
                 @livewire('attendance.line')


                    @endsection
                    
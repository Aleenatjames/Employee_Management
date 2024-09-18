@extends('layouts.employee_dashboard')

@section('title', 'Attendance-View')

@section('sidebar')
@parent


@endsection

@section('content')

<div class="h-full mt- mb- md:ml-72">
    <section class="mt-10 mx- ">
       
            <div class="mt-20 w-full">
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
                    
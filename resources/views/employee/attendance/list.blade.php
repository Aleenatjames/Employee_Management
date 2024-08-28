@extends('layouts.employee_dashboard')

@section('title', 'Project-Create')

@section('sidebar')
@parent


@endsection

@section('content')

<div class="h-full ml-100 mt- mb-10 md:ml-64">
    <section class="mt-10 mx-20 ">
       
            <div class="mt-20 bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                @include('elements.check-button')
                <!-- Success Message -->
                @if (session()->has('message'))
                <div class="bg-green-500 text-white p-4 rounded shadow-md mb-6">
                    {{ session('message') }}
                </div>
                @endif
                @livewireScripts
                @livewire('attendance.view')


@endsection
@extends('layouts.employee_dashboard')

@section('title', 'Project-Create')

@section('sidebar')
@parent


@endsection

@section('content')

<div class="h-full ml-14 mt-14 mb-10 md:ml-64">

    <div class="h-full ml-14 mt-14 mb-10 md:ml-64">
        <section class="mt-10">
            <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                <div class="mt-20 bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    @include('elements.check-button')
                    <!-- Success Message -->
                    @if (session()->has('message'))
                    <div class="bg-green-500 text-white p-4 rounded shadow-md mb-6">
                        {{ session('message') }}
                    </div>
                    @endif



                    @endsection
@extends('layouts.employee_dashboard')

@section('title', 'Employee Report')

@section('sidebar')
    @parent

@endsection

@section('content')

       
@livewire('employee-report.index')


@endsection
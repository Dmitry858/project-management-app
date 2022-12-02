@extends('layouts.app')

@section('content')
    @include('include.flash-success')
    @include('include.flash-error')
    @include('include.tasks-filter')
    @include('include.tasks-list', ['mode' => 'full'])
@endsection

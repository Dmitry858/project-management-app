@extends('layouts.app')

@section('content')
    @include('include.flash-success')
    @include('include.flash-error')
    @include('include.filter', ['entity' => 'tasks'])
    @include('include.tasks-list', ['mode' => 'full'])
@endsection

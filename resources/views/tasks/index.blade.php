@extends('layouts.app')

@section('content')
    {{ Breadcrumbs::render('tasks') }}
    @include('include.flash-success')
    @include('include.flash-error')
    @include('include.filter', ['entity' => 'tasks'])
    @include('include.tasks-list', ['mode' => 'full'])
@endsection

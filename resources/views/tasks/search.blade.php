@extends('layouts.app')

@section('content')
    {{ Breadcrumbs::render('tasks-search') }}
    @include('include.flash-success')
    @include('include.flash-error')
    @include('include.tasks-list', ['mode' => 'full', 'page' => 'search'])
@endsection

@extends('layouts.app')
@section('title', 'Create Competitions')
@section('content')
    @include('competitions._form', ['formMode' => 'create'])
@endsection

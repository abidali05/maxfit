@extends('layouts.app')
@section('title', 'Edit Competitions')
@section('content')
    @include('competitions._form', ['formMode' => 'edit'])
@endsection

@extends('layouts.app')

@section('title', 'Leader Dashboard')

@section('content')
    <p>Hi {{ auth()->user()->name }}! You’re a <strong>Team Leader</strong>.</p>
@endsection

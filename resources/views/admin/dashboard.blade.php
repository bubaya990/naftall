@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <p>Welcome, {{ auth()->user()->name }}! You are an <strong>Admin</strong>.</p>
@endsection

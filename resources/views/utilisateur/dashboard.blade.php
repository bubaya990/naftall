@extends('layouts.app')

@section('title', 'Utilisateur Dashboard')

@section('content')
    <p>Bonjour, {{ auth()->user()->name }}! Ceci est votre <strong>tableau de bord utilisateur</strong>.</p>
@endsection

@extends('front.layouts.app')

@section('title', 'Amici')

@section('styles')
<style>
    .friend-card {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: all 0.3s;
    }
    .friend-card:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .friend-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
    }
    .tab-buttons .btn {
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
    }
</style>
@endsection

@section('content')
<div class="container my-5">
    <h2 class="mb-4"><i class="fas fa-users"></i> Amici</h2>

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @livewire('friends-list')
</div>
@endsection

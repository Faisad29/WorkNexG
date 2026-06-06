<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — WorkNexG</title>
    <meta name="description" content="@yield('meta_description', 'WorkNexG Workforce Management')">
    @include('partials.styles')
    @stack('styles')
</head>
<body>

@include('components.navbar')
@include('components.sidebar')

<main class="main-wrapper">
    <div class="page-container">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success" role="alert">✓ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger" role="alert">✕ {{ session('error') }}</div>
        @endif
        @if(session('warning'))
            <div class="alert alert-warning" role="alert">⚠ {{ session('warning') }}</div>
        @endif

        @yield('content')
    </div>
</main>

{{-- Toast Container --}}
<div class="toast-container" id="toastContainer"></div>

@include('partials.scripts')
@stack('scripts')
</body>
</html>

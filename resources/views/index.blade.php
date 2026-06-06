@extends('layouts.app')

@section('content')

<div class="container">

    <div class="py-5 text-center">
        <h1 class="display-4 fw-bold">🚀 WorkNexG</h1>

        <p class="lead">
            Next Generation Workforce & Operations Platform
        </p>

        <p>
            Automate attendance, payroll, compliance and workforce management.
        </p>

        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
            Get Started
        </a>

        <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg">
            Login
        </a>
    </div>

    <div class="row g-4 py-5">

        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5>Workforce Management</h5>
                    <p>Manage employees and organization structure.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5>Smart Attendance</h5>
                    <p>GPS, QR and site-based attendance tracking.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5>Payroll Automation</h5>
                    <p>Automated salary and overtime calculations.</p>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection
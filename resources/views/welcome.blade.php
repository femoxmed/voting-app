<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <div class="d-flex">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-outline-light">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-light me-2">Log in</a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <main class="container my-5">
        <div class="p-5 text-center bg-white rounded-3 shadow-sm">
            <h1 class="display-4">Secure & Transparent Corporate Voting</h1>
            <p class="lead col-lg-8 mx-auto text-muted">
                Empowering shareholders with a modern, reliable, and accessible platform for Annual General Meetings and voting procedures.
            </p>
            <hr class="my-4">
            <p>Join us to make your voice heard.</p>
            @auth
                <a class="btn btn-primary btn-lg" href="{{ route('dashboard') }}" role="button">Go to Dashboard</a>
            @else
                <a class="btn btn-primary btn-lg" href="{{ route('login') }}" role="button">Get Started</a>
            @endauth
        </div>
    </main>

    <script src="{{ mix('js/app.js') }}" defer></script>
</body>
</html>

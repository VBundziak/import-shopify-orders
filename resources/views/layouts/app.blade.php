<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('img/favicon/favicon.ico') }}">

    <title>@yield('title', 'Dashboard')</title>

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/bootstrap-table/dist/bootstrap-table.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/nexaris.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div id="app-page">
    <div id="app-content">
        <div class="page-layout @yield('page-class')">
            <div class="page-layout-content">

                <div class="container-fluid">
                    @yield('content')
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/bootstrap-table/dist/bootstrap-table.min.js"></script>

@stack('scripts')
</body>
</html>

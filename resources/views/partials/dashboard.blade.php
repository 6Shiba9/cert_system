<!DOCTYPE html>
<html lang="th">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cert System Dashboard - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .active-link {
            font-weight: bold;
            color: #3b82f6;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased">
    @include('partials.navbar')
    <!-- Overall Container -->
    <div class="flex h-screen bg-gray-100">

        <!-- Include the sidebar partial -->
        @include('partials.sidebar')

        <!-- Main Content Area -->
        <main class="flex-1 p-8 overflow-y-auto">
            @yield('content')
        </main>

    </div>

</body>
</html>

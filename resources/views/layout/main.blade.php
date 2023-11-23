<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    {{-- @vite(['resources/css/app.css','resources/js/app.js']) --}}
    <link rel="stylesheet" href="{{ asset('build/assets/app-987e74be.css') }}">
</head>
<body>
    @yield('content')
    <script src="{{ asset('build/assets/app-73e0935b.js') }}"></script>
</body>
</html>
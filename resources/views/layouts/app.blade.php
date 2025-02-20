<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Firebase-Laravel</title>
    {{-- styles --}}
    @vite('resources/css/app.css')
</head>

<body class=" antialiased dark:bg-gray-950 dark:text-white">

    @yield('content')
</body>

</html>

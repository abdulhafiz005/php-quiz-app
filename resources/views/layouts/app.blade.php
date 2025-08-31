<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quiz App</title>
    <style>
        .box { background: lightblue; padding: 10px; margin: 10px; border: 1px solid blue; }
        button { background: gray; color: white; }
    </style>
</head>
<body>
    @yield('content')
</body>
</html>
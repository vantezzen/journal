<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
</head>
<body>
    {{ $title }} - {{ $description }}<br />
    
    <a href="{{ $url }}">Go home</a>
    @foreach ($menu as $menuItem)
        <a href="{{ $menuItem['url'] }}">{{ $menuItem['text'] }}</a>
    @endforeach

    @yield('content')
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Weibo 9 App') - Weibo 9</title>
    <link rel="stylesheet" href="css/app.css">
    <!-- <link rel="stylesheet" href="{{ mix('css/app.css') }}"> -->
    <!-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> -->
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">Weibo 9 App</a>
            <ul class="navbar-nav justify-content-end">
                <li class="nav-item"><a class="nav-link" href="/help">幫助</a></li>
                <li class="nav-item"><a class="nav-link" href="#">登入</a></li>
            </ul>
        </div>
    </nav>
    <div class="container">
        @yield('content')
    </div>
</body>
</html>
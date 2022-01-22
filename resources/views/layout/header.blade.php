<html>
    <head>
        <title>Ladder Management System v0.0.1</title>

        <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
        <link href="{{ asset('css/group.css') }}" rel="stylesheet" />
        <link href="{{ asset('css/ladder.css') }}" rel="stylesheet" />
        <link href="{{ asset('css/player.css') }}" rel="stylesheet" />
        <link href="{{ asset('css/game.css') }}" rel="stylesheet" />
    </head>
    <body>
        <div class="container__modified-header">
            @foreach($links as $link)
                <a href="{{ $link['href'] }}" class="container__header-link {{ $link['class'] ?? '' }}">
                    {{ $link['name'] }}
                </a>
            @endforeach
            <form method="POST" action="{{ route('logout') }}" class="header__logout-form">
                <input type="submit" value="Log out" class="header__logout-button" />
            </form>
        </div>

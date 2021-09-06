<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @yield('head.dependencies')
</head>
<body>
    
@php
    $currentRoute = Route::current();
    $routeParameters = json_decode(json_encode($currentRoute->parameters), FALSE);
@endphp

<nav class="main-navigation">
    <div class="header smallPadding rata-tengah">
        <div class="wrap super">
            <div class="icon mb-1">{{ $myData->initial }}</div>
            <h2>{{ $myData->name }}</h2>
            {{-- <div class="teks-kecil mb-2">{{ $myData->booth->name }}</div> --}}
        </div>
    </div>
    <ul>
        <a href="{{ route('admin.dashboard') }}">
            <li class="{{ $currentRoute->uri == 'admin/dashboard' ? 'active' : '' }}">
                <div class="icon"><i class="fas fa-home"></i></div>
                <div class="text">Dashboard</div>
            </li>
        </a>
        <a href="{{ route('admin.topic') }}">
            <li class="{{ $currentRoute->uri == 'admin/topic' ? 'active' : '' }}">
                <div class="icon"><i class="fas fa-list"></i></div>
                <div class="text">Division</div>
            </li>
        </a>
        <a href="#">
            <li class="{{ $currentRoute->getPrefix() == 'admin/user' ? 'active' : '' }}">
                <div class="icon"><i class="fas fa-users"></i></div>
                <div class="text">Users
                    <i class="fas fa-angle-down"></i>
                </div>
                <ul>
                    <a href="{{ route('admin.agent') }}">
                        <li class="{{ Route::currentRouteName() == 'admin.agent' ? 'active' : '' }}">
                            <div class="icon"><i class="fas fa-users"></i></div>
                            <div class="text">Agents</div>
                        </li>
                    </a>
                    <a href="{{ route('admin.visitor') }}">
                        <li class="{{ Route::currentRouteName() == 'admin.visitor' ? 'active' : '' }}">
                            <div class="icon"><i class="fas fa-users"></i></div>
                            <div class="text">Visitors</div>
                        </li>
                    </a>
                </ul>
            </li>
        </a>
        <a href="{{ route('admin.conversation') }}">
            <li class="{{ $currentRoute->uri == 'admin/conversation' ? 'active' : '' }}">
                <div class="icon"><i class="fas fa-comments"></i></div>
                <div class="text">Conversations</div>
            </li>
        </a>
        <a href="{{ route('admin.logout') }}">
            <li class="{{ $currentRoute->uri == 'admin/logout' ? 'active' : '' }}">
                <div class="icon"><i class="fas fa-sign-out-alt"></i></div>
                <div class="text">Logout</div>
            </li>
        </a>
    </ul>
</nav>

<header>
    <h1>@yield('title')</h1>
    <div class="action">
        @yield('header.action')
    </div>
</header>

<div class="content">
    @yield('content')
    <div class="tinggi-70"></div>
</div>

<script src="{{ asset('js/base.js') }}"></script>
@yield('javascript')

</body>
</html>
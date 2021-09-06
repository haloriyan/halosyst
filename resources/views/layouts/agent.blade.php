<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('fa/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/agent.css') }}">
</head>
<body>
    
<header>
    asd
</header>

<div class="chatRoom smallPadding">
    <div class="wrap super">
        <div class="room">
            asd
        </div>
    </div>
</div>

<div class="content">
    <div class="wrap">
        <div class="chatArea">
            <div class="chat">
                Lorem ipsum dolor sit amet
            </div>
            @for ($i = 0; $i < 15; $i++)
            <div class="chat mine">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Facere repellat rerum earum nam, quam reiciendis aut vitae repudiandae quasi tempore voluptatibus id ut vero, ipsa maxime fugit officia adipisci hic!
            </div>
            @endfor
        </div>
        <div class="tinggi-70"></div>
    </div>
</div>

<div class="typingArea">
    <form action="#">
        {{ csrf_field() }}
        <div class="bagi lebar-95">
            <input type="text" class="box" placeholder="Type message...">
        </div>
        <div class="bagi lebar-5">
            <button class="biru lebar-100">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </form>
</div>

<script src="{{ asset('js/base.js') }}"></script>
<script src="{{ asset('js/storage.js') }}"></script>

</body>
</html>
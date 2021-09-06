@extends('layouts.auth')

@section('title', "Login Admin")
    
@section('content')
<div class="rata-tengah">
    <h1>Login</h1>
</div>

<div class="bg-putih rounded bayangan-5 smallPadding mt-4">
    <div class="wrap super">
        <form action="{{ route('admin.login') }}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="r" value="{{ $r }}">

            @if ($errors->count() != 0)
                @foreach ($errors->all() as $err)
                    <div class="bg-merah-transparan rounded p-2">
                        {{ $err }}
                    </div>
                @endforeach
            @endif

            <div class="mt-2">Email :</div>
            <input type="email" class="box" name="email" required>
            <div class="mt-2">Password :</div>
            <input type="password" class="box" name="password" required>

            <div class="rata-kanan">
                <button class="lebar-50 mt-3 biru rounded-none">Login</button>
            </div>
        </form>
    </div>
</div>
@endsection
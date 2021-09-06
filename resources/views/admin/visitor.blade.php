@extends('layouts.admin')

@section('title', "Visitor")
    
@section('content')
<div class="bg-putih rounded bayangan-5 smallPadding">
    <div class="wrap">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Asked for</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($visitors as $visitor)
                    <tr>
                        <td>
                            {{ $visitor->name }}
                            <div class="teks-kecil mt-1">{{ $visitor->contact }}</div>
                        </td>
                        <td>
                            {{ $visitor->room->topic->name }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
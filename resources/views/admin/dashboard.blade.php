@extends('layouts.admin')

@section('title', "Dashboard")

@php
    use Carbon\Carbon;
@endphp

@section('head.dependencies')
<style>
    .visitor-box li {
        list-style: none;
        border-bottom: 1px solid #ddd;
        padding: 10px 0px;
        margin-bottom: 8px;
    }
</style>
@endsection

@section('content')
<div class="bagi bagi-3">
    <div class="wrap">
        <div class="bg-putih rounded bayangan-5 smallPadding">
            <div class="wrap super">
                <h2>{{ count($rooms) }}</h2>
                <div class="teks-transparan">visitor asked this month</div>
            </div>
        </div>
    </div>
</div>

<div class="bagi bagi-3">
    <div class="wrap">
        <div class="bg-putih rounded bayangan-5 smallPadding">
            <div class="wrap super">
                <h2><i class="fas fa-star mr-1 teks-emas"></i> {{ $mean }} of 5</h2>
                <div class="teks-transparan">visitor rate this month</div>
            </div>
        </div>
    </div>
</div>

<div class="bagi bagi-3">
    <div class="wrap">
        <div class="bg-putih rounded bayangan-5 smallPadding">
            <div class="wrap super">
                <h2>
                    {{ array_values($sortedAgents)[0]['agent']->name }}
                </h2>
                <div class="teks-transparan">is the best agent this month</div>
            </div>
        </div>
    </div>
</div>

<div class="bagi bagi-2">
    <div class="wrap">
        <div class="bg-putih rounded bayangan-5 smallPadding">
            <h3 class="p-2 border-bottom m-0">Top Agents Performance</h3>
            <div class="wrap super">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Rate Earned</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sortedAgents as $item)
                            <tr>
                                <td>{{ $item['agent']->name }}</td>
                                <td>{{ $item['rate'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="bagi bagi-2">
    <div class="wrap">
        <div class="bg-putih rounded bayangan-5 smallPadding visitor-box">
            <h3 class="p-2 border-bottom mt-0">New Visitors</h3>
            <div class="wrap">
                @foreach ($visitors as $visitor)
                    <li>
                        <div class="bagi lebar-60">
                            {{ $visitor->name }} - {{ $visitor->room->topic->name }}
                            <div class="teks-kecil teks-transparan mt-1">{{ $visitor->contact }}</div>
                        </div>

                        <div class="bagi lebar-40 teks-kecil rata-kanan">
                            {{ Carbon::parse($visitor->created_at)->isoFormat('MMMM D, Y') }}
                        </div>
                    </li>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
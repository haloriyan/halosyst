@extends('layouts.admin')

@section('title', "Conversations")

@php
    use Carbon\Carbon;
@endphp

@section('head.dependencies')
<link rel="stylesheet" href="{{ asset('js/flatpickr/dist/flatpickr.min.css') }}">
<link rel="stylesheet" href="{{ asset('js/flatpickr/dist/themes/material_blue.css') }}">
@endsection
    
@section('content')
<div class="bg-putih rounded bayangan-5 smallPadding">
    <div class="wrap">
        <div class="rata-kanan">
            <div class="bagi lebar-40">
                <div class="mt-1">Filter by Date :</div>
                <input type="text" class="box" name="filter" id="dateFilter" onchange="filter(this.value)">
            </div>
        </div>
        <div class="tinggi-30"></div>
        <table>
            <thead>
                <tr>
                    <th>
                        <i class="fas fa-calendar"></i>
                    </th>
                    <th>Visitor</th>
                    <th>Agent</th>
                    <th>Time</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rooms as $room)
                    @php
                        $start = Carbon::parse($room->created_at);
                        $end = Carbon::parse($room->ended_at);
                    @endphp
                    <tr>
                        <td>
                            {{ $start->format('d M Y') }}
                        </td>
                        <td>{{ $room->visitor->name }}</td>
                        <td>{{ $room->agent->name }} - {{ $room->topic->name }}</td>
                        <td>
                            {{ $start->format('H:i') }} - {{ $end->format('H:i') }}
                            <div class="mt-1 teks-kecil teks-transparan">
                                <i class="fas fa-clock"></i>&nbsp;
                                {{ $end->diff($start)->format('%H:%I') }}
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('admin.conversation.export', $room->id) }}" class="bg-hijau-transparan rounded p-1 pl-2 pr-2 teks-kecil pointer">
                                <i class="fas fa-download mr-1"></i> Download
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('javascript')
<script src="{{ asset('js/flatpickr/dist/flatpickr.min.js') }}"></script>     
<script>
    flatpickr("#dateFilter", {
        mode: 'range',
        defaultDate: ["{{ $request->start_date }}", "{{ $request->end_date }}"]
    });

    let url = new URL(document.URL);
    const filter = val => {
        let date = val.split(' to ');

        if (date[1] !== undefined) {
            url.searchParams.set('start_date', date[0]);
            url.searchParams.set('end_date', date[1]);
            window.location = url.toString();
        }
    }
</script>
@endsection
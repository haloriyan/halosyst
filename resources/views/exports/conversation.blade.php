@php
    use Carbon\Carbon;
    $start = Carbon::parse($datas->created_at);
    $end = Carbon::parse($datas->ended_at);
@endphp
<table>
    <thead>
        <tr>
            <th colspan="4"
            style="font-weight: bold;text-align: center;border-bottom: 1px solid #black;background: #3498db;color: #ffffff;">
                <span style="font-size: 24px;">CONVERSATION {{ $datas->topic->name }}</span>
            </th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center;">{{ $end->diff()->format("%H:%I") }} minutes</th>
        </tr>
        <tr>
            <th style="font-weight: bold;border-bottom: 1px solid black;">Agent ({{ $datas->agent->name }})</th>
            <th style="font-weight: bold;border-bottom: 1px solid black;">Visitor ({{ $datas->visitor->name }})</th>
            <th style="font-weight: bold;border-bottom: 1px solid black;">Message</th>
            <th style="font-weight: bold;border-bottom: 1px solid black;">Sent at</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($datas->chats as $chat)
            <tr>
                <td>
                    @if ($chat->sender == "agent")
                        {{ $datas->agent->name }}
                    @endif
                </td>
                <td>
                    @if ($chat->sender != "agent")
                        {{ $datas->visitor->name }}
                    @endif
                </td>
                <td>{{ $chat->body }}</td>
                <td>{{ Carbon::parse($chat->created_at)->format('Y-m-d H:i:s') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@extends('layouts.admin')

@section('title', "Divisions")

@section('header.action')
<button class="teks-kecil" onclick="munculPopup('#addTopic')">
    <i class="fas fa-plus mr-1"></i> NEW DIVISION
</button>
@endsection

@section('content')
<div class="bg-putih bordered smallPadding">
    <div class="wrap">
        @if ($message != "")
            <div class="bg-hijau-transparan rounded p-2 mb-3">
                {{ $message }}
            </div>
        @endif

        @if ($topics->count() == 0)
            <div class="rata-tengah">
                <h2>No data available</h2>
                <button onclick="munculPopup('#addTopic')">
                    Add New Topic
                </button>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th class="lebar-25">Used in Chat</th>
                        <th class="lebar-25"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($topics as $topic)
                        <tr>
                            <td>{{ $topic->name }}</td>
                            <td>{{ $topic->used_in_chat }}</td>
                            <td>
                                <span class="pointer bg-hijau-transparan rounded p-1 pl-2 pr-2 mr-1" onclick="edit('{{ $topic }}')">
                                    <i class="fas fa-edit"></i>
                                </span>
                                <span class="pointer bg-merah-transparan rounded p-1 pl-2 pr-2" onclick="remove('{{ $topic }}')">
                                    <i class="fas fa-trash"></i>
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

<div class="bg"></div>
<div class="popupWrapper" id="addTopic">
    <div class="popup">
        <div class="wrap">
            <h3>Add New Topic
                <i class="fas fa-times ke-kanan pointer" onclick="hilangPopup('#addTopic')"></i>
            </h3>
            <form action="{{ route('admin.topic.store') }}" method="POST" class="wrap super">
                {{ csrf_field() }}
                <div class="mt-2">Topic name :</div>
                <input type="text" class="box" name="name" required>

                <button class="biru lebar-100 mt-2">Save</button>
            </form>
        </div>
    </div>
</div>

<div class="popupWrapper" id="editTopic">
    <div class="popup">
        <div class="wrap">
            <h3>Edit Topic
                <i class="fas fa-times ke-kanan pointer" onclick="hilangPopup('#editTopic')"></i>
            </h3>
            <form action="{{ route('admin.topic.update') }}" method="POST" class="wrap super">
                {{ csrf_field() }}
                <input type="hidden" name="id" id="id" required>
                <div class="mt-2">Topic name :</div>
                <input type="text" class="box" name="name" id="name" required>

                <button class="biru lebar-100 mt-2">Save Change</button>
            </form>
        </div>
    </div>
</div>

<div class="popupWrapper" id="removeTopic">
    <div class="popup">
        <div class="wrap">
            <h3>Delete Topic
                <i class="fas fa-times ke-kanan pointer" onclick="hilangPopup('#removeTopic')"></i>
            </h3>
            <form action="{{ route('admin.topic.delete') }}" method="POST" class="wrap super">
                {{ csrf_field() }}
                <input type="hidden" name="id" id="id" required>
                Are you sure willing to delete <span class="teks-tebal" id="name"></span>?

                <button class="merah lebar-100 mt-2">Yes, please</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script>
    const edit = data => {
        data = JSON.parse(data);
        munculPopup("#editTopic");
        select("#editTopic #id").value = data.id;
        select("#editTopic #name").value = data.name;
    }

    const remove = data => {
        data = JSON.parse(data);
        munculPopup("#removeTopic");
        select("#removeTopic #id").value = data.id;
        select("#removeTopic #name").innerText = data.name;
    }
</script>
@endsection
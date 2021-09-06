@extends('layouts.admin')

@section('title', "Agents")

@section('head.dependencies')
<style>
    .agent-photo {
        width: 150px;
        height: 150px;
        border-radius: 900px;
    }
    table .agent-photo {
        width: 60px;
        height: 60px;
    }
    input[type=file] { height: 150px; }
</style>
@endsection

@section('header.action')
<button class="teks-kecil" onclick="munculPopup('#addAgent')">
    <i class="fas fa-plus mr-1"></i> NEW AGENT
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

        @if ($agents->count() == 0)
            <div class="rata-tengah">
                <h2>No data available</h2>
                <button onclick="munculPopup('#addAgent')">
                    Add New Agent
                </button>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Agents</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($agents as $agent)
                        <tr>
                            <td>
                                <div class="bagi">
                                    <div class="agent-photo tinggi-50" bg-image="{{ asset('storage/agent_photos/'.$agent->photo) }}"></div>
                                </div>
                                <div class="bagi ml-2">
                                    <h3 class="mt-1 mb-1">{{ $agent->name }}</h3>
                                    <div class="teks-kecil">{{ $agent->topic->name }} specialist</div>
                                </div>
                            </td>
                            <td>
                                <span class="pointer bg-hijau-transparan rounded p-1 pl-2 pr-2 mr-1" onclick="edit('{{ $agent }}')">
                                    <i class="fas fa-edit"></i>
                                </span>
                                <span class="pointer bg-merah-transparan rounded p-1 pl-2 pr-2" onclick="remove('{{ $agent }}')">
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
<div class="popupWrapper" id="addAgent">
    <div class="popup">
        <div class="wrap">
            <h3>Add New Agent
                <i class="fas fa-times ke-kanan pointer" onclick="hilangPopup('#addAgent')"></i>
            </h3>
            <form action="{{ route('admin.agent.store') }}" method="POST" enctype="multipart/form-data" class="wrap">
                {{ csrf_field() }}
                <div class="rata-tengah">
                    <input type="file" name="photo" class="bagi" required onchange="inputFile(this, '#agentPhoto')">
                    <br />
                    <div class="agent-photo bagi mb-2 uploadArea" id="agentPhoto">
                        <i class="fas fa-upload"></i>
                        <div class="mt-1">Upload File</div>
                    </div>
                </div>
                <div class="mt-2">Agent name :</div>
                <input type="text" class="box" name="name" id="name" required>
                <div class="mt-2">Division :</div>
                <select name="topic_id" id="topic_id" class="box" required>
                    <option value="">-- CHOOSE DIVISION --</option>
                    @foreach ($topics as $topic)
                        <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                    @endforeach
                </select>
                <div class="mt-2">Email :</div>
                <input type="email" class="box" name="email" id="email" required>
                <div class="mt-2">Password :</div>
                <input type="password" class="box" name="password" id="password" required>

                <button class="biru lebar-100 mt-3">Save</button>
            </form>
        </div>
    </div>
</div>

<div class="bg"></div>
<div class="popupWrapper" id="editAgent">
    <div class="popup">
        <div class="wrap">
            <h3>Edit Agent
                <i class="fas fa-times ke-kanan pointer" onclick="hilangPopup('#editAgent')"></i>
            </h3>
            <form action="{{ route('admin.agent.update') }}" method="POST" enctype="multipart/form-data" class="wrap">
                {{ csrf_field() }}
                <input type="hidden" name="id" id="id">
                <div class="rata-tengah">
                    <input type="file" name="photo" class="bagi" onchange="inputFile(this, '#agentPhotoEdit')">
                    <br />
                    <div class="agent-photo bagi mb-2 uploadArea" id="agentPhotoEdit">
                        <i class="fas fa-upload"></i>
                        <div class="mt-1">Upload File</div>
                    </div>
                </div>
                <div class="mt-2">Agent name :</div>
                <input type="text" class="box" name="name" id="name" required>
                <div class="mt-2">Division :</div>
                <select name="topic_id" id="topic_id" class="box" required>
                    <option value="">-- CHOOSE DIVISION --</option>
                    @foreach ($topics as $topic)
                        <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                    @endforeach
                </select>
                <div class="mt-2">Email :</div>
                <input type="email" class="box" name="email" id="email" required>
                <div class="mt-2">Change Password :</div>
                <input type="password" class="box" name="password" id="password">
                <div class="teks-kecil mt-1">keep it blank if didn't want to change password</div>

                <button class="biru lebar-100 mt-3">Save</button>
            </form>
        </div>
    </div>
</div>

<div class="popupWrapper" id="removeAgent">
    <div class="popup">
        <div class="wrap">
            <h3>Delete Agent
                <i class="fas fa-times ke-kanan pointer" onclick="hilangPopup('#removeAgent')"></i>
            </h3>
            <form action="{{ route('admin.agent.delete') }}" method="POST" class="wrap super">
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
        munculPopup("#editAgent");

        select("#editAgent #id").value = data.id;
        select("#editAgent #name").value = data.name;
        select("#editAgent #email").value = data.email;
        select(`#editAgent #topic_id option[value='${data.topic_id}']`).selected = true;
        select("#editAgent .agent-photo").innerHTML = "";
        select("#editAgent .agent-photo").setAttribute('bg-image', `{{ asset('storage/agent_photos') }}/${data.photo}`);
        bindDivWithImage();
    }

    const remove = data => {
        data = JSON.parse(data);
        munculPopup("#removeAgent");
        select("#removeAgent #id").value = data.id;
        select("#removeAgent #name").innerText = data.name;
    }
</script>
@endsection
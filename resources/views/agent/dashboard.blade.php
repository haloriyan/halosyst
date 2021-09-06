<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('fa/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/agent.css') }}">
</head>
<body>
    
<input type="hidden" id="myData" value="{{ $myData }}">

<header>
    <h1>Agent Dashboard</h1>
    <nav>
        <button class="hijau-alt d-none" id="solveButton" onclick="munculPopup('#closeCase')">
            <i class="fas fa-check"></i>
        </button>
        <a href="{{ route('agent.logout') }}">
            <li>Logout</li>
        </a>
    </nav>
</header>

<div class="roomArea smallPadding">
    <div class="wrap super">
        <div class="render"></div>
    </div>
</div>

<div class="content">
    <div class="wrap">
        <div class="chatArea">
            <div class="startMessage rata-tengah">
                <div class="tinggi-80"></div>
                <i class="fas fa-comments"></i><br />
                <h3 class="mt-4">Please click at chat room on your left to start conversation</h3>
            </div>
        </div>
        <div class="tinggi-70"></div>
    </div>
</div>

<div class="typingArea d-none">
    <div class="caseClosed d-none rata-tengah mt-1 mb-2">This case has been marked as solved</div>
    <form action="#" method="POST" id="type">
        {{ csrf_field() }}
        <div class="bagi lebar-90">
            <input type="text" id="body" class="box" placeholder="Type message...">
        </div>
        <div class="bagi lebar-5">
            <button type="button">
                <i class="fas fa-link"></i>
            </button>
        </div>
        <div class="bagi lebar-5">
            <button class="biru lebar-100 rounded-circle">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </form>
</div>

<div class="bg"></div>
<div class="popupWrapper" id="closeCase">
    <div class="popup">
        <div class="wrap">
            <h3>Mark as Solved ?
                <i class="fas fa-times ke-kanan pointer" onclick="hilangPopup('#closeCase')"></i>
            </h3>
            <form action="#" method="POST">
                {{ csrf_field() }}
                <div class="mt-3">Are you sure willing to mark this case as solved? You will not get star rate if you did this instead of our visitor?</div>
                <button class="hijau-alt lebar-100 mt-3">Yes please</button>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('js/base.js') }}"></script>
<script src="{{ asset('js/storage.js') }}"></script>
<script>
    let csrfToken = select("input[name='_token']").value;
    let myData = JSON.parse(select("#myData").value);
    let state = {
        roomID: null,
        room: null,
        storedMessages: [],
        storedRooms: []
    }

    const getRoom = () => {
        let req = post("{{ route('api.agent.room') }}", {
            id: myData.id,
            csrfToken: csrfToken
        })
        .then(res => {
            if (res.rooms.length != state.storedRooms) {
                select(".roomArea .render").innerHTML = "";
                res.rooms.forEach(room => {
                    let classes = "room";
                    let roomsToRender = `<div>
        <h3 class="m-0">${room.visitor.name}</h3>`;
                    
                    if (room.ended_at != null) {
                        roomsToRender += "<div class='mt-1 teks-kecil'>Case Closed</div>";
                    }
                    roomsToRender += "</div>";

                    if (room.id == state.roomID) {
                        classes += " active";

                        if (room.ended_at != null) {
                            // Case already solved
                            select(".typingArea .caseClosed").classList.remove('d-none');
                            select(".typingArea form").classList.add('d-none');
                            select("#solveButton").classList.add('d-none');
                        } else {
                            select(".typingArea .caseClosed").classList.add('d-none');
                            select(".typingArea form").classList.remove('d-none');
                            select("#solveButton").classList.remove('d-none');
                        }
                    }
                    createElement({
                        el: 'div',
                        attributes: [
                            ['class', classes],
                            ['room-id', room.id],
                            ['onclick', 'chooseRoom(this)']
                        ],
                        html: roomsToRender,
                        createTo: '.roomArea .render'
                    });
                });

                state.storedRooms = res.rooms;
            }
        });
    }

    const chooseRoom = room => {
        selectAll(".roomArea .room").forEach(r => r.classList.remove('active'));
        room.classList.add('active');

        let roomID = room.getAttribute('room-id');
        state['roomID'] = roomID;
        select(".chatArea").innerHTML = "loading...";
        select(".typingArea").classList.remove('d-none');
    }

    const loadChat = () => {
        if (state.roomID == null) {
            console.log('tiada');
        } else {
            let req = post("{{ route('api.chat.get') }}", {
                roomID: state.roomID
            })
            .then(datas => {
                if (datas.length == 0) {
                    select(".chatArea").innerHTML = "<div class='rata-tengah'>Visitor have not send any message yet</div>";
                }
                if (datas.length != state.storedMessages) {
                    datas = datas.reverse();
                    select(".chatArea").innerHTML = "";

                    datas.forEach(message => {
                        let classes = "chat";
                        if (message.sender == "agent") {
                            classes += " mine";
                        }
                        createElement({
                            el: 'div',
                            attributes: [
                                ['class', classes]
                            ],
                            html: message.body,
                            createTo: '.chatArea'
                        });
                    });

                    state.storedMessages = datas;
                }
            });
        }
    }

    select("form#type").onsubmit = function (e) {
        let input = select("input#body");
        let body = input.value;
        let req = post("{{ route('api.chat.send') }}", {
            csrfToken: csrfToken,
            roomID: state.roomID,
            body: body,
            sender: 'agent'
        })
        .then(res => {
            input.value = "";
        });

        e.preventDefault();
    }

    select("#closeCase form").onsubmit = function(e) {
        let req = post("{{ route('api.chat.end') }}", {
            room_id: state.roomID
        })
        .then(res => {
            console.log(res);
            hilangPopup("#closeCase");
        })
        e.preventDefault();
    }

    setInterval(() => {
        getRoom();
        loadChat();
    }, 1000);

</script>

</body>
</html>
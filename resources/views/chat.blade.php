<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('fa/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/chatUser.css') }}">
</head>
<body>
    
<div class="profile">
    <div class="picture"></div>
    <div class="info">
        <h2 id="name"></h2>
        <p id="topic"></p>
    </div>
    <button class="hijau-alt d-none" id="solveButton" onclick="solveProblem()">
        <i class="fas fa-check mr-1"></i>
        <span>Mark as Solved</span>
    </button>
</div>

<div class="contentArea">
    {{ csrf_field() }}
    <div class="content">
        <div class="rata-tengah loading">loading...</div>
    </div>
</div>

<div class="typingArea">
    <form action="#" method="POST" id="type">
        {{ csrf_field() }}
        <input type="hidden" name="room_id" id="roomID">
        <input type="file" class="d-none" name="attachment" id="attachment" onchange="upload(this)">
        <input type="text" class="box" name="body" id="body" placeholder="Type message...">
        {{-- <button type="button d-none" class="button m-0 p-0" style="height: auto;" onclick="chooseAttachment()">
            <i class="fas fa-paperclip"></i>
        </button> --}}
    </form>
</div>

<div class="bg"></div>
<div class="popupWrapper" id="solve">
    <div class="popup">
        <div class="wrap">
            <h3>
                <i class="fas fa-times ke-kanan pointer" onclick="hilangPopup('#solve')"></i>
            </h3>
            <form action="{{ route('visitor.chat.end') }}" id="endChat" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="room_id" id="roomID">
                <input type="hidden" name="star" id="star" required value="0">
                <p>Before we mark this as solved, we like to hear from you about our service</p>
                <div class="stars rata-tengah">
                    @for ($i = 1; $i <= 5; $i++)
                        <div class="star" onclick="chooseStar('{{ $i }}')"><i class="fas fa-star"></i></div>
                    @endfor
                </div>

                <button class="hijau lebar-100 mt-3">Submit</button>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('js/base.js') }}"></script>
<script src="{{ asset('js/storage.js') }}"></script>
<script>
    let userToken = new Storage("user_token");
    let csrfToken = select("input[name='_token']").value;
    let state = {
        roomID: null,
        storedMessages: []
    }

    const markSolvedTypingArea = () => {
        let typingArea = select(".typingArea");
        typingArea.innerHTML = "This case has been marked as solved";
        typingArea.classList.add('p-2')
        typingArea.style.borderRadius = '0px';
        select("button#solveButton").classList.add('d-none');
    }

    const init = () => {
        let req = post("{{ route('api.visitor.initChat') }}", {
            token: userToken.get(),
            csrfToken: csrfToken
        })
        .then(res => {
            let data = res.data;
            let room = data.room;
            let agent = data.room.agent;
            document.title = `Chat with ${agent.name} - {{ env('APP_NAME') }}`;

            state['roomID'] = room.id;

            select(".profile .picture").setAttribute('bg-image', `{{ asset("storage/agent_photos") }}/${agent.photo}`)
            select(".profile #name").innerText = agent.name;
            select(".profile #topic").innerText = `${room.topic.name} specialist`;
            select("form#endChat #roomID").value = room.id;
            bindDivWithImage();

            console.log(room.ended_at);
            if (room.ended_at != null) {
                markSolvedTypingArea();
            } else {
                select("button#solveButton").classList.remove('d-none');
            }
        });
    }

    const loadChat = () => {
        let req = post("{{ route('api.chat.get') }}", {
            csrfToken: csrfToken,
            roomID: state.roomID
        })
        .then(datas => {
            if (datas.length == 0) {
                select(".loading").innerHTML = "Start type message in box at bottom"
            }
            if (datas[0].room.ended_at != null) {
                markSolvedTypingArea();
            }
            if (datas.length != state.storedMessages) {
                datas = datas.reverse();
                select(".content").innerHTML = "";

                datas.forEach(message => {
                    let classes = "message";
                    if (message.sender == "visitor") {
                        classes += " mine";
                    }
                    createElement({
                        el: 'div',
                        attributes: [['class', classes]],
                        html: message.body,
                        createTo: '.content'
                    });
                });

                state.storedMessages = datas;
            }
        })
    }

    select("form#type").onsubmit = function (e) {
        let input = select("input#body");
        let body = input.value;
        let req = post("{{ route('api.chat.send') }}", {
            csrfToken: csrfToken,
            roomID: state.roomID,
            body: body,
            sender: 'visitor'
        })
        .then(res => {
            input.value = "";
        });
        
        e.preventDefault();
    }

    const upload = input => {
        let file = input.files[0];
        console.log(file);
        let formData = new FormData();
        formData.append('attachment', file);

        fetch('{{ route("api.chat.send") }}', {
            headers: {
                'Content-Type': 'multipart/form-data'
            },
            method: 'POST',
            body: formData
        })
        .then(res => res.text())
        .then(res => {
            console.log(res);
        })
    }

    init();
    setInterval(() => {
        loadChat();
    }, 1000);

    const solveProblem = () => {
        munculPopup("#solve")
    }
    const chooseStar = star => {
        let stars = selectAll(".star");
        select("input#star").value = star;
        for (let i = 0; i < star; i++) {
            stars[i].classList.add('active');
        }
    }
    const chooseAttachment = () => {
        select("input#attachment").click();
    }
</script>

</body>
</html>
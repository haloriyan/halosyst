<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('fa/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>
<body>

<div class="content">
    <form method="POST" id="formStart">
        <h1>
            Hi, <input type="text" class="box" id="name" name="name" required placeholder="Your name...">
        </h1>
        {{ csrf_field() }}
        <p class="teks-besar teks-tebal">
            How can we help you with 
            <select name="topics" id="topic" class="box teks-besar ml-1 mr-1" required>
                <option value="">...</option>
                @foreach ($topics as $topic)
                    <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                @endforeach
            </select>
            ?
        </p>

        <button class="biru mt-2 mr-2">
            <i class="fas fa-paper-plane mr-2"></i>
            START CONVERSATION
        </button>

        <div class="contactList">
            or see our <a href="#" class="teks-biru">contact list</a>
        </div>

        <div class="mt-5 rata-tengah">
            <div class="bagi lebar-70 mt-1 rata-kiri">
                and will your share your contact with us?
                <br />
                <input type="text" id="contact" class="box bordered teks-kecil lebar-100" placeholder="Maybe email address or phone number">
            </div>
        </div>
    </form>
</div>
    
<script src="{{ asset('js/base.js') }}"></script>
<script src="{{ asset('js/storage.js') }}"></script>
<script>
    select("#name").focus();
    let csrfToken = select("input[name='_token']").value;
    let userToken = new Storage('user_token');

    select("form").onsubmit = function (e) {
        let name = select("#name").value;
        let contact = select("#contact").value;
        let topicID = select("#topic").value;
        
        let req = post("{{ route('visitor.register') }}", {
            name: name,
            contact: contact,
            topicID: topicID,
            csrfToken: csrfToken
        })
        .then(res => {
            userToken.set(res.token);

            window.location = "{{ route('visitor.waiting') }}";
        });

        e.preventDefault();
    }
</script>

</body>
</html>
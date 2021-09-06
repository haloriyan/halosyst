<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Looking for our agents...</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('fa/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/loader.css') }}">
</head>
<body>
    
<div class="content rata-tengah">
    <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
    <h1>Please waiting for a second...</h1>
    <div class="tinggi-40"></div>
    <p>
        We are finding an agent for you
    </p>
</div>

<script src="{{ asset('js/base.js') }}"></script>
<script src="{{ asset('js/storage.js') }}"></script>
<script>
    let userToken = new Storage("user_token");

    const lookup = () => {
        console.log('looking up...');
        let req = post("{{ route('api.visitor.lookingAgent') }}", {
            token: userToken.get(),
        })
        .then(res => {
            if (res.status == 404) {
                setTimeout(() => {
                    lookup();
                }, 1000);
            } else {
                window.location = "{{ route('visitor.chat') }}";
            }
        });
    }

    lookup();
</script>

</body>
</html>
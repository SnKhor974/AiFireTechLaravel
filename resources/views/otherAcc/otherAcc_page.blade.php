<!DOCTYPE html>
<html>
<head>
    <title>Others</title>
    <link rel="icon" type="image/png" href="{{ asset('img/aifiretechlogo.png')}}">
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/autocomplete.css') }}">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/sakura.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  
</head>
<body>
    <div class="container">
        <img src="{{ asset('img/Screenshot 2024-07-15 203702.png') }}" alt="AiFireTechnology" width=100%>
        <h1>Logged in as Others - {{$username}}</h1>
        
        <form id="otherAcc-logout-form" action="{{ route('otherAcc-logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <p><a href="#" onclick="event.preventDefault(); document.getElementById('otherAcc-logout-form').submit();">Log out</a></p>
        
    </div>
</body>
</html>
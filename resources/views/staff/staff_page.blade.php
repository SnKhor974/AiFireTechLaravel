<!DOCTYPE html>
<html>
<head>
    <title>Staff</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/sakura.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  
</head>
<body>
    
    <img src="{{ asset('img/Screenshot 2024-07-15 203702.png') }}" alt="AiFireTechnology" width=100%>
    <h1>Logged in as Staff - {{$username}}</h1>
    <a href="">Register new account</a>
    <form id="staff-logout-form" action="{{ route('staff-logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    <p><a href="#" onclick="event.preventDefault(); document.getElementById('staff-logout-form').submit();">Log out</a></p>
    <table>
        <tr>
            <th>User ID</th>
            <th>Username</th>
        </tr>

        @foreach($user_list as $user)
            <tr>
                <td>{{$user->id}}</td>
                <td>{{$user->username}}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>
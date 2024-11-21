<!DOCTYPE html>
<html>
<head>
    <title>Admin</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <p>Welcome to the admin page!</p>
    <p>Logged in as {{$username}}</p>
    <form id="logout-form" action="{{ route('admin-logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    <p><a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log out</a></p>
</body>
</html>
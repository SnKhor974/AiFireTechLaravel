<!DOCTYPE html>
<html>
<head>
    <title>Admin</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/sakura.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/autocomplete.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
</head>
<body>
    <img src="{{ asset('img/Screenshot 2024-07-15 203702.png') }}" alt="AiFireTechnology" width=100%>
    <h1>Logged in as Admin - {{$username}}</h1>
    <a href="">Register new account</a>
    <form id="admin-logout-form" action="{{ route('admin-logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    <p><a href="#" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">Log out</a></p>

    <label style="font-size:30px">Check user details: </label>
    <div>
        <form method="post" autocomplete="off" action="{{ route('admin-view-user-by-id') }}">
            @csrf
            <label>Search by ID:</label>
            @if (session('user_id_invalid'))
                <label style="color: red; font-size: 1.5rem;">{{ session('user_id_invalid') }}</label>
            @endif
            <input type="text" name="search_id" id="search_id" placeholder="Enter ID">
            <button>Search</button>
        </form>
    
        <form method="post" autocomplete="off" action="{{ route('admin-view-user-by-name') }}">
            @csrf
            <label>Search by Name:</label>
            @if (session('user_name_invalid'))
                <label style="color: red; font-size: 1.5rem">{{ session('user_name_invalid') }}</label>
            @endif
            <div class="autocomplete-wrapper" id="autocomplete-wrapper">
                <input type="text" name="search_name" id="search_name" class="form-control" placeholder="Enter Name">
            </div>
            <button>Search</button> 
        </form>
    </div>

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
<script type="text/javascript">

const inputE1 = document.querySelector('#search_name');

inputE1.addEventListener("input", onInputChange);

const profileNames = <?php echo $name_list; ?>;

function onInputChange(){
    removeAutocompleteDropdown();

    const value = inputE1.value;  

    if (value.length === 0) return;

    const filteredNames = [];

    profileNames.forEach((name)=>{
        if (name.substr(0,value.length).toLowerCase() === value.toLowerCase())
            filteredNames.push(name);
    });

    createAutocompleteDropdown(filteredNames);

}

function createAutocompleteDropdown(list){
    const listE1 = document.createElement("ul");
    listE1.className = "autocomplete-list";
    listE1.id = "autocomplete-list";

    list.forEach((names)=>{
        const listItem = document.createElement("li");
        const nameButton = document.createElement("button");
        nameButton.innerHTML = names;
        nameButton.addEventListener("click", onNameButtonClick);
        listItem.appendChild(nameButton);

        listE1.appendChild(listItem);
    });

    document.querySelector("#autocomplete-wrapper").appendChild(listE1);
}

function removeAutocompleteDropdown(){
    const listE1 = document.querySelector("#autocomplete-list");
    console.log(listE1);
    if(listE1) listE1.remove();
}

function onNameButtonClick(e){
    e.preventDefault();
    const buttonE1 = e.target;
    inputE1.value = buttonE1.innerHTML;

    removeAutocompleteDropdown();
}

</script>
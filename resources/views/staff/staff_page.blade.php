<!DOCTYPE html>
<html>
<head>
    <title>Staff</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/autocomplete.css') }}">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  
</head>
<body>
    <div class="container">
        @if (session('success'))
            <div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <script>
                // Automatically dismiss the alert after 5 seconds
                setTimeout(function() {
                    $('#success-alert').alert('close');
                }, 5000); // 5000 ms = 5 seconds
            </script>
        @endif
        <img src="{{ asset('img/Screenshot 2024-07-15 203702.png') }}" alt="AiFireTechnology" width=100%>
        <h1>Logged in as Staff - {{$username}}</h1>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#regModal">
            Register new account
        </button>
        <form id="staff-logout-form" action="{{ route('staff-logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <p><a href="#" onclick="event.preventDefault(); document.getElementById('staff-logout-form').submit();">Log out</a></p>
        
        <label style="font-size:30px">Check user details: </label>
        <div>
            <form method="post" autocomplete="off" action="{{ route('staff-view-user') }}">
                @csrf
                <label>Search by ID:</label>
                @if (session('user_id_invalid'))
                    <label style="color: red; font-size: 1.5rem;">{{ session('user_id_invalid') }}</label>
                @endif
                <input type="hidden" name="search" value="id">
                <input type="text" name="search_id" id="search_id" placeholder="Enter ID">
                <button>Search</button>
            </form>
        
            <form method="post" autocomplete="off" action="{{ route('staff-view-user') }}">
                @csrf
                <label>Search by Name:</label>
                @if (session('user_name_invalid'))
                    <label style="color: red; font-size: 1.5rem">{{ session('user_name_invalid') }}</label>
                @endif
                <input type="hidden" name="search" value="name">
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
    </div>
</body>
</html>

<!-- Modal Structure -->
<div class="modal fade" id="regModal" tabindex="-1" role="dialog" aria-labelledby="regModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="regModalLabel">User Registration</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Form Inside the Modal -->
        <form action="{{ route('staff-store-reg') }}" method="POST">
        @csrf
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                    <label for="company_name">Company name</label>
                    <input type="text" class="form-control" id="company_name" name="company_name">
                </div>
                <div class="form-group">
                    <label for="company_address">Company address</label>
                    <input type="text" class="form-control" id="company_address" name="company_address">
                </div>
                <div class="form-group">
                    <label for="person_in_charge">Person in charge</label>
                    <input type="text" class="form-control" id="person_in_charge" name="person_in_charge">
                </div>
                <div class="form-group">
                    <label for="contact">Contact</label>
                    <input type="text" class="form-control" id="contact" name="contact">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" id="email" name="email">
                </div>
                <div class="form-group">
                    <label for="area">Area</label>
                    <input type="text" class="form-control" id="area" name="area">
                </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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
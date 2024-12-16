<!DOCTYPE html>
<html>
<head>
    <title>Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('img/aifiretechlogo.png')}}">
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/autocomplete.css') }}">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/sakura.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
  

    
</head>
<body>
    <div class="container" >
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
        <h1>Logged in as Admin - {{$username}}</h1>
        <button type="button" data-toggle="modal" data-target="#regModal">
            Register new account
        </button>
        <form id="admin-logout-form" action="{{ route('admin-logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <p><a href="#" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">Log out</a></p>

        <!-- <label style="font-size:30px">Check user details: </label>
        <div>
            <form method="post" autocomplete="off" action="{{ route('admin-view-user') }}">
                @csrf
                <label>Search by ID:</label>
                @if (session('user_id_invalid'))
                    <label style="color: red; font-size: 1.5rem;">{{ session('user_id_invalid') }}</label>
                @endif
                <input type="hidden" name="search" value="id">
                <input type="text" name="search_id" id="search_id" placeholder="Enter ID">
                <button>Search</button>
            </form>
        
            <form method="post" autocomplete="off" action="{{ route('admin-view-user') }}">
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
        </div> -->
        <!-- <table id="myTable">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Area</th>
                    <th>Staff in Charge</th>
                </tr>
            </thead>
        </table> -->

        <table id="myTable">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Area</th>
                    <th>Staff in Charge</th>
                    <th>Action</th>
                </tr>
            </thead>


            <!-- @foreach($user_list as $user)
                <tr>
                    <td>{{$user->id}}</td>
                    <td>{{$user->username}}</td>
                    <td>{{$user->area}}</td>
                    <td>
                        @if ($user->staff_id_in_charge == 0)
                            Admin
                        @else
                            @foreach ($staff_list as $staff)
                                @if ($user->staff_id_in_charge == $staff->id)
                                    {{ $staff->username }}
                                @endif
                            @endforeach
                        @endif
                    </td>
                    <td>
                        <form id="redirectForm-{{ $user->id }}" action="{{ route('admin-view-user') }}" method="POST" style="display: none;">
                            @csrf
                            <input type="hidden" name="search" value="id">
                            <input type="hidden" name="search_id" value="{{ $user->id }}">
                        </form>
                        <button onclick="document.getElementById('redirectForm-{{ $user->id }}').submit();">
                            View
                        </button>
                    </td>
                </tr>
            @endforeach -->
        </table>
    </div>
    
    
</body>
</html>

<!-- Modal Structure -->
<div class="modal fade" id="regModal" tabindex="-1" role="dialog" aria-labelledby="regModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="regModalLabel">Account Registration</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Form Inside the Modal -->
        <form action="{{ route('admin-store-reg') }}" autocomplete="off" method="POST">
        @csrf
            <div class="form-group">
                <label for="role">Register for:</label>
                <select onchange="toggleUserDiv()" id="role" name="role" required>
                    <option value="admin">Admin</option>
                    <option value="staff">Staff</option>
                    <option value="user">User</option>
                </select>
            </div>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <!-- <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div> -->
            <div id="userDiv" style="display: none;">
                <div class="form-group">
                    <label for="company_name">Company name:</label>
                    <input type="text" id="company_name" name="company_name">
                </div>
                <div class="form-group">
                    <label for="company_address">Company address:</label>
                    <input type="text" id="company_address" name="company_address">
                </div>
                <div class="form-group">
                    <label for="person_in_charge">Person in charge:</label>
                    <input type="text" id="person_in_charge" name="person_in_charge">
                </div>
                <div class="form-group">
                    <label for="contact">Contact:</label>
                    <input type="text" id="contact" name="contact">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="text"  id="email" name="email">
                </div>
                <div class="form-group">
                    <label for="search_area">Area:</label>
                    <div class="autocomplete-wrapper" id="autocomplete-wrapper">
                        <input type="text" name="search_area" id="search_area">
                    </div>
                </div>
            </div>
            <button type="submit">Register</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script type="text/javascript">

const profileNames = <?php echo $name_list; ?>;
const areaNames = <?php echo $area_list; ?>;



$(document).ready(function() {
    console.log($('#myTable'));
    $('#myTable').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        lengthChange: false,
        responsive: true,
        ajax: {
            url: "{{ route('getUsersData') }}", // Update with your route
            type: "POST", // Use POST method
            dataSrc: function (json) {
                return json.data; // Ensure that 'data' is the key from your API
            },
            data: function(d) {
                // Send additional data to the server (if needed)
                return $.extend({}, d, {
                    // Example: Add any additional parameters here
                    _token: '{{ csrf_token() }}'  // CSRF token for security
                });
            }
        },
        columns: [
            { data: "id" },                // User ID column
            { data: "username" },          // Username column
            { data: "area" },              // Area column
            { data: "staff_in_charge" },
            { data: null,                  // Action column
                render: function(data, type, row) {
                    console.log(row)
                    return `
                        <button class="btn btn-primary view-btn" data-id="${row.id}" >View</button>
                        <button class="btn btn-warning edit-btn" data-id="${row.id}">Edit</button>
                        <button class="btn btn-danger delete-btn" data-id="${row.id}">Delete</button>
                    `;
                }
            }
        ],
        columnDefs: [
            { targets: [-1], searchable: false, orderable: false } // Disable sorting/searching for last column (Action column)
        ],
        order: [[0, 'asc']],
    });

    // Handle View button click
    $(document).on('click', '.view-btn', function() {
        var userId = $(this).data('id');
        window.location.href = "{{ route('admin-view-user') }}?id=" + userId;
    });

    // Handle Edit button click
    $(document).on('click', '.edit-btn', function() {
        var userId = $(this).data('id');
        editUser(userId);
    });

    // Handle Delete button click
    $(document).on('click', '.delete-btn', function() {
        var userId = $(this).data('id');
        deleteUser(userId);
    });
});



        // Function to edit user (Open modal or redirect)
        function editUser(userId) {
            // Example: Redirect to the edit page
            window.location.href = "/edit-user/" + userId;
        }

        // Function to delete user
        function deleteUser(userId) {
            if (confirm("Are you sure you want to delete this user?")) {
                $.ajax({
                    url: "/delete-user",  // Your delete route
                    type: "POST",
                    data: {
                        id: userId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('User deleted successfully!');
                            $('#myTable').DataTable().ajax.reload();  // Reload the table data
                        } else {
                            alert('Error deleting user!');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Something went wrong!');
                    }
                });
            }
        }

    // View user function
    function viewUser(userId) {
        window.location.href = "{{route('admin-view-user')}}"; // Example route to view user
    }


// function setupAutocomplete(inputSelector, dataArray) {
//     const inputE1 = document.querySelector(inputSelector);

//     inputE1.addEventListener("input", function() {
//         onInputChange(inputE1, dataArray);
//     });

//     function onInputChange(inputE1, dataArray) {
//         removeAutocompleteDropdown(inputE1);

//         const value = inputE1.value;

//         if (value.length === 0) return;

//         const filteredNames = dataArray.filter(name => 
//             name.substr(0, value.length).toLowerCase() === value.toLowerCase()
//         );

//         createAutocompleteDropdown(filteredNames, inputE1);
//     }

//     function createAutocompleteDropdown(list, inputE1) {
//         const listE1 = document.createElement("ul");
//         listE1.className = "autocomplete-list";
//         listE1.id = "autocomplete-list";

//         list.forEach(name => {
//             const listItem = document.createElement("li");
//             const nameButton = document.createElement("button");
//             nameButton.innerHTML = name;
//             nameButton.addEventListener("click", function(e) {
//                 onNameButtonClick(e, inputE1);
//             });
//             listItem.appendChild(nameButton);

//             listE1.appendChild(listItem);
//         });

//         inputE1.parentNode.appendChild(listE1);
//     }

//     function removeAutocompleteDropdown(inputE1) {
//         const listE1 = inputE1.parentNode.querySelector(".autocomplete-list");
//         if (listE1) listE1.remove();
//     }

//     function onNameButtonClick(e, inputE1) {
//         e.preventDefault();
//         const buttonE1 = e.target;
//         inputE1.value = buttonE1.innerHTML;

//         removeAutocompleteDropdown(inputE1);
//     }
// }

function toggleUserDiv() {
    const role = document.getElementById('role').value;
    const userDiv = document.getElementById('userDiv');

    if (role === 'user') {
        userDiv.style.display = 'block'; // Show the div
    } else {
        userDiv.style.display = 'none'; // Hide the div
    }
}

</script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
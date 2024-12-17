<!DOCTYPE html>
<html>
<head>
    <title>Staff</title>
    <link rel="icon" type="image/png" href="{{ asset('img/aifiretechlogo.png')}}">
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/autocomplete.css') }}">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/sakura.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
  
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
        <button type="button" data-toggle="modal" data-target="#regModal">
            Register new account
        </button>
        <form id="staff-logout-form" action="{{ route('staff-logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <p><a href="#" onclick="event.preventDefault(); document.getElementById('staff-logout-form').submit();">Log out</a></p>
        
        <table id="myTable">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Area</th>
                    <th>Action</th>
                </tr>
            </thead>
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
        <form action="{{ route('staff-store-reg') }}" autocomplete="off" method="POST">
        @csrf
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                    <label for="company_name">Company name:</label>
                    <input type="text" id="company_name" name="company_name"required>
                </div>
                <div class="form-group">
                    <label for="company_address">Company address:</label>
                    <input type="text" id="company_address" name="company_address" required>
                </div>
                <div class="form-group">
                    <label for="person_in_charge">Person in charge:</label>
                    <input type="text" id="person_in_charge" name="person_in_charge" required>
                </div>
                <div class="form-group">
                    <label for="contact">Contact:</label>
                    <input type="text" id="contact" name="contact" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="text" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="search_area">Area:</label>
                    <div class="autocomplete-wrapper" id="autocomplete-wrapper">
                        <input type="text" name="area" id="area" required>
                    </div>
                </div>
            <button type="submit">Register</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editUserForm" autocomplete="off">
                    @csrf
                    <input type="hidden" id="editUserId" name="id">
                    <div class="mb-3 form-group">
                        <label for="editUsername">Username:</label>
                        <input type="text" id="editUsername" name="username" required>
                    </div>
                    <div class="mb-3 form-group">
                        <label for="search_area">Area:</label>
                        <div class="autocomplete-wrapper" id="autocomplete-wrapper">
                            <input type="text" name="area" id="editArea" required>
                        </div>
                    </div>
                    
                    <h5>Account Details</h5>
                    <div class="mb-3 form-group">
                        <label for="editCompanyName">Company Name:</label>
                        <input type="text" id="editCompanyName" name="company_name" required>
                    </div>
                    <div class="mb-3 form-group">
                        <label for="editCompanyAddress">Company Address:</label>
                        <textarea id="editCompanyAddress" name="company_address" required></textarea>
                    </div>
                    <div class="mb-3 form-group">
                        <label for="editPersonInCharge">Person in Charge:</label>
                        <input type="text" id="editPersonInCharge" name="person_in_charge" required>
                    </div>
                    <div class="mb-3 form-group">
                        <label for="editContact">Contact:</label>
                        <input type="text" id="editContact" name="contact" required>
                    </div>
                    <div class="mb-3 form-group">
                        <label for="editEmail">Email:</label>
                        <input type="text" id="editEmail" name="email" required>
                    </div>
                    <button type="submit" >Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    console.log($('#myTable'));
    $('#myTable').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        lengthChange: false,
        responsive: true,
        ajax: {
            url: "{{ route('staff-getUsersData') }}", // Update with your route
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
        window.location.href = "{{ route('staff-view-user') }}?id=" + userId;
    });

    // Handle Edit button click
    $(document).on('click', '.edit-btn', function() {

        var userId = $(this).data('id');
        fetchUserData(userId);
    });

    // Handle Delete button click
    $(document).on('click', '.delete-btn', function() {
        var userId = $(this).data('id');
        deleteUser(userId);
    });

    // Fetch user data for editing
    function fetchUserData(userId) {
        $.ajax({
            url: "{{ route('staff-fetchUserData') }}?id=" + userId, // Fetch user data
            type: "GET",
            success: function(response) {
                // Populate modal fields with user data
                $('#editUserId').val(response.id);
                $('#editUsername').val(response.username);
                $('#editArea').val(response.area);
                $('#editStaffInCharge').val(response.staff_in_charge);
                $('#editCompanyName').val(response.company_name);
                $('#editCompanyAddress').val(response.company_address);
                $('#editPersonInCharge').val(response.person_in_charge);
                $('#editContact').val(response.contact);
                $('#editEmail').val(response.email);

                // Open the modal
                $('#editUserModal').modal('show');
            },
            error: function(xhr) {
                alert("Error fetching user data!");
            }
        });
    }

     // Handle form submission for saving changes
     $('#editUserForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: "{{ route('staff-updateUserData') }}",// Update user data
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {
                // Close the modal
                $('#editUserModal').modal('hide');

                // Refresh DataTable
                $('#myTable').DataTable().ajax.reload(null, false);

                alert("User updated successfully!");
            },
            error: function(xhr) {
                alert("Error updating user!");
            }
        });
    });

    // Function to delete user
    function deleteUser(userId) {
        if (confirm("Are you sure you want to delete this user?")) {
            $.ajax({
                url: "{{ route('staff-deleteUserData') }}",  // Your delete route
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
});

// Autocomplete functionality
const areaNames = <?php echo $area_list_autocomplete; ?>;
const areaNamesReg = <?php echo $area_list_autocomplete; ?>;

setupAutocomplete("#editArea", areaNames);
setupAutocomplete("#area", areaNamesReg);

function setupAutocomplete(inputSelector, dataArray) {
    const inputE1 = document.querySelector(inputSelector);

    inputE1.addEventListener("input", function() {
        onInputChange(inputE1, dataArray);
    });

    function onInputChange(inputE1, dataArray) {
        removeAutocompleteDropdown(inputE1);

        const value = inputE1.value;

        if (value.length === 0) return;

        const filteredNames = dataArray.filter(name => 
            name.substr(0, value.length).toLowerCase() === value.toLowerCase()
        );

        createAutocompleteDropdown(filteredNames, inputE1);
    }

    function createAutocompleteDropdown(list, inputE1) {
        const listE1 = document.createElement("ul");
        listE1.className = "autocomplete-list";
        listE1.id = "autocomplete-list";

        list.forEach(name => {
            const listItem = document.createElement("li");
            const nameButton = document.createElement("button");
            nameButton.innerHTML = name;
            nameButton.addEventListener("click", function(e) {
                onNameButtonClick(e, inputE1);
            });
            listItem.appendChild(nameButton);

            listE1.appendChild(listItem);
        });

        inputE1.parentNode.appendChild(listE1);
    }

    function removeAutocompleteDropdown(inputE1) {
        const listE1 = inputE1.parentNode.querySelector(".autocomplete-list");
        if (listE1) listE1.remove();
    }

    function onNameButtonClick(e, inputE1) {
        e.preventDefault();
        const buttonE1 = e.target;
        inputE1.value = buttonE1.innerHTML;

        removeAutocompleteDropdown(inputE1);
    }
}
</script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
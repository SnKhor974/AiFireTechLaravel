<!DOCTYPE html>
<html>
<head>
    <title>Viewing User</title>
    <link rel="icon" type="image/png" href="{{ asset('img/aifiretechlogo.png')}}">
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/autocomplete.css') }}">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/sakura.css?id=1') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
</head>
<body>
    <input type="hidden" id="userId" value="{{ $user_details->id }}">
    @if ($errors->any())
        <div id="error-alert" class="alert alert-danger alert-dismissible fade show" role="alert">
            @foreach ($errors->all() as $error)
                {{ $error }}
            @endforeach
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <script>
            // Automatically dismiss the alert after 5 seconds
            setTimeout(function() {
                $('#error-alert').alert('close');
            }, 5000); // 5000 ms = 5 seconds
        </script>
        </div>
    @endif
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
    <img src="{{ asset('img/Screenshot 2024-07-15 203702.png') }}" alt="AiFireTechnology" width="70%" style="display: block; margin-left: auto; margin-right: auto;">
    <h1>Viewing - {{$user_details->username}}</h1>
    <a href="{{ route('staff-page') }}">Back</a>

    <form action="{{ route('staff-generate-report') }}" method="GET">
        <button type="submit" >Generate Report</button>
        <input type="hidden" name="id" value="{{$user_details->id}}">
    </form>

    <form>
        <button type="button" class="edit-btn" data-id="{{ $user_details->id }}" style="margin-top: 10px">Edit Details</button>
    </form>

    <table>
        <tr>
            <td>Company Name:</td>
            <td>{{$user_details->company_name}}</td>
        </tr>
        <tr>
            <td>Company Address:</td>
            <td>{{$user_details->company_address}}</td>
        </tr>
        <tr>
            <td>Person in charge:</td>
            <td>{{$user_details->person_in_charge}}</td>
        </tr>
        <tr>
            <td>Contact:</td>
            <td>{{$user_details->contact}}</td>
        </tr>
        <tr>
            <td>Email:</td>
            <td>{{$user_details->email}}</td>
        </tr>
        <tr>
            <td>Area:</td>
            <td>{{$user_details->area}}</td>
    </table>

    <button type="button" data-toggle="modal" data-target="#addModal">
        Add Fire Extinguisher
    </button>

    <table id="myTable">
        <thead>
            <tr>
                <th>No.</th>
                <th>F/E Location</th>
                <th>F/E Serial Number</th>
                <th>F/E Type</th>
                <th>F/E Brand</th>
                <th>F/E Man. Date</th>
                <th>F/E Exp. Date</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>

</body>
</html>

<!-- Modal Structure -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addModalLabel">Add Fire Extinguisher</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Form Inside the Modal -->
        <form action="{{ route('staff-add-fe')}}" autocomplete="off" method="POST">
        @csrf
            <div class="form-group">
                <label for="serial_number">Serial Number:</label>
                <input type="text" id="serial_number" name="serial_number" required>
            </div>
            <div class="form-group">
                <label for="expiry_date">Expiry Date:</label>
                <input type="text" id="expiry_date" name="expiry_date" placeholder="DD/MM/YYYY" required>
            </div>
            <div class="form-group">
                <label for="location">Location:</label>
                <input type="text" id="location" name="location" required>
            </div>
            
            <input type="hidden" name="user_id" id="user_id" value="{{ $user_details->id }}">
            
            <button type="submit">Add</button>
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
                        <label for="editPassword">Password:</label>
                        <input type="password" id="editPassword" name="password" placeholder="Leave blank to keep password.">
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

<!-- Edit FE Modal -->
<div class="modal fade" id="editFeModal" tabindex="-1" aria-labelledby="editFEModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editFeModalLabel">Edit fire extinguisher</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editFeForm" autocomplete="off">
                    @csrf
                    <input type="hidden" id="editFeId" name="id">
                    <div class="mb-3 form-group">
                        <label for="editFeLocation">Location:</label>
                        <input type="text" id="editFeLocation" name="fe_location" required>
                    </div>
                    <div class="mb-3 form-group">
                        <label for="editFeSerialNumber">Serial Number:</label>
                        <input type="text" id="editFeSerialNumber" name="fe_serial_number" required>
                    </div>
                    <div class="mb-3 form-group">
                        <label for="editExpiryDate">Expiry Date:</label>
                        <input type="text" id="editExpiryDate" name="fe_exp_date" required>
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
    $('#myTable').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        lengthChange: false,
        responsive: true,
        pageLength: 20,
        ajax: {
            url: "{{ route('staff-getFeData') }}", // Update with your route
            type: "POST", // Use POST method
            dataSrc: function (json) {
                return json.fe_data; // Ensure that 'fe_data' is the key from your API
            },
            data: function(d) {
                // Send additional data to the server (if needed)
                return $.extend({}, d, {
                    // Example: Add any additional parameters here
                    _token: '{{ csrf_token() }}',  // CSRF token for security
                    user_id: $('#userId').val()    // Send user ID
                });
            }
        },
        columns: [
            { data: null,                  // No. column
                render: function(data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            { data: "fe_location" },       // F/E Location column
            { data: "fe_serial_number" },  // F/E Serial Number column
            { data: "fe_type" },           // F/E Type column
            { data: "fe_brand" },          // F/E Brand column
            { data: "fe_man_date" },       // F/E Man. Date column
            { data: "fe_exp_date" },  
            { data: null,                  // Action column
                render: function(data, type, row) {
                    return `
                        <button class="btn btn-primary renew-fe-btn" data-id="${row.id}" >Renew</button>
                        <button class="btn btn-warning edit-fe-btn" data-id="${row.id}">Edit</button>
                        <button class="btn btn-danger delete-fe-btn" data-id="${row.id}">Delete</button>
                    `;
                }
            }
        ],
        columnDefs: [
            { targets: [-1], searchable: false, orderable: false } // Disable sorting/searching for last column (Action column)
        ],
        order: [[0, 'asc']],
    });

    // Handle Edit FE button click
    $(document).on('click', '.edit-fe-btn', function() {
        var feId = $(this).data('id');
        fetchFeData(feId);
    });

    // Handle Delete FE button click
    $(document).on('click', '.delete-fe-btn', function() {
        var feId = $(this).data('id');
        deleteFe(feId);
    });

    // Handle Edit button click
    $(document).on('click', '.edit-btn', function() {
        var userId = $(this).data('id');
        fetchUserData(userId);
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

                alert("User updated successfully!");
                location.reload();
            },
            error: function(xhr) {
                alert("Error updating user!");
            }
        });
    });

    // Fetch FE data for editing
    function fetchFeData(feId) {
        $.ajax({
            url: "{{ route('staff-fetchFeData') }}?id=" + feId, // Fetch fe data
            type: "GET",
            success: function(response) {
                // Populate modal fields with fe data
                $('#editFeId').val(response.id);
                $('#editFeLocation').val(response.fe_location);
                $('#editFeSerialNumber').val(response.fe_serial_number);
                $('#editExpiryDate').val(response.fe_exp_date);
                
                // Open the modal
                $('#editFeModal').modal('show');
            },
            error: function(xhr) {
                alert("Error fetching fire extinguisher data!");
            }
        });
    }

     // Handle form submission for saving changes
     $('#editFeForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: "{{ route('staff-updateFeData') }}",// Update user data
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {
                // Close the modal
                $('#editFeModal').modal('hide');

                // Refresh DataTable
                $('#myTable').DataTable().ajax.reload(null, false);

                alert("Fire extinguisher updated successfully!");
            },
            error: function(xhr) {
                alert("Error updating fire extinguisher!");
            }
        });
    });

    // Function to delete fire extinguisher
    function deleteFe(feId) {
        if (confirm("Are you sure you want to delete this fire extinguisher?")) {
            $.ajax({
                url: "{{ route('staff-deleteFeData') }}",  // Your delete route
                type: "POST",
                data: {
                    id: feId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        alert('Fire extinguisher deleted successfully!');
                        $('#myTable').DataTable().ajax.reload();  // Reload the table data
                    } else {
                        alert('Error deleting fire extinguisher!');
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

setupAutocomplete("#editArea", areaNames);

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
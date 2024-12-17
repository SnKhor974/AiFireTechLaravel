<!DOCTYPE html>
<html>
<head>
    <title>Other</title>
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
        <img src="{{ asset('img/Screenshot 2024-07-15 203702.png') }}" alt="AiFireTechnology" width="70%" style="display: block; margin-left: auto; margin-right: auto;">
        <h1>Logged in as Others - {{$username}}</h1>
        
        <form id="otherAcc-logout-form" action="{{ route('otherAcc-logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <p><a href="#" onclick="event.preventDefault(); document.getElementById('otherAcc-logout-form').submit();">Log out</a></p>
        
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
        pageLength: 20,
        ajax: {
            url: "{{ route('otherAcc-getUsersData') }}", // Update with your route
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
        window.location.href = "{{ route('otherAcc-view-user') }}?id=" + userId;
    });

});

</script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
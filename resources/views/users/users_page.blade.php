<!DOCTYPE html>
<html>
<head>
    <title>User</title>
    <link rel="icon" type="image/png" href="{{ asset('img/aifiretechlogo.png')}}">
    <meta charset="UTF-8">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/sakura.css?id=1') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
</head>
<body>
    <input type="hidden" id="userId" value="{{ $user_details->id }}">
    <img src="{{ asset('img/Screenshot 2024-07-15 203702.png') }}" alt="AiFireTechnology" width="70%" style="display: block; margin-left: auto; margin-right: auto;">
    <h1>Logged in as User - {{$username}}</h1>
    <form id="users-logout-form" action="{{ route('users-logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    <p><a href="#" onclick="event.preventDefault(); document.getElementById('users-logout-form').submit();">Log out</a></p>

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
    </table>

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
            </tr>
        </thead>
    </table>

</body>
</html>
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
            url: "{{ route('users-getFeData') }}", // Update with your route
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
            { data: "fe_exp_date" } 
        ],
        columnDefs: [
            { targets: [-1], searchable: false, orderable: false } // Disable sorting/searching for last column (Action column)
        ],
        order: [[0, 'asc']],
    });
});
</script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
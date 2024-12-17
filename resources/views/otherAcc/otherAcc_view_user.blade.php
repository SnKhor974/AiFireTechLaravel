<!DOCTYPE html>
<html>
<head>
    <title>Viewing User</title>
    <link rel="icon" type="image/png" href="{{ asset('img/aifiretechlogo.png')}}">
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/autocomplete.css') }}">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/sakura.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <img src="{{ asset('img/Screenshot 2024-07-15 203702.png') }}" alt="AiFireTechnology" width=100%>
    <h1>Viewing - {{$user_details->username}}</h1>
    <a href="{{ route('otherAcc-page') }}">Back</a>

    <form action="{{ route('otherAcc-generate-report') }}" method="GET">
        <button type="submit" >Generate Report</button>
        <input type="hidden" name="id" value="{{$user_details->id}}">
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

    <table class="fe-table">
        <tr>
            <th>No.</th>
            <th>F/E Location</th>
            <th>F/E Serial Number</th>
            <th>F/E Type</th>
            <th>F/E Brand</th>
            <th>F/E Man. Date</th>
            <th>F/E Exp. Date</th>
        </tr>

        <?php
        
            $counter = 1;
        
        ?>

        @foreach($fe_list as $fe)
            <tr>
                <td>{{$counter}}</td>
                <td>{{$fe->fe_location}}</td>
                <td>{{$fe->fe_serial_number}}</td>
                <td>{{$fe->fe_type}}</td>
                <td>{{$fe->fe_brand}}</td>
                <td>{{$fe->fe_man_date}}</td>
                <td>{{$fe->fe_exp_date}}</td>
            </tr>
        <?php
        
            $counter++;
        ?>
        @endforeach

        
    </table>
</body>
</html>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script type="text/javascript">

</script>
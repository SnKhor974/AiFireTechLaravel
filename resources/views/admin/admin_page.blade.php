<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h1>Home</h1>
    <p>Welcome to the admin page!</p>

    <div>
        <form method="post" autocomplete="off">
            
            <div class="input-box">
            <label for="search" style="font-size:30px">Check user details: </label>
            <label>Search by ID:</label>
            </div>
            <div>
                <input type="text" name="search" id="search" placeholder="Enter ID">
            
                <button>Search</button>
            </div>
            
        </form>
    </div>

    <div>
        <form method="post" autocomplete="off">
        
            <div class="input-box">
            <label>Search by Name:</label>
            </div>
            <div class="autocomplete-wrapper" id="autocomplete-wrapper">
                <input type="text" name="search_name" id="search_name" class="form-control" placeholder="Enter Name">

            </div>
            <button>Search</button>
            
        </form>
    </div>
</body>
</html>
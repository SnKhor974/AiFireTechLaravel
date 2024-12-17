<!DOCTYPE html>
<html>
    <head>
        <title>Login as Staff</title>
        <link rel="icon" type="image/png" href="{{ asset('img/aifiretechlogo.png')}}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/login.css?id=1') }}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
    </head>

    <body>
        <div class="mainbody">
            <div class="main">
                <img src="{{ asset('img/aifiretechlogo.png') }}" alt="AiFireTechnology"class="logo">

                <div class="boxouter">
                    <div class="box">
                        <div class="login">
                            <form method="post" autocomplete="off" action="{{ route( 'staff-login') }}">
                            @csrf
                                <h1 style="font-size: 2rem;">Login as Staff:</h1>

                                @if (session('staff_login_error'))
                                    <label style="color: red;">{{ session('staff_login_error') }}</label>
                                @endif
                     
                                <input type="text" name="username" id="username" placeholder="Username" required>
                                <input type="password" name="password" id="password" placeholder="Password" required>                               

                                <div class="action-button">
                                    <a href="/" class="optionbutton">Back</a>

                                    <button class="optionbutton">Login</button>
                                </div>
                                
                            </form>
                        </div>
                        <div class="more">
                            <div>
                                <h2>Need Help?</h2>
                            </div>
                            <div class="options">
                                <img src="{{asset('img/questionmark.png')}}"><a href="#">Forgot Username/Password?</a>
                            </div>
                            <div class="options">
                                <img src="{{asset('img/questionmark.png')}}"><a href="#">Other Login Problems</a>
                            </div>
                            <div class ="chat">
                                <div class="chat-text">
                                    <h2>Contact us</h2>
                                    <h2>016-551 9831</h2>
                                </div>
                                <div class="chat-icon">
                                    <img src="{{asset('img/chaticon.png')}}" style="width: 80px; height: 80px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        

            </div>
            <div class="copyright">
                <p><b>©2024 AIFIRE TECHNOLOGY SDN BHD 202301004204 (1498123-U). All rights reserved.</b></p>
            </div>
            
        </div>
    
    </body>

</html>
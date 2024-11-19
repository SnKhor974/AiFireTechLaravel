<!DOCTYPE html>
<html>
    <head>
        <title>AiFireTechnology</title>
        <link rel="icon" type="image/png" href="aifiretechlogo.png">
        <link rel="stylesheet" type="text/css" href="css/login.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
    </head>

    <body>
        <div class="mainbody">
            <div class="main">
                <img src="img/aifiretechlogo.png" alt="AiFireTechnology"style="width: 300px; height: 300px;">

                <div class="boxouter">
                    <div class="box">
                        <div class="login">
                            <form method="POST" autocomplete="off" action="{{ route('login') }}">
                                <h1 style="font-size: 2rem;">Login as Admin:</h1>
                     
                                <div class="input-box" >
                                    <input type="text" name="username" style="background-image: url('img/profileicon.png');" id="username" placeholder="Username" required>
                                </div>

                                <div class="input-box">
                                    <input type="password" name="password" style="background-image: url('img/passwordicon.png');" id="password" placeholder="Password" required>
                                </div>

                                <div class="action-button">
                                    <a href="/" class="backbutton">Back</a>

                                    <button class="loginbutton">Login</button>
                                </div>
                                
                            </form>
                        </div>
                        <div class="more">
                            <div>
                                <h2>Need Help?</h2>
                            </div>
                            <div class="options">
                                <img src="img/questionmark.png"><a href="#">Forgot Username/Password?</a>
                            </div>
                            <div class="options">
                                <img src="img/questionmark.png"><a href="#">Reset Account</a>
                            </div>
                            <div class="options">
                                <img src="img/questionmark.png"><a href="#">Other Login Problems</a>
                            </div>
                            <div class ="chat">
                                    <div class="chat-text">
                                        <h2>Contact us</h2>
                                        <h2>016-551 9831</h2>
                                    </div>
                                    <div class="chat-icon">
                                        <img src="img/chaticon.png" style="width: 80px; height: 80px;">
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
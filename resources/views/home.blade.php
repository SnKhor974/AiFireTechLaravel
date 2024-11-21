<!DOCTYPE html>
<html>
    <head>
        <title>Home</title>
        <link rel="icon" type="image/png" href="aifiretechlogo.png">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="css/home.css">
    </head>
    <body>
        <div class="background-image" style="background-image: url('img/index-background.jpg');">
            <div class="transparent-box">
                <div class="section left">
                    <img src="img/aifiretechlogo.png" class="aifiretechlogo">
                    <div class="title">
                        <h1>
                            AiFIRE TECHNOLOGY<br>艾火科技有限公司
                        </h1>
                        <hr class="title-hr">
                        <p style="color: darkred">
                            <b>Fire Protection <br>&<br> Compliance Specialist</b>
                        </p>
                        
                    </div>
                </div>
                <div class="section right">
                    <div class="menu">
                        <div class="dropdown">
                            <button class=""><b>Log In</b></button>
                            <div class="content">
                                <a href="{{route('admin-login-page')}}" style="font-family: sans-serif;"><b>Admin</b></a>
                                <a href="{{route('staff-login-page')}}" style="font-family: sans-serif;"><b>Staff</b></a>
                                <a href="{{route('users-login-page')}}" style="font-family: sans-serif;"><b>User</b></a>
                            </div>
                        </div>
                
                        <a href="img/AiFIRE_Company Profile.pdf" target="_blank" style="text-decoration: none;">
                            <button class="button"><b>Company Profile</b></button>
                        </a>
                        <a href="javascript:void(0);" onclick="openFullscreen('img/OurServices.jpg')" style="text-decoration: none;">
                            <button class="button"><b>Our Services</b></button>
                        </a>
                        <div class="overlay" id="fullscreenOverlay" onclick="closeFullscreen()">
                            <img src="" alt="Fullscreen" class="fullscreen-image" id="fullscreenImage">
                        </div>
                        <a href="javascript:void(0);" onclick="openFullscreen('img/Namecard.jpg')" style="text-decoration: none;">
                            <button class="button"><b>Contact Us</b></button>
                        </a>
                        <div class="overlay" id="fullscreenOverlay" onclick="closeFullscreen()">
                            <img src="" alt="Fullscreen" class="fullscreen-image" id="fullscreenImage">
                        </div>
                        <hr class="menu-hr">
                        <p class="social-media"><b>FOLLOW US ON SOCIAL MEDIA</b></p>
                        <div class="social-media-link">
                            <form action="https://www.instagram.com/aifiretechnology?igsh=aW53cGNkaDNsMGsw" target="_blank" style="padding-left: 20px; padding-right: 8px">
                                <input type="image" src="img/instagram.png" alt="Submit" width="80" height="80">
                            </form>
                            <form action="https://www.facebook.com/share/dsh6wNyMq2aZoxx4/?mibextid=qi2Omg" target="_blank" style="padding-top: 14px; padding-left: 20px; padding-right: 20px">
                                <input type="image" src="img/facebook.png" alt="Submit" width="50" height="50">
                            </form>
                            <form action="https://www.tiktok.com/@aifire3?_t=8pXl0jnt3Ba&_r=1" target="_blank" style="padding-top: 14px; padding-left: 20px; padding-right: 20px">
                                <input type="image" src="img/tiktok.png" alt="Submit" width="50" height="50">
                            </form>
                        </div>
                    </div>
                </div>             
            </div>         
        </div>
        <script>
            function openFullscreen(src) {
                const overlay = document.getElementById("fullscreenOverlay");
                const fullscreenImage = document.getElementById("fullscreenImage");
                fullscreenImage.src = src;
                overlay.style.display = "flex";
            }

            function closeFullscreen() {
                document.getElementById("fullscreenOverlay").style.display = "none";
            }
        </script>
    </body>
</html>
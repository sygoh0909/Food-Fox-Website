<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
</head>
<body>
<header>
    <nav>
        <div class="navbar">
            <div class="socialmedia">
                <a href="https://facebook.com" class="fa fa-facebook"></a>
                <a href="https://instagram.com" class="fa fa-instagram"></a>
                <a href="https://youtube.com" class="fa fa-youtube"></a>
            </div>

            <div class="nav-links">
                <a href="login.php" class="roundButton login">Login</a>
                <a href="signup.php" class="roundButton signup">Sign Up</a>
            </div>
        </div>
    </nav>
</header>
    <main>
        <div class="banner">
            <img src="banner.png" alt="Banner">
            <div class="overlay-content">
                <div class="logo">
                    <img src="logo.png" alt="Logo">
                </div>
                <div class="companyDescription">
                    <h1>Food Fox</h1>
                    <p>"Join Food Fox in the fight to make zero hunger a reality for everyone."</p>
                    <a href="signup.php" class="roundButton join">Join Us Now!</a>
                </div>
                <div class="overlay-nav">
                    <a href="mainpage.php" class="roundButton">Home</a>
                    <a href="events.php" class="roundButton">Events</a>
                    <a href="volunteers.php" class="roundButton">Volunteers</a>
                    <a href="donations.php" class="roundButton">Donation</a>
                </div>
            </div>
        </div>
    </main>
</body>
<style>
    body{
        background-color: burlywood;
        margin: 0;
        font-family: Arial;
    }
    .navbar{
        display: flex;
        justify-content: space-between;
        padding: 15px 20px;
        background-color: #5C4033;
    }
    .socialmedia{
        display: flex;
        gap: 10px;
    }
    .nav-links{
        display: flex;
        gap: 20px;
    }
    .roundButton{
        padding: 8px 20px;
        border-radius: 25px;
        text-decoration: none;
        font-weight: bold;
        font-size: smaller;
    }
    .login{
        background-color: white;
        color: #d3a029;
    }
    .signup{
        background-color: #d3a029;
        color: white;
    }
    .login:hover, .signup:hover{
        transform: translateY(-2px);
    }
    .banner{
        position: relative;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }
    .banner img{
        width: 100%;
        height: auto;
        display: block; /*remove extra spaces around the image*/
        object-fit: cover;
    }
    .overlay-content{
        position: absolute;
        display: flex;
        flex-direction: column;
        top: 0;
        left: 0;
    }
    .logo img{
        width: 60%;
        height: 50%;
    }
    .companyDescription{
        padding-left: 50px;
        color: white;
    }
    .companyDescription h1{
        font-size: 80px;
        font-weight: bold;
    }
    .companyDescription p{
        margin-bottom: 50px;
        font-size: 20px;
    }
    .join{
        background-color: white;
        color: #d3a029;
        padding: 10px 20px;
    }
    .overlay-nav{
        position: absolute;
        display: flex;
        gap: 50px;
        top: 50px;
        left: 700px;
    }
    .overlay-nav a{
        text-decoration: none;
        color: white;
        font-size: larger;
    }
    .overlay-nav a:hover{
        border-radius: 25px;
        background-color: rgba(255,255,255,0.2);
    }
</style>
</html>

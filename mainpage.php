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
            <div class="social-media">
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
        <div class="main-info">
            <section class="aboutus">
                <h2>About Us</h2>
                <p>xxx</p>
                <img src="aboutus1.png" alt="About Us 1">
                <img src="aboutus2.png" alt="About Us 2">
            </section>
            <section class="team-member">
                <h2>Our Team</h2>
                <div class="team-member1">
                    <img src="logo.png" alt="Team Member 1">
                    <p><b>Goh Shu Yi</b></p>
                    <p>xxx</p>
                </div>
                <div class="team-member2">
                    <img src="logo.png" alt="Team Member 2">
                    <p><b>Tiong Jia Yi</b></p>
                    <p>xxx</p>
                </div>
                <div class="team-member3">
                    <img src="logo.png" alt="Team Member 3">
                    <p><b>Yim Sook Xin</b></p>
                    <p>xxx</p>
                </div>
                <div class="team-member4">
                    <img src="logo.png" alt="Team Member 4">
                    <p><b>Phang Pei Mei</b></p>
                    <p>xxx</p>
                </div>
            </section>
            <section class="approach">
                <h2>Our Approach</h2>
                <p>Food Fox is a Malaysian based non-profit organisation focused on providing food to the underprivileged community who striuggle to put food on the table everyday.</p>
            </section>
            <section class="getinvolved">
                <h2>Get Involved</h2>
                <p>xxx</p>
                <img src="getinvolved.png" alt="Get Involved">
            </section>
        </div>
    </main>
</body>
<footer>

</footer>
<style>
    body{
        background-color: #F5EEDC;
        margin: 0;
        font-family: Arial, sans-serif;
    }
    .navbar{
        display: flex;
        justify-content: space-between;
        padding: 15px 20px;
        background-color: #5C4033;
    }
    .social-media{
        display: flex;
        gap: 10px;
    }
    .social-media a{
        color: white;
        font-size: 20px;
        text-decoration: none;
    }
    .nav-links{
        display: flex;
        gap: 30px;
    }
    .roundButton{
        padding: 8px 20px;
        border-radius: 25px;
        text-decoration: none;
        font-weight: bold;
    }
    .login{
        background-color: white;
        color: #d3a029;
        font-size: smaller;
    }
    .signup{
        background-color: #d3a029;
        color: white;
        font-size: smaller;
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
        height: 100%;
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
        padding-left: 200px;
        color: white;
    }
    .companyDescription h1{
        margin-top: 0;
        font-size: 80px;
        font-weight: bold;
    }
    .companyDescription p{
        margin-bottom: 70px;
        font-size: 20px;
    }
    .join{
        background-color: white;
        color: #d3a029;
        padding: 20px 25px;
        border-radius: 10px;
    }
    .overlay-nav{
        position: absolute;
        display: flex;
        gap: 50px;
        top: 50px;
        left: 400px;
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
    .main-info{
        display: flex;
        flex-direction: column;
        text-align: center;
    }
    .main-info section{
        margin: 30px 0;
    }
    .team-member div{
        display: inline-block;
        text-align: center;
    }
    .team-member img{
        width: 250px;
        height: 250px;
        border-radius: 50%;
        padding: 30px;
    }
    .team-member p{
        font-size: smaller;
    }
    .aboutus img, .approach img{
        border-radius: 10px;
    }
    .aboutus img{
        width: 200px;
        height: auto;
    }
</style>
</html>

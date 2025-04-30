<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <title>Login Page</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
   <section>
        <div class="box">
            <div class="form">
                <img src="login-form/images/profile.jpg" class="user" alt="">
                <h2>Login to Your Account</h2>
                <form class="" action="dashboard.html" method="post">
                    <div class="inputBx">
                        <input type="text" name="username" placeholder="Username" id="username" required autofocus>
                        <img src="login-form/images/user.png" alt="">
                    </div>
                    <div class="inputBx">
                        <input type="password" name="password" id="password" placeholder="Password" required>
                        <img src="login-form/images/lock.png" alt="">
                        <button type="button" id="togglePassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: white;">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="inputBx"> 
                        <input type="submit" name="submit" value="Login">
                    </div>
                </form>
                <div>
                    <span style="float: left;"><a href="registrasi.php" style="color: black;">Don't have an account? Register</a></span>
                </div>
            </div>
        </div>
    </section>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</body>
</html>
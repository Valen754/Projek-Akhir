<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <title>Login Page</title>
    <link rel="stylesheet" href="../../css/login.css">
    <style>
    body, h2, input, a, span {
        color: black !important;
    }

    input::placeholder {
        color: #444 !important;
    }
</style>

</head>
<body>
   <section>
        <div class="box">
            <div class="form">
                <img src="" class="user" alt="">
                <h2>Login to Your Account</h2>
                <form action="logic/update/login_logic.php" method="post" enctype="multipart/form-data">
                    <div class="inputBx">
                        <input type="text" name="username" placeholder="Username" id="username" required autofocus>
                        <img src="login-form/images/user.png" alt="">
                    </div>
                    <div class="inputBx">
                        <input type="password" name="password" placeholder="Password" id="password" required>
                        <img src="login-form/images/lock.png" alt="">
                        <button type="button" id="toggleLoginPassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: black;">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="inputBx">
                        <input type="submit" name="login" value="Login">
                    </div>
                </form>
                <div>
                    <span style="float: left;"><a href="registrasi.php">Don't have an account? Register</a></span>
                </div>
            </div>
        </div>
    </section>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script>
        // Toggle password visibility
        document.getElementById("toggleLoginPassword").addEventListener("click", function () {
            const password = document.getElementById("password");
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            this.querySelector("i").classList.toggle("fa-eye");
            this.querySelector("i").classList.toggle("fa-eye-slash");
        });
    </script>
</body>
</html>

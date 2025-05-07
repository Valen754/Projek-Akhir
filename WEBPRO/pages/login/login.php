<!DOCTYPE html>
<html>

<head>

    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <title>Login Page</title>
    <link rel="stylesheet" href="../../css/login.css">
</head>

<body>
    <section>
        <div class="box">

            <div class="form">
                <img src="login-form/images/profile.jpg" class="user" alt="">
                <h2>Login to your Account</h2>
                <form action="logic/login_logic.php" method="post">

                    <div class="inputBx">
                        <input type="text" name="username" placeholder="Username" id="username" oninput="validation()"
                            required autofocus>
                        <img src="login-form/images/user.png" alt="">
                    </div>
                    <div class="inputBx">
                        <input type="password" name="password" id="password" placeholder="Password"
                            oninput="validation()" required>
                        <img src="login-form/images/lock.png" alt="">
                        <button type="button" id="togglePassword"
                            style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: white;">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <label class="remember" style="color: white;"><input type="checkbox"> Remember Me</label>
                    <div class="inputBx">
                        <input type="submit" name="submit" value="Login" id="submit" disabled>
                    </div>
                </form>
                <div style="margin-top: 20px;">
                    <span style="float: left;"><a href="reset-password.html">Forgot Password</a></span>
                    <span style="float: right;"><a href="../registrasi/registrasi.php">Register Here</a></span>
                </div>

            </div>

        </div>
    </section>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script type="text/javascript">
        function validation() {
            let username = document.getElementById("username").value;
            let pass = document.getElementById("password").value;
            if (username != "" && pass != "") {
                document.getElementById("submit").disabled = false;
            } else {
                document.getElementById("submit").disabled = true;
            }
        }

        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordField = document.getElementById('password');
            const passwordFieldType = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', passwordFieldType);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    </script>
</body>

</html>
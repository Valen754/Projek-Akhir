<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <title>Register Page</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../js/register.js">
</head>
<body>
   <section>
        <div class="box">
            <div class="form">
                <img src="login-form/images/profile.jpg" class="user" alt="">
                <h2>Create a New Account</h2>
                <!-- Form mengarah ke registrasi_logic.php -->
                <form action="../logic/create/registrasi_logic.php" method="post" enctype="multipart/form-data">
                    <div class="inputBx">
                        <input type="text" name="username" placeholder="Username" id="username" oninput="validation()" required autofocus>
                        <img src="login-form/images/user.png" alt="">
                    </div>
                    <div class="inputBx">
                        <input type="email" name="email" placeholder="Email" id="email" oninput="validation()" required>
                        <img src="login-form/images/email.png" alt="">
                    </div>
                    <div class="inputBx">
                        <input type="password" name="password" id="password" placeholder="Password" oninput="validation()" required>
                        <img src="login-form/images/lock.png" alt="">
                        <button type="button" id="togglePassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: white;">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="inputBx">
                        <input type="password" name="confirm_password" id="confirmPassword" placeholder="Confirm Password" oninput="validation()" required>
                        <img src="login-form/images/lock.png" alt="">
                        <button type="button" id="toggleConfirmPassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: white;">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <!-- Tambahkan dropdown untuk memilih role -->
                    <div class="inputBx">
                        <select name="role" id="role" required>
                            <option value="member" selected>Member</option>
                            <option value="admin">Admin</option>
                            <option value="kasir">Kasir</option>
                        </select>
                    </div>
                    <div class="inputBx"> 
                        <input type="submit" name="submit" value="Register" id="submit">
                    </div>
                </form>
                <div>
                    <span style="float: left;"><a href="login.html">Already have an account? Login</a></span>
                </div>
            </div>
        </div>
    </section>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</body>
</html>
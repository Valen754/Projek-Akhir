<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <title>Login Page</title>
    <link rel="stylesheet" href="../../css/login.css"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background: #8d6748; /* Background utama */
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-container {
            display: flex;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 950px; /* Lebar maksimal container */
            min-height: 500px; /* Tinggi minimal untuk form login */
        }

        .login-left {
            flex: 1;
            background-image: linear-gradient(to right bottom, #a67c52, #6d4c2b); /* Gradient warna coklat */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 30px;
            color: #fff;
            text-align: center;
            position: relative;
        }

        .login-left h1 {
            font-size: 2.8em;
            margin-bottom: 15px;
            letter-spacing: 1px;
        }

        .login-left p {
            font-size: 1.1em;
            line-height: 1.6;
            opacity: 0.9;
            max-width: 80%;
        }

        /* Optional: Add some abstract shapes */
        .login-left::before, .login-left::after {
            content: '';
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            z-index: 0;
        }

        .login-left::before {
            width: 150px;
            height: 150px;
            top: 20%;
            left: -50px;
        }

        .login-left::after {
            width: 200px;
            height: 200px;
            bottom: 10%;
            right: -80px;
        }

        .login-form-section {
            flex: 1.2; /* Lebih lebar dari sisi kiri */
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .login-form-section h2 {
            font-size: 2em;
            color: #333;
            margin-bottom: 30px;
        }

        .form-group {
            width: 100%;
            position: relative;
            margin-bottom: 25px; /* Jarak antar input */
            display: flex; /* Menggunakan flexbox untuk penyejajaran */
            align-items: center; /* Menyejajarkan item secara vertikal */
        }

        .form-group input {
            flex-grow: 1; /* Input akan mengisi ruang yang tersedia */
            padding: 12px 15px; /* Padding atas/bawah, kanan/kiri */
            padding-left: 40px; /* Ruang untuk ikon */
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
            color: #333;
            font-size: 1em;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            box-sizing: border-box;
            height: 45px; /* Tinggi tetap untuk input */
            line-height: 1.5; /* Penyejajaran vertikal placeholder */
        }

        .form-group input:focus {
            border-color: #a67c52;
            box-shadow: 0 0 0 3px rgba(166, 124, 82, 0.2);
        }

        /* Gaya ikon */
        .form-group .icon-fix {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
            font-size: 1.1em;
            z-index: 1;
            pointer-events: none; /* Klik pada ikon akan mengaktifkan input */
        }

        /* Gaya tombol toggle password */
        .form-group .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #aaa;
            font-size: 1.1em;
            z-index: 2;
        }

        .remember-me {
            width: 100%;
            text-align: left;
            margin-bottom: 25px;
            font-size: 0.9em;
            color: #555;
        }

        .remember-me input {
            margin-right: 8px;
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            background: linear-gradient(to right, #6d4c2b, #a67c52); /* Gradient warna coklat */
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-login:hover:enabled {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .btn-login:disabled {
            background: #ccc;
            cursor: not-allowed;
            box-shadow: none;
        }

        .or-separator {
            width: 100%;
            margin: 25px 0;
            display: flex;
            align-items: center;
            color: #aaa;
            font-size: 0.9em;
        }

        .or-separator::before,
        .or-separator::after {
            content: '';
            flex: 1;
            height: 1px;
            background-color: #eee;
        }

        .or-separator::before {
            margin-right: 15px;
        }

        .or-separator::after {
            margin-left: 15px;
        }

        .social-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 25px;
            width: 100%;
        }

        .social-button {
            flex: 1 1 calc(50% - 7.5px);
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            color: #555;
            font-size: 1em;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.2s, border-color 0.2s;
            box-sizing: border-box;
        }

        .social-button:hover {
            background-color: #f5f5f5;
            border-color: #ccc;
        }

        .social-button i {
            margin-right: 8px;
            font-size: 1.2em;
        }

        .links-bottom {
            width: 100%;
            display: flex;
            justify-content: space-between;
            font-size: 0.9em;
        }

        .links-bottom a {
            color: #a67c52;
            text-decoration: none;
            font-weight: 600;
        }

        .links-bottom a:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                max-width: 90%;
                min-height: auto;
            }

            .login-left {
                padding: 20px;
                min-height: 150px; /* Lebih kecil di mobile */
            }

            .login-left h1 {
                font-size: 2.2em;
            }

            .login-left p {
                font-size: 0.9em;
                max-width: 90%;
            }

            .login-form-section {
                padding: 30px;
            }

            .social-buttons {
                flex-direction: column;
            }

            .social-button {
                width: 100%;
                flex: none;
            }
        }

        @media (max-width: 480px) {
            .login-form-section {
                padding: 20px;
            }
            .login-form-section h2 {
                font-size: 1.8em;
            }
            .form-group input {
                padding-left: 35px; /* Sesuaikan padding ikon untuk mobile */
            }
            .form-group .icon-fix {
                left: 10px; /* Sesuaikan posisi ikon untuk mobile */
            }
            .links-bottom {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
   <div class="login-container">
        <div class="login-left">
            <h1>Welcome to Tapal Kuda</h1>
            <p>Silakan masuk untuk menikmati pengalaman pemesanan yang lebih mudah dan akses ke penawaran eksklusif kami.</p>
        </div>
        <div class="login-form-section">
            <h2>Login to your Account</h2>
            <?php if (isset($_GET['error'])): ?>
                <div style="color: #fff; background: #e57373; padding: 10px 15px; border-radius: 6px; margin-bottom: 18px; text-align:center;">
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>
            <form action="logic/login_logic.php" method="post" style="width: 100%;">
                <div class="form-group">
                    <i class="fas fa-user icon-fix"></i>
                    <input type="text" name="username" placeholder="Username" id="username" oninput="validation()" required autofocus>
                </div>
                <div class="form-group">
                    <i class="fas fa-lock icon-fix"></i>
                    <input type="password" name="password" placeholder="Password" id="password" oninput="validation()" required>
                    <button type="button" id="togglePassword" class="toggle-password">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div class="remember-me">
                    <input type="checkbox" id="rememberMe">
                    <label for="rememberMe">Remember Me</label>
                </div>
                <button type="submit" name="submit" class="btn-login" id="submit" disabled>Login</button>

                <div class="or-separator">OR</div>

                <div class="social-buttons">
                    <button type="button" class="social-button"><i class="fab fa-google"></i> Google</button>
                    <button type="button" class="social-button"><i class="fab fa-facebook-f"></i> Facebook</button>
                </div>

                <div class="links-bottom">
                    <a href="../registrasi/registrasi.php">Register Here</a>
                    <a href="#">Forgot Password?</a>
                </div>
            </form>
        </div>
   </div>

    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script type="text/javascript">
        function validation() {
            let username = document.getElementById("username").value;
            let pass = document.getElementById("password").value;
            if (username !== "" && pass !== "") {
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

        // Add event listeners to input fields to trigger validation
        document.getElementById('username').addEventListener('input', validation);
        document.getElementById('password').addEventListener('input', validation);

        // Initial validation call
        validation();
    </script>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <title>Register Page</title>
    <link rel="stylesheet" href="../../css/registrasi.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background: #8d6748;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        .register-container {
            display: flex;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 950px;
            min-height: 600px;
        }

        .register-left {
            flex: 1;
            background-image: linear-gradient(to right bottom, #a67c52, #6d4c2b); /* Warm gradient */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 30px;
            color: #fff;
            text-align: center;
            position: relative; /* Needed for absolute positioning of design elements */
        }

        .register-left h1 {
            font-size: 2.8em;
            margin-bottom: 15px;
            letter-spacing: 1px;
        }

        .register-left p {
            font-size: 1.1em;
            line-height: 1.6;
            opacity: 0.9;
            max-width: 80%;
        }

        /* Optional: Add some abstract shapes to the left side */
        .register-left::before, .register-left::after {
            content: '';
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            z-index: 0;
        }

        .register-left::before {
            width: 150px;
            height: 150px;
            top: 20%;
            left: -50px;
        }

        .register-left::after {
            width: 200px;
            height: 200px;
            bottom: 10%;
            right: -80px;
        }

        .register-form-section {
            flex: 1.2; /* Make this section slightly larger */
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .profile-pic-upload {
            margin-bottom: 25px;
            position: relative;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: #eee;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            overflow: hidden;
            border: 3px solid #ddd;
            transition: border-color 0.2s;
        }

        .profile-pic-upload:hover {
            border-color: #a67c52;
        }

        .profile-pic-upload img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            position: absolute;
            z-index: 1;
        }

        .profile-pic-upload .upload-icon {
            font-size: 3em;
            color: #aaa;
            z-index: 2;
        }

        .profile-pic-upload input[type="file"] {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
            z-index: 3;
        }

        .register-form-section h2 {
            font-size: 2em;
            color: #333;
            margin-bottom: 25px;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap; /* Allow wrapping */
            gap: 15px;
            width: 100%;
            margin-bottom: 15px;
        }

        .form-group {
            flex: 1 1 calc(50% - 7.5px); /* Two columns layout by default */
            position: relative;
            margin-bottom: 0; /* Adjusted for row gap */
        }

        .form-group.full-width {
            flex: 1 1 100%; /* Make this group take full width */
        }

        .form-label {
            display: block;
            font-size: 0.9em;
            color: #555;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1em;
            color: #333;
            background-color: #f9f9f9;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            box-sizing: border-box;
        }

        .form-group select {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1em;
            color: #333;
            background-color: #f9f9f9;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            box-sizing: border-box;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23888' d='M10.293 3.293L6 7.586 1.707 3.293A1 1 0 00.293 4.707l5 5a1 1 0 001.414 0l5-5a1 1 0 10-1.414-1.414z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #a67c52;
            box-shadow: 0 0 0 3px rgba(166, 124, 82, 0.2);
        }

        .form-group .icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #a67c52;
            font-size: 1.2em;
            pointer-events: none;
            width: 20px;
            text-align: center;
        }

        .form-group .input-wrapper {
            position: relative;
            width: 100%;
        }

        /* Adjust textarea icon position */
        .form-group textarea ~ .icon {
            top: 25px;
            transform: none;
        }

        /* Password toggle button styling */
        .form-group .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #a67c52;
            font-size: 1.1em;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            transition: color 0.2s;
        }

        .form-group .toggle-password:hover {
            color: #8d6748;
        }

        .form-group textarea {
            min-height: 90px; /* Slightly taller for better input */
            resize: vertical;
        }

        .checkbox-group {
            width: 100%;
            text-align: left;
            margin-top: 15px;
            margin-bottom: 25px;
            font-size: 0.9em;
            color: #555;
        }

        .checkbox-group input {
            margin-right: 8px;
        }

        .btn-register {
            width: 100%;
            padding: 15px;
            background: linear-gradient(to right, #6d4c2b, #a67c52);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-register:hover:enabled {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .btn-register:disabled {
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

        .login-link {
            font-size: 0.9em;
            color: #555;
        }

        .login-link a {
            color: #a67c52;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .register-container {
                flex-direction: column;
                max-width: 90%;
                min-height: auto;
            }

            .register-left {
                padding: 20px;
                min-height: 180px;
            }

            .register-left h1 {
                font-size: 2.2em;
            }

            .register-left p {
                font-size: 0.9em;
                max-width: 90%;
            }

            .register-form-section {
                padding: 30px;
            }

            .form-row {
                flex-direction: column; /* Stack inputs vertically on smaller screens */
                gap: 10px;
            }

            .form-group {
                flex: none; /* Remove flex sizing on smaller screens */
                width: 100%; /* Take full width */
            }

            .social-buttons {
                flex-direction: column;
            }
        }

        @media (max-width: 480px) {
            .register-form-section {
                padding: 20px;
            }
            .register-form-section h2 {
                font-size: 1.8em;
            }
            .profile-pic-upload {
                width: 100px;
                height: 100px;
            }
        }
    </style>
</head>
<body>
   <div class="register-container">
        <div class="register-left">
            <h1>Welcome to Tapal Kuda</h1>
            <p>Daftar sekarang untuk menikmati pengalaman pemesanan yang lebih mudah dan akses ke penawaran eksklusif kami.</p>
        </div>
        <div class="register-form-section">
            <h2>Create an Account</h2>
            <form action="logic/registrasi_logic.php" method="post" enctype="multipart/form-data" style="width: 100%;">
                <div class="profile-pic-upload" id="profilePicUpload">
                    <i class="fas fa-camera upload-icon"></i>
                    <img id="previewProfilePic" src="#" alt="Profile Preview" style="display: none;">
                    <input type="file" name="profile_picture" id="profile_picture" accept="image/*" onchange="previewFile()">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-wrapper">
                            <i class="fas fa-user icon"></i>
                            <input type="text" name="username" placeholder="Masukkan username" id="username" oninput="validation()" required autofocus>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <div class="input-wrapper">
                            <i class="fas fa-id-card icon"></i>
                            <input type="text" name="nama" placeholder="Masukkan nama lengkap" id="nama" required>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="gender" class="form-label">Jenis Kelamin</label>
                        <div class="input-wrapper">
                            <i class="fas fa-venus-mars icon"></i>
                            <select name="gender" id="gender" required>
                                <option value="" disabled selected>Pilih jenis kelamin</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="no_telp" class="form-label">Nomor Telepon</label>
                        <div class="input-wrapper">
                            <i class="fas fa-phone icon"></i>
                            <input type="tel" name="no_telp" placeholder="Masukkan nomor telepon" id="no_telp" required>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-wrapper">
                            <i class="fas fa-envelope icon"></i>
                            <input type="email" name="email" placeholder="Masukkan email" id="email" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="alamat" class="form-label">Alamat</label>
                        <div class="input-wrapper">
                            <i class="fas fa-map-marker-alt icon"></i>
                            <textarea name="alamat" id="alamat" placeholder="Masukkan alamat lengkap" required></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock icon"></i>
                            <input type="password" name="password" placeholder="Masukkan password" id="password" required>
                            <button type="button" class="toggle-password" onclick="togglePassword('password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock icon"></i>
                            <input type="password" name="confirm_password" placeholder="Konfirmasi password" id="confirm_password" required>
                            <button type="button" class="toggle-password" onclick="togglePassword('confirm_password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="checkbox-group">
                    <label>
                        <input type="checkbox" name="terms" required>
                        Saya setuju dengan syarat dan ketentuan
                    </label>
                </div>

                <button type="submit" class="btn-register" disabled>Register</button>

                <div class="or-separator">atau</div>

                <div class="social-buttons">
                    <button type="button" class="social-button">
                        <i class="fab fa-google"></i>
                        Google
                    </button>
                    <button type="button" class="social-button">
                        <i class="fab fa-facebook-f"></i>
                        Facebook
                    </button>
                </div>

                <div class="login-link">
                    Sudah punya akun? <a href="../login/login.php">Login disini</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
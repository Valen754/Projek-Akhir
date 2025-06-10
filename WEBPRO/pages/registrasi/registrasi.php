<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <title>Register Page</title>
    <link rel="stylesheet" href="../../css/registrasi.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
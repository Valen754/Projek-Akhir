function validation() {
    let username = document.getElementById("username").value;
    let email = document.getElementById("email").value;
    let pass = document.getElementById("password").value;
    let confirmPass = document.getElementById("confirmPassword").value;
    if (username != "" && email != "" && pass != "" && confirmPass != "" && pass === confirmPass) {
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

document.getElementById('toggleConfirmPassword').addEventListener('click', function () {
    const confirmPasswordField = document.getElementById('confirmPassword');
    const confirmPasswordFieldType = confirmPasswordField.getAttribute('type') === 'password' ? 'text' : 'password';
    confirmPasswordField.setAttribute('type', confirmPasswordFieldType);
    this.querySelector('i').classList.toggle('fa-eye');
    this.querySelector('i').classList.toggle('fa-eye-slash');
});

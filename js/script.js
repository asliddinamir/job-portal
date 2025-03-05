function togglePassword(inputId, eyeIcon) {
    var passwordField = document.getElementById(inputId);
    if (passwordField.type === "password") {
        passwordField.type = "text";
        eyeIcon.classList.remove("fa-eye");
        eyeIcon.classList.add("fa-eye-slash");
    } else {
        passwordField.type = "password";
        eyeIcon.classList.remove("fa-eye-slash");
        eyeIcon.classList.add("fa-eye");
    }
}

// function validatePasswords() {
//     var password = document.getElementById("password").value;
//     var confirmPassword = document.getElementById("confirm-password").value;

//     if (password !== confirmPassword) {
//         document.getElementById("password-message").textContent = "❌ Passwords do not match!";
//         return false;
//     }
//     return true;
// }
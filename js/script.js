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

function updateFileName(input) {
    const placeholder = document.querySelector('.file-placeholder');
    if (input.files && input.files[0]) {
        placeholder.textContent = input.files[0].name;
        placeholder.style.color = '#333';
    }
}

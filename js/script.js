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

document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    const profileIcon = document.querySelector(".profile-icon");
    const closeButton = document.querySelector(".close-btn");

    function toggleSidebar() {
        sidebar.classList.toggle("show");
    }

    // Close sidebar when clicking outside
    document.addEventListener("click", function (event) {
        if (!sidebar.contains(event.target) && !profileIcon.contains(event.target)) {
            sidebar.classList.remove("show");
        }
    });

    // Close sidebar when clicking the "×" button
    closeButton.addEventListener("click", function () {
        sidebar.classList.remove("show");
    });

    // Attach function to profile icon click
    profileIcon.addEventListener("click", function (event) {
        event.stopPropagation(); // Prevents event from bubbling up
        toggleSidebar();
    });
});


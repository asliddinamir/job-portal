// Function to toggle the visibility of a password field
function togglePassword(inputId, eyeIcon) {
    var passwordField = document.getElementById(inputId); // Get the password input field by ID
    if (passwordField.type === "password") { // If the field is of type password
        passwordField.type = "text"; // Change it to text to show the password
        eyeIcon.classList.remove("fa-eye"); // Change the icon to indicate visibility
        eyeIcon.classList.add("fa-eye-slash");
    } else { // If the field is of type text
        passwordField.type = "password"; // Change it back to password to hide the password
        eyeIcon.classList.remove("fa-eye-slash"); // Change the icon to indicate hidden password
        eyeIcon.classList.add("fa-eye");
    }
}

// Function to update the file name display when a file is selected
function updateFileName(input) {
    const placeholder = document.querySelector('.file-placeholder'); // Get the placeholder element
    if (input.files && input.files[0]) { // If a file is selected
        placeholder.textContent = input.files[0].name; // Update the placeholder text with the file name
        placeholder.style.color = '#333'; // Change the text color
    }
}

// Event listener for when the DOM content is fully loaded
document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar"); // Get the sidebar element by ID
    const profileIcon = document.querySelector(".profile-icon"); // Get the profile icon element
    const closeButton = document.querySelector(".close-btn"); // Get the close button element

    // Function to toggle the sidebar visibility
    function toggleSidebar() {
        sidebar.classList.toggle("show"); // Toggle the 'show' class on the sidebar
    }

    // Event listener to close the sidebar when clicking outside of it
    document.addEventListener("click", function (event) {
        if (!sidebar.contains(event.target) && !profileIcon.contains(event.target)) { // If the click is outside the sidebar and profile icon
            sidebar.classList.remove("show"); // Remove the 'show' class to hide the sidebar
        }
    });

    // Event listener to close the sidebar when clicking the close button
    closeButton.addEventListener("click", function () {
        sidebar.classList.remove("show"); // Remove the 'show' class to hide the sidebar
    });

    // Event listener to toggle the sidebar when clicking the profile icon
    profileIcon.addEventListener("click", function (event) {
        event.stopPropagation(); // Prevent the click event from bubbling up
        toggleSidebar(); // Toggle the sidebar visibility
    });
});

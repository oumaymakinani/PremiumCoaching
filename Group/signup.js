import { isValidFullName, isValidEmail, isValidPhone, isStrongPassword, showFieldError, clearFieldError } from './main.js';

document.getElementById("signupForm").addEventListener("submit", function(event) {
    event.preventDefault();

    const fullName = document.getElementById("full_name");
    const email = document.getElementById("email");
    const password = document.getElementById("password");
    const phone = document.getElementById("phone");

    let valid = true;

    [fullName, email, password, phone].forEach(clearFieldError);

    if (fullName.value.trim().length < 2) {
        showFieldError(fullName, "Full name must be at least 2 characters");
        valid = false;
    }

    if (!isValidFullName(fullName.value)) {
        showFieldError(fullName, "Full name should only contain letters and spaces");
        valid = false;
    }    

    if (!isValidEmail(email.value)) {
        showFieldError(email, "Invalid email format");
        valid = false;
    }

    if (!isStrongPassword(password.value)) {
        showFieldError(password, "Password must be at least 6 characters");
        valid = false;
    }

    if (!isValidPhone(phone.value)) {
        showFieldError(phone, "Invalid phone number");
        valid = false;
    }

    if (!valid) return;

    const formData = new FormData(this);

    fetch("backend/signup.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = "LoginPage.html";
        } else {
            alert("Signup failed: " + data.error); 
        }
    })
    .catch(err => console.error("Signup error:", err));
});

// login.js
import { isValidEmail, isStrongPassword } from "./main.js";

document.addEventListener("DOMContentLoaded", () => {
    const loginForm = document.getElementById("loginForm");
    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("password");
    const loginBtn = document.querySelector(".login-btn");

    [emailInput, passwordInput].forEach(input => {
        const errorMsg = document.createElement("small");
        errorMsg.classList.add("error-msg");
        input.parentNode.appendChild(errorMsg);
    });

    // 👁️ 
    const toggleBtn = document.createElement("span");
    toggleBtn.textContent = "👁️";
    toggleBtn.style.cursor = "pointer";
    toggleBtn.style.position = "absolute";
    toggleBtn.style.right = "0px";
    toggleBtn.style.top = "40px";
    toggleBtn.style.transform = "translateY(-50%)";
    toggleBtn.addEventListener("click", () => {
        passwordInput.type = passwordInput.type === "password" ? "text" : "password";
    });
    passwordInput.parentNode.style.position = "relative";
    passwordInput.parentNode.appendChild(toggleBtn);

    function validateInputs() {
        let isValid = true;

        const emailMsg = emailInput.nextElementSibling;
        if (!emailInput.value.trim()) {
            emailMsg.textContent = "Email is required.";
            emailInput.style.borderColor = "red";
            isValid = false;
        } else if (!isValidEmail(emailInput.value.trim())) {
            emailMsg.textContent = "Invalid email format.";
            emailInput.style.borderColor = "red";
            isValid = false;
        } else {
            emailMsg.textContent = "";
            emailInput.style.borderColor = "green";
        }

        // Password
        const passMsg = passwordInput.nextElementSibling;
        if (!passwordInput.value.trim()) {
            passMsg.textContent = "Password is required.";
            passwordInput.style.borderColor = "red";
            isValid = false;
        } else if (!isStrongPassword(passwordInput.value)) {
            passMsg.textContent = "Password must be at least 6 characters.";
            passwordInput.style.borderColor = "red";
            isValid = false;
        } else {
            passMsg.textContent = "";
            passwordInput.style.borderColor = "green";
        }

        loginBtn.disabled = !isValid;
    }

    emailInput.addEventListener("input", validateInputs);
    passwordInput.addEventListener("input", validateInputs);

    loginForm.addEventListener("submit", function (event) {
        event.preventDefault();
        validateInputs(); 

        const formData = new FormData(loginForm);

        fetch("backend/login.php", {
            method: "POST",
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    localStorage.setItem("user_id", data.user_id);
                    if (data.coach_id) {
                        localStorage.setItem("coach_id", data.coach_id);
                    }

                    window.location.href = data.role === "player"
                        ? "ProfilePage.html"
                        : `CoachProfile.html?coach_id=${data.coach_id}`;
                } else {
                    passwordInput.nextElementSibling.textContent = data.error || "Login failed";
                }
            })
            .catch(error => {
                passwordInput.nextElementSibling.textContent = "Error logging in.";
                console.error("Login error:", error);
            });
    });
});

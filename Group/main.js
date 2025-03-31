// main.js
export function isValidFullName(name) {
    return /^[A-Za-z\s]{2,}$/.test(name.trim());
}

export function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

export function isValidPhone(phone) {
    return /^\+?[0-9]{7,15}$/.test(phone);
}

export function isStrongPassword(password) {
    return password.length >= 6;
}

// Show error message under an input field
export function showFieldError(inputElement, message) {
    clearFieldError(inputElement); 

    const error = document.createElement("div");
    error.className = "field-error";
    error.innerText = message;
    inputElement.classList.add("input-error");

    inputElement.parentNode.appendChild(error);
}

// Clear error message
export function clearFieldError(inputElement) {
    const parent = inputElement.parentNode;
    const existingError = parent.querySelector(".field-error");
    if (existingError) {
        existingError.remove();
    }
    inputElement.classList.remove("input-error");
}

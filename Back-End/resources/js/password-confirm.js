const password = document.getElementById("password");
const confirmPassword = document.getElementById("password-confirm");
const message = document.getElementById("message");
const registerBtn = document.getElementById("register-btn")
registerBtn.disabled = true;

confirmPassword.addEventListener("input", function () {
    if (password.value === confirmPassword.value) {
        message.innerHTML = "Le password corrispondono!";
        message.style.color = "green";
        registerBtn.disabled = false;
    } else {
        message.innerHTML = "Le password non corrispondono!";
        message.style.color = "red";
        registerBtn.disabled = true;
    }
});
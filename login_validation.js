document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const username = document.getElementById("username");
    const password = document.getElementById("password");

    form.addEventListener("submit", function (e) {
        let errors = [];

        username.style.borderColor = "";
        password.style.borderColor = "";

        if (username.value.trim() === "") {
            errors.push("Username is required.");
            username.style.borderColor = "red";
        }

        if (password.value.trim() === "") {
            errors.push("Password is required.");
            password.style.borderColor = "red";
        }

        if (errors.length > 0) {
            e.preventDefault(); // Stop form submission
            alert(errors.join("\n"));
        }
    });
});

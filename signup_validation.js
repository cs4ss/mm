document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const password = document.getElementById("password");
    const confirmPassword = document.getElementById("confirm_password");

    form.addEventListener("submit", function (e) {
        let errors = [];

        const inputs = form.querySelectorAll("input");
        inputs.forEach(input => input.style.borderColor = "");

        // Check for empty fields
        inputs.forEach(input => {
            if (input.value.trim() === "") {
                errors.push(`${input.name} is required.`);
                input.style.borderColor = "red";
            }
        });

        // Check password match
        if (password.value !== confirmPassword.value) {
            errors.push("Passwords do not match.");
            password.style.borderColor = "red";
            confirmPassword.style.borderColor = "red";
        }

        // Show error messages
        if (errors.length > 0) {
            e.preventDefault(); // stop form submission

            // Display error message
            alert(errors.join("\n")); 
        }
    });
});

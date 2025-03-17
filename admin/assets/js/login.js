document.addEventListener("DOMContentLoaded", () => {
    const loginForm = document.getElementById("loginForm");
    const submitButton = document.getElementById("submit-btn");
    const buttonText = document.getElementById("btn-text");
    const spinner = document.getElementById("spinner");

    loginForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        // Disable button and show loading state
        submitButton.disabled = true;
        buttonText.textContent = "Logging in...";
        spinner.style.display = "inline-block";

        const formData = {
            email: document.getElementById("email").value,
            password: document.getElementById("password").value,
        };

        try {
            const response = await fetch("http://localhost/warren/admin/assets/php/adminlogin.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(formData),
            });

            const result = await response.json();

            if (response.ok) {
                Swal.fire({
                    icon: "success",
                    title: "Login Successful!",
                    text: result.message,
                    timer: 3000,
                    showConfirmButton: false,
                    willClose: () => {
                        window.location.href = "users.php"; // Redirect to dashboard
                    },
                });
            } else {
                throw new Error(result.message || "Login failed");
            }
        } catch (error) {
            Swal.fire({
                icon: "error",
                title: "Login Failed",
                text: error.message,
            });
        } finally {
            // Re-enable button and reset state
            submitButton.disabled = false;
            buttonText.textContent = "Sign In";
            spinner.style.display = "none";
        }
    });
});
document.addEventListener("DOMContentLoaded", () => {
  const loginForm = document.getElementById("loginForm");
  const loginButton = document.getElementById("loginButton");
  const buttonText = loginButton.querySelector(".button-text");
  const spinner = loginButton.querySelector(".spinner-border");

  // Login form submission
  loginForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    // Disable button and show loading state
    loginButton.disabled = true;
    buttonText.textContent = "Logging in...";
    spinner.classList.remove("d-none");

    const formData = {
      email: document.getElementById("email").value,
      password: document.getElementById("password").value,
      remember: document.getElementById("remeber").checked,
    };

    try {
      const response = await fetch(
        "http://localhost/warren/assets/php/login.php",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(formData),
        }
      );

      const result = await response.json();

      if (response.ok) {
        Swal.fire({
          icon: "success",
          title: "Login Successful!",
          text: result.message,
          timer: 3000,
          showConfirmButton: false,
          willClose: () => {
            // Redirect based on is_subscribed status
            if (result.user.is_subscribed) {
              window.location.href = "http://localhost/warren/dashboard.php"; // Redirect to dashboard
            } else {
              window.location.href = "http://localhost/warren/deposit.php"; // Redirect to deposit
            }
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
      loginButton.disabled = false;
      buttonText.textContent = "Login";
      spinner.classList.add("d-none");
    }
  });
});

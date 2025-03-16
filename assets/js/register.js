// Function to get URL parameters
function getQueryParam(param) {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get(param);
}

// Automatically populate the referral code field if 'ref' is present in the URL
document.addEventListener("DOMContentLoaded", function () {
  const referralCode = getQueryParam('ref');
  if (referralCode) {
      const referralInput = document.getElementById('referral_code');
      if (referralInput) {
          referralInput.value = referralCode;
      }
  }
});

// Function to handle user registration
async function registerUser(formData) {
  const registerButton = document.getElementById("registerButton");
  const buttonText = registerButton.querySelector(".button-text");
  const spinner = registerButton.querySelector(".spinner-border");

  // Disable button and show loading state
  registerButton.disabled = true;
  buttonText.textContent = "Processing...";
  spinner.classList.remove("d-none");

  try {
      const response = await fetch(
          "http://localhost/warren/assets/php/register.php",
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
              title: "Account Created!",
              text: result.message,
              timer: 3000,
              showConfirmButton: false,
              willClose: () => {
                  window.location.href = "http://localhost/warren/deposit.php"; // Redirect to dashboard
              },
          });
      } else {
          throw new Error(result.message || "Registration failed");
      }
  } catch (error) {
      Swal.fire({
          icon: "error",
          title: "Error",
          text: error.message,
      });
  } finally {
      // Re-enable button and reset state
      registerButton.disabled = false;
      buttonText.textContent = "Register";
      spinner.classList.add("d-none");
  }
}

// Handle form submission
document.getElementById("registrationForm").addEventListener("submit", (e) => {
  e.preventDefault();

  const formData = {
      full_name: document.getElementById("full_name").value,
      email: document.getElementById("email").value,
      password: document.getElementById("password").value,
      confirm_password: document.getElementById("confirm_password").value,
      withdrawal_password: document.getElementById("withdrawal_password").value,
      referral_code: document.getElementById("referral_code").value,
  };

  console.log("Form Data:", formData); // Log the form data

  registerUser(formData);
});
async function checkAuth() {
  try {
    const response = await fetch(
      "http://localhost/warren/assets/php/checkAuth.php",
      {
        method: "GET",
        headers: {
          "Content-Type": "application/json",
        },
        credentials: "include", // Include cookies for session handling
      }
    );

    const result = await response.json();

    if (!result.success) {
      // User is not logged in, redirect to login page
      window.location.href = result.redirect;
      return;
    }

    // User is logged in
    const user = result.user;

    if (result.redirect) {
      // User is not subscribed, redirect to deposit page
      window.location.href = result.redirect;
      return;
    }

    // User is subscribed, allow access to the current page
    console.log("User is logged in and subscribed:", user);
  } catch (error) {
    console.error("Authentication check failed:", error);
    window.location.href = "http://localhost/warren/login.php"; // Fallback redirect
  }
}

// Run the authentication check when the page loads
document.addEventListener("DOMContentLoaded", checkAuth);

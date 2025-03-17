document.addEventListener("DOMContentLoaded", () => {
    const tableBody = document.querySelector("#customers tbody");

    // Fetch user data from the server
    const fetchUsers = async () => {
        try {
            const response = await fetch("https://warrencol.com/admin/assets/php/retrieveUser.php");
            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || "Failed to fetch users");
            }

            // Clear existing table rows
            tableBody.innerHTML = "";

            // Populate the table with user data
            result.users.forEach(user => {
                const row = document.createElement("tr");
                row.classList.add("text-center");

                // Map user data to table columns
                row.innerHTML = `
                    <td>${user.id}</td>
                    <td>${user.full_name}</td>
                    <td>${user.email}</td>
                    <td>${user.referral_code}</td>
                    <td>${user.referred_by}</td> <!-- Format: name(id) -->
                    <td><p class="${user.is_subscribed ? 'delivered' : 'pending'} text-center">${user.is_subscribed ? "Subscribed" : "Not Subscribed"}</p></td>
                    <td>${new Date(user.created_at).toLocaleString()}</td> <!-- Format created_at as "Registered On" -->
                `;

                tableBody.appendChild(row);
            });
        } catch (error) {
            console.error("Error fetching users:", error);
            alert("Failed to fetch users. Please try again later.");
        }
    };

    // Fetch users when the page loads
    fetchUsers();
});
document.addEventListener("DOMContentLoaded", () => {
    const tableContainer = document.getElementById("tableContainer");

    // Fetch user data from the server
    const fetchUsers = async () => {
        try {
            const response = await fetch("admin/assets/php/retrieveUser.php");
            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || "Failed to fetch users");
            }

            // Create the table element
            const table = document.createElement("table");
            table.id = "customers";
            table.className = "table table-hover";

            // Create the table header
            const thead = document.createElement("thead");
            const headerRow = document.createElement("tr");
            headerRow.className = "table-light text-center";
            headerRow.innerHTML = `
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Referral Code</th>
                <th>Referred By</th>
                <th>Package</th>
                <th>Registered On</th>
            `;
            thead.appendChild(headerRow);
            table.appendChild(thead);

            // Create the table body
            const tbody = document.createElement("tbody");
            result.users.forEach(user => {
                const row = document.createElement("tr");
                row.classList.add("text-center");
                row.innerHTML = `
                    <td>${user.id}</td>
                    <td>${user.full_name}</td>
                    <td>${user.email}</td>
                    <td>${user.referral_code}</td>
                    <td>${user.referred_by}</td>
                    <td>${user.package_name}</td> <!-- Display package name -->
                    <td>${new Date(user.created_at).toLocaleString()}</td>
                `;
                tbody.appendChild(row);
            });
            table.appendChild(tbody);

            // Append the table to the container
            tableContainer.appendChild(table);

            $('#customers').DataTable({
                paging: true, 
                searching: true, 
                ordering: true, 
                info: true, 
                responsive: true,
            });
        } catch (error) {
            console.error("Error fetching users:", error);
            alert("Failed to fetch users. Please try again later.");
        }
    };

    // Fetch users when the page loads
    fetchUsers();
});
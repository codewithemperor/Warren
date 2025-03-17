document.addEventListener("DOMContentLoaded", () => {
    const tableContainer = document.getElementById("tableContainer");

    // Fetch withdrawal data from the server
    const fetchWithdrawals = async () => {
        try {
            const response = await fetch("https://warrencol.com/admin/assets/php/getWithdrawal.php");
            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || "Failed to fetch withdrawals");
            }

            // Create the table element
            const table = document.createElement("table");
            table.id = "orders";
            table.className = "table table-hover";

            // Create the table header
            const thead = document.createElement("thead");
            const headerRow = document.createElement("tr");
            headerRow.className = "table-light text-center";
            headerRow.innerHTML = `
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Amount</th>
                <th>Fee</th>
                <th>USDT Address</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Action</th>
            `;
            thead.appendChild(headerRow);
            table.appendChild(thead);

            // Create the table body
            const tbody = document.createElement("tbody");
            result.withdrawals.forEach(withdrawal => {
                const row = document.createElement("tr");
                row.classList.add("text-center");
                row.innerHTML = `
                    <td>${withdrawal.id}</td>
                    <td>${withdrawal.username}</td>
                    <td>${withdrawal.email}</td>
                    <td>${withdrawal.amount}</td>
                    <td>${withdrawal.fee}</td>
                    <td>${withdrawal.usdt_address}</td>
                    <td><p class="${withdrawal.status} text-center">${withdrawal.status}</p></td>
                    <td>${new Date(withdrawal.created_at).toLocaleString()}</td>
                    <td>
                        ${withdrawal.status === 'pending' ? `
                            <div class='text-center'>
                                <button class="d-block mb-1 btn btn-p btn-sm me-2" onclick="confirmAction(${withdrawal.id}, 'approved')">Approve</button>
                                <button class="d-block btn btn-a btn-sm" onclick="confirmAction(${withdrawal.id}, 'rejected')">Reject</button>
                            </div>
                        ` : ''}
                    </td>
                `;
                tbody.appendChild(row);
            });
            table.appendChild(tbody);

            // Append the table to the container
            tableContainer.appendChild(table);

            // Initialize DataTables AFTER the table is appended
            $('#orders').DataTable({
                paging: true, // Enable pagination
                searching: true, // Enable search
                ordering: true, // Enable sorting
                info: true, // Show table information
                responsive: true, // Make the table responsive
            });
        } catch (error) {
            console.error("Error fetching withdrawals:", error);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Failed to fetch withdrawals. Please try again later.",
            });
        }
    };

    // Fetch withdrawals when the page loads
    fetchWithdrawals();
});

// Confirm action with SweetAlert
const confirmAction = (id, status) => {
    const actionText = status === "approved" ? "approve" : "reject";
    Swal.fire({
        title: `Are you sure?`,
        text: `You are about to ${actionText} this withdrawal.`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: `Yes, ${actionText} it!`,
    }).then((result) => {
        if (result.isConfirmed) {
            if (status === "approved") {
                approveWithdrawal(id);
            } else {
                rejectWithdrawal(id);
            }
        }
    });
};

// Approve a withdrawal
const approveWithdrawal = async (id) => {
    try {
        const response = await fetch("https://warrencol.com/admin/assets/php/updateWithdrawalStatus.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ id, status: "approved" }),
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || "Failed to approve withdrawal");
        }

        Swal.fire({
            icon: "success",
            title: "Success",
            text: "Withdrawal approved successfully!",
        }).then(() => {
            location.reload(); // Refresh the page to reflect changes
        });
    } catch (error) {
        console.error("Error approving withdrawal:", error);
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Failed to approve withdrawal. Please try again later.",
        });
    }
};

// Reject a withdrawal
const rejectWithdrawal = async (id) => {
    try {
        const response = await fetch("https://warrencol.com/admin/assets/php/updateWithdrawalStatus.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ id, status: "rejected" }),
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || "Failed to reject withdrawal");
        }

        Swal.fire({
            icon: "success",
            title: "Success",
            text: "Withdrawal rejected successfully!",
        }).then(() => {
            location.reload(); // Refresh the page to reflect changes
        });
    } catch (error) {
        console.error("Error rejecting withdrawal:", error);
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Failed to reject withdrawal. Please try again later.",
        });
    }
};
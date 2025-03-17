document.addEventListener("DOMContentLoaded", () => {
    const tableBody = document.querySelector("#orders tbody");

    // Fetch withdrawal data from the server
    const fetchWithdrawals = async () => {
        try {
            const response = await fetch("https://warrencol.com/admin/assets/php/getWithdrawal.php");
            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || "Failed to fetch withdrawals");
            }

            // Clear existing table rows
            tableBody.innerHTML = "";

            // Populate the table with withdrawal data
            result.withdrawals.forEach(withdrawal => {
                const row = document.createElement("tr");
                row.classList.add("text-center");

                // Map withdrawal data to table columns
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

                tableBody.appendChild(row);
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
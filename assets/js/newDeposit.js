document.addEventListener("DOMContentLoaded", function () {
    const planContainer = document.getElementById("planContainer");

    // Fetch packages from the server
    fetch('assets/php/getPackages.php')
        .then(response => response.json())
        .then(plans => {
            plans.forEach((plan) => {
                // Determine button class, text, and disabled state
                const buttonClass = plan.disabled === 1 ? "eg-btn btn4" : "eg-btn btn3";
                const buttonText = plan.disabled === 1 ? "Disabled" : "Subscribe";
                const disabledAttribute = plan.disabled === 1 ? "disabled" : "";

                const planHTML = `
                    <div class="col">
                        <div class="partner-card">
                            <div class="invest-card-icon">
                                <img src="${plan.image_url}" alt="icon"/>
                            </div>
                            <h3 class="partner-card-text two mt-20">${plan.name}</h3>
                            <p class="plan-price">$${plan.price}</p>
                            <p class="plan-returns">Withdraw ${plan.daily_withdrawal_limit}% daily (${plan.validity_days} days validity)</p>
                            <button class="${buttonClass}" data-plan-id="${plan.id}" data-plan-name="${plan.name}" data-plan-price="${plan.price}" ${disabledAttribute}>
                                ${buttonText}
                            </button>
                        </div>
                    </div>
                `;
                planContainer.insertAdjacentHTML("beforeend", planHTML);
            });

            // Add event listener to handle package selection
            const subscribeButtons = document.querySelectorAll(".eg-btn.btn3");

            subscribeButtons.forEach((button) => {
                button.addEventListener("click", async () => {
                    // Disable all buttons and change the clicked button to "Loading"
                    const allButtons = document.querySelectorAll(".eg-btn.btn3, .eg-btn.btn4");
                    allButtons.forEach(btn => {
                        btn.disabled = true; // Disable all buttons
                    });

                    // Change the clicked button to "Loading"
                    button.textContent = "Loading...";

                    const selectedPlanId = button.getAttribute("data-plan-id");
                    const planTitle = button.getAttribute("data-plan-name");
                    const planPrice = button.getAttribute("data-plan-price");

                    try {
                        // Fetch admin wallet address
                        const walletResponse = await fetch("assets/php/getAdminWallet.php");
                        const walletData = await walletResponse.json();

                        if (!walletResponse.ok) {
                            throw new Error(walletData.error || "Failed to fetch admin wallet address");
                        }

                        const adminWalletAddress = walletData.wallet_address;

                        // Save payment details to the database without requiring a transaction hash
                        const paymentResponse = await fetch("assets/php/handlePayment.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                            },
                            body: JSON.stringify({
                                // user_id: 1, // Replace with actual user ID (e.g., from session)
                                package_id: selectedPlanId,
                                price: planPrice,
                                // currency: "USDT BSC", // Add currency type
                            }),
                        });

                        const paymentResult = await paymentResponse.json();

                        if (paymentResponse.ok) {
                            // Show success message with "Copy Wallet" button
                            Swal.fire({
                                icon: 'success',
                                title: 'Payment Initiated!',
                                html: `
                                    <p>Plan: ${planTitle}</p>
                                    <p>Amount: $${planPrice} (USDT BSC)</p>
                                    <p>Send payment to: <strong class='d-block' id="walletAddress">${adminWalletAddress}</strong></p>
                                    <p>After making the payment, copy your <strong>transaction hash</strong> and return to verify your payment.</p>
                                `,
                                confirmButtonText: 'Copy Wallet',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Copy wallet address to clipboard
                                    navigator.clipboard.writeText(adminWalletAddress).then(() => {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Wallet Copied!',
                                            timer: 1000,
                                            showConfirmButton: false
                                        });
                                    });
                                }
                            });
                        } else {
                            throw new Error(paymentResult.error || "Failed to initiate payment");
                        }
                    } catch (error) {
                        // Show error message using SweetAlert2
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: error.message,
                        });
                    } finally {
                        // Re-enable all buttons and revert the clicked button to "Subscribe"
                        allButtons.forEach(btn => {
                            btn.disabled = false; // Re-enable all buttons
                        });
                        button.textContent = "Subscribe"; // Revert the clicked button to "Subscribe"
                    }
                });
            });
        })
        .catch(error => {
            console.error('Error fetching packages:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to fetch packages. Please try again.',
            });
        });
});

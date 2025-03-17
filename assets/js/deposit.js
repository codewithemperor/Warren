document.addEventListener("DOMContentLoaded", function () {
    const planContainer = document.getElementById("planContainer");

    // Fetch packages from the server
    fetch('https://warrencol.com/assets/php/getPackages.php')
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
                        // Create payment request using NowPayments
                        const response = await fetch("https://warrencol.com/assets/php/createPayment.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                            },
                            body: JSON.stringify({
                                package_id: selectedPlanId,
                                price: planPrice,
                                planTitle: planTitle,
                            }),
                        });

                        const result = await response.json();

                        // Log the response to the console
                        console.log("API Response:", result);

                        if (response.ok) {
                            // Redirect the user to the NowPayments invoice page
                            if (result.invoice_url) {
                                window.location.href = result.invoice_url;
                            } else {
                                throw new Error("Invoice URL not found in response");
                            }
                        } else {
                            throw new Error(result.error || "Failed to create payment");
                        }
                    } catch (error) {
                        // Show error message using SweetAlert2
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: error.message,
                        });

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
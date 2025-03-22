document.addEventListener("DOMContentLoaded", () => {
    const amountInput = document.querySelector("input[name='amount']");
    const feeInput = document.querySelector("input[name='fee']");
    const withdrawalButton = document.querySelector("button[type='submit']");
    const buttonText = withdrawalButton.querySelector(".button-text");

    // Calculate withdrawal fee dynamically
    amountInput.addEventListener("input", () => {
        const amount = parseFloat(amountInput.value);
        if (!isNaN(amount) && amount > 0) {
            const fee = amount * 0; // 1.5% fee
            feeInput.value = fee.toFixed(8); // Display fee with 2 decimal places

            // Update button text
            const totalAmount = (amount + fee).toFixed(8);
            buttonText.textContent = `Withdraw $${totalAmount}`;
        } else {
            feeInput.value = ""; // Clear fee if amount is invalid
            buttonText.textContent = "Withdraw"; // Reset button text
        }
    });

    // Withdrawal form submission
    const withdrawalForm = document.querySelector(".document-form-wrap form");
    withdrawalForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        // Disable button and show loading state
        withdrawalButton.disabled = true;
        buttonText.textContent = "Processing...";
        const spinner = withdrawalButton.querySelector(".spinner-border");
        if (spinner) spinner.classList.remove("d-none");

        const formData = {
            amount: withdrawalForm.querySelector("input[name='amount']").value,
            usdt_address: withdrawalForm.querySelector("input[name='usdt_address']").value,
            withdrawal_password: withdrawalForm.querySelector("input[name='withdrawal_password']").value
        };

        try {
            const response = await fetch("assets/php/withdrawal.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();

            if (response.ok) {
                Swal.fire({
                    icon: "success",
                    title: "Withdrawal Successful!",
                    text: result.message,
                    timer: 3000,
                    showConfirmButton: false,
                    willClose: () => {
                        window.location.reload(); // Refresh the page
                    }
                });
            } else {
                throw new Error(result.message || "Withdrawal failed");
            }
        } catch (error) {
            Swal.fire({
                icon: "error",
                title: "Withdrawal Failed",
                text: error.message
            });
        } finally {
            // Re-enable button and reset state
            withdrawalButton.disabled = false;
            buttonText.textContent = "Withdraw";
            if (spinner) spinner.classList.add("d-none");
        }
    });
});
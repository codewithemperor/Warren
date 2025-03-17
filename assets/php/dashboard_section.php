<?php
// Start the session (if not already started)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database configuration file
require 'assets/php/config.php'; // Ensure this path is correct
require 'assets/php/earnings_functions.php'; // Include reusable functions

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get the logged-in user's ID from the session
$userId = $_SESSION['user']['id'];

try {
    // Enable PDO error reporting
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // TODAY'S EARNINGS CALCULATION
    // 1. Referral commissions from today's subscriptions
    $todayReferralEarnings = getTotalReferralEarnings($pdo, $userId, true); // true for today's earnings

    // 2. Today's task earnings
    $todayTaskEarnings = getTotalTaskEarnings($pdo, $userId, true); // true for today's earnings

    // 3. Fetch the active package's daily withdrawal limit and price
    $activePackage = getActivePackage($pdo, $userId);

    // Calculate package earnings
    $dailyPackageEarnings = 0;
    $totalPackageEarnings = 10;

    if ($activePackage) {
        // Daily earnings
        $dailyPackageEarnings = ($activePackage['price'] * $activePackage['daily_withdrawal_limit']) / 100;

        // Total package earnings
        $totalPackageEarnings = getTotalPackageEarnings($pdo, $userId);
    }

    // Calculate today's total earnings
    $todayEarnings = $todayReferralEarnings + $todayTaskEarnings + $dailyPackageEarnings;

    // TOTAL EARNINGS CALCULATION
    $totalReferralEarnings = getTotalReferralEarnings($pdo, $userId);
    $totalTaskEarnings = getTotalTaskEarnings($pdo, $userId);
    $totalWithdrawals = getTotalWithdrawals($pdo, $userId);

    // Calculate total earnings and available balance
    $totalEarnings = $totalReferralEarnings + $totalTaskEarnings + $totalPackageEarnings;
    $availableBalance = $totalEarnings - $totalWithdrawals;

    // Fetch the number of referrals
    $referralCount = getReferralCount($pdo, $userId);

    // Generate the referral URL
    $referralUrl = APP_URL . "/register.php?ref=" . $_SESSION['user']['referral_code'];

} catch (PDOException $e) {
    // Handle database errors
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    exit;
}
?>

<!-- Dashboard Section HTML -->
<section class="container pt-150 pb-40">
    <div class="row row-cols-1 row-cols-lg-4 gy-2">
        <!-- Today Earnings -->
        <div class="col">
            <div class="feature-card two">
                <div class="feature-card-icon">
                    <img src="assets/img/update/icon/feature-icon1-1.svg" alt="Icon"/>
                </div>
                <div class="feature-card-details">
                    <h4 class="feature-card-title">Today Earnings</h4>
                    <p class="feature-card-text">$<?php echo number_format($todayEarnings, 2); ?></p>
                </div>
            </div>
        </div>

        <!-- Active Package -->
        <div class="col">
            <div class="feature-card two">
                <div class="feature-card-icon">
                    <img src="assets/img/update/icon/feature-icon1-3.svg" alt="Icon"/>
                </div>
                <div class="feature-card-details">
                    <h4 class="feature-card-title">Active Package</h4>
                    <p class="feature-card-text"><?php echo $activePackage['name'] ?? 'No Active Package'; ?></p>
                </div>
            </div>
        </div>

        <!-- Number of Referrals -->
        <div class="col">
            <div class="feature-card two">
                <div class="feature-card-icon">
                    <img src="assets/img/update/icon/feature-icon1-2.svg" alt="Icon"/>
                </div>
                <div class="feature-card-details">
                    <h4 class="feature-card-title">Number of Referrals</h4>
                    <p class="feature-card-text"><?php echo $referralCount; ?></p>
                </div>
            </div>
        </div>

         <!-- Total Referral Earnings -->
         <div class="col">
            <div class="feature-card two">
                <div class="feature-card-icon">
                    <img src="assets/img/update/icon/feature-icon1-2.svg" alt="Icon"/>
                </div>
                <div class="feature-card-details">
                    <h4 class="feature-card-title">Referral Earnings </h4>
                    <p class="feature-card-text">$<?php echo number_format($totalReferralEarnings, 2); ?></p>
                </div>
            </div>
        </div>

        <!-- Total Task Earnings -->
        <div class="col">
            <div class="feature-card two">
                <div class="feature-card-icon">
                    <img src="assets/img/update/icon/feature-icon1-2.svg" alt="Icon"/>
                </div>
                <div class="feature-card-details">
                    <h4 class="feature-card-title">Total Task Earnings </h4>
                    <p class="feature-card-text">$<?php echo number_format($totalTaskEarnings, 2); ?></p>
                </div>
            </div>
        </div>

        <!-- Total Earnings -->
        <div class="col">
            <div class="feature-card two">
                <div class="feature-card-icon">
                    <img src="assets/img/update/icon/feature-icon1-2.svg" alt="Icon"/>
                </div>
                <div class="feature-card-details">
                    <h4 class="feature-card-title">Total Plan Commission</h4>
                    <p class="feature-card-text">$<?php echo number_format($totalPackageEarnings, 2); ?></p>
                </div>
            </div>
        </div>

        <!-- Total Withdrawals -->
        <div class="col">
            <div class="feature-card two">
                <div class="feature-card-icon">
                    <img src="assets/img/update/icon/feature-icon1-2.svg" alt="Icon"/>
                </div>
                <div class="feature-card-details">
                    <h4 class="feature-card-title">Total Withdrawals</h4>
                    <p class="feature-card-text">$<?php echo number_format($totalWithdrawals, 2); ?></p>
                </div>
            </div>
        </div>

        <!-- Available Balance -->
        <div class="col">
            <div class="feature-card two">
                <div class="feature-card-icon">
                    <img src="assets/img/update/icon/feature-icon1-2.svg" alt="Icon"/>
                </div>
                <div class="feature-card-details">
                    <h4 class="feature-card-title">Available Balance</h4>
                    <p class="feature-card-text">$<?php echo number_format($availableBalance, 2); ?></p>
                </div>
            </div>
        </div>

        
    </div>

    <!-- Referral URL Section -->
    <div class="footer-widget two widget-newsletter">
        <form class="newsletter-form col-12">
            <div class="form-group">
                <input disabled class="form-control" type="text" value="<?php echo $referralUrl; ?>" />
            </div>
            <button type="button" class="eg-btn btn5" onclick="copyReferralUrl()">Copy</button>
        </form>
    </div>
</section>

<!-- JavaScript for Copying Referral URL -->
<script>
function copyReferralUrl() {
    const referralUrl = document.querySelector('.newsletter-form input').value;
    navigator.clipboard.writeText(referralUrl).then(() => {
        Swal.fire({
            icon: 'success',
            title: 'Referral URL Copied!',
            text: 'The referral URL has been copied to your clipboard.',
            timer: 1500,
            showConfirmButton: false
        });
    }).catch(() => {
        Swal.fire({
            icon: 'error',
            title: 'Failed to Copy',
            text: 'Failed to copy the referral URL. Please try again.',
            timer: 1500,
            showConfirmButton: false
        });
    });
}
</script>
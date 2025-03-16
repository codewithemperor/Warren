<?php
function getTotalReferralEarnings($pdo, $userId, $todayOnly = false) {
    $query = "
        SELECT IFNULL(SUM(p.referral_bonus), 0)
        FROM subscriptions s
        JOIN packages p ON s.package_id = p.id
        JOIN referrals r ON s.user_id = r.referred_id
        WHERE r.referrer_id = :user_id
    ";
    if ($todayOnly) {
        $query .= " AND DATE(s.start_date) = CURDATE()";
    }
    $stmt = $pdo->prepare($query);
    $stmt->execute(['user_id' => $userId]);
    return $stmt->fetchColumn();
}

function getTotalTaskEarnings($pdo, $userId, $todayOnly = false) {
    $query = "
        SELECT IFNULL(SUM(t.task_price), 0)
        FROM user_tasks ut
        JOIN tasks t ON ut.task_id = t.id
        WHERE ut.user_id = :user_id
    ";
    if ($todayOnly) {
        $query .= " AND DATE(ut.completed_at) = CURDATE()";
    }
    $stmt = $pdo->prepare($query);
    $stmt->execute(['user_id' => $userId]);
    return $stmt->fetchColumn();
}

function getTotalPackageEarnings($pdo, $userId) {
    $query = "
        SELECT IFNULL(SUM(
            (p.price * p.daily_withdrawal_limit / 100) * 
            (DATEDIFF(CURDATE(), s.start_date) + 1) -- Add 1 to include the current date
        ), 0)
        FROM subscriptions s
        JOIN packages p ON s.package_id = p.id
        WHERE s.user_id = :user_id
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['user_id' => $userId]);
    return $stmt->fetchColumn();
}

function getTotalWithdrawals($pdo, $userId) {
    $query = "
        SELECT IFNULL(SUM(amount), 0)
        FROM withdrawals
        WHERE user_id = :user_id
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['user_id' => $userId]);
    return $stmt->fetchColumn();
}



function getReferralCount($pdo, $userId) {
    $query = "
        SELECT COUNT(*) 
        FROM referrals 
        WHERE referrer_id = :user_id
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['user_id' => $userId]);
    return $stmt->fetchColumn();
}

function getActivePackage($pdo, $userId) {
    $query = "
        SELECT p.*, 
               DATEDIFF(CURDATE(), DATE(s.start_date)) AS days_active
        FROM subscriptions s
        JOIN packages p ON s.package_id = p.id
        WHERE s.user_id = :user_id
        AND CURDATE() BETWEEN DATE(s.start_date) AND DATE(s.end_date)
        ORDER BY s.end_date DESC
        LIMIT 1
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['user_id' => $userId]);
    return $stmt->fetch();
}

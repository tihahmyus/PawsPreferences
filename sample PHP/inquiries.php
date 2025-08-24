<?php
session_start();
include('admin-sidebar.html');

// Ensure the admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: admin-login.php");
    exit;
}

$admin_name = $_SESSION['admin'];

// Include database connection
require_once 'dbconnect.php';

try {
    $query = "SELECT `inquiries`.`id`,
                              `inquiries`.`name`,
                              `inquiries`.`email`,
                              `inquiries`.`subject`,
                              `inquiries`.`comment`,
                              `inquiries`.`created_at`
                       FROM `petcare`.`inquiries`
                       ORDER BY `created_at` DESC";

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $inquiries = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Requests - MyPet</title>
    <link rel="stylesheet" href="admin-styles.css">
</head>
<body>

<div class="content">
    <h1>Welcome, <?= htmlspecialchars($admin_name); ?>!</h1>
    <h1>All Inquiries and Suggestions</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Comment</th>
                <th>Inquiry Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($inquiries as $inquiry): ?>
                <tr>
                    <td><?= htmlspecialchars($inquiry['id']) ?></td>
                    <td><?= htmlspecialchars($inquiry['name']) ?></td>
                    <td><?= htmlspecialchars($inquiry['email']) ?></td>
                    <td><?= htmlspecialchars($inquiry['subject']) ?></td>
                    <td><?= htmlspecialchars($inquiry['comment']) ?></td>
                    <td><?= htmlspecialchars($inquiry['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>

<?php
require "dbconnect.php";
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("
    SELECT ar.request_id, ar.message, ar.status, ar.request_date, p.name AS pet_name, p.species
    FROM adoption_requests ar
    JOIN pets p ON ar.pet_id = p.pet_id
    WHERE ar.user_id = ?
    ORDER BY ar.request_date DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

require "includes/header.php";
?>

<h2>My Adoption Requests</h2>

<table class="data-table">
    <tr>
        <th>Pet</th>
        <th>Species</th>
        <th>Message</th>
        <th>Status</th>
        <th>Date</th>
    </tr>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['pet_name']) ?></td>
                <td><?= htmlspecialchars($row['species']) ?></td>
                <td><?= htmlspecialchars($row['message']) ?></td>
                <td><span class="status-<?= htmlspecialchars($row['status']) ?>"><?= htmlspecialchars(ucfirst($row['status'])) ?></span></td>
                <td><?= htmlspecialchars($row['request_date']) ?></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="5">You haven't made any adoption requests yet.</td></tr>
    <?php endif; ?>
</table>

<?php require "includes/footer.php"; ?>

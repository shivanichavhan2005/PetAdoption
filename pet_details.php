<?php
require "dbconnect.php";
if (session_status() === PHP_SESSION_NONE) session_start();

$pet_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$message = "";

// Fetch pet
$stmt = $conn->prepare("SELECT * FROM pets WHERE pet_id = ?");
$stmt->bind_param("i", $pet_id);
$stmt->execute();
$pet = $stmt->get_result()->fetch_assoc();

if (!$pet) {
    require "includes/header.php";
    echo "<p>Pet not found.</p>";
    require "includes/footer.php";
    exit;
}

// Handle adoption request submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
    $user_id = $_SESSION['user_id'];
    $req_message = trim($_POST['message']);

    $insert = $conn->prepare("INSERT INTO adoption_requests (pet_id, user_id, message) VALUES (?, ?, ?)");
    $insert->bind_param("iis", $pet_id, $user_id, $req_message);
    if ($insert->execute()) {
        // Mark pet as pending so others see it's being considered
        $update = $conn->prepare("UPDATE pets SET status = 'pending' WHERE pet_id = ?");
        $update->bind_param("i", $pet_id);
        $update->execute();
        $message = "Your adoption request has been submitted!";
        $pet['status'] = 'pending';
    } else {
        $message = "Something went wrong submitting your request.";
    }
}

require "includes/header.php";
?>

<h2><?= htmlspecialchars($pet['name']) ?></h2>
<?php if ($message): ?><p class="alert success"><?= htmlspecialchars($message) ?></p><?php endif; ?>

<div class="pet-detail-card">
    <p><strong>Species:</strong> <?= htmlspecialchars($pet['species']) ?></p>
    <p><strong>Breed:</strong> <?= htmlspecialchars($pet['breed']) ?></p>
    <p><strong>Age:</strong> <?= (int)$pet['age'] ?> years</p>
    <p><strong>Gender:</strong> <?= htmlspecialchars($pet['gender']) ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars(ucfirst($pet['status'])) ?></p>
    <p><?= htmlspecialchars($pet['description']) ?></p>
</div>

<?php if ($pet['status'] === 'available'): ?>
    <?php if (isset($_SESSION['user_id'])): ?>
        <h3>Request to Adopt</h3>
        <form method="POST" class="form-card">
            <label>Why would you like to adopt <?= htmlspecialchars($pet['name']) ?>?</label>
            <textarea name="message" rows="4" required></textarea>
            <button type="submit">Submit Request</button>
        </form>
    <?php else: ?>
        <p><a href="login.php">Log in</a> to request adoption.</p>
    <?php endif; ?>
<?php else: ?>
    <p>This pet is currently <?= htmlspecialchars($pet['status']) ?> and not accepting new requests.</p>
<?php endif; ?>

<?php require "includes/footer.php"; ?>

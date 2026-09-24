<?php
require "dbconnect.php";
require "includes/header.php";

$sql = "SELECT * FROM pets WHERE status = 'available' ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<h2>Pets Available for Adoption</h2>

<div class="pet-grid">
<?php if ($result && $result->num_rows > 0): ?>
    <?php while ($pet = $result->fetch_assoc()): ?>
        <div class="pet-card">
            <h3><?= htmlspecialchars($pet['name']) ?></h3>
            <p><strong><?= htmlspecialchars($pet['species']) ?></strong> · <?= htmlspecialchars($pet['breed']) ?></p>
            <p>Age: <?= (int)$pet['age'] ?> yrs · <?= htmlspecialchars($pet['gender']) ?></p>
            <p><?= htmlspecialchars($pet['description']) ?></p>
            <a href="pet_details.php?id=<?= (int)$pet['pet_id'] ?>" class="btn">View / Adopt</a>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No pets available right now. Check back soon!</p>
<?php endif; ?>
</div>

<?php require "includes/footer.php"; ?>

<?php
require "dbconnect.php";
$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($full_name === "" || $email === "" || $password === "") {
        $error = "Please fill in all required fields.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        // Check if email already registered
        $check = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "An account with this email already exists.";
        } else {
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, phone, address, role) VALUES (?, ?, ?, ?, ?, 'user')");
            $stmt->bind_param("sssss", $full_name, $email, $hashed, $phone, $address);
            if ($stmt->execute()) {
                $success = "Registration successful! You can now log in.";
            } else {
                $error = "Something went wrong. Please try again.";
            }
            $stmt->close();
        }
        $check->close();
    }
}
require "includes/header.php";
?>

<h2>Create an Account</h2>

<?php if ($error): ?><p class="alert error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
<?php if ($success): ?><p class="alert success"><?= htmlspecialchars($success) ?></p><?php endif; ?>

<form method="POST" class="form-card">
    <label>Full Name *</label>
    <input type="text" name="full_name" required>

    <label>Email *</label>
    <input type="email" name="email" required>

    <label>Phone</label>
    <input type="text" name="phone">

    <label>Address</label>
    <input type="text" name="address">

    <label>Password *</label>
    <input type="password" name="password" required>

    <label>Confirm Password *</label>
    <input type="password" name="confirm_password" required>

    <button type="submit">Register</button>
</form>
<p>Already have an account? <a href="login.php">Login here</a></p>

<?php require "includes/footer.php"; ?>

<?php 
include 'config/db.php';
include 'includes/header.php';

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $row['role'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Authentication failed: Invalid password.";
        }
    } else {
        $error = "Authentication failed: User account not found.";
    }
}
?>

<div class="card">
    <h2>Sign In to Your Account</h2>
    <br>
    <?php if ($error): ?>
        <p style="color: #c0392b; font-weight: bold; margin-bottom: 15px;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form action="login.php" method="POST">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">Login</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
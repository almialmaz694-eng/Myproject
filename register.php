<?php 
include 'config/db.php';
include 'includes/header.php';

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $check->bind_param("ss", $username, $email);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        $error = "Registration failed: Username or Email is already registered.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, username, password, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $full_name, $email, $username, $password, $role);
        if ($stmt->execute()) {
            header("Location: login.php");
            exit;
        } else {
            $error = "System error during account creation.";
        }
    }
}
?>

<div class="card">
    <h2>Create an Account</h2>
    <p>Register as an Employer to post listings, or as a Job Seeker to apply for jobs.</p>
    <br>
    <?php if ($error): ?>
        <p style="color: #c0392b; font-weight: bold; margin-bottom: 15px;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form action="register.php" method="POST">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="full_name" required>
        </div>
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <div class="form-group">
            <label>Account Role</label>
            <select name="role" required>
                <option value="jobseeker">Job Seeker (Looking for Employment)</option>
                <option value="employer">Employer (Hiring & Posting Jobs)</option>
            </select>
        </div>
        <button type="submit">Create Account</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
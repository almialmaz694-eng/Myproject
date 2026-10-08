<?php 
include 'config/db.php';
include 'includes/header.php';

$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    if (!empty($name) && !empty($email) && !empty($message)) {
        $stmt = $conn->prepare("INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $message);
        if ($stmt->execute()) {
            $msg = "Thank you! Your inquiry has been stored successfully.";
        } else {
            $msg = "Error submitting contact request.";
        }
        $stmt->close();
    }
}
?>

<div class="card">
    <h2>Contact Us</h2>
    <p>Have questions, issues, or general feedback regarding the Job Portal platform? Submit a message below.</p>
    <br>
    <?php if ($msg): ?>
        <p style="color: #27ae60; font-weight: bold; margin-bottom: 15px;"><?php echo $msg; ?></p>
    <?php endif; ?>
    <form action="contact.php" method="POST">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name" required>
        </div>
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Message Content</label>
            <textarea name="message" rows="5" required></textarea>
        </div>
        <button type="submit">Submit Message</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
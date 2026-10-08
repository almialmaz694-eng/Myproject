<?php 
include 'config/db.php';
include 'includes/header.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['apply_job'])) {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'jobseeker') {
        $message = "You must be logged in as a Job Seeker to submit an application.";
    } else {
        $job_id = intval($_POST['job_id']);
        $jobseeker_id = $_SESSION['user_id'];
        
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_name = time() . "_" . basename($_FILES["resume"]["name"]);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["resume"]["tmp_name"], $target_file)) {
            $stmt = $conn->prepare("INSERT INTO applications (job_id, jobseeker_id, resume_file) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $job_id, $jobseeker_id, $file_name);
            if ($stmt->execute()) {
                $message = "Application and resume uploaded successfully!";
            } else {
                $message = "Database error processing your application.";
            }
            $stmt->close();
        } else {
            $message = "Failed to upload resume file.";
        }
    }
}

$result = $conn->query("SELECT jobs.*, users.full_name as employer_name FROM jobs JOIN users ON jobs.employer_id = users.id ORDER BY created_at DESC");
?>

<h2>Current Job Openings</h2>
<p style="margin-bottom: 15px;">Browse positions posted by registered companies and employers.</p>

<?php if ($message): ?>
    <div class="card" style="border-left: 5px solid #3498db; padding: 12px;">
        <p style="color: #2980b9; font-weight: bold; margin: 0;"><?php echo $message; ?></p>
    </div>
<?php endif; ?>

<?php if ($result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="card">
            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
            <p><strong>Company:</strong> <?php echo htmlspecialchars($row['company']); ?> | <strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?> <span class="badge">Posted by: <?php echo htmlspecialchars($row['employer_name']); ?></span></p>
            <p style="margin-top: 10px;"><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
            <br>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'jobseeker'): ?>
                <form action="jobs.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="job_id" value="<?php echo $row['id']; ?>">
                    <div class="form-group">
                        <label>Upload Your Resume (PDF or Doc format):</label>
                        <input type="file" name="resume" required>
                    </div>
                    <button type="submit" name="apply_job">Submit Application</button>
                </form>
            <?php elseif (!isset($_SESSION['user_id'])): ?>
                <p><em><a href="login.php">Log in</a> as a Job Seeker to apply for this vacancy.</em></p>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <div class="card"><p>No job listings are currently available in the database.</p></div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
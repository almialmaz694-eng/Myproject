<?php 
include 'config/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$msg = "";

if ($role === 'employer' && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post_job'])) {
    $title = trim($_POST['title']);
    $company = trim($_POST['company']);
    $location = trim($_POST['location']);
    $description = trim($_POST['description']);

    $stmt = $conn->prepare("INSERT INTO jobs (employer_id, title, company, location, description) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $title, $company, $location, $description);
    if ($stmt->execute()) {
        $msg = "New job vacancy published successfully!";
    }
}
?>

<h2>User Dashboard</h2>
<p style="margin-bottom: 20px;">Logged in as: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong> <span class="badge"><?php echo ucfirst($role); ?></span></p>

<?php if ($role === 'employer'): ?>
    <div class="card">
        <h3>Publish a New Job Listing</h3>
        <?php if ($msg): ?>
            <p style="color: #27ae60; font-weight: bold; margin-bottom: 15px;"><?php echo $msg; ?></p>
        <?php endif; ?>
        <form action="dashboard.php" method="POST">
            <div class="form-group">
                <label>Job Title</label>
                <input type="text" name="title" required>
            </div>
            <div class="form-group">
                <label>Company Name</label>
                <input type="text" name="company" required>
            </div>
            <div class="form-group">
                <label>Job Location</label>
                <input type="text" name="location" required>
            </div>
            <div class="form-group">
                <label>Position Description & Requirements</label>
                <textarea name="description" rows="5" required></textarea>
            </div>
            <button type="submit" name="post_job">Publish Vacancy</button>
        </form>
    </div>

    <div class="card">
        <h3>Your Posted Vacancies & Applications</h3>
        <?php
        $stmt = $conn->prepare("SELECT * FROM jobs WHERE employer_id = ? ORDER BY created_at DESC");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $my_jobs = $stmt->get_result();
        ?>
        <?php if ($my_jobs->num_rows > 0): ?>
            <?php while($job = $my_jobs->fetch_assoc()): ?>
                <div style="border-bottom: 1px solid #eee; padding: 10px 0;">
                    <h4><?php echo htmlspecialchars($job['title']); ?></h4>
                    <p><small>Location: <?php echo htmlspecialchars($job['location']); ?></small></p>
                    
                    <?php
                    $app_stmt = $conn->prepare("SELECT applications.*, users.full_name, users.email FROM applications JOIN users ON applications.jobseeker_id = users.id WHERE applications.job_id = ?");
                    $app_stmt->bind_param("i", $job['id']);
                    $app_stmt->execute();
                    $apps = $app_stmt->get_result();
                    ?>
                    <p><strong>Applicants (<?php echo $apps->num_rows; ?>):</strong></p>
                    <?php if ($apps->num_rows > 0): ?>
                        <ul>
                            <?php while($applicant = $apps->fetch_assoc()): ?>
                                <li>
                                    <?php echo htmlspecialchars($applicant['full_name']); ?> (<?php echo htmlspecialchars($applicant['email']); ?>) - 
                                    <a href="uploads/<?php echo urlencode($applicant['resume_file']); ?>" target="_blank">Download Resume</a>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <p><em>No applications submitted yet for this vacancy.</em></p>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>You have not published any job listings yet.</p>
        <?php endif; ?>
    </div>

<?php else: ?>
    <div class="card">
        <h3>Your Active Applications</h3>
        <?php 
        $stmt = $conn->prepare("SELECT jobs.title, jobs.company, jobs.location, applications.applied_at, applications.resume_file FROM applications JOIN jobs ON applications.job_id = jobs.id WHERE applications.jobseeker_id = ? ORDER BY applications.applied_at DESC");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $apps = $stmt->get_result();
        ?>
        <?php if ($apps->num_rows > 0): ?>
            <ul>
                <?php while($a = $apps->fetch_assoc()): ?>
                    <li style="margin-bottom: 12px;">
                        <strong><?php echo htmlspecialchars($a['title']); ?></strong> at <?php echo htmlspecialchars($a['company']); ?> (<?php echo htmlspecialchars($a['location']); ?>)<br>
                        <small>Submitted on: <?php echo $a['applied_at']; ?> | Attached file: <?php echo htmlspecialchars($a['resume_file']); ?></small>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>You have not submitted any job applications yet.</p>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
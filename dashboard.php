<?php
require_once 'includes/auth.php';

declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php'; // Add this line
require_once __DIR__ . '/includes/db.php';

// Rest of your dashboard code...
requireLogin();

// Get user files
global $pdo;
$stmt = $pdo->prepare("SELECT * FROM files WHERE user_id = ? ORDER BY uploaded_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$files = $stmt->fetchAll();

// Handle file deletion
if (isset($_GET['delete'])) {
    $fileId = $_GET['delete'];
    
    // Verify file belongs to user
    $stmt = $pdo->prepare("SELECT * FROM files WHERE id = ? AND user_id = ?");
    $stmt->execute([$fileId, $_SESSION['user_id']]);
    $file = $stmt->fetch();
    
    if ($file) {
        // Delete from filesystem
        $filepath = UPLOAD_DIR . $file['filename'];
        if (file_exists($filepath)) {
            unlink($filepath);
        }
        
        // Delete from database
        $stmt = $pdo->prepare("DELETE FROM files WHERE id = ?");
        $stmt->execute([$fileId]);
        
        $_SESSION['success'] = 'File deleted successfully';
        header('Location: dashboard.php');
        exit();
    }
}

include 'includes/header.php';
?>

<div class="container mt-5">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h2>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    
    <div class="card mb-4">
        <div class="card-header">
            <h4>Upload File</h4>
        </div>
        <div class="card-body">
            <form action="includes/upload.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="file" name="file" class="form-control-file" required>
                </div>
                <button type="submit" class="btn btn-primary">Upload</button>
            </form>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h4>Your Files</h4>
        </div>
        <div class="card-body">
            <?php if (empty($files)): ?>
                <p>No files uploaded yet.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Size</th>
                                <th>Uploaded</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($files as $file): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($file['original_name']); ?></td>
                                    <td><?php echo htmlspecialchars($file['type']); ?></td>
                                    <td><?php echo formatSize($file['size']); ?></td>
                                    <td><?php echo date('M j, Y H:i', strtotime($file['uploaded_at'])); ?></td>
                                    <td>
                                        <a href="download.php?id=<?php echo $file['id']; ?>" class="btn btn-sm btn-success">Download</a>
                                        <a href="dashboard.php?delete=<?php echo $file['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
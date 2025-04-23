<?php
require_once 'includes/auth.php';
requireLogin();

if (isset($_GET['id'])) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM files WHERE id = ? AND user_id = ?");
    $stmt->execute([$_GET['id'], $_SESSION['user_id']]);
    $file = $stmt->fetch();
    
    if ($file) {
        $filepath = UPLOAD_DIR . $file['filename'];
        
        if (file_exists($filepath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: ' . $file['type']);
            header('Content-Disposition: attachment; filename="' . $file['original_name'] . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filepath));
            readfile($filepath);
            exit;
        }
    }
}

$_SESSION['error'] = 'File not found';
header('Location: dashboard.php');
exit();
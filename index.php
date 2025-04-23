<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';

// Redirect to dashboard if already logged in
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit();
}

include __DIR__ . '/includes/header.php';
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h3><i class="fas fa-cloud-upload-alt me-2"></i>HASS & MUBZ File Manager</h3>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                    <img src="assets/images/extension_icon.png" alt="" alt="Upload Icon" style="height: 100px;" class="mb-3">                    >
                                                <h4>Welcome to Your Secure File Management System</h4>
                        <p class="text-muted">Please sign in or register to continue</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="d-grid gap-2">
                                <a href="login.php" class="btn btn-success btn-lg">
                                    <i class="fas fa-sign-in-alt me-2"></i>Login
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-grid gap-2">
                                <a href="register.php" class="btn btn-primary btn-lg">
                                    <i class="fas fa-user-plus me-2"></i>Register
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center text-muted">
                    <small>Secure file storage powered by PHP & MySQL</small>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include __DIR__ . '/includes/footer.php';
?>
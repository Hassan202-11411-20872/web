<?php
try {
    require_once __DIR__ . '/includes/config.php';
    require_once __DIR__ . '/includes/auth.php';

    if (isLoggedIn()) {
        header('Location: dashboard.php');
        exit();
    }

    $error = '';
    $formData = ['name' => '', 'email' => ''];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $formData = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? '')
        ];
        
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        try {
            // Validate inputs
            if (empty($formData['name']) || empty($formData['email']) || empty($password)) {
                throw new InvalidArgumentException("All fields are required");
            }

            if (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
                throw new InvalidArgumentException("Invalid email format");
            }

            if ($password !== $confirm_password) {
                throw new InvalidArgumentException("Passwords don't match");
            }

            if (strlen($password) < 8) {
                throw new InvalidArgumentException("Password must be at least 8 characters");
            }

            // Attempt registration
            $user_id = register($formData['name'], $formData['email'], $password);
            
            $_SESSION['registration_success'] = true;
            header('Location: login.php?registered=1');
            exit();
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }

    include __DIR__ . '/includes/header.php';
    ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h4><i class="fas fa-user-plus me-2"></i>Register</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_GET['registered'])): ?>
                            <div class="alert alert-success">Registration successful! Please login.</div>
                        <?php endif; ?>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" novalidate>
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?= htmlspecialchars($formData['name']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                       value="<?= htmlspecialchars($formData['email']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm_password" 
                                       name="confirm_password" required>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Register</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <small>Already have an account? <a href="login.php">Login here</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php 
    include __DIR__ . '/includes/footer.php';
} catch (Throwable $e) {
    error_log("Registration page error: " . $e->getMessage());
    die("An error occurred. Please try again later.");
}
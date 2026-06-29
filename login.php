<?php
session_start();
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — EMS</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="container">
        <div class="login-container">
            <div class="login-logo">🏢</div>
            <h2 style="text-align: center; color: #2c3e50; margin-bottom: 10px;">Employee Management</h2>
            <p style="text-align: center; color: #7f8c8d; margin-bottom: 30px;">Admin Login</p>

            <?php if (isset($_SESSION['login_error'])): ?>
                <div class="status error"><?php echo $_SESSION['login_error']; unset($_SESSION['login_error']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['logout_msg'])): ?>
                <div class="status success"><?php echo $_SESSION['logout_msg']; unset($_SESSION['logout_msg']); ?></div>
            <?php endif; ?>

            <form action="authenticate.php" method="post" id="loginForm">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" id="username" placeholder="admin">
                    <div class="error-msg" id="usernameError">Username is required</div>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" id="password" placeholder="••••••">
                    <div class="error-msg" id="passwordError">Password is required</div>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Sign In</button>
            </form>

            <div style="text-align: center; margin-top: 20px; color: #7f8c8d; font-size: 0.9rem;">
                <p>Demo: admin / admin123</p>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            let valid = true;
            const u = document.getElementById('username');
            const p = document.getElementById('password');
            
            if (!u.value.trim()) {
                document.getElementById('usernameError').classList.add('show');
                u.classList.add('error');
                valid = false;
            } else {
                document.getElementById('usernameError').classList.remove('show');
                u.classList.remove('error');
            }
            
            if (!p.value.trim()) {
                document.getElementById('passwordError').classList.add('show');
                p.classList.add('error');
                valid = false;
            } else {
                document.getElementById('passwordError').classList.remove('show');
                p.classList.remove('error');
            }
            
            if (!valid) e.preventDefault();
        });
    </script>

</body>
</html>

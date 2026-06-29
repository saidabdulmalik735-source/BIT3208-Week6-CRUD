<?php
session_start();
$_SESSION = array();
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}
session_destroy();
session_start();
$_SESSION['logout_msg'] = "You have been logged out successfully.";
header("Location: login.php");
exit();
?>

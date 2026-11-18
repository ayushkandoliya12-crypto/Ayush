<?php
// Session ko start karein agar pehle se active nahi hai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Saare session variables ko unregister/clear karein
$_SESSION = array();

// Agar session cookie ka use ho raha hai, toh use delete/expire karein
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Session ko completely destroy karein
session_destroy();

// User ko successful logout ke baad homepage (index.php) par redirect karein
header("Location: index.php");
exit();
?>
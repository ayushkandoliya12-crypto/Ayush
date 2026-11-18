<?php
session_start();

$_SESSION = [];
if (session_id() !== '') {
    session_unset();
    session_destroy();
}
header("Location: index.php");
exit();

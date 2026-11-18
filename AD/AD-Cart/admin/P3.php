<?php
    $cn = mysqli_connect("localhost", "root", "", "project") or die("Database connection failed: " . mysqli_connect_error());
    mysqli_set_charset($cn, "utf8");
?>

   
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SESSION['user_id'] >= 1 && $_SESSION['user_id'] <= 3) {
    include 'items/navbar_admin.php';
} else {
    include 'items/navbar_user.php';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Notas</title>
</head>
<body>
<h1>Notas</h1>
</body>
</html>
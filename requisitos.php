
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Requisitos</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <?php 
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    // Determinar qué navbar incluir
    if ($_SESSION['user_id'] >= 1 && $_SESSION['user_id'] <= 3) {
        include 'items/navbar_admin.php';
    } else {
        include 'items/navbar_user.php';
    }
    
    
    ?>
    <h1>Requisitos</h1>
</body>
</html>
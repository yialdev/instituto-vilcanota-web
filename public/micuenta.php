<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/login.php");
    exit();
}

require_once '../config.php';

// Fetch user data based on session ID
$user_id = $_SESSION['user_id'];
$sql = "SELECT username FROM usuarios WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Determine which navbar to include
if ($_SESSION['user_id'] >= 1 && $_SESSION['user_id'] <= 3) {
    include '../admin/includes/navbar_admin.php';
} else {
    include '../user/includes/navbar_user.php';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Cuenta</title>
    <link rel="stylesheet" type="text/css" href="../public/css/styles.css">
    <link rel="stylesheet" type="text/css" href="../public/css/perfil_cuenta.css">
</head>
<body>
    <h1>Mi Cuenta</h1>
    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-info">
                <h2><?php echo htmlspecialchars($user['username']); ?></h2>
                <p>12345678</p>
                <p>juan_perez@example.com</p>
                <p>987654321</p>
            </div>
        </div>
        <div class="profile-details" style="display: flex; justify-content: center;">
            <div class="profile-column">
                <h3>Datos Personales</h3>
                <p><strong>Fecha de Nacimiento:</strong> 15 de mayo de 1995</p>
                <p><strong>Género:</strong> Masculino</p>
                <p><strong>Dirección:</strong> Av. Siempre Viva 123</p>
            </div>
            <div class="profile-column">
                <h3>Historial Académico</h3>
                <p><strong>Institución:</strong> Universidad Nacional</p>
                <p><strong>Carrera:</strong> Ingeniería Civil</p>
                <p><strong>Fecha de Ingreso:</strong> Marzo 2014</p>
                <p><strong>Fecha de Egreso:</strong> Diciembre 2019</p>
                <p><strong>Promedio:</strong> 16.8</p>
            </div>
        </div>
    </div>
</body>
</html>
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'config.php';

// Fetch user data from database
$user_id = $_SESSION['user_id'];
$sql = "SELECT username, rol, id_relacionado, id_admin FROM usuarios WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Initialize variables
$student = null;
$admin = null;
$person = null;

// Fetch related data based on which ID is present
if ($user['id_relacionado']) {
    $sql = "SELECT codigo_estudiante, dni, nombres, apellidos, genero, edad, estado_academico 
            FROM estudiantes WHERE id_estudiante = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user['id_relacionado']);
    $stmt->execute();
    $student = $result = $stmt->get_result()->fetch_assoc();
    $person = $student;
} elseif ($user['id_admin']) {
    $sql = "SELECT nombres, apellidos, genero, dni, edad, fecha_ingreso 
            FROM admins WHERE id_admin = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user['id_admin']);
    $stmt->execute();
    $admin = $result = $stmt->get_result()->fetch_assoc();
    $person = $admin;
}

// Determine which navbar to include
if ($_SESSION['user_id'] >= 1 && $_SESSION['user_id'] <= 3) {
    include 'items/navbar_admin.php';
} else {
    include 'items/navbar_user.php';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Cuenta</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <div class="main-content">
        <h1>Mi Cuenta</h1>
        
        <div class="career-item">
            <div class="institute-intro">
                <h2><?php 
                    if ($person) {
                        echo htmlspecialchars($person['nombres']) . " " . htmlspecialchars($person['apellidos']). "<br><br> DNI: " . htmlspecialchars($person['dni']);
                    } else {
                        echo "Not Found";
                    }
                ?></h2>
            </div>
            
            <div class="profile-container">
                <table class="profile-table">
                    <tr>
                        <td>Nombre de Usuario</td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                    </tr>
                    <tr>
                        <td>Rol</td>
                        <td><?php echo htmlspecialchars($user['rol']); ?></td>
                    </tr>
                    <?php if ($student): ?>
                        <tr>
                            <td>Nombre</td>
                            <td><?php echo htmlspecialchars($student['nombres']); ?></td>
                        </tr>
                        <tr>
                            <td>Apellidos</td>
                            <td><?php echo htmlspecialchars($student['apellidos']); ?></td>
                        </tr>
                        <tr>
                            <td>DNI</td>
                            <td><?php echo htmlspecialchars($student['dni']); ?></td>
                        </tr>
                        <tr>
                            <td>Código de Estudiante</td>
                            <td><?php echo htmlspecialchars($student['codigo_estudiante']); ?></td>
                        </tr>
                        <tr>
                            <td>Género</td>
                            <td><?php echo htmlspecialchars($student['genero']); ?></td>
                        </tr>
                        <tr>
                            <td>Edad</td>
                            <td><?php echo htmlspecialchars($student['edad']); ?></td>
                        </tr>
                        <tr>
                            <td>Estado Académico</td>
                            <td><?php echo htmlspecialchars($student['estado_academico']); ?></td>
                        </tr>
                    <?php elseif ($admin): ?>
                        <tr>
                            <td>Nombre</td>
                            <td><?php echo htmlspecialchars($admin['nombres']); ?></td>
                        </tr>
                        <tr>
                            <td>Apellidos</td>
                            <td><?php echo htmlspecialchars($admin['apellidos']); ?></td>
                        </tr>
                        <tr>
                            <td>DNI</td>
                            <td><?php echo htmlspecialchars($admin['dni']); ?></td>
                        </tr>
                        <tr>
                            <td>Género</td>
                            <td><?php echo htmlspecialchars($admin['genero']); ?></td>
                        </tr>
                        <tr>
                            <td>Edad</td>
                            <td><?php echo htmlspecialchars($admin['edad']); ?></td>
                        </tr>
                        <tr>
                            <td>Fecha de Ingreso</td>
                            <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($admin['fecha_ingreso']))); ?></td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
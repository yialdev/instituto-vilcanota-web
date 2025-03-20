<?php
require_once 'config_titulacion.php';

// Add delete handling at the top
if (isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];
    $delete_sql = "DELETE FROM estudiantes WHERE id_estudiante = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: actas_titulacion.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actas de Titulación - Lista de Estudiantes</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        include 'items/navbar.php';
    } else {
        // Determinar qué navbar incluir
        if ($_SESSION['user_id'] >= 1 && $_SESSION['user_id'] <= 3) {
            include 'items/navbar_admin.php';
        } else { 
            include 'items/navbar_user.php';
        }
    }
    ?>

    <div class="main-content">
        <h1>Acta de Titulación - Información del Estudiante</h1>
        
        <div class="table-container">
            <?php
            $sql = "SELECT e.*, 
                    p.nombre as programa,
                    j1.nombre as jurado1_nombre,
                    j2.nombre as jurado2_nombre,
                    j3.nombre as jurado3_nombre
                    FROM estudiantes e 
                    LEFT JOIN programas p ON e.id_programa = p.id_programa
                    LEFT JOIN jurados j1 ON e.id_jurado_1 = j1.id_jurado
                    LEFT JOIN jurados j2 ON e.id_jurado_2 = j2.id_jurado
                    LEFT JOIN jurados j3 ON e.id_jurado_3 = j3.id_jurado";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    ?>
                    <table class="solicitudes-table acta-table">
                        <tr>
                            <td><strong>ID</strong></td>
                            <td><?php echo $row["id_estudiante"]; ?></td>
                            <td><strong>Edad</strong></td>
                            <td><?php echo $row["edad"]; ?></td>
                            <td><strong>DNI</strong></td>
                            <td><?php echo $row["dni"]; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Nombre</strong></td>
                            <td><?php echo $row["nombre"]; ?></td>
                            <td><strong>Jurado 1</strong></td>
                            <td><?php echo $row["jurado1_nombre"]; ?></td>
                            <td><strong>Nota</strong></td>
                            <td><?php echo $row["nota_1"]; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Apellido</strong></td>
                            <td><?php echo $row["apellido"]; ?></td>
                            <td><strong>Jurado 2</strong></td>
                            <td><?php echo $row["jurado2_nombre"]; ?></td>
                            <td><strong>Nota</strong></td>
                            <td><?php echo $row["nota_2"]; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Género</strong></td>
                            <td><?php echo $row["genero"]; ?></td>
                            <td><strong>Jurado 3</strong></td>
                            <td><?php echo $row["jurado3_nombre"]; ?></td>
                            <td><strong>Nota</strong></td>
                            <td><?php echo $row["nota_3"]; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Programa</strong></td>
                            <td colspan="3"><?php echo $row["programa"]; ?></td>
                            <td><strong>Promedio</strong></td>
                            <td><?php echo $row["nota_final"]; ?></td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-center">
                                <a href='acta.php?id=<?php echo $row["id_estudiante"]; ?>' class='btn-ver-acta'>
                                    <i class='fas fa-file-alt'></i> Ver Acta
                                </a>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('¿Está seguro de eliminar este registro?');">
                                    <input type="hidden" name="delete_id" value="<?php echo $row["id_estudiante"]; ?>">
                                    <button type="submit" class="delete-btn">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    </table>
                    <?php
                }
            } else {
                echo "<p class='status-message'>No se encontraron estudiantes</p>";
            }
            ?>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</body>
</html>

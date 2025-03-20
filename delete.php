<?php
session_start();
require_once 'config.php';

$mensaje = '';
$resultados = [];
$tipo_busqueda = isset($_POST['tipo_busqueda']) ? $_POST['tipo_busqueda'] : '';

// Buscar
if (isset($_POST['buscar'])) {
    $busqueda = $_POST['termino_busqueda'];
    
    switch($tipo_busqueda) {
        case 'users':
            $sql = "SELECT u.*, a.nombres as admin_nombre, a.apellidos as admin_apellidos 
                   FROM usuarios u 
                   LEFT JOIN admins a ON u.id_admin = a.id_admin 
                   WHERE u.username LIKE ?";
            $busqueda = "%$busqueda%";
            break;
        case 'estudiantes':
            $sql = "SELECT * FROM estudiantes WHERE nombres LIKE ? OR apellidos LIKE ?";
            $busqueda = "%$busqueda%";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $busqueda, $busqueda);
            break;
        case 'personal':
            $sql = "SELECT * FROM admins WHERE nombres LIKE ? OR apellidos LIKE ?";
            $busqueda = "%$busqueda%";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $busqueda, $busqueda);
            break;
    }
    
    if ($sql) {
        if ($tipo_busqueda == 'users') {
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $busqueda);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $resultados[] = $row;
            }
        } else {
            $mensaje = "<span style='color: red;'>No se encontraron resultados</span>";
        }
    }
}

// Eliminar
if (isset($_POST['eliminar'])) {
    $id = $_POST['id'];
    $tabla = $_POST['tabla'];
    
    switch($tabla) {
        case 'users':
            $sql = "DELETE FROM usuarios WHERE id_usuario = ?";
            $id_field = "id_usuario";
            break;
        case 'estudiantes':
            $sql = "DELETE FROM estudiantes WHERE id_estudiante = ?";
            $id_field = "id_estudiante";
            break;
        case 'personal':
            $sql = "DELETE FROM admins WHERE id_admin = ?";
            $id_field = "id_admin";
            break;
    }
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $mensaje = "<span style='color: green;'>Registro eliminado exitosamente</span>";
    } else {
        $mensaje = "<span style='color: red;'>Error al eliminar el registro</span>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Eliminar Registro</title>
    <style>
        .container {
            width: 50%;
            margin: 20px auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .user-info {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .delete-btn {
            background-color: #ff4444;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .delete-btn:hover {
            background-color: #cc0000;
        }
    </style>
</head>
<body>
    <?php include 'items/navbar_admin.php'; ?>
    
    <div class="container">
        <h2>BUSCAR Y ELIMINAR REGISTRO</h2>
        <?php if ($mensaje) echo "<p>$mensaje</p>"; ?>

        <form method="POST">
            <div class="form-group">
                <label>Buscar en:</label>
                <select name="tipo_busqueda" required>
                    <option value="">Seleccione una opción</option>
                    <option value="users" <?php echo $tipo_busqueda == 'users' ? 'selected' : ''; ?>>Usuarios</option>
                    <option value="estudiantes" <?php echo $tipo_busqueda == 'estudiantes' ? 'selected' : ''; ?>>Estudiantes</option>
                    <option value="personal" <?php echo $tipo_busqueda == 'personal' ? 'selected' : ''; ?>>Personal</option>
                </select>
            </div>
            <div class="form-group">
                <label>T��rmino de búsqueda:</label>
                <input type="text" name="termino_busqueda" required>
                <input type="submit" name="buscar" value="Buscar" class="submit-btn" style="width: auto;">
            </div>
        </form>

        <?php if (!empty($resultados)): ?>
            <div class="user-info">
                <h3>Resultados de la búsqueda</h3>
                <div class="table-responsive">
                    <table class="results-table">
                        <thead>
                            <tr>
                                <?php if ($tipo_busqueda == 'users'): ?>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Rol</th>
                                    <th>ID Relacionado</th>
                                    <th>Admin Asignado</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                <?php elseif ($tipo_busqueda == 'estudiantes'): ?>
                                    <th>ID</th>
                                    <th>Código</th>
                                    <th>DNI</th>
                                    <th>Nombres</th>
                                    <th>Apellidos</th>
                                    <th>Género</th>
                                    <th>Edad</th>
                                    <th>Estado Académico</th>
                                    <th>Acciones</th>
                                <?php else: ?>
                                    <th>ID</th>
                                    <th>Nombres</th>
                                    <th>Apellidos</th>
                                    <th>Género</th>
                                    <th>DNI</th>
                                    <th>Edad</th>
                                    <th>Fecha de Ingreso</th>
                                    <th>Acciones</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($resultados as $resultado): ?>
                                <tr>
                                    <?php if ($tipo_busqueda == 'users'): ?>
                                        <td><?php echo $resultado['id_usuario']; ?></td>
                                        <td><?php echo $resultado['username']; ?></td>
                                        <td><?php echo $resultado['rol']; ?></td>
                                        <td><?php echo $resultado['id_relacionado']; ?></td>
                                        <td><?php echo $resultado['admin_nombre'] . ' ' . $resultado['admin_apellidos']; ?></td>
                                        <td><?php echo $resultado['estado'] ? 'Activo' : 'Inactivo'; ?></td>
                                    <?php elseif ($tipo_busqueda == 'estudiantes'): ?>
                                        <td><?php echo $resultado['id_estudiante']; ?></td>
                                        <td><?php echo $resultado['codigo_estudiante']; ?></td>
                                        <td><?php echo $resultado['dni']; ?></td>
                                        <td><?php echo $resultado['nombres']; ?></td>
                                        <td><?php echo $resultado['apellidos']; ?></td>
                                        <td><?php echo $resultado['genero']; ?></td>
                                        <td><?php echo $resultado['edad']; ?></td>
                                        <td><?php echo $resultado['estado_academico']; ?></td>
                                    <?php else: ?>
                                        <td><?php echo $resultado['id_admin']; ?></td>
                                        <td><?php echo $resultado['nombres']; ?></td>
                                        <td><?php echo $resultado['apellidos']; ?></td>
                                        <td><?php echo $resultado['genero']; ?></td>
                                        <td><?php echo $resultado['dni']; ?></td>
                                        <td><?php echo $resultado['edad']; ?></td>
                                        <td><?php echo date('Y-m-d H:i:s', strtotime($resultado['fecha_ingreso'])); ?></td>
                                    <?php endif; ?>
                                    <td>
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('¿Está seguro de que desea eliminar este registro?');">
                                            <input type="hidden" name="id" value="<?php echo $resultado[array_key_first($resultado)]; ?>">
                                            <input type="hidden" name="tabla" value="<?php echo $tipo_busqueda; ?>">
                                            <input type="submit" name="eliminar" value="Eliminar" class="delete-btn">
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <style>
        .results-table th, .results-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .results-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .results-table th {
            background-color: #4CAF50;
            color: white;
        }
        .delete-btn {
            background-color: #ff4444;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .table-responsive {
            overflow-x: auto;
            margin-top: 20px;
        }
        .results-table {
            min-width: 100%;
            white-space: nowrap;
        }
        .results-table th {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
        }
        .results-table td {
            padding: 8px;
        }
    </style>
</body>
</html>
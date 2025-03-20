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

include 'config.php';

// Handle status updates
if (isset($_POST['action'])) {
    $id = $_POST['id_solicitud'];
    $newStatus = ($_POST['action'] === 'accept') ? 'procesado' : 'rechazado';
    
    $sql = "UPDATE solicitudes SET estado = ? WHERE id_solicitud = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $newStatus, $id);
    
    if ($stmt->execute()) {
        $message = "Estado actualizado a: " . $newStatus;
    } else {
        $message = "Error al actualizar el estado";
    }
}

// Handle file download
if(isset($_GET['download'])) {
    $id = $_GET['id'];
    $type = $_GET['type'];
    
    $sql = "SELECT nombre, $type FROM solicitudes WHERE id_solicitud = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($row = $result->fetch_assoc()) {
        $filename = $row['nombre'] . '_' . $type . '.pdf';
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        echo $row[$type];
        exit();
    }
}

// Fetch all solicitudes
$sql = "SELECT * FROM solicitudes ORDER BY id_solicitud DESC";
$result = $conn->query($sql);

// Helper function to create document link
function createDocumentLink($id, $fieldName, $value) {
    if ($value) {
        return "<a href='?download=1&id={$id}&type={$fieldName}' class='file-icon' title='Descargar {$fieldName}'>📄</a>";
    }
    return "<span>No disponible</span>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Solicitudes</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .file-icon { 
            text-decoration: none; 
            padding: 5px;
            background: #f0f0f0;
            border-radius: 4px;
            margin: 2px;
        }
        .file-icon:hover {
            background: #e0e0e0;
        }
        .solicitudes-table {
            width: 100%;
            overflow-x: auto;
            font-size: 14px;
        }
        .solicitudes-table th, .solicitudes-table td {
            padding: 8px;
            text-align: center;
            border: 1px solid #ddd;
        }
        .solicitudes-table th {
            background-color: #103c64;
        }
        .accept-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            margin: 2px;
        }
        .reject-btn {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            margin: 2px;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <h1>Lista de Solicitudes</h1>
        
        <?php if (isset($message)): ?>
            <div class="alert-warning"><?php echo $message; ?></div>
        <?php endif; ?>

        <div class="table-container">
            <table class="solicitudes-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>DNI</th>
                        <th>Programa</th>
                        <th>Modalidad</th>
                        <th>Solicitud</th>
                        <th>Partida</th>
                        <th>Cert. Secundaria</th>
                        <th>DNI Copy</th>
                        <th>Cert. Superior</th>
                        <th>Resoluciones</th>
                        <th>Cert. Egresado</th>
                        <th>Ficha Seguimiento</th>
                        <th>Const. Egreso</th>
                        <th>Const. Ética</th>
                        <th>Const. No Deuda</th>
                        <th>Actas Prácticas</th>
                        <th>Const. Práct. Empresa</th>
                        <th>Const. Práct. Coord</th>
                        <th>Cert. Modular</th>
                        <th>Acta Idioma</th>
                        <th>Cert. Idioma</th>
                        <th>Voucher Sustentación</th>
                        <th>Voucher Medalla</th>
                        <th>Voucher Acta</th>
                        <th>Fotos</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id_solicitud']; ?></td>
                        <td><?php echo $row['nombre']; ?></td>
                        <td><?php echo $row['dni']; ?></td>
                        <td><?php echo $row['programa']; ?></td>
                        <td><?php echo $row['modalidad']; ?></td>
                        <td><?php echo createDocumentLink($row['id_solicitud'], 'solicitud', $row['solicitud']); ?></td>
                        <td><?php echo createDocumentLink($row['id_solicitud'], 'partida', $row['partida']); ?></td>
                        <td><?php echo createDocumentLink($row['id_solicitud'], 'cert_secundaria', $row['cert_secundaria']); ?></td>
                        <td><?php echo createDocumentLink($row['id_solicitud'], 'dni_copy', $row['dni_copy']); ?></td>
                        <td><?php echo createDocumentLink($row['id_solicitud'], 'cert_superior', $row['cert_superior']); ?></td>
                        <td><?php echo createDocumentLink($row['id_solicitud'], 'resoluciones', $row['resoluciones']); ?></td>
                        <td><?php echo createDocumentLink($row['id_solicitud'], 'cert_egresado', $row['cert_egresado']); ?></td>
                        <td><?php echo createDocumentLink($row['id_solicitud'], 'ficha_seguimiento', $row['ficha_seguimiento']); ?></td>
                        <td><?php echo createDocumentLink($row['id_solicitud'], 'const_egreso', $row['const_egreso']); ?></td>
                        <td><?php echo createDocumentLink($row['id_solicitud'], 'const_etica', $row['const_etica']); ?></td>
                        <td><?php echo createDocumentLink($row['id_solicitud'], 'const_nodeuda', $row['const_nodeuda']); ?></td>
                        <td><?php echo createDocumentLink($row['id_solicitud'], 'actas_practicas', $row['actas_practicas']); ?></td>
                        <td><?php echo createDocumentLink($row['id_solicitud'], 'const_practicas_empresa', $row['const_practicas_empresa']); ?></td>
                        <td><?php echo createDocumentLink($row['id_solicitud'], 'const_practicas_coord', $row['const_practicas_coord']); ?></td>
                        <td><?php echo createDocumentLink($row['id_solicitud'], 'cert_modular', $row['cert_modular']); ?></td>
                        <td><?php echo createDocumentLink($row['id_solicitud'], 'acta_idioma', $row['acta_idioma']); ?></td>
                        <td><?php echo createDocumentLink($row['id_solicitud'], 'cert_idioma', $row['cert_idioma']); ?></td>
                        <td><?php echo createDocumentLink($row['id_solicitud'], 'voucher_sustentacion', $row['voucher_sustentacion']); ?></td>
                        <td><?php echo createDocumentLink($row['id_solicitud'], 'voucher_medalla', $row['voucher_medalla']); ?></td>
                        <td><?php echo createDocumentLink($row['id_solicitud'], 'voucher_acta', $row['voucher_acta']); ?></td>
                        <td><?php echo createDocumentLink($row['id_solicitud'], 'fotos', $row['fotos']); ?></td>
                        <td><?php echo $row['estado']; ?></td>
                        <td class="actions">
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="id_solicitud" value="<?php echo $row['id_solicitud']; ?>">
                                <button type="submit" name="action" value="accept" class="accept-btn" 
                                    onclick="return confirm('¿Está seguro de aceptar esta solicitud?')">✓</button>
                                <button type="submit" name="action" value="reject" class="reject-btn" 
                                    onclick="return confirm('¿Está seguro de rechazar esta solicitud?')">✗</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
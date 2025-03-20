
<?php
require_once 'config_titulacion.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $sql = "SELECT foto FROM estudiantes WHERE id_estudiante = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($imagen);
    $stmt->fetch();

    if ($imagen) {
        header("Content-Type: image/jpeg");
        header("Content-Disposition: attachment; filename=estudiante_" . $id . ".jpg");
        header("Content-Length: " . strlen($imagen));
        echo $imagen;
    }
    $stmt->close();
}
$conn->close();
?>
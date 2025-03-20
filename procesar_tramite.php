<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $nombre = $_POST['nombre'];
    $dni = $_POST['dni'];
    $programa = $_POST['programa'];
    $modalidad = $_POST['modalidad'];
    
    // Function to process file uploads
    function processUploadedFile($file) {
        if (isset($file) && $file['error'] == 0) {
            return file_get_contents($file['tmp_name']);
        }
        return null;
    }

    // Process all file uploads
    $solicitud = processUploadedFile($_FILES['solicitud']);
    $partida = processUploadedFile($_FILES['partida']);
    $cert_secundaria = processUploadedFile($_FILES['cert_secundaria']);
    $dni_copy = processUploadedFile($_FILES['dni_copy']);
    $cert_superior = processUploadedFile($_FILES['cert_superior']);
    $resoluciones = processUploadedFile($_FILES['resoluciones']);
    $cert_egresado = processUploadedFile($_FILES['cert_egresado']);
    $ficha_seguimiento = processUploadedFile($_FILES['ficha_seguimiento']);
    $const_egreso = processUploadedFile($_FILES['const_egreso']);
    $const_etica = processUploadedFile($_FILES['const_etica']);
    $const_nodeuda = processUploadedFile($_FILES['const_nodeuda']);
    $actas_practicas = processUploadedFile($_FILES['actas_practicas']);
    $const_practicas_empresa = processUploadedFile($_FILES['const_practicas_empresa']);
    $const_practicas_coord = processUploadedFile($_FILES['const_practicas_coord']);
    $cert_modular = processUploadedFile($_FILES['cert_modular']);
    $acta_idioma = processUploadedFile($_FILES['acta_idioma']);
    $cert_idioma = processUploadedFile($_FILES['cert_idioma']);
    $voucher_sustentacion = processUploadedFile($_FILES['voucher_sustentacion']);
    $voucher_medalla = processUploadedFile($_FILES['voucher_medalla']);
    $voucher_acta = processUploadedFile($_FILES['voucher_acta']);

    // Process multiple photos into a single BLOB
    $fotos = null;
    if (isset($_FILES['fotos'])) {
        $fotosArray = array();
        foreach ($_FILES['fotos']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['fotos']['error'][$key] == 0) {
                $fotosArray[] = file_get_contents($tmp_name);
            }
        }
        if (!empty($fotosArray)) {
            $fotos = implode('', $fotosArray);
        }
    }

    // Set initial estado
    $estado = 'Pendiente';

    // Prepare SQL statement
    $sql = "INSERT INTO solicitudes (nombre, dni, programa, modalidad, solicitud, partida, cert_secundaria, 
            dni_copy, cert_superior, resoluciones, cert_egresado, ficha_seguimiento, const_egreso, 
            const_etica, const_nodeuda, actas_practicas, const_practicas_empresa, const_practicas_coord, 
            cert_modular, acta_idioma, cert_idioma, voucher_sustentacion, voucher_medalla, voucher_acta, 
            fotos, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sissssssssssssssssssssssss", 
        $nombre, $dni, $programa, $modalidad, $solicitud, $partida, $cert_secundaria, 
        $dni_copy, $cert_superior, $resoluciones, $cert_egresado, $ficha_seguimiento, 
        $const_egreso, $const_etica, $const_nodeuda, $actas_practicas, 
        $const_practicas_empresa, $const_practicas_coord, $cert_modular, $acta_idioma, 
        $cert_idioma, $voucher_sustentacion, $voucher_medalla, $voucher_acta, $fotos, $estado
    );

    if ($stmt->execute()) {
        header("Location: tramite.php?success=1");
    } else {
        header("Location: tramite.php?error=1");
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: tramite.php");
}
?>
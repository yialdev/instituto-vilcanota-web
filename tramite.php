<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tramitar requisitos</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <link rel="stylesheet" type="text/css" href="css/tramite_style.css">
    <style>
        .success-message {
            display: none;
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <?php 
    include 'config.php';
    
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

    // Verificar cursos pendientes
    $user_id = $_SESSION['user_id'];
    $query = "SELECT cursos_pendientes FROM estado_academico WHERE id_estudiante = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    ?>

    <div class="main-content">
        <h1>Tramitar requisitos / documentos adjuntos</h1>
        <div id="successMessage" class="success-message">
            Su solicitud ya está en proceso, se le notificará en unos días
        </div>

        <?php if ($row && $row['cursos_pendientes'] == 0): ?>
            <form id="suficienciaForm" class="form-container" action="procesar_tramite.php" method="POST" enctype="multipart/form-data">
                <table class="form-table">
                    <tr>
                        <td class="form-section" colspan="2">Datos Personales</td>
                    </tr>
                    <tr>
                        <td><label for="nombre">Nombre:</label></td>
                        <td><input type="text" id="nombre" name="nombre"></td>
                    </tr>
                    <tr>
                        <td><label for="dni">DNI:</label></td>
                        <td><input type="text" id="dni" name="dni" pattern="[0-9]{8}"></td>
                    </tr>
                    <tr>
                        <td><label for="programa">Programa:</label></td>
                        <td>
                            <select id="programa" name="programa">
                                <option value="">Seleccione un programa</option>
                                <option value="DSI">Desarrollo de Sistemas de Información</option>
                                <option value="ENF">Enfermería</option>
                                <option value="CON">Contabilidad</option>
                                <option value="CCI">Construcción Civil</option>
                                <option value="PAG">Producción Agropecuaria</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="modalidad">Modalidad de Titulación:</label></td>
                        <td>
                            <select id="modalidad" name="modalidad">
                                <option value="">Seleccione modalidad</option>
                                <option value="EXA">Examen de suficiencia</option>
                                <option value="PRO">Proyecto de investigación</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td class="form-section" colspan="2">Documentos Requeridos</td>
                    </tr>

                    <tr>
                        <td><label for="solicitud">Solicitud de revisión:</label></td>
                        <td><input type="file" id="solicitud" name="solicitud" accept=".pdf,.doc,.docx"></td>
                    </tr>

                    <tr>
                        <td><label for="partida">Partida de nacimiento:</label></td>
                        <td><input type="file" id="partida" name="partida" accept=".pdf"></td>
                    </tr>

                    <tr>
                        <td><label for="cert_secundaria">Certificado secundaria:</label></td>
                        <td><input type="file" id="cert_secundaria" name="cert_secundaria" accept=".pdf"></td>
                    </tr>

                    <tr>
                        <td><label for="dni_copy">Fotocopia DNI autenticada:</label></td>
                        <td><input type="file" id="dni_copy" name="dni_copy" accept=".pdf,.jpg,.jpeg,.png"></td>
                    </tr>

                    <tr>
                        <td><label for="cert_superior">Certificado de Estudios Superiores:</label></td>
                        <td><input type="file" id="cert_superior" name="cert_superior" accept=".pdf"></td>
                    </tr>

                    <tr>
                        <td><label for="resoluciones">Resoluciones de Licencias/Reinicio/Traslado:</label></td>
                        <td><input type="file" id="resoluciones" name="resoluciones" accept=".pdf" multiple></td>
                    </tr>

                    <tr>
                        <td><label for="cert_egresado">Certificado de egresado:</label></td>
                        <td><input type="file" id="cert_egresado" name="cert_egresado" accept=".pdf"></td>
                    </tr>

                    <tr>
                        <td><label for="ficha_seguimiento">Ficha de Seguimiento Académico:</label></td>
                        <td><input type="file" id="ficha_seguimiento" name="ficha_seguimiento" accept=".pdf"></td>
                    </tr>

                    <tr>
                        <td><label for="const_egreso">Constancia de Egreso con código:</label></td>
                        <td><input type="file" id="const_egreso" name="const_egreso" accept=".pdf"></td>
                    </tr>

                    <tr>
                        <td><label for="const_etica">Constancia de Ética Personal:</label></td>
                        <td><input type="file" id="const_etica" name="const_etica" accept=".pdf"></td>
                    </tr>

                    <tr>
                        <td><label for="const_nodeuda">Constancia de no adeudar:</label></td>
                        <td><input type="file" id="const_nodeuda" name="const_nodeuda" accept=".pdf"></td>
                    </tr>

                    <tr>
                        <td><label for="actas_practicas">Actas de evaluación de prácticas pre-profesionales:</label></td>
                        <td><input type="file" id="actas_practicas" name="actas_practicas" accept=".pdf"></td>
                    </tr>

                    <tr>
                        <td><label for="const_practicas_empresa">Constancia de prácticas (35%) de la Empresa:</label></td>
                        <td><input type="file" id="const_practicas_empresa" name="const_practicas_empresa" accept=".pdf"></td>
                    </tr>

                    <tr>
                        <td><label for="const_practicas_coord">Constancia de prácticas (65%) del Coordinador:</label></td>
                        <td><input type="file" id="const_practicas_coord" name="const_practicas_coord" accept=".pdf"></td>
                    </tr>

                    <tr>
                        <td><label for="cert_modular">Certificados modulares:</label></td>
                        <td><input type="file" id="cert_modular" name="cert_modular" accept=".pdf"></td>
                    </tr>

                    <tr>
                        <td><label for="acta_idioma">Acta de evaluación del idioma:</label></td>
                        <td><input type="file" id="acta_idioma" name="acta_idioma" accept=".pdf"></td>
                    </tr>

                    <tr>
                        <td><label for="cert_idioma">Certificado de idioma:</label></td>
                        <td><input type="file" id="cert_idioma" name="cert_idioma" accept=".pdf"></td>
                    </tr>

                    <tr>
                        <td class="form-section" colspan="2">Vouchers de Pago</td>
                    </tr>

                    <tr>
                        <td><label for="voucher_sustentacion">Pago Sustentación:</label></td>
                        <td><input type="file" id="voucher_sustentacion" name="voucher_sustentacion" accept=".pdf,.jpg,.jpeg,.png"></td>
                    </tr>

                    <tr>
                        <td><label for="voucher_medalla">Pago medalla:</label></td>
                        <td><input type="file" id="voucher_medalla" name="voucher_medalla" accept=".pdf,.jpg,.jpeg,.png"></td>
                    </tr>

                    <tr>
                        <td><label for="voucher_acta">Pago acta titulación:</label></td>
                        <td><input type="file" id="voucher_acta" name="voucher_acta" accept=".pdf,.jpg,.jpeg,.png"></td>
                    </tr>

                    <tr>
                        <td><label for="fotos">Fotografías:</label></td>
                        <td>
                            <input type="file" id="fotos" name="fotos[]" accept=".jpg,.jpeg,.png" multiple>
                            <small>Subir 3 fotos tamaño pasaporte con fondo blanco</small>
                        </td>
                    </tr>
                </table>

                <div class="submit-container">
                    <button type="submit" class="submit-btn">Enviar Solicitud</button>
                </div>
            </form>

            <script>
                document.getElementById('suficienciaForm').addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Verificar que todos los campos estén llenos
                    const inputs = this.querySelectorAll('input[required]');
                    let allFilled = true;
                    
                    inputs.forEach(input => {
                        if (!input.value) {
                            allFilled = false;
                        }
                    });
                    
                    if (allFilled) {
                        // Mostrar mensaje de éxito
                        document.getElementById('successMessage').style.display = 'block';
                        
                        // Enviar el formulario después de 2 segundos
                        setTimeout(() => {
                            this.submit();
                        }, 2000);
                    } else {
                        alert('Por favor, complete todos los campos requeridos');
                    }
                });
            </script>
        <?php else: ?>
            <div class="alert alert-warning">
                <p>No puede procesar la solicitud de titulación porque tiene cursos pendientes.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
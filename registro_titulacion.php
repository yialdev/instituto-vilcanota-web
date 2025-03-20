
<?php
require_once 'config_titulacion.php';
session_start();

// Validar si es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] > 3) {
    header("Location: login.php");
    exit();
}

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_estudiante = $_POST['id_estudiante'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $genero = $_POST['genero'] ?? '';
    $edad = $_POST['edad'] ?? '';
    $dni = $_POST['dni'] ?? '';
    $nota_1 = $_POST['nota_1'] ?? '';
    $nota_2 = $_POST['nota_2'] ?? '';
    $nota_3 = $_POST['nota_3'] ?? '';
    $nota_final = ($nota_1 + $nota_2 + $nota_3) / 3;
    $id_programa = $_POST['id_programa'] ?? '';

    if ($_POST['action'] == 'create') {
        $sql = "INSERT INTO estudiantes (nombre, apellido, genero, edad, dni, nota_1, nota_2, nota_3, nota_final, id_programa, id_jurado_1, id_jurado_2, id_jurado_3) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, 2, 3)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssisssssi", $nombre, $apellido, $genero, $edad, $dni, $nota_1, $nota_2, $nota_3, $nota_final, $id_programa);
    } elseif ($_POST['action'] == 'update') {
        $sql = "UPDATE estudiantes SET nombre=?, apellido=?, genero=?, edad=?, dni=?, nota_1=?, nota_2=?, nota_3=?, nota_final=?, id_programa=? WHERE id_estudiante=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssisssssii", $nombre, $apellido, $genero, $edad, $dni, $nota_1, $nota_2, $nota_3, $nota_final, $id_programa, $id_estudiante);
    } elseif ($_POST['action'] == 'delete') {
        $sql = "DELETE FROM estudiantes WHERE id_estudiante=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_estudiante);
    }

    if ($stmt->execute()) {
        $mensaje = "Operación realizada con éxito";
    } else {
        $mensaje = "Error en la operación";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Titulación</title>
    <link rel="stylesheet" href="css/styles.css">
    <script>
        function calcularPromedio() {
            var nota1 = parseFloat(document.getElementById('nota_1').value) || 0;
            var nota2 = parseFloat(document.getElementById('nota_2').value) || 0;
            var nota3 = parseFloat(document.getElementById('nota_3').value) || 0;
            
            if (nota1 >= 13 && nota2 >= 13 && nota3 >= 13) {
                var promedio = (nota1 + nota2 + nota3) / 3;
                document.getElementById('nota_final').value = promedio.toFixed(2);
            } else {
                alert('Las notas deben ser mayores o iguales a 13');
                return false;
            }
        }

        function validarNota(input) {
            if (input.value < 13) {
                alert('La nota mínima es 13');
                input.value = '';
            }
        }
    </script>
</head>
<body>
    <?php include 'items/navbar_admin.php'; ?>
    
    <div class="main-content">
        <h1>Registro de Titulación</h1>
        
        <?php if (isset($mensaje)) echo "<div class='alert-message'>$mensaje</div>"; ?>

        <div class="registro-container">
            <form method="POST" onsubmit="return calcularPromedio()">
                <div class="form-row">
                    <div class="form-column">
                        <div class="form-group">
                            <label for="nombre">Nombre:</label>
                            <input type="text" id="nombre" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="apellido">Apellido:</label>
                            <input type="text" id="apellido" name="apellido" required>
                        </div>
                        <div class="form-group">
                            <label for="genero">Género:</label>
                            <select id="genero" name="genero" required>
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edad">Edad:</label>
                            <input type="number" id="edad" name="edad" required>
                        </div>
                        <div class="form-group">
                            <label for="dni">DNI:</label>
                            <input type="text" id="dni" name="dni" required>
                        </div>
                    </div>
                    <div class="form-column">
                        <div class="form-group">
                            <label for="nota_1">Nota 1:</label>
                            <input type="number" id="nota_1" name="nota_1" min="13" max="20" step="0.01" onchange="validarNota(this)" required>
                        </div>
                        <div class="form-group">
                            <label for="nota_2">Nota 2:</label>
                            <input type="number" id="nota_2" name="nota_2" min="13" max="20" step="0.01" onchange="validarNota(this)" required>
                        </div>
                        <div class="form-group">
                            <label for="nota_3">Nota 3:</label>
                            <input type="number" id="nota_3" name="nota_3" min="13" max="20" step="0.01" onchange="validarNota(this)" required>
                        </div>
                        <div class="form-group">
                            <label for="nota_final">Promedio:</label>
                            <input type="number" id="nota_final" name="nota_final" readonly>
                        </div>
                        <div class="form-group">
                            <label for="id_programa">Programa:</label>
                            <select id="id_programa" name="id_programa" required>
                                <?php
                                $sql = "SELECT id_programa, nombre FROM programas";
                                $result = $conn->query($sql);
                                while($row = $result->fetch_assoc()) {
                                    echo "<option value='".$row['id_programa']."'>".$row['nombre']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="action" value="create">
                <button type="submit" class="submit-btn">Registrar</button>
            </form>
        </div>
    </div>
</body>
</html>
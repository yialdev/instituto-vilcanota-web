<?php
session_start();
require_once 'config.php';

// Procesar formulario de usuarios
if (isset($_POST['submit_usuario'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $rol = $_POST['rol'];
    $estado = 1;

    // Initialize relationship IDs
    $id_relacionado = null;
    $id_admin = null;

    // Set the appropriate ID based on role
    if ($rol === 'estudiante') {
        $id_relacionado = $_POST['id_estudiante'];
    } elseif ($rol === 'admin') {
        $id_admin = $_POST['id_admin'];
    }

    // Verificar si el usuario ya existe
    $check_sql = "SELECT username FROM usuarios WHERE username = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        $mensaje_usuario = "<span style='color: red;'>Error: El nombre de usuario ya existe</span>";
    } else {
        $sql = "INSERT INTO usuarios (username, password, rol, id_relacionado, id_admin, estado) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssiii", $username, $password, $rol, $id_relacionado, $id_admin, $estado);
        
        if ($stmt->execute()) {
            $mensaje_usuario = "Usuario agregado exitosamente";
        } else {
            $mensaje_usuario = "<span style='color: red;'>Error al crear el usuario</span>";
        }
    }
}

// Procesar formulario de estudiantes
if (isset($_POST['submit_estudiante'])) {
    $dni = $_POST['dni'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $genero = $_POST['genero'];
    $edad = $_POST['edad'];
    $id_programa = $_POST['id_programa'];
    $estado_academico = "cursando";

    // Verificar si el DNI ya existe
    $check_dni = "SELECT dni FROM estudiantes WHERE dni = ?";
    $check_stmt = $conn->prepare($check_dni);
    $check_stmt->bind_param("s", $dni);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        $mensaje_estudiante = "<span style='color: red;'>Error: El DNI ya está registrado</span>";
    } else {

        
        // Generar código único de estudiante
        do {
            $year = date('Y');
            $random = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
            $codigo_estudiante = $year . $id_programa . $random;
            
            $check_code = "SELECT codigo_estudiante FROM estudiantes WHERE codigo_estudiante = ?";
            $check_stmt = $conn->prepare($check_code);
            $check_stmt->bind_param("s", $codigo_estudiante);
            $check_stmt->execute();
            $result = $check_stmt->get_result();
        } while ($result->num_rows > 0);

        $sql = "INSERT INTO estudiantes (codigo_estudiante, dni, nombres, apellidos, id_programa, estado_academico, genero, edad) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssisii", $codigo_estudiante, $dni, $nombres, $apellidos, $id_programa, $estado_academico, $genero, $edad);
        
        if ($stmt->execute()) {
            $mensaje_estudiante = "<span style='color: green;'>Estudiante agregado exitosamente. Código: $codigo_estudiante</span>";
        } else {
            $mensaje_estudiante = "<span style='color: red;'>Error al crear el estudiante: " . $stmt->error . "</span>";
        }
    }
}

// Procesar formulario de administradores
if (isset($_POST['submit_admin'])) {
    $dni = $_POST['admin_dni'];
    $nombres = $_POST['admin_nombres'];
    $apellidos = $_POST['admin_apellidos'];
    $genero = $_POST['admin_genero'];
    $edad = $_POST['admin_edad'];
    $fecha_ingreso = date('Y-m-d H:i:s'); // Current timestamp

    // Verificar si el DNI ya existe
    $check_dni = "SELECT dni FROM admins WHERE dni = ?";
    $check_stmt = $conn->prepare($check_dni);
    $check_stmt->bind_param("i", $dni);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        $mensaje_admin = "<span style='color: red;'>Error: El DNI ya está registrado</span>";
    } else {
        $sql = "INSERT INTO admins (nombres, apellidos, genero, dni, edad, fecha_ingreso) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssiss", $nombres, $apellidos, $genero, $dni, $edad, $fecha_ingreso);
        
        if ($stmt->execute()) {
            $mensaje_admin = "<span style='color: green;'>Administrador agregado exitosamente</span>";
        } else {
            $mensaje_admin = "<span style='color: red;'>Error al crear el administrador: " . $stmt->error . "</span>";
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Administración</title>
    <style>
        .container {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            flex-wrap: wrap;
        }
        .column {
            width: 30%;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .success {
            color: green;
            margin-bottom: 10px;
        }
        .hidden {
            display: none;
        }
    </style>
    <script>
        function toggleRelatedFields() {
            var rol = document.querySelector('select[name="rol"]').value;
            var estudianteField = document.getElementById('estudiante-field');
            var adminField = document.getElementById('admin-field');

            estudianteField.style.display = 'none';
            adminField.style.display = 'none';

            if (rol === 'estudiante') {
                estudianteField.style.display = 'block';
            } else if (rol === 'admin') {
                adminField.style.display = 'block';
            }
        }
    </script>
</head>
<body>
    <?php include 'items/navbar_admin.php'; ?>
    
    <div class="container">
        <div class="column">
            <h2>ADMINISTRAR USUARIOS</h2>
            <?php if (isset($mensaje_usuario)) echo "<p class='success'>$mensaje_usuario</p>"; ?>
            <form method="POST">
                <div class="form-group">
                    <label>Usuario:</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label>Contraseña:</label>
                    <input type="password" name="password" required>
                </div>
                <div class="form-group">
                    <label>Rol:</label>
                    <select name="rol" required onchange="toggleRelatedFields()">
                        <option value="" selected disabled>Seleccione un rol</option>
                        <option value="estudiante">Estudiante</option>
                        <option value="admin">Admin</option>
                        <option value="secretaria">Secretaría</option>
                    </select>
                </div>
                <div class="form-group hidden" id="estudiante-field">
                    <label>ID Estudiante:</label>
                    <input type="number" name="id_estudiante">
                </div>
                <div class="form-group hidden" id="admin-field">
                    <label>ID Admin:</label>
                    <input type="number" name="id_admin">
                </div>
                <input type="submit" name="submit_usuario" value="Agregar Usuario" class="submit-btn">
            </form>
        </div>

        <div class="column">
            <h2>ADMINISTRAR ESTUDIANTES</h2>
            <?php if (isset($mensaje_estudiante)) echo "<p class='success'>$mensaje_estudiante</p>"; ?>
            <form method="POST">
                <div class="form-group">
                    <label>DNI:</label>
                    <input type="number" name="dni" required>
                </div>
                <div class="form-group">
                    <label>Nombre:</label>
                    <input type="text" name="nombres" required>
                </div>
                <div class="form-group">
                    <label>Apellidos:</label>
                    <input type="text" name="apellidos" required>
                </div>
                <div class="form-group">
                    <label>Género:</label>
                    <select name="genero" required>
                        <option value="" selected disabled>Seleccione género</option>
                        <option value="masculino">Masculino</option>
                        <option value="femenino">Femenino</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Edad:</label>
                    <input type="number" name="edad" required>
                </div>
                <div class="form-group">
                    <label>Programa:</label>
                    <select name="id_programa" required>
                        <option value="" selected disabled>Seleccione un programa</option>
                        <option value="1">Desarrollo de Sistemas de Información</option>
                        <option value="2">Enfermería Técnica</option>
                        <option value="3">Contabilidad</option>
                        <option value="4">Construcción Civil</option>
                        <option value="5">Agropecuaria</option>
                    </select>
                </div>
                <input type="submit" name="submit_estudiante" value="Agregar Estudiante" class="submit-btn">
            </form>
        </div>

        <div class="column">
            <h2>ADMINISTRAR PERSONAL</h2>
            <?php if (isset($mensaje_admin)) echo "<p class='success'>$mensaje_admin</p>"; ?>
            <form method="POST">
                <div class="form-group">
                    <label>DNI:</label>
                    <input type="number" name="admin_dni" required>
                </div>
                <div class="form-group">
                    <label>Nombre:</label>
                    <input type="text" name="admin_nombres" required>
                </div>
                <div class="form-group">
                    <label>Apellidos:</label>
                    <input type="text" name="admin_apellidos" required>
                </div>
                <div class="form-group">
                    <label>Género:</label>
                    <select name="admin_genero" required>
                        <option value="" selected disabled>Seleccione género</option>
                        <option value="masculino">Masculino</option>
                        <option value="femenino">Femenino</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Edad:</label>
                    <input type="number" name="admin_edad" required>
                </div>
                <input type="submit" name="submit_admin" value="Agregar Administrador" class="submit-btn">
            </form>
        </div>
    </div>
</body>
</html>
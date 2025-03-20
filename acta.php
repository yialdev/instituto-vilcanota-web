<?php
require_once 'config_titulacion.php';
setlocale(LC_TIME, 'es_ES.UTF-8', 'Spanish_Spain.1252');

if (!isset($_GET['id'])) {
    header('Location: actas_titulacion.php');
    exit;
}

$id_estudiante = $_GET['id'];
$sql = "SELECT e.*, p.nombre as programa 
        FROM estudiantes e 
        LEFT JOIN programas p ON e.id_programa = p.id_programa 
        WHERE e.id_estudiante = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_estudiante);
$stmt->execute();
$result = $stmt->get_result();
$estudiante = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acta de Titulación</title>
    <style>
        @media print {
            .no-print {
                display: none;
            }
            @page {
                size: landscape;
            }
        }
        .acta-container {
            width: 29.7cm;    /* Ancho A4 horizontal */
            min-height: 21cm;  /* Alto A4 horizontal */
            padding: 2cm;
            margin: 1cm auto;
            border: 1px solid #D3D3D3;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            position: relative;
            background: white;
        }
        .acta-header {
            text-align: center;
            margin-bottom: 0.8cm;  /* Reducido el margen */
        }
        .republica {
            font-size: 0.8em;
            margin-bottom: 0.3cm;  /* Ajustado el margen */
        }
        .ministerio {
            font-size: 1.1em;
            margin-bottom: 0.8cm;
        }
        .nacion {
            font-size: 2em;
            font-weight: bold;
            margin: 0.8cm 0;
        }
        .director {
            font-size: 1.1em;
            margin: 0.8cm 0;
            line-height: 1.5;
        }
        .considerando {
            font-size: 1em;
            text-align: justify;
            padding: 0 2cm;
            line-height: 1.6;
        }
        .nombre-estudiante {
            font-family: Arial, sans-serif;
            font-weight: bold;
            font-style: normal;
        }
        .titulo-profesional {
            font-family: Arial, sans-serif;
            font-weight: bold;
            font-style: normal;
            text-transform: uppercase;
        }
        .linea-puntos {
            border-top: 1px dotted #000;
            width: 100%;
            margin-bottom: 10px;
        }
        .por-tanto {
            margin: 0.8cm 0;
            line-height: 1.5;
        }
        .fecha {
            margin-top: 1.5cm;
            text-align: center;
        }
        .firmas {
            margin-top: 2cm;
            display: flex;
            justify-content: center;
        }
        .firma {
            text-align: center;
            width: 300px;
            font-family: Arial, sans-serif;
            font-size: 0.9em;  /* Aumentado ligeramente */
            margin: 0 auto;
        }
        .firma-rol {
            margin-bottom: 0.5cm;
            font-weight: bold;
        }
        .firma-detalle {
            font-size: 0.85em;
            color: #444;
        }
        body {
            background: #f0f0f0;
            margin: 0;
            padding: 1cm;
        }
        .btn-imprimir {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <button onclick="window.print();" class="btn-imprimir no-print">Imprimir Acta</button>
    
    <div class="acta-container">
        <div class="acta-header">
            <div class="republica">REPÚBLICA DEL PERÚ</div>
            <div class="ministerio">MINISTERIO DE EDUCACIÓN</div>
            
            <div class="nacion">A NOMBRE DE LA NACIÓN</div>
            
            <div class="director">
                El Director General del Instituto de Educación Superior Tecnológico Público<br>
                "Vilcanota"
            </div>
            
            <div class="considerando">
                Por cuanto: <span class="nombre-estudiante"><?php echo $estudiante['nombre'] . ' ' . $estudiante['apellido']; ?></span> 
                ha cumplido satisfactoriamente con las normas y disposiciones reglamentarias vigentes, 
                le otorga el título de: <span class="titulo-profesional">Profesional Técnico en <?php echo $estudiante['programa']; ?></span>
            </div>
            
            <div class="por-tanto">
                POR TANTO:<br>
                Se expide el presente TÍTULO para que se le reconozca como tal.
            </div>
            
            <div class="fecha">
                Dado en Sicuani a los <?php 
                    echo date('d') . ' días del mes de ' . 
                    strftime('%B') . ' de ' . 
                    date('Y'); 
                ?>
            </div>
            
            <div class="firmas">
                <div class="firma">
                    <div class="linea-puntos"></div>
                    <p class="firma-rol">DIRECTOR GENERAL</p>
                    <p class="firma-detalle">(Sello, firma, postfirma)</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
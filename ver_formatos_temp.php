<?php 
include("conexion.php");

$con = odbc_connect($dsn, $usudb, $pwdb);

if (!$con) {
    die("Error de conexión");
}

// Log de acceso
$log_file = "c:\\temp\\ver_formatos_temp.log";
@mkdir("c:\\temp", 0777, true);
file_put_contents($log_file, "\n=== ACCESO ===" . date("Y-m-d H:i:s") . " ===\n", FILE_APPEND);

// Verificar que la tabla existe
$sql_check = "SELECT TOP 1 * FROM formatos_temperatura";
$result_check = @odbc_exec($con, $sql_check);
if (!$result_check) {
    file_put_contents($log_file, "Error: Tabla no existe - " . odbc_errormsg($con) . "\n", FILE_APPEND);
}

// Contar registros
$sql_count = "SELECT COUNT(*) AS total FROM formatos_temperatura";
$result_count = @odbc_exec($con, $sql_count);
$total_records = 0;
if ($result_count) {
    $row_count = @odbc_fetch_array($result_count);
    $total_records = $row_count['total'] ?? 0;
    file_put_contents($log_file, "Total de registros: $total_records\n", FILE_APPEND);
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Formatos de Temperatura Guardados</title>
<link href="css/bootstrap.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
body {
    font-family: Arial, sans-serif;
    padding: 20px;
    background: #f5f5f5;
}
.container {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    max-width: 1400px;
    margin: 0 auto;
}
h2 {
    color: #a8814e;
    margin-bottom: 30px;
    text-align: center;
}
.table {
    margin-top: 20px;
}
.btn-ver {
    background: #205ca4;
    color: white;
    padding: 5px 15px;
    border-radius: 5px;
    text-decoration: none;
    display: inline-block;
}
.btn-ver:hover {
    background: #164273;
    color: white;
}
.btn-nuevo {
    background: #28a745;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    display: inline-block;
    margin-bottom: 20px;
}
.btn-nuevo:hover {
    background: #218838;
    color: white;
}

.btn-volver {
    background: #6c757d;
    color: white;
    padding: 8px 16px;
    border-radius: 5px;
    text-decoration: none;
    display: inline-block;
    margin-bottom: 20px;
}
.btn-volver:hover {
    background: #5a6268;
    color: white;
}

.btn-diagnostico {
    background: #17a2b8;
    color: white;
    padding: 8px 16px;
    border-radius: 5px;
    text-decoration: none;
    display: inline-block;
    margin-left: 10px;
}
.btn-diagnostico:hover {
    background: #138496;
    color: white;
}

.info-box {
    background: #e3f2fd;
    border-left: 4px solid #2196F3;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 4px;
}

.empty-state {
    text-align: center;
    padding: 40px;
    color: #999;
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 15px;
}
</style>
</head>
<body>

<div class="container">
    <div align="center">
        <img src="img/logo_dos.png" width="262" height="81" alt="Logo" />
    </div>
    
    <h2>
        <i class="fas fa-thermometer-half"></i> 
        Formatos de Control de Temperatura
    </h2>
    
    <div>
        <a href="dashboard.php" class="btn-volver">⬅ Volver</a>
        <a href="control_temperatura.php" class="btn-nuevo">
            <i class="fas fa-plus"></i> Crear Nuevo Formato
        </a>
        <a href="diagnostico.php" class="btn-diagnostico" target="_blank">
            <i class="fas fa-tools"></i> Diagnóstico
        </a>
    </div>

    <div class="info-box">
        <strong>📊 Total de registros guardados:</strong> <span style="font-size:18px;color:#2196F3;font-weight:bold;"><?= $total_records ?></span>
    </div>

    <table class="table table-striped table-bordered">
        <thead style="background: #205ca4; color: white;">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Fecha</th>
                <th>Placa</th>
                <th>Mes</th>
                <th>Año</th>
                <th>Servicio</th>
                <th>Fecha Registro</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT TOP 100 id_formato, nombre, fecha, placa, mes, anio, servicio, fecha_registro 
                    FROM formatos_temperatura 
                    ORDER BY id_formato DESC";
            
            file_put_contents($log_file, "SQL Query: $sql\n", FILE_APPEND);
            
            $result = @odbc_exec($con, $sql);
            
            if (!$result) {
                echo "<tr><td colspan='9' class='text-center' style='color:#dc3545;'>";
                echo "<strong>❌ Error al consultar:</strong> " . odbc_errormsg($con);
                echo "</td></tr>";
                file_put_contents($log_file, "Error en query: " . odbc_errormsg($con) . "\n", FILE_APPEND);
            } else {
                $hay_datos = false;
                $contador = 0;
                
                while ($row = @odbc_fetch_array($result)) {
                    $hay_datos = true;
                    $contador++;
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id_formato']) ?></td>
                        <td><?= htmlspecialchars($row['nombre']) ?></td>
                        <td><?= htmlspecialchars($row['fecha']) ?></td>
                        <td><?= htmlspecialchars($row['placa']) ?></td>
                        <td><?= htmlspecialchars($row['mes']) ?></td>
                        <td><?= htmlspecialchars($row['anio']) ?></td>
                        <td><?= htmlspecialchars($row['servicio']) ?></td>
                        <td><?= htmlspecialchars($row['fecha_registro']) ?></td>
                        <td>
                            <a href="ver_detalle_temp.php?id=<?= $row['id_formato'] ?>" class="btn-ver">
                                <i class="fas fa-eye"></i> Ver Detalle
                            </a>
                        </td>
                    </tr>
                    <?php
                }
                
                file_put_contents($log_file, "Registros mostrados: $contador\n", FILE_APPEND);
                
                if (!$hay_datos) {
                    echo "<tr><td colspan='9'>";
                    echo "<div class='empty-state'>";
                    echo "<i class='fas fa-inbox'></i>";
                    echo "<p><strong>No hay formatos guardados</strong></p>";
                    echo "<p style='font-size:12px;'>Crea un nuevo formato haciendo clic en el botón de arriba</p>";
                    echo "</div>";
                    echo "</td></tr>";
                    file_put_contents($log_file, "Sin registros en la BD\n", FILE_APPEND);
                }
            }
            
            odbc_close($con);
            ?>
        </tbody>
    </table>
</div>

</body>
 <!-- By Luis Maldonado -->
</html>
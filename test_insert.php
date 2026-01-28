<?php
// Test simple de inserción
include("conexion.php");

echo "<!DOCTYPE html>";
echo "<html><head><meta charset='utf-8'><style>body{font-family:Arial;padding:20px;}</style></head><body>";

$con = @odbc_connect($dsn, $usudb, $pwdb);

if (!$con) {
    echo "<h2>❌ Error de conexión ODBC</h2>";
    echo "<p>" . odbc_errormsg() . "</p>";
    die("</body></html>");
}

echo "<h1>✅ Conexión ODBC exitosa</h1>";

// Test 1: Verificar tabla
echo "<h2>Test 1: Verificar tabla formatos_temperatura</h2>";
$sql_check = "SELECT TOP 1 * FROM formatos_temperatura";
$result = @odbc_exec($con, $sql_check);

if ($result) {
    echo "<p style='color:green;'>✅ Tabla existe</p>";
} else {
    echo "<p style='color:red;'>❌ Tabla NO existe: " . odbc_errormsg($con) . "</p>";
}

// Test 2: Contar registros
echo "<h2>Test 2: Contar registros actuales</h2>";
$sql_count = "SELECT COUNT(*) AS total FROM formatos_temperatura";
$result_count = @odbc_exec($con, $sql_count);

if ($result_count) {
    $row = @odbc_fetch_array($result_count);
    $total = $row['total'] ?? 0;
    echo "<p>Total de registros: <strong>$total</strong></p>";
    
    if ($total > 0) {
        echo "<h3>Últimos 3 registros:</h3>";
        $sql_list = "SELECT TOP 3 id_formato, nombre, fecha, fecha_registro FROM formatos_temperatura ORDER BY id_formato DESC";
        $result_list = @odbc_exec($con, $sql_list);
        
        echo "<table style='border-collapse:collapse;'>";
        echo "<tr style='background:#f0f0f0;'><th style='border:1px solid #ddd;padding:8px;'>ID</th><th style='border:1px solid #ddd;padding:8px;'>Nombre</th><th style='border:1px solid #ddd;padding:8px;'>Fecha</th><th style='border:1px solid #ddd;padding:8px;'>Fecha Reg</th></tr>";
        
        while ($row_list = @odbc_fetch_array($result_list)) {
            echo "<tr>";
            echo "<td style='border:1px solid #ddd;padding:8px;'>" . $row_list['id_formato'] . "</td>";
            echo "<td style='border:1px solid #ddd;padding:8px;'>" . htmlspecialchars($row_list['nombre']) . "</td>";
            echo "<td style='border:1px solid #ddd;padding:8px;'>" . $row_list['fecha'] . "</td>";
            echo "<td style='border:1px solid #ddd;padding:8px;'>" . $row_list['fecha_registro'] . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    }
} else {
    echo "<p style='color:red;'>❌ Error al contar: " . odbc_errormsg($con) . "</p>";
}

// Test 3: Insertar registro de prueba
echo "<h2>Test 3: Insertar registro de prueba</h2>";

$nombre_test = "TEST_" . date("YmdHis");
$fecha_test = date("Y-m-d");

$sql_insert = "INSERT INTO formatos_temperatura 
               (nombre, fecha, placa, mes, anio, servicio, fecha_registro)
               VALUES ('$nombre_test', '$fecha_test', 'TEST001', 'ENERO', '2025', 'Prueba', GETDATE())";

echo "<p><strong>Ejecutando:</strong></p><code style='background:#f0f0f0;padding:10px;display:block;overflow-x:auto;'>$sql_insert</code>";

$result_insert = @odbc_exec($con, $sql_insert);

if ($result_insert) {
    echo "<p style='color:green;'>✅ Inserción exitosa!</p>";
    
    // Obtener el ID
    $sql_id = "SELECT MAX(id_formato) AS id_formato FROM formatos_temperatura";
    $result_id = @odbc_exec($con, $sql_id);
    
    if ($result_id) {
        $row_id = @odbc_fetch_array($result_id);
        $id = $row_id['id_formato'];
        echo "<p>ID generado: <strong>$id</strong></p>";
        
        // Verificar que se guardó
        $sql_verify = "SELECT * FROM formatos_temperatura WHERE id_formato = $id";
        $result_verify = @odbc_exec($con, $sql_verify);
        
        if ($result_verify && $row_verify = @odbc_fetch_array($result_verify)) {
            echo "<p style='color:green;'>✅ Registro verificado en BD:</p>";
            echo "<pre>" . json_encode($row_verify, JSON_PRETTY_PRINT) . "</pre>";
        }
    }
} else {
    echo "<p style='color:red;'>❌ Error en inserción: " . odbc_errormsg($con) . "</p>";
}

@odbc_close($con);

echo "</body></html>";
?>

<?php
include("conexion.php");

echo "<!DOCTYPE html>";
echo "<html><head><meta charset='utf-8'><title>Diagnóstico</title>";
echo "<style>body{font-family:Arial;padding:20px;background:#f5f5f5;} .section{background:white;padding:15px;margin:10px 0;border-radius:5px;border-left:4px solid #205ca4;} .success{border-left-color:#28a745;} .error{border-left-color:#dc3545;} .warning{border-left-color:#ffc107;} code{background:#f0f0f0;padding:5px 10px;border-radius:3px;display:block;margin:10px 0;}</style>";
echo "</head><body>";

echo "<h1>🔍 Diagnóstico del Sistema</h1>";

// 1. Verificar conexión
echo "<div class='section'>";
echo "<h2>1️⃣ Conexión a BD</h2>";
$con = @odbc_connect($dsn, $usudb, $pwdb);
if ($con) {
    echo "<p class='success'>✅ Conexión ODBC exitosa</p>";
} else {
    echo "<p class='error'>❌ Error de conexión: " . odbc_errormsg() . "</p>";
    die("</div></body></html>");
}
echo "</div>";

// 2. Verificar si la tabla existe
echo "<div class='section'>";
echo "<h2>2️⃣ Tabla formatos_temperatura</h2>";
$sql = "SELECT TOP 1 * FROM formatos_temperatura";
$result = @odbc_exec($con, $sql);
if ($result) {
    echo "<p class='success'>✅ Tabla existe</p>";
    
    // Obtener campos
    $num_cols = odbc_num_fields($result);
    echo "<p><strong>Columnas:</strong></p><code>";
    for ($i = 1; $i <= $num_cols; $i++) {
        echo odbc_field_name($result, $i) . " ";
    }
    echo "</code>";
} else {
    echo "<p class='error'>❌ Tabla no encontrada: " . odbc_errormsg($con) . "</p>";
}
echo "</div>";

// 3. Contar registros
echo "<div class='section'>";
echo "<h2>3️⃣ Registros en formatos_temperatura</h2>";
$sql_count = "SELECT COUNT(*) AS total FROM formatos_temperatura";
$result_count = @odbc_exec($con, $sql_count);
if ($result_count) {
    $row = odbc_fetch_array($result_count);
    $total = $row['total'] ?? 0;
    
    if ($total > 0) {
        echo "<p class='success'>✅ Se encontraron <strong>$total</strong> registros</p>";
    } else {
        echo "<p class='warning'>⚠️ No hay registros en la tabla (está vacía)</p>";
    }
} else {
    echo "<p class='error'>❌ Error al contar: " . odbc_errormsg($con) . "</p>";
}
echo "</div>";

// 4. Listar últimos registros
echo "<div class='section'>";
echo "<h2>4️⃣ Últimos 5 Registros</h2>";
$sql_list = "SELECT TOP 5 id_formato, nombre, fecha, fecha_registro FROM formatos_temperatura ORDER BY id_formato DESC";
$result_list = @odbc_exec($con, $sql_list);
if ($result_list) {
    echo "<table style='width:100%;border-collapse:collapse;'>";
    echo "<tr style='background:#f0f0f0;'><th style='border:1px solid #ddd;padding:8px;'>ID</th><th style='border:1px solid #ddd;padding:8px;'>Nombre</th><th style='border:1px solid #ddd;padding:8px;'>Fecha</th><th style='border:1px solid #ddd;padding:8px;'>Fecha Registro</th></tr>";
    
    $count = 0;
    while ($row = odbc_fetch_array($result_list)) {
        $count++;
        echo "<tr>";
        echo "<td style='border:1px solid #ddd;padding:8px;'>" . $row['id_formato'] . "</td>";
        echo "<td style='border:1px solid #ddd;padding:8px;'>" . htmlspecialchars($row['nombre']) . "</td>";
        echo "<td style='border:1px solid #ddd;padding:8px;'>" . $row['fecha'] . "</td>";
        echo "<td style='border:1px solid #ddd;padding:8px;'>" . $row['fecha_registro'] . "</td>";
        echo "</tr>";
    }
    
    if ($count == 0) {
        echo "<tr><td colspan='4' style='border:1px solid #ddd;padding:8px;text-align:center;'>No hay registros</td></tr>";
    }
    
    echo "</table>";
} else {
    echo "<p class='error'>❌ Error al listar: " . odbc_errormsg($con) . "</p>";
}
echo "</div>";

// 5. Verificar archivo de log
echo "<div class='section'>";
echo "<h2>5️⃣ Archivo de Log de Inserción</h2>";
$debug_log = "c:\\temp\\insertar_temp_debug.log";
if (file_exists($debug_log)) {
    echo "<p class='success'>✅ Archivo de log existe</p>";
    echo "<p><strong>Contenido:</strong></p>";
    $log_content = file_get_contents($debug_log);
    echo "<code style='white-space:pre-wrap;overflow-x:auto;'>" . htmlspecialchars($log_content) . "</code>";
} else {
    echo "<p class='warning'>⚠️ Archivo de log no existe: $debug_log</p>";
    echo "<p>Se creará automáticamente cuando guardes un formato.</p>";
}
echo "</div>";

// 6. Tablas relacionadas
echo "<div class='section'>";
echo "<h2>6️⃣ Tablas Relacionadas</h2>";

$tablas_related = [
    'control_temperatura' => 'Temperaturas',
    'control_humedad' => 'Humedad',
    'control_frio' => 'Cadena de Frío',
    'control_firmas' => 'Firmas'
];

foreach ($tablas_related as $tabla => $nombre) {
    $sql_check = "SELECT COUNT(*) AS total FROM $tabla";
    $result_check = @odbc_exec($con, $sql_check);
    
    if ($result_check) {
        $row_check = odbc_fetch_array($result_check);
        $total_check = $row_check['total'] ?? 0;
        echo "<p>✅ $nombre ($tabla): <strong>$total_check registros</strong></p>";
    } else {
        echo "<p>⚠️ $nombre ($tabla): No existe</p>";
    }
}
echo "</div>";

odbc_close($con);

echo "<div class='section' style='background:#f0f0f0;'>";
echo "<h2>📋 Instrucciones</h2>";
echo "<p>1. Si ves '✅ Se encontraron X registros', los datos SÍ se están guardando. El problema está en ver_formatos_temp.php</p>";
echo "<p>2. Si ves '⚠️ No hay registros', guarda un nuevo formato y vuelve aquí</p>";
echo "<p>3. Si ves errores, comparte la información con el desarrollador</p>";
echo "</div>";

echo "</body></html>";
?>

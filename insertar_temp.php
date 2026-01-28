<?php
// Mostrar errores para debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Log para debugging
$debug_log = "c:\\temp\\insertar_temp_debug.log";
if (!file_exists("c:\\temp")) {
    @mkdir("c:\\temp", 0777, true);
}

// Registrar que recibimos POST
file_put_contents($debug_log, "\n=== REQUEST RECIBIDO ===" . date("Y-m-d H:i:s") . " ===\n", FILE_APPEND);
file_put_contents($debug_log, "POST data: " . json_encode($_POST, JSON_PRETTY_PRINT) . "\n", FILE_APPEND);

include("conexion.php");

$con = @odbc_connect($dsn, $usudb, $pwdb);

if (!$con) {
    file_put_contents($debug_log, "Error de conexión ODBC: " . odbc_errormsg() . "\n", FILE_APPEND);
    http_response_code(500);
    die("❌ Error de conexión a la base de datos: " . odbc_errormsg());
}

file_put_contents($debug_log, "Conexión ODBC exitosa\n", FILE_APPEND);

try {
    /* ================================
       1️⃣ RECIBIR Y VALIDAR DATOS
    ================================ */
    
    $nombre   = isset($_POST['nombre'])   ? str_replace("'", "''", trim($_POST['nombre']))   : '';
    $fecha    = isset($_POST['fecha'])    ? trim($_POST['fecha'])    : '';
    $placa    = isset($_POST['placa'])    ? str_replace("'", "''", trim($_POST['placa']))    : '';
    $mes      = isset($_POST['mes'])      ? str_replace("'", "''", trim($_POST['mes']))      : '';
    $anio     = isset($_POST['anio'])     ? str_replace("'", "''", trim($_POST['anio']))     : '';
    $servicio = isset($_POST['servicio']) ? str_replace("'", "''", trim($_POST['servicio'])) : '';

    file_put_contents($debug_log, "Datos validados: nombre=$nombre, fecha=$fecha\n", FILE_APPEND);

    // Validación básica
    if (empty($nombre) || empty($fecha)) {
        throw new Exception("Los campos nombre y fecha son obligatorios");
    }

    /* ================================
       2️⃣ INICIAR TRANSACCIÓN
    ================================ */
    
    $begin = @odbc_exec($con, "BEGIN TRANSACTION");
    if (!$begin) {
        throw new Exception("No se pudo iniciar la transacción");
    }

    file_put_contents($debug_log, "Transacción iniciada\n", FILE_APPEND);

    /* ================================
       3️⃣ INSERT FORMATO PRINCIPAL
    ================================ */

    $sql_formato = "INSERT INTO formatos_temperatura 
                    (nombre, fecha, placa, mes, anio, servicio, fecha_registro)
                    VALUES ('$nombre', '$fecha', '$placa', '$mes', '$anio', '$servicio', GETDATE())";

    file_put_contents($debug_log, "SQL: $sql_formato\n", FILE_APPEND);

    $resultado = @odbc_exec($con, $sql_formato);
    
    if (!$resultado) {
        $error_msg = "Error al insertar formato: " . odbc_errormsg($con);
        file_put_contents($debug_log, $error_msg . "\n", FILE_APPEND);
        throw new Exception($error_msg);
    }

    file_put_contents($debug_log, "INSERT exitoso\n", FILE_APPEND);

    /* ================================
       4️⃣ OBTENER ID INSERTADO
    ================================ */

    $sql_id = "SELECT CAST(IDENT_CURRENT('formatos_temperatura') AS INT) AS id_formato";
    $res_id = @odbc_exec($con, $sql_id);
    
    $id_formato = null;
    
    if ($res_id) {
        $row_id = @odbc_fetch_array($res_id);
        if ($row_id && isset($row_id['id_formato'])) {
            $id_formato = (int)$row_id['id_formato'];
        }
    }
    
    // Método alternativo si falla
    if (!$id_formato) {
        $sql_max = "SELECT MAX(id_formato) AS id_formato FROM formatos_temperatura";
        $res_max = @odbc_exec($con, $sql_max);
        if ($res_max) {
            $row_max = @odbc_fetch_array($res_max);
            if ($row_max && isset($row_max['id_formato'])) {
                $id_formato = (int)$row_max['id_formato'];
            }
        }
    }

    if (!$id_formato || $id_formato <= 0) {
        throw new Exception("No se pudo obtener el ID del formato insertado");
    }

    file_put_contents($debug_log, "ID formato obtenido: $id_formato\n", FILE_APPEND);

    /* ================================
       5️⃣ GUARDAR TEMPERATURA AMBIENTE
    ================================ */

    $temp = isset($_POST['temp']) ? $_POST['temp'] : array();

    foreach ($temp as $dia => $turnos) {
        foreach ($turnos as $turno => $temperatura) {
            if (!empty($temperatura)) {
                $sql_temp = "INSERT INTO control_temperatura (id_formato, dia, turno, temperatura) 
                             VALUES ($id_formato, $dia, '$turno', $temperatura)";
                @odbc_exec($con, $sql_temp);
            }
        }
    }

    /* ================================
       6️⃣ GUARDAR HUMEDAD RELATIVA
    ================================ */

    $hum = isset($_POST['hum']) ? $_POST['hum'] : array();

    foreach ($hum as $dia => $turnos) {
        foreach ($turnos as $turno => $humedad) {
            if (!empty($humedad)) {
                $sql_hum = "INSERT INTO control_humedad (id_formato, dia, turno, humedad) 
                            VALUES ($id_formato, $dia, '$turno', '$humedad')";
                @odbc_exec($con, $sql_hum);
            }
        }
    }

    /* ================================
       7️⃣ GUARDAR CADENA DE FRÍO
    ================================ */

    $frio = isset($_POST['frio']) ? $_POST['frio'] : array();

    foreach ($frio as $dia => $turnos) {
        foreach ($turnos as $turno => $temperatura_frio) {
            if (!empty($temperatura_frio) || $temperatura_frio === '0') {
                $sql_frio = "INSERT INTO control_frio (id_formato, dia, turno, temperatura) 
                             VALUES ($id_formato, $dia, '$turno', $temperatura_frio)";
                @odbc_exec($con, $sql_frio);
            }
        }
    }

    /* ================================
       8️⃣ GUARDAR FIRMAS Y Vo.Bo
    ================================ */

    $firma = isset($_POST['firma']) ? $_POST['firma'] : array();
    $vobo  = isset($_POST['vobo'])  ? $_POST['vobo']  : array();

    for ($dia = 1; $dia <= 30; $dia++) {
        foreach (array('6', '14', '22') as $hora) {

            $val_firma = isset($firma[$dia][$hora]) ? trim($firma[$dia][$hora]) : '';
            $val_vobo  = isset($vobo[$dia][$hora])  ? trim($vobo[$dia][$hora])  : '';

            // Si ambos vienen vacíos, no insertar
            if ($val_firma === '' && $val_vobo === '') {
                continue;
            }

            $sql_firmas = "INSERT INTO control_firmas (id_formato, dia, hora, firma, vobo) 
                           VALUES ($id_formato, $dia, '$hora', '$val_firma', '$val_vobo')";

            @odbc_exec($con, $sql_firmas);
        }
    }

    /* ================================
       9️⃣ COMMIT
    ================================ */
    
    $commit = @odbc_exec($con, "COMMIT");
    if (!$commit) {
        throw new Exception("Error al confirmar la transacción");
    }

    @odbc_close($con);

    file_put_contents($debug_log, "=== ÉXITO - ID: $id_formato ===\n\n", FILE_APPEND);

    // Respuesta de éxito
    http_response_code(200);
    echo "<!DOCTYPE html>";
    echo "<html><head><meta charset='utf-8'><script>
    window.setTimeout(function() {
        window.location.href = 'ver_formatos_temp.php';
    }, 2000);
    </script></head><body>";
    echo "<h2>✅ Formato de temperatura guardado exitosamente</h2>";
    echo "<p>ID del formato: <strong>$id_formato</strong></p>";
    echo "<p>Redirigiendo en 2 segundos...</p>";
    echo "<p><a href='ver_formatos_temp.php'>📋 O haz clic aquí para ver todos los formatos</a></p>";
    echo "</body></html>";
    exit;

    foreach ($hum as $dia => $turnos) {
        foreach ($turnos as $turno => $humedad) {
            if (!empty($humedad)) {
                $sql_hum = "INSERT INTO control_humedad (id_formato, dia, turno, humedad) 
                            VALUES ($id_formato, $dia, '$turno', $humedad)";
                @odbc_exec($con, $sql_hum);
            }
        }
    }

    /* ================================
       7️⃣ GUARDAR CADENA DE FRÍO
    ================================ */

    $frio = isset($_POST['frio']) ? $_POST['frio'] : array();

    foreach ($frio as $dia => $turnos) {
        foreach ($turnos as $turno => $temperatura_frio) {
            if (!empty($temperatura_frio) || $temperatura_frio === '0') {
                $sql_frio = "INSERT INTO control_frio (id_formato, dia, turno, temperatura) 
                             VALUES ($id_formato, $dia, '$turno', $temperatura_frio)";
                @odbc_exec($con, $sql_frio);
            }
        }
    }

    /* ================================
       8️⃣ GUARDAR FIRMAS Y Vo.Bo
    ================================ */

    $firma = isset($_POST['firma']) ? $_POST['firma'] : array();
    $vobo  = isset($_POST['vobo'])  ? $_POST['vobo']  : array();

    for ($dia = 1; $dia <= 30; $dia++) {
        foreach (array('6', '14', '22') as $hora) {

            $val_firma = isset($firma[$dia][$hora]) ? trim($firma[$dia][$hora]) : '';
            $val_vobo  = isset($vobo[$dia][$hora])  ? trim($vobo[$dia][$hora])  : '';

            // Si ambos vienen vacíos, no insertar
            if ($val_firma === '' && $val_vobo === '') {
                continue;
            }

            $sql_firmas = "INSERT INTO control_firmas (id_formato, dia, hora, firma, vobo) 
                           VALUES ($id_formato, $dia, '$hora', '$val_firma', '$val_vobo')";

            @odbc_exec($con, $sql_firmas);
        }
    }

    /* ================================
       9️⃣ COMMIT
    ================================ */
    
    $commit = @odbc_exec($con, "COMMIT");
    if (!$commit) {
        throw new Exception("Error al confirmar la transacción");
    }

    @odbc_close($con);

    // Redirigir con éxito
    echo "<!DOCTYPE html>";
    echo "<html><head><meta charset='utf-8'><script>
    window.setTimeout(function() {
        window.location.href = 'ver_formatos_temp.php';
    }, 2000);
    </script></head><body>";
    echo "<h2>✅ Formato de temperatura guardado exitosamente</h2>";
    echo "<p>ID del formato: <strong>$id_formato</strong></p>";
    echo "<p>Redirigiendo en 2 segundos...</p>";
    echo "<p><a href='ver_formatos_temp.php'>📋 O haz clic aquí para ver todos los formatos</a></p>";
    echo "</body></html>";
    exit;

} catch (Exception $e) {

    /* ================================
       ROLLBACK SI FALLA ALGO
    ================================ */
    
    @odbc_exec($con, "ROLLBACK");
    @odbc_close($con);

    $error_msg = $e->getMessage();
    file_put_contents($debug_log, "=== ERROR ===" . $error_msg . "\n\n", FILE_APPEND);

    http_response_code(400);
    echo "<!DOCTYPE html>";
    echo "<html><head><meta charset='utf-8'><title>Error</title>";
    echo "<style>body{font-family:Arial;padding:20px;} .error{background:#ffebee;border:1px solid #f44336;padding:15px;border-radius:5px;color:#c62828;}</style>";
    echo "</head><body>";
    echo "<div class='error'>";
    echo "<h3>❌ Error al guardar el formato</h3>";
    echo "<p><strong>Detalle:</strong> " . htmlspecialchars($error_msg) . "</p>";
    echo "</div>";
    echo "<br><a href='javascript:history.back()'>← Volver al formulario</a>";
    echo "</body></html>";
    exit;
}
?>
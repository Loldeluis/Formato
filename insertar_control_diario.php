<?php
// Mostrar errores para debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar que el archivo de conexión existe
if (!file_exists("conexion.php")) {
    die("Error: No se encuentra el archivo conexion.php");
}

include("conexion.php");

// Verificar que las variables de conexión están definidas
if (!isset($dsn) || !isset($usudb) || !isset($pwdb)) {
    die("Error: Variables de conexión no definidas en conexion.php");
}

$con = @odbc_connect($dsn, $usudb, $pwdb);

if (!$con) {
    die("Error de conexión a la base de datos: " . odbc_errormsg());
}

try {
    /* ================================
       1️⃣ RECIBIR Y VALIDAR DATOS
    ================================ */
    
    $mes   = isset($_POST['mes'])   ? str_replace("'", "''", trim($_POST['mes']))   : '';
    $anio  = isset($_POST['anio'])  ? str_replace("'", "''", trim($_POST['anio']))  : '';
    $sede  = isset($_POST['sede'])  ? str_replace("'", "''", trim($_POST['sede']))  : '';
    $movil = isset($_POST['movil']) ? str_replace("'", "''", trim($_POST['movil'])) : '';
    
    $prod_limpieza      = isset($_POST['prod_limpieza'])      ? str_replace("'", "''", trim($_POST['prod_limpieza']))      : '';
    $conc_limpieza      = isset($_POST['conc_limpieza'])      ? str_replace("'", "''", trim($_POST['conc_limpieza']))      : '';
    $prod_desinfectante = isset($_POST['prod_desinfectante']) ? str_replace("'", "''", trim($_POST['prod_desinfectante'])) : '';
    $conc_desinfectante = isset($_POST['conc_desinfectante']) ? str_replace("'", "''", trim($_POST['conc_desinfectante'])) : '';
    $observaciones      = isset($_POST['observaciones'])      ? str_replace("'", "''", trim($_POST['observaciones']))      : '';

    /* ================================
       2️⃣ INICIAR TRANSACCIÓN
    ================================ */
    
    $begin = @odbc_exec($con, "BEGIN TRANSACTION");
    if (!$begin) {
        throw new Exception("No se pudo iniciar la transacción");
    }

    /* ================================
       3️⃣ INSERT FORMATO PRINCIPAL
    ================================ */

    $sql_formato = "INSERT INTO formatos_limpieza 
                    (mes, anio, sede, movil, prod_limpieza, conc_limpieza, 
                     prod_desinfectante, conc_desinfectante, observaciones, fecha_registro)
                    VALUES ('$mes', '$anio', '$sede', '$movil', '$prod_limpieza', 
                            '$conc_limpieza', '$prod_desinfectante', '$conc_desinfectante', 
                            '$observaciones', GETDATE())";

    $resultado = @odbc_exec($con, $sql_formato);
    
    if (!$resultado) {
        throw new Exception("Error al insertar formato: " . odbc_errormsg($con));
    }

    /* ================================
       4️⃣ OBTENER ID INSERTADO
    ================================ */

    // Método 1: IDENT_CURRENT
    $sql_id = "SELECT CAST(IDENT_CURRENT('formatos_limpieza') AS INT) AS id_formato";
    $res_id = @odbc_exec($con, $sql_id);
    
    $id_formato = null;
    
    if ($res_id) {
        $row_id = @odbc_fetch_array($res_id);
        if ($row_id && isset($row_id['id_formato'])) {
            $id_formato = (int)$row_id['id_formato'];
        }
    }
    
    // Método 2: Si falla, usar MAX
    if (!$id_formato) {
        $sql_max = "SELECT MAX(id_formato) AS id_formato FROM formatos_limpieza";
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

    /* ================================
       5️⃣ INSERT CONTROL (CHECKBOXES)
    ================================ */

    $control = isset($_POST['control']) ? $_POST['control'] : array();
    $extra   = isset($_POST['extra'])   ? $_POST['extra']   : array();

    $areas = array(
        0 => "Cajón de vía circulatoria",
        1 => "Cajón de vía respiratoria",
        2 => "Cajón de quirúrgicos",
        3 => "Cajón de pediátricos",
        4 => "Cajón de accesorios",
        5 => "Estante donde van los equipos biomédicos",
        6 => "Monitor de signos vitales",
        7 => "Desfibrilador",
        8 => "Aspirador de secreciones",
        9 => "Bombas de infusión",
        10 => "Ventilador",
        11 => "Soporte y redes de oxígeno",
        12 => "Sillas de acompañante",
        13 => "Sillas de la tripulación",
        14 => "Camilla",
        15 => "Barandas de la camilla",
        16 => "Atril portátil",
        17 => "Silla de ruedas"
    );

    // Procesar checkboxes de control
    foreach ($control as $indice_area => $dias) {
        $nombre_area = isset($areas[$indice_area]) ? $areas[$indice_area] : "Área desconocida";
        
        foreach ($dias as $dia => $turnos) {
            foreach ($turnos as $turno => $valor) {
                if ($valor) {
                    $sql_control = "INSERT INTO control_limpieza (id_formato, area, dia, turno)
                                   VALUES ($id_formato, '$nombre_area', $dia, '$turno')";
                    @odbc_exec($con, $sql_control);
                }
            }
        }
    }

    // Procesar checkboxes extra
    $extras = array(
        0 => "Paredes y piso de la ambulancia",
        1 => "Canecas"
    );

    foreach ($extra as $indice_extra => $dias) {
        $nombre_extra = isset($extras[$indice_extra]) ? $extras[$indice_extra] : "Extra desconocido";
        
        foreach ($dias as $dia => $turnos) {
            foreach ($turnos as $turno => $valor) {
                if ($valor) {
                    $sql_extra = "INSERT INTO control_extra (id_formato, area, dia, turno)
                                 VALUES ($id_formato, '$nombre_extra', $dia, '$turno')";
                    @odbc_exec($con, $sql_extra);
                }
            }
        }
    }

    /* ================================
       6️⃣ INSERT CONTROL DIARIO
    ================================ */

    $realiza   = isset($_POST['realiza'])   ? $_POST['realiza']   : array();
    $supervisa = isset($_POST['supervisa']) ? $_POST['supervisa'] : array();

    for ($dia = 1; $dia <= 31; $dia++) {
        foreach (array('M', 'T', 'N') as $turno) {

            $val_realiza   = isset($realiza[$dia][$turno])   ? str_replace("'", "''", trim($realiza[$dia][$turno]))   : '';
            $val_supervisa = isset($supervisa[$dia][$turno]) ? str_replace("'", "''", trim($supervisa[$dia][$turno])) : '';

            // Si ambos vienen vacíos, no insertar
            if ($val_realiza === '' && $val_supervisa === '') {
                continue;
            }

            $sql_diario = "INSERT INTO control_diario (id_formato, dia, turno, realiza, supervisa)
                          VALUES ($id_formato, $dia, '$turno', '$val_realiza', '$val_supervisa')";

            @odbc_exec($con, $sql_diario);
        }
    }

    /* ================================
       7️⃣ COMMIT
    ================================ */
    
    $commit = @odbc_exec($con, "COMMIT");
    if (!$commit) {
        throw new Exception("Error al confirmar la transacción");
    }

    // Cerrar conexión
    @odbc_close($con);

    // Redirigir con éxito
    echo "<!DOCTYPE html>";
    echo "<html><head><meta charset='utf-8'><script>
    window.setTimeout(function() {
        window.location.href = 'ver_formatos_control_diario.php';
    }, 2000);
    </script></head><body>";
    echo "<h2>✅ Control diario guardado exitosamente</h2>";
    echo "<p>ID del formato: <strong>$id_formato</strong></p>";
    echo "<p>Redirigiendo en 2 segundos...</p>";
    echo "<p><a href='ver_formatos_control_diario.php'>📋 O haz clic aquí para ver todos los formatos</a></p>";
    echo "</body></html>";
    exit;

} catch (Exception $e) {

    /* ================================
       ROLLBACK SI FALLA ALGO
    ================================ */
    
    @odbc_exec($con, "ROLLBACK");
    @odbc_close($con);

    echo "<!DOCTYPE html>";
    echo "<html><head><meta charset='utf-8'><title>Error</title>";
    echo "<style>body{font-family:Arial;padding:20px;} .error{background:#ffebee;border:1px solid #f44336;padding:15px;border-radius:5px;}</style>";
    echo "</head><body>";
    echo "<div class='error'>";
    echo "<h3>❌ Error al guardar el control diario</h3>";
    echo "<p><strong>Mensaje:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div>";
    echo "<br><a href='javascript:history.back()'>← Volver al formulario</a>";
    echo "</body></html>";
}
?>

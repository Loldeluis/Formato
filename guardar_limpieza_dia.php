<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

include("conexion.php");
$con = odbc_connect($dsn, $usudb, $pwdb);

if (!$con) {
    die("Error de conexión");
}

try {
    odbc_exec($con, "BEGIN TRANSACTION");

    $id_formato = isset($_POST['id_formato']) && !empty($_POST['id_formato']) ? (int)$_POST['id_formato'] : null;
    $movil = trim($_POST['movil']);
    $mes_numerico = (int)$_POST['mes_numerico'];
    $anio_numerico = (int)$_POST['anio_numerico'];
    $dia_actual = (int)$_POST['dia_actual'];
    $accion = isset($_POST['accion']) ? $_POST['accion'] : 'guardar';

    // Si no existe formato, crear uno nuevo
    if (!$id_formato) {
        $mes = trim($_POST['mes']);
        $anio = trim($_POST['anio']);
        $sede = trim($_POST['sede']);

        $sql_nuevo = "INSERT INTO dbo.formatos_limpieza 
                      (mes, anio, sede, movil, mes_numerico, anio_numerico, 
                       estado, ultimo_dia_editado, fecha_registro)
                      VALUES ('$mes', '$anio', '$sede', '$movil', 
                              $mes_numerico, $anio_numerico, 'en_progreso', 
                              $dia_actual, GETDATE())";

        $result = odbc_exec($con, $sql_nuevo);
        if (!$result) {
            throw new Exception("Error al crear formato: " . odbc_errormsg($con));
        }

        // Obtener ID
        $sql_id = "SELECT CAST(IDENT_CURRENT('dbo.formatos_limpieza') AS INT) AS id_formato";
        $res_id = odbc_exec($con, $sql_id);
        $row_id = odbc_fetch_array($res_id);
        $id_formato = (int)$row_id['id_formato'];
    } else {
        // Actualizar último día editado
        $sql_update = "UPDATE dbo.formatos_limpieza 
                       SET ultimo_dia_editado = $dia_actual 
                       WHERE id_formato = $id_formato";
        odbc_exec($con, $sql_update);
    }

    // Eliminar datos del día actual (para permitir sobrescribir)
    $sql_delete_control = "DELETE FROM dbo.control_limpieza 
                           WHERE id_formato = $id_formato AND dia = $dia_actual";
    odbc_exec($con, $sql_delete_control);

    $sql_delete_diario = "DELETE FROM dbo.control_diario 
                          WHERE id_formato = $id_formato AND dia = $dia_actual";
    odbc_exec($con, $sql_delete_diario);

    // Guardar checkboxes del día actual
    $control = isset($_POST['control']) ? $_POST['control'] : array();
    
    $areas = [
        "Cajón de vía circulatoria", "Cajón de vía respiratoria", "Cajón de quirúrgicos",
        "Cajón de pediátricos", "Cajón de accesorios", "Estante donde van los equipos biomédicos",
        "Monitor de signos vitales", "Desfibrilador", "Aspirador de secreciones",
        "Bombas de infusión", "Ventilador", "Soporte y redes de oxígeno",
        "Sillas de acompañante", "Sillas de la tripulación", "Camilla",
        "Barandas de la camilla", "Atril portátil", "Silla de ruedas"
    ];

    foreach ($control as $indice_area => $dias) {
        $nombre_area = $areas[$indice_area];
        
        if (isset($dias[$dia_actual])) {
            foreach ($dias[$dia_actual] as $turno => $valor) {
                if ($valor === 'on') {
                    $sql_check = "INSERT INTO dbo.control_limpieza (id_formato, area, dia, turno)
                                  VALUES ($id_formato, '$nombre_area', $dia_actual, '$turno')";
                    odbc_exec($con, $sql_check);
                }
            }
        }
    }

    // Guardar control diario
    $realiza = isset($_POST['realiza']) ? $_POST['realiza'] : array();
    $supervisa = isset($_POST['supervisa']) ? $_POST['supervisa'] : array();

    if (isset($realiza[$dia_actual])) {
        foreach (array('M', 'T', 'N') as $turno) {
            $val_realiza = isset($realiza[$dia_actual][$turno]) ? trim($realiza[$dia_actual][$turno]) : '';
            $val_supervisa = isset($supervisa[$dia_actual][$turno]) ? trim($supervisa[$dia_actual][$turno]) : '';

            if ($val_realiza !== '' || $val_supervisa !== '') {
                $sql_diario = "INSERT INTO dbo.control_diario (id_formato, dia, turno, realiza, supervisa)
                               VALUES ($id_formato, $dia_actual, '$turno', '$val_realiza', '$val_supervisa')";
                odbc_exec($con, $sql_diario);
            }
        }
    }

    // Si marcó como completado
    if ($accion === 'completar') {
        $sql_completar = "UPDATE dbo.formatos_limpieza 
                          SET estado = 'completado' 
                          WHERE id_formato = $id_formato";
        odbc_exec($con, $sql_completar);
    }

    odbc_exec($con, "COMMIT");
    odbc_close($con);

    // Redirigir
    if ($accion === 'completar') {
        header("Location: dashboard.php?msg=completado");
    } else {
        header("Location: formato_limpieza.php?movil=" . urlencode($movil) . "&guardado=1");
    }
    exit;

} catch (Exception $e) {
    odbc_exec($con, "ROLLBACK");
    odbc_close($con);
    
    echo "<!DOCTYPE html>";
    echo "<html><head><meta charset='utf-8'></head><body>";
    echo "<h3>❌ Error al guardar</h3>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<a href='javascript:history.back()'>← Volver</a>";
    echo "</body></html>";
}
?>
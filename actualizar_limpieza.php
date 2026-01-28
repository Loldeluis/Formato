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

    $id_formato = (int)$_POST['id_formato'];
    $mes = trim($_POST['mes']);
    $anio = trim($_POST['anio']);
    $sede = trim($_POST['sede']);
    $movil = trim($_POST['movil']);
    $prod_limpieza = isset($_POST['prod_limpieza']) ? trim($_POST['prod_limpieza']) : '';
    $conc_limpieza = isset($_POST['conc_limpieza']) ? trim($_POST['conc_limpieza']) : '';
    $prod_desinfectante = isset($_POST['prod_desinfectante']) ? trim($_POST['prod_desinfectante']) : '';
    $conc_desinfectante = isset($_POST['conc_desinfectante']) ? trim($_POST['conc_desinfectante']) : '';
    $observaciones = isset($_POST['observaciones']) ? trim($_POST['observaciones']) : '';

    // 1. Actualizar datos del formato
    $sql_update_formato = "UPDATE formatos_limpieza 
                           SET mes = '$mes', 
                               anio = '$anio', 
                               sede = '$sede', 
                               movil = '$movil',
                               prod_limpieza = '$prod_limpieza',
                               conc_limpieza = '$conc_limpieza',
                               prod_desinfectante = '$prod_desinfectante',
                               conc_desinfectante = '$conc_desinfectante',
                               observaciones = '$observaciones'
                           WHERE id_formato = $id_formato";
    
    $result = odbc_exec($con, $sql_update_formato);
    if (!$result) {
        throw new Exception("Error al actualizar formato: " . odbc_errormsg($con));
    }

    // 2. Eliminar todos los datos de control anteriores
    $sql_delete_control = "DELETE FROM control_limpieza WHERE id_formato = $id_formato";
    odbc_exec($con, $sql_delete_control);

    $sql_delete_diario = "DELETE FROM control_diario WHERE id_formato = $id_formato";
    odbc_exec($con, $sql_delete_diario);

    // 3. Insertar nuevos datos de control (checkboxes)
    $control = isset($_POST['control']) ? $_POST['control'] : array();
    $extra = isset($_POST['extra']) ? $_POST['extra'] : array();

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
        
        foreach ($dias as $dia => $turnos) {
            foreach ($turnos as $turno => $valor) {
                if ($valor === 'on') {
                    $sql_check = "INSERT INTO control_limpieza (id_formato, area, dia, turno)
                                  VALUES ($id_formato, '$nombre_area', $dia, '$turno')";
                    odbc_exec($con, $sql_check);
                }
            }
        }
    }

    // Extras
    $extras = ["Paredes y piso de la ambulancia", "Canecas"];
    foreach ($extra as $indice_extra => $dias) {
        $nombre_extra = $extras[$indice_extra];
        
        foreach ($dias as $dia => $turnos) {
            foreach ($turnos as $turno => $valor) {
                if ($valor === 'on') {
                    $sql_check = "INSERT INTO control_limpieza (id_formato, area, dia, turno)
                                  VALUES ($id_formato, '$nombre_extra', $dia, '$turno')";
                    odbc_exec($con, $sql_check);
                }
            }
        }
    }

    // 4. Insertar nuevos datos de control diario
    $realiza = isset($_POST['realiza']) ? $_POST['realiza'] : array();
    $supervisa = isset($_POST['supervisa']) ? $_POST['supervisa'] : array();

    for ($dia = 1; $dia <= 31; $dia++) {
        foreach (array('M', 'T', 'N') as $turno) {
            $val_realiza = isset($realiza[$dia][$turno]) ? trim($realiza[$dia][$turno]) : '';
            $val_supervisa = isset($supervisa[$dia][$turno]) ? trim($supervisa[$dia][$turno]) : '';

            if ($val_realiza !== '' || $val_supervisa !== '') {
                $sql_diario = "INSERT INTO control_diario (id_formato, dia, turno, realiza, supervisa)
                               VALUES ($id_formato, $dia, '$turno', '$val_realiza', '$val_supervisa')";
                odbc_exec($con, $sql_diario);
            }
        }
    }

    odbc_exec($con, "COMMIT");
    odbc_close($con);

    // Redirigir con mensaje de éxito
    header("Location: ver_formatos.php?actualizado=1");
    exit;

} catch (Exception $e) {
    odbc_exec($con, "ROLLBACK");
    odbc_close($con);
    
    echo "<!DOCTYPE html>";
    echo "<html><head><meta charset='utf-8'></head><body>";
    echo "<h3>❌ Error al actualizar</h3>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<a href='javascript:history.back()'>← Volver</a>";
    echo "</body></html>";
}
?>
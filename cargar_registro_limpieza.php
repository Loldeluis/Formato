<?php
// Este archivo carga un registro de la BD y lo devuelve como JSON
include("conexion.php");

header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'ID no proporcionado']);
    exit;
}

$id_formato = (int)$_GET['id'];

$con = @odbc_connect($dsn, $usudb, $pwdb);

if (!$con) {
    echo json_encode(['error' => 'Error de conexión a la base de datos']);
    exit;
}

try {
    // 1. Obtener datos del formato principal
    $sql_formato = "SELECT * FROM formatos_limpieza WHERE id_formato = $id_formato";
    $res_formato = @odbc_exec($con, $sql_formato);
    
    if (!$res_formato) {
        throw new Exception("Error al consultar formato");
    }
    
    $formato = @odbc_fetch_array($res_formato);
    
    if (!$formato) {
        throw new Exception("Registro no encontrado");
    }
    
    // 2. Obtener datos de control_limpieza
    $sql_control = "SELECT * FROM control_limpieza WHERE id_formato = $id_formato ORDER BY area, dia, turno";
    $res_control = @odbc_exec($con, $sql_control);
    
    $control_data = [];
    while ($row = @odbc_fetch_array($res_control)) {
        $control_data[] = $row;
    }
    
    // 3. Obtener datos de control_diario
    $sql_diario = "SELECT * FROM control_diario WHERE id_formato = $id_formato ORDER BY dia, turno";
    $res_diario = @odbc_exec($con, $sql_diario);
    
    $diario_data = [];
    while ($row = @odbc_fetch_array($res_diario)) {
        $diario_data[] = $row;
    }
    
    // 4. Armar respuesta
    $response = [
        'id_formato' => $formato['id_formato'],
        'mes' => $formato['mes'],
        'anio' => $formato['anio'],
        'sede' => $formato['sede'],
        'movil' => $formato['movil'],
        'prod_limpieza' => $formato['prod_limpieza'],
        'conc_limpieza' => $formato['conc_limpieza'],
        'prod_desinfectante' => $formato['prod_desinfectante'],
        'conc_desinfectante' => $formato['conc_desinfectante'],
        'observaciones' => $formato['observaciones'],
        'control' => $control_data,
        'diario' => $diario_data
    ];
    
    echo json_encode($response);
    
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    @odbc_close($con);
}
?>

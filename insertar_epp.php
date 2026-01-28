<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include("conexion.php");

$con = odbc_connect($dsn, $usudb, $pwdb);
if (!$con) {
    die("Error de conexión: " . odbc_errormsg());
}

try {

    /* =========================
       1. DATOS PRINCIPALES
    ========================= */

    $servicio       = str_replace("'", "''", trim($_POST['servicio'] ?? ''));
    $sede           = str_replace("'", "''", trim($_POST['sede'] ?? ''));
    $entregado_por  = str_replace("'", "''", trim($_POST['entregado_por'] ?? ''));
    $fecha_entrega  = trim($_POST['fecha_entrega'] ?? '');

    $total_gorros   = (int)($_POST['total_gorros'] ?? 0);
    $total_batas    = (int)($_POST['total_batas'] ?? 0);
    $total_pijamas  = (int)($_POST['total_pijamas'] ?? 0);
    $total_masc_q   = (int)($_POST['total_masc_q'] ?? 0);
    $total_masc_n95 = (int)($_POST['total_masc_n95'] ?? 0);
    $total_polainas = (int)($_POST['total_polainas'] ?? 0);
    $total_overoles = (int)($_POST['total_overoles'] ?? 0);

    /* =========================
       2. TRANSACCIÓN
    ========================= */

    odbc_exec($con, "BEGIN TRANSACTION");

    /* =========================
       3. INSERT FORMATO
    ========================= */

  $sql_formato = "
INSERT INTO dbo.formatos_epp
(servicio, sede, entregado_por, fecha_entrega,
 total_gorros, total_batas, total_pijamas,
 total_masc_q, total_masc_n95, total_polainas, total_overoles)
OUTPUT INSERTED.id_formato
VALUES
('$servicio','$sede','$entregado_por','$fecha_entrega',
 $total_gorros,$total_batas,$total_pijamas,
 $total_masc_q,$total_masc_n95,$total_polainas,$total_overoles)
";

$res = odbc_exec($con, $sql_formato);
$row = odbc_fetch_array($res);

$id_formato = (int)$row['id_formato'];

if ($id_formato <= 0) {
    die("No se pudo obtener el ID del formato");
}


    /* =========================
       5. INSERTAR DETALLES
    ========================= */

    $epp = $_POST['epp'] ?? [];

    foreach ($epp as $fila => $row) {

        $fecha  = trim($row['fecha'] ?? '');
        $turno  = str_replace("'", "''", trim($row['turno'] ?? ''));
        $nombre = str_replace("'", "''", trim($row['nombre'] ?? ''));

        $gorros     = (int)($row['gorros'] ?? 0);
        $batas      = (int)($row['batas'] ?? 0);
        $pijamas    = (int)($row['pijamas'] ?? 0);
        $masc_q     = (int)($row['masc_q'] ?? 0);
        $masc_n95   = (int)($row['masc_n95'] ?? 0);
        $polainas   = (int)($row['polainas'] ?? 0);
        $overoles   = (int)($row['overoles'] ?? 0);
        $obs        = str_replace("'", "''", trim($row['obs'] ?? ''));

        // Si la fila está completamente vacía, no insertar
        if (
            $fecha === '' && $turno === '' && $nombre === '' &&
            $gorros === 0 && $batas === 0 && $pijamas === 0 &&
            $masc_q === 0 && $masc_n95 === 0 &&
            $polainas === 0 && $overoles === 0 && $obs === ''
        ) {
            continue;
        }

        $sql_detalle = "
            INSERT INTO detalles_epp
            (id_formato, fila, fecha, turno, nombre,
             gorros, batas, pijamas, masc_quirurgica,
             masc_n95, polainas, overoles, observacion)
            VALUES (
                $id_formato, $fila, 
                " . ($fecha ? "'$fecha'" : "NULL") . ",
                '$turno', '$nombre',
                $gorros, $batas, $pijamas, $masc_q,
                $masc_n95, $polainas, $overoles, '$obs'
            )
        ";

        if (!odbc_exec($con, $sql_detalle)) {
            throw new Exception("Error al insertar detalle fila $fila");
        }
    }

    /* =========================
       6. COMMIT
    ========================= */

    odbc_exec($con, "COMMIT");
    odbc_close($con);

    echo "<!DOCTYPE html>";
    echo "<html><head><meta charset='utf-8'><script>
    window.setTimeout(function() {
        window.location.href = 'ver_formatos_epp.php';
    }, 2000);
    </script></head><body>";
    echo "<h2>✅ Formato de EPP guardado exitosamente</h2>";
    echo "<p>ID del formato: <strong>$id_formato</strong></p>";
    echo "<p>Redirigiendo en 2 segundos...</p>";
    echo "<p><a href='ver_formatos_epp.php'>📋 O haz clic aquí para ver todos los formatos</a></p>";
    echo "</body></html>";
    exit;

} catch (Exception $e) {

    odbc_exec($con, "ROLLBACK");
    odbc_close($con);

    echo "<h3>Error al guardar formato EPP</h3>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<a href='javascript:history.back()'>Volver</a>";
}
?>

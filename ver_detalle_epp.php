<?php 
include("conexion.php");

$con = odbc_connect($dsn, $usudb, $pwdb);
if (!$con) die("Error de conexión");

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) die("ID no válido");

// Obtener formato principal
$sql_formato = "SELECT * FROM dbo.formatos_epp WHERE id_formato = $id";
$result_formato = odbc_exec($con, $sql_formato);
$formato = odbc_fetch_array($result_formato);
if (!$formato) die("Formato no encontrado");

// Obtener detalles
$sql_detalles = "SELECT * FROM dbo.detalles_epp WHERE id_formato = $id ORDER BY fila";
$result_detalles = odbc_exec($con, $sql_detalles);
$detalles = array();
while ($row = odbc_fetch_array($result_detalles)) {
    $detalles[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detalle Formato EPP</title>
<link href="css/bootstrap.min.css" rel="stylesheet" />
<style>
body { 
    font-family: Arial; 
    padding: 20px; 
}
.container { 
    max-width: 100%; 
    margin: 0 auto; 
}
h3 { 
    text-align: center; 
    color: #a8814e; 
    margin: 20px 0; 
}
.header-info { 
    background: #f5f5f5; 
    padding: 15px; 
    border-radius: 5px; 
    margin-bottom: 20px; 
    border: 2px solid #205ca4; 
}
.header-info strong { 
    color: #205ca4; 
}
.table-scroll {
    width: 100%;
    overflow-x: auto;
    margin: 20px 0;
}
table { 
    width: 100%; 
    border-collapse: collapse; 
    margin: 20px 0; 
}
table, th, td { 
    border: 1px solid #000; 
}
th, td { 
    padding: 8px; 
    text-align: center; 
    font-size: 13px; 
}
th { 
    background: #e0e0e0; 
    font-weight: bold; 
}
.btn-back, .btn-print { 
    padding: 10px 20px; 
    border-radius: 5px; 
    text-decoration: none; 
    display: inline-block; 
    margin: 10px 5px; 
}
.btn-back { 
    background: #205ca4; 
    color: white; 
}
.btn-print { 
    background: #28a745; 
    color: white; 
}
.total-row {
    background: #ffffcc;
    font-weight: bold;
}
.summary-box {
    background: #e8f5e9;
    padding: 15px;
    border-radius: 5px;
    margin: 20px 0;
    border: 2px solid #4caf50;
}
.summary-box h4 {
    color: #2e7d32;
    margin-top: 0;
}
.summary-item {
    display: inline-block;
    margin: 5px 15px;
}
@media print { 
    .btn-back, .btn-print { 
        display: none; 
    } 
}
</style>
</head>
<body>

<div class="container">
    <div align="center">
        <img src="img/logo_dos.png" width="262" height="81" alt="Logo" />
    </div>

    <h3>FORMATO DE RELACIÓN DE GASTOS DIARIOS DE EPP</h3>

    <a href="ver_formatos_epp.php" class="btn-back">← Volver</a>
    <a href="javascript:window.print()" class="btn-print">🖨️ Imprimir</a>

    <div class="header-info">
        <strong>Servicio:</strong> <?= htmlspecialchars($formato['servicio']) ?> &nbsp;&nbsp;
        <strong>Sede:</strong> <?= htmlspecialchars($formato['sede']) ?><br>
        <strong>Entregado por:</strong> <?= htmlspecialchars($formato['entregado_por']) ?> &nbsp;&nbsp;
        <strong>Fecha de entrega:</strong> <?= $formato['fecha_entrega'] ?>
    </div>


    <?php if (count($detalles) > 0): ?>
    <h4>Detalle de Entregas</h4>
    <div class="table-scroll">
        <table>
            <thead>
                <tr>
                    <th rowspan="2">Fila</th>
                    <th rowspan="2">Fecha</th>
                    <th rowspan="2">Turno</th>
                    <th rowspan="2">Nombre</th>
                    <th colspan="7">Elementos de Protección Personal</th>
                    <th rowspan="2">Observación</th>
                </tr>
                <tr>
                    <th>Gorros</th>
                    <th>Batas</th>
                    <th>Pijamas</th>
                    <th>Mascarilla<br>Quirúrgica</th>
                    <th>Mascarilla<br>N95</th>
                    <th>Polainas</th>
                    <th>Overoles</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sum_gorros = 0;
                $sum_batas = 0;
                $sum_pijamas = 0;
                $sum_masc_q = 0;
                $sum_masc_n95 = 0;
                $sum_polainas = 0;
                $sum_overoles = 0;
                
                foreach ($detalles as $det): 
                    $sum_gorros += $det['gorros'];
                    $sum_batas += $det['batas'];
                    $sum_pijamas += $det['pijamas'];
                    $sum_masc_q += $det['masc_quirurgica'];
                    $sum_masc_n95 += $det['masc_n95'];
                    $sum_polainas += $det['polainas'];
                    $sum_overoles += $det['overoles'];
                ?>
                <tr>
                    <td><?= $det['fila'] ?></td>
                    <td><?= $det['fecha'] ? $det['fecha'] : '-' ?></td>
                    <td><?= htmlspecialchars($det['turno']) ?></td>
                    <td style="text-align:left;"><?= htmlspecialchars($det['nombre']) ?></td>
                    <td><?= $det['gorros'] > 0 ? $det['gorros'] : '-' ?></td>
                    <td><?= $det['batas'] > 0 ? $det['batas'] : '-' ?></td>
                    <td><?= $det['pijamas'] > 0 ? $det['pijamas'] : '-' ?></td>
                    <td><?= $det['masc_quirurgica'] > 0 ? $det['masc_quirurgica'] : '-' ?></td>
                    <td><?= $det['masc_n95'] > 0 ? $det['masc_n95'] : '-' ?></td>
                    <td><?= $det['polainas'] > 0 ? $det['polainas'] : '-' ?></td>
                    <td><?= $det['overoles'] > 0 ? $det['overoles'] : '-' ?></td>
                    <td style="text-align:left;"><?= htmlspecialchars($det['observacion']) ?></td>
                </tr>
                <?php endforeach; ?>
                
                <!-- FILA DE TOTALES CALCULADOS -->
                <tr class="total-row">
                    <td colspan="4"><strong>Total Calculado:</strong></td>
                    <td><strong><?= $sum_gorros ?></strong></td>
                    <td><strong><?= $sum_batas ?></strong></td>
                    <td><strong><?= $sum_pijamas ?></strong></td>
                    <td><strong><?= $sum_masc_q ?></strong></td>
                    <td><strong><?= $sum_masc_n95 ?></strong></td>
                    <td><strong><?= $sum_polainas ?></strong></td>
                    <td><strong><?= $sum_overoles ?></strong></td>
                    <td></td>
                </tr>

                <!-- FILA DE TOTALES REGISTRADOS -->
                <tr class="total-row" style="background: #fff3cd;">
                    <td colspan="4"><strong>Total Registrado:</strong></td>
                    <td><strong><?= $formato['total_gorros'] ?></strong></td>
                    <td><strong><?= $formato['total_batas'] ?></strong></td>
                    <td><strong><?= $formato['total_pijamas'] ?></strong></td>
                    <td><strong><?= $formato['total_masc_q'] ?></strong></td>
                    <td><strong><?= $formato['total_masc_n95'] ?></strong></td>
                    <td><strong><?= $formato['total_polainas'] ?></strong></td>
                    <td><strong><?= $formato['total_overoles'] ?></strong></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div style="text-align: center; padding: 40px; color: #999;">
        <p>No hay detalles de entregas registrados para este formato.</p>
    </div>
    <?php endif; ?>

    <div style="margin-top: 50px; text-align: center;">
        <div style="width: 300px; margin: 0 auto; border-top: 2px solid #000; padding-top: 10px;">
            <strong>Firma Jefe del Servicio</strong>
        </div>
    </div>

</div>

</body>
 <!-- By Luis Maldonado -->
</html>

<?php odbc_close($con); ?>
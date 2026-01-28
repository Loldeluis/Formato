<?php 
include("conexion.php");

$con = odbc_connect($dsn, $usudb, $pwdb);

if (!$con) {
    die("Error de conexión");
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die("ID no válido");
}

// Obtener datos del formato
$sql_formato = "SELECT * FROM formatos_temperatura WHERE id_formato = $id";
$result_formato = odbc_exec($con, $sql_formato);
$formato = odbc_fetch_array($result_formato);

if (!$formato) {
    die("Formato no encontrado");
}

// Obtener temperatura ambiente
$sql_temp = "SELECT dia, turno, temperatura FROM control_temperatura WHERE id_formato = $id";
$result_temp = odbc_exec($con, $sql_temp);
$temp_data = array();
while ($row = odbc_fetch_array($result_temp)) {
    $temp_data[$row['dia']][$row['turno']] = $row['temperatura'];
}

// Obtener humedad
$sql_hum = "SELECT dia, turno, humedad FROM control_humedad WHERE id_formato = $id";
$result_hum = odbc_exec($con, $sql_hum);
$hum_data = array();
while ($row = odbc_fetch_array($result_hum)) {
    $hum_data[$row['dia']][$row['turno']] = $row['humedad'];
}

// Obtener cadena de frío
$sql_frio = "SELECT dia, turno, temperatura FROM control_frio WHERE id_formato = $id";
$result_frio = odbc_exec($con, $sql_frio);
$frio_data = array();
while ($row = odbc_fetch_array($result_frio)) {
    $frio_data[$row['dia']][$row['turno']] = $row['temperatura'];
}

// Obtener firmas
$sql_firmas = "SELECT dia, hora, firma, vobo FROM control_firmas WHERE id_formato = $id";
$result_firmas = odbc_exec($con, $sql_firmas);
$firmas_data = array();
while ($row = odbc_fetch_array($result_firmas)) {
    $firmas_data[$row['dia']][$row['hora']] = array(
        'firma' => $row['firma'],
        'vobo' => $row['vobo']
    );
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detalle Control de Temperatura</title>
<link href="css/bootstrap.min.css" rel="stylesheet" />
<style>
body {
    font-family: Arial, sans-serif;
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
.btn-back, .btn-print {
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    display: inline-block;
    margin-bottom: 20px;
    margin-right: 10px;
}
.btn-back {
    background: #205ca4;
    color: white;
}
.btn-print {
    background: #28a745;
    color: white;
}
.table-scroll {
    width: 100%;
    overflow-x: auto;
    margin: 20px 0;
}
.temp-table {
    width: 100%;
    table-layout: fixed;
    border-collapse: collapse;
}
.temp-table th,
.temp-table td {
    border: 1px solid #000;
    padding: 4px 6px;
    text-align: center;
    font-size: 10px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
}
.temp-table th {
    background: #e0e0e0;
    font-weight: bold;
}
.selected-value {
    background: #90EE90;
    font-weight: bold;
    font-size: 14px;
}
.vertical-text {
    writing-mode: vertical-rl;
    transform: rotate(180deg);
    font-weight: bold;
    min-width: 26px;
    padding: 2px 4px;
    font-size: 11px;
}
@media print {
    .btn-back, .btn-print {
        display: none;
    }
    .temp-table {
        font-size: 9px;
        width: 100%;
        table-layout: fixed;
    }
    .table-scroll { overflow-x: visible; }
}
/* Print helpers to split long tables into two printable pages */
.page-break { page-break-after: always; }
.print-page { page-break-inside: avoid; width: 100%; }
/* Make the small M/T/N header row more compact */
.temp-table .compact-headers th {
    padding: 2px 4px;
    font-size: 10px;
}
</style>
</head>
<body>

<div class="container">
    <div align="center">
        <img src="img/logo_dos.png" width="262" height="81" alt="Logo" />
    </div>

    <h3>CONTROL DE TEMPERATURA, HUMEDAD RELATIVA Y CADENA DE FRÍO</h3>

    <a href="ver_formatos_temp.php" class="btn-back">← Volver al Listado</a>
    <a href="javascript:window.print()" class="btn-print">🖨️ Imprimir</a>

    <div class="header-info">
        <strong>Nombre:</strong> <?= htmlspecialchars($formato['nombre']) ?> &nbsp;&nbsp;
        <strong>Fecha:</strong> <?= $formato['fecha'] ?> &nbsp;&nbsp;
        <strong>Placa:</strong> <?= htmlspecialchars($formato['placa']) ?><br>
        <strong>Mes:</strong> <?= htmlspecialchars($formato['mes']) ?> &nbsp;&nbsp;
        <strong>Año:</strong> <?= htmlspecialchars($formato['anio']) ?> &nbsp;&nbsp;
        <strong>Servicio:</strong> <?= htmlspecialchars($formato['servicio']) ?>
    </div>

    <!-- TEMPERATURA AMBIENTE -->
    <h4>📊 TEMPERATURA AMBIENTE (°C)</h4>
    <div class="table-scroll">
        <!-- Página 1: Días 1 - 15 -->
        <table class="temp-table print-page">
            <tr>
                <th rowspan="2" class="vertical-text" >TEMPERATURA</th>
                <?php for ($d = 1; $d <= 15; $d++): ?>
                    <th colspan="3" ><?= $d ?></th>
                <?php endfor; ?>
            </tr>
            <tr class="compact-headers">
                <?php for ($d = 1; $d <= 15; $d++): ?>
                    <th>M</th><th>T</th><th>N</th>
                <?php endfor; ?>
            </tr>
            <?php for ($t = 30; $t >= 14; $t--): ?>
            <tr>
                <th><?= $t ?>°C</th>
                <?php for ($d = 1; $d <= 15; $d++): ?>
                    <td class="<?= (isset($temp_data[$d]['M']) && $temp_data[$d]['M'] == $t) ? 'selected-value' : '' ?>">
                        <?= (isset($temp_data[$d]['M']) && $temp_data[$d]['M'] == $t) ? '✓' : '' ?>
                    </td>
                    <td class="<?= (isset($temp_data[$d]['T']) && $temp_data[$d]['T'] == $t) ? 'selected-value' : '' ?>">
                        <?= (isset($temp_data[$d]['T']) && $temp_data[$d]['T'] == $t) ? '✓' : '' ?>
                    </td>
                    <td class="<?= (isset($temp_data[$d]['N']) && $temp_data[$d]['N'] == $t) ? 'selected-value' : '' ?>">
                        <?= (isset($temp_data[$d]['N']) && $temp_data[$d]['N'] == $t) ? '✓' : '' ?>
                    </td>
                <?php endfor; ?>
            </tr>
            <?php endfor; ?>
        </table>

        <div class="page-break"></div>

        <!-- Página 2: Días 16 - 30 -->
        <table class="temp-table print-page">
            <tr>
                <th rowspan="2" class="vertical-text">TEMPERATURA</th>
                <?php for ($d = 16; $d <= 30; $d++): ?>
                    <th colspan="3"><?= $d ?></th>
                <?php endfor; ?>
            </tr>
            <tr class="compact-headers">
                <?php for ($d = 16; $d <= 30; $d++): ?>
                    <th>M</th><th>T</th><th>N</th>
                <?php endfor; ?>
            </tr>
            <?php for ($t = 30; $t >= 14; $t--): ?>
            <tr>
                <th><?= $t ?>°C</th>
                <?php for ($d = 16; $d <= 30; $d++): ?>
                    <td class="<?= (isset($temp_data[$d]['M']) && $temp_data[$d]['M'] == $t) ? 'selected-value' : '' ?>">
                        <?= (isset($temp_data[$d]['M']) && $temp_data[$d]['M'] == $t) ? '✓' : '' ?>
                    </td>
                    <td class="<?= (isset($temp_data[$d]['T']) && $temp_data[$d]['T'] == $t) ? 'selected-value' : '' ?>">
                        <?= (isset($temp_data[$d]['T']) && $temp_data[$d]['T'] == $t) ? '✓' : '' ?>
                    </td>
                    <td class="<?= (isset($temp_data[$d]['N']) && $temp_data[$d]['N'] == $t) ? 'selected-value' : '' ?>">
                        <?= (isset($temp_data[$d]['N']) && $temp_data[$d]['N'] == $t) ? '✓' : '' ?>
                    </td>
                <?php endfor; ?>
            </tr>
            <?php endfor; ?>
        </table>
    </div>

    <!-- HUMEDAD RELATIVA -->
    <h4>💧 HUMEDAD RELATIVA (%)</h4>
    <div class="table-scroll">
        <!-- Página 1: Días 1 - 15 -->
        <table class="temp-table print-page">
            <tr>
                <th rowspan="2" class="vertical-text">HUMEDAD</th>
                <?php for ($d = 1; $d <= 15; $d++): ?>
                    <th colspan="3"><?= $d ?></th>
                <?php endfor; ?>
            </tr>
            <tr class="compact-headers">
                <?php for ($d = 1; $d <= 15; $d++): ?>
                    <th>M</th><th>T</th><th>N</th>
                <?php endfor; ?>
            </tr>
            <?php for ($h = 69; $h >= 41; $h--): ?>
            <tr>
                <th><?= $h ?>%</th>
                <?php for ($d = 1; $d <= 15; $d++): ?>
                    <td class="<?= (isset($hum_data[$d]['M']) && $hum_data[$d]['M'] == $h) ? 'selected-value' : '' ?>">
                        <?= (isset($hum_data[$d]['M']) && $hum_data[$d]['M'] == $h) ? '✓' : '' ?>
                    </td>
                    <td class="<?= (isset($hum_data[$d]['T']) && $hum_data[$d]['T'] == $h) ? 'selected-value' : '' ?>">
                        <?= (isset($hum_data[$d]['T']) && $hum_data[$d]['T'] == $h) ? '✓' : '' ?>
                    </td>
                    <td class="<?= (isset($hum_data[$d]['N']) && $hum_data[$d]['N'] == $h) ? 'selected-value' : '' ?>">
                        <?= (isset($hum_data[$d]['N']) && $hum_data[$d]['N'] == $h) ? '✓' : '' ?>
                    </td>
                <?php endfor; ?>
            </tr>
            <?php endfor; ?>
        </table>

        <div class="page-break"></div>

        <!-- Página 2: Días 16 - 30 -->
        <table class="temp-table print-page">
            <tr>
                <th rowspan="2" class="vertical-text">HUMEDAD</th>
                <?php for ($d = 16; $d <= 30; $d++): ?>
                    <th colspan="3"><?= $d ?></th>
                <?php endfor; ?>
            </tr>
            <tr class="compact-headers">
                <?php for ($d = 16; $d <= 30; $d++): ?>
                    <th>M</th><th>T</th><th>N</th>
                <?php endfor; ?>
            </tr>
            <?php for ($h = 69; $h >= 41; $h--): ?>
            <tr>
                <th><?= $h ?>%</th>
                <?php for ($d = 16; $d <= 30; $d++): ?>
                    <td class="<?= (isset($hum_data[$d]['M']) && $hum_data[$d]['M'] == $h) ? 'selected-value' : '' ?>">
                        <?= (isset($hum_data[$d]['M']) && $hum_data[$d]['M'] == $h) ? '✓' : '' ?>
                    </td>
                    <td class="<?= (isset($hum_data[$d]['T']) && $hum_data[$d]['T'] == $h) ? 'selected-value' : '' ?>">
                        <?= (isset($hum_data[$d]['T']) && $hum_data[$d]['T'] == $h) ? '✓' : '' ?>
                    </td>
                    <td class="<?= (isset($hum_data[$d]['N']) && $hum_data[$d]['N'] == $h) ? 'selected-value' : '' ?>">
                        <?= (isset($hum_data[$d]['N']) && $hum_data[$d]['N'] == $h) ? '✓' : '' ?>
                    </td>
                <?php endfor; ?>
            </tr>
            <?php endfor; ?>
        </table>
    </div>

    <!-- CADENA DE FRÍO -->
    <h4>❄️ CADENA DE FRÍO (°C)</h4>
    <div class="table-scroll">
        <!-- Página 1: Días 1 - 15 -->
        <table class="temp-table print-page">
            <tr>
                <th rowspan="2" class="vertical-text">FRÍO</th>
                <?php for ($d = 1; $d <= 15; $d++): ?>
                    <th colspan="3"><?= $d ?></th>
                <?php endfor; ?>
            </tr>
            <tr class="compact-headers">
                <?php for ($d = 1; $d <= 15; $d++): ?>
                    <th>M</th><th>T</th><th>N</th>
                <?php endfor; ?>
            </tr>
            <?php for ($c = 10; $c >= 0; $c--): ?>
            <tr>
                <th><?= $c ?>°C</th>
                <?php for ($d = 1; $d <= 15; $d++): ?>
                    <td class="<?= (isset($frio_data[$d]['M']) && $frio_data[$d]['M'] == $c) ? 'selected-value' : '' ?>">
                        <?= (isset($frio_data[$d]['M']) && $frio_data[$d]['M'] == $c) ? '✓' : '' ?>
                    </td>
                    <td class="<?= (isset($frio_data[$d]['T']) && $frio_data[$d]['T'] == $c) ? 'selected-value' : '' ?>">
                        <?= (isset($frio_data[$d]['T']) && $frio_data[$d]['T'] == $c) ? '✓' : '' ?>
                    </td>
                    <td class="<?= (isset($frio_data[$d]['N']) && $frio_data[$d]['N'] == $c) ? 'selected-value' : '' ?>">
                        <?= (isset($frio_data[$d]['N']) && $frio_data[$d]['N'] == $c) ? '✓' : '' ?>
                    </td>
                <?php endfor; ?>
            </tr>
            <?php endfor; ?>
        </table>

        <div class="page-break"></div>

        <!-- Página 2: Días 16 - 30 -->
        <table class="temp-table print-page">
            <tr>
                <th rowspan="2" class="vertical-text">FRÍO</th>
                <?php for ($d = 16; $d <= 30; $d++): ?>
                    <th colspan="3"><?= $d ?></th>
                <?php endfor; ?>
            </tr>
            <tr class="compact-headers">
                <?php for ($d = 16; $d <= 30; $d++): ?>
                    <th>M</th><th>T</th><th>N</th>
                <?php endfor; ?>
            </tr>
            <?php for ($c = 10; $c >= 0; $c--): ?>
            <tr>
                <th><?= $c ?>°C</th>
                <?php for ($d = 16; $d <= 30; $d++): ?>
                    <td class="<?= (isset($frio_data[$d]['M']) && $frio_data[$d]['M'] == $c) ? 'selected-value' : '' ?>">
                        <?= (isset($frio_data[$d]['M']) && $frio_data[$d]['M'] == $c) ? '✓' : '' ?>
                    </td>
                    <td class="<?= (isset($frio_data[$d]['T']) && $frio_data[$d]['T'] == $c) ? 'selected-value' : '' ?>">
                        <?= (isset($frio_data[$d]['T']) && $frio_data[$d]['T'] == $c) ? '✓' : '' ?>
                    </td>
                    <td class="<?= (isset($frio_data[$d]['N']) && $frio_data[$d]['N'] == $c) ? 'selected-value' : '' ?>">
                        <?= (isset($frio_data[$d]['N']) && $frio_data[$d]['N'] == $c) ? '✓' : '' ?>
                    </td>
                <?php endfor; ?>
            </tr>
            <?php endfor; ?>
        </table>
    </div>

    <!-- FIRMAS Y Vo.Bo -->
    <h4>✍️ FIRMAS Y Vo.Bo</h4>
    <div class="table-scroll">
        <!-- Página 1: Días 1 - 15 -->
        <table class="temp-table print-page">
            <tr>
                <th class="vertical-text">HORA</th>
                <?php for ($d = 1; $d <= 15; $d++): ?>
                    <th>6</th><th>14</th><th>22</th>
                <?php endfor; ?>
            </tr>
            <tr>
                <th class="vertical-text">FIRMA</th>
                <?php for ($d = 1; $d <= 15; $d++): ?>
                    <td><?= isset($firmas_data[$d]['6']['firma']) ? htmlspecialchars($firmas_data[$d]['6']['firma']) : '' ?></td>
                    <td><?= isset($firmas_data[$d]['14']['firma']) ? htmlspecialchars($firmas_data[$d]['14']['firma']) : '' ?></td>
                    <td><?= isset($firmas_data[$d]['22']['firma']) ? htmlspecialchars($firmas_data[$d]['22']['firma']) : '' ?></td>
                <?php endfor; ?>
            </tr>
            <tr>
                <th class="vertical-text">Vo.Bo</th>
                <?php for ($d = 1; $d <= 15; $d++): ?>
                    <td><?= isset($firmas_data[$d]['6']['vobo']) ? htmlspecialchars($firmas_data[$d]['6']['vobo']) : '' ?></td>
                    <td><?= isset($firmas_data[$d]['14']['vobo']) ? htmlspecialchars($firmas_data[$d]['14']['vobo']) : '' ?></td>
                    <td><?= isset($firmas_data[$d]['22']['vobo']) ? htmlspecialchars($firmas_data[$d]['22']['vobo']) : '' ?></td>
                <?php endfor; ?>
            </tr>
        </table>

        <div class="page-break"></div>

        <!-- Página 2: Días 16 - 30 -->
        <table class="temp-table print-page">
            <tr>
                <th class="vertical-text">HORA</th>
                <?php for ($d = 16; $d <= 30; $d++): ?>
                    <th>6</th><th>14</th><th>22</th>
                <?php endfor; ?>
            </tr>
            <tr>
                <th class="vertical-text">FIRMA</th>
                <?php for ($d = 16; $d <= 30; $d++): ?>
                    <td><?= isset($firmas_data[$d]['6']['firma']) ? htmlspecialchars($firmas_data[$d]['6']['firma']) : '' ?></td>
                    <td><?= isset($firmas_data[$d]['14']['firma']) ? htmlspecialchars($firmas_data[$d]['14']['firma']) : '' ?></td>
                    <td><?= isset($firmas_data[$d]['22']['firma']) ? htmlspecialchars($firmas_data[$d]['22']['firma']) : '' ?></td>
                <?php endfor; ?>
            </tr>
            <tr>
                <th class="vertical-text">Vo.Bo</th>
                <?php for ($d = 16; $d <= 30; $d++): ?>
                    <td><?= isset($firmas_data[$d]['6']['vobo']) ? htmlspecialchars($firmas_data[$d]['6']['vobo']) : '' ?></td>
                    <td><?= isset($firmas_data[$d]['14']['vobo']) ? htmlspecialchars($firmas_data[$d]['14']['vobo']) : '' ?></td>
                    <td><?= isset($firmas_data[$d]['22']['vobo']) ? htmlspecialchars($firmas_data[$d]['22']['vobo']) : '' ?></td>
                <?php endfor; ?>
            </tr>
        </table>
    </div>

</div>

</body>
 <!-- By Luis Maldonado -->
</html>

<?php
odbc_close($con);
?>
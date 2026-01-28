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
$sql_formato = "SELECT * FROM formatos_limpieza WHERE id_formato = $id";
$result_formato = odbc_exec($con, $sql_formato);
$formato = odbc_fetch_array($result_formato);

if (!$formato) {
    die("Formato no encontrado");
}

// Obtener control de limpieza
$sql_control = "SELECT area, dia, turno FROM control_limpieza WHERE id_formato = $id ORDER BY dia, turno";
$result_control = odbc_exec($con, $sql_control);

$control_data = array();
while ($row = odbc_fetch_array($result_control)) {
    $control_data[$row['area']][$row['dia']][$row['turno']] = true;
}

// Obtener control diario
$sql_diario = "SELECT dia, turno, realiza, supervisa FROM control_diario WHERE id_formato = $id ORDER BY dia, turno";
$result_diario = odbc_exec($con, $sql_diario);

$diario_data = array();
while ($row = odbc_fetch_array($result_diario)) {
    $diario_data[$row['dia']][$row['turno']] = array(
        'realiza' => $row['realiza'],
        'supervisa' => $row['supervisa']
    );
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detalle del Formato</title>
<link href="css/bootstrap.min.css" rel="stylesheet" />
<style>
body {
    font-family: Arial, sans-serif;
    padding: 20px;
}
.container {
    max-width: 1400px;
    margin: 0 auto;
}
h3 {
    text-align: center;
    color: #a8814e;
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
    font-size: 12px;
}
.header-info {
    background: #f5f5f5;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
}
.header-info strong {
    color: #205ca4;
}
.check-mark {
    color: #28a745;
    font-size: 18px;
    font-weight: bold;
}
.area-name {
    text-align: left;
    font-weight: bold;
}
.btn-back {
    background: #205ca4;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    display: inline-block;
    margin-bottom: 20px;
}
.btn-print {
    background: #28a745;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    display: inline-block;
    margin-bottom: 20px;
    margin-left: 10px;
}
@media print {
    .btn-back, .btn-print {
        display: none;
    }
}
/* For print splitting into two pages */
.page-break { page-break-after: always; }
.print-page { page-break-inside: avoid; width: 100%; }
</style>
</head>
<body>

<div class="container">
    <div align="center">
        <img src="img/logo_dos.png" width="262" height="81" alt="Logo" />
    </div>

    <h3>CONTROL DIARIO DE LIMPIEZA Y DESINFECCIÓN DE AMBULANCIAS</h3>

    <a href="ver_formatos.php" class="btn-back">← Volver al Listado</a>
    <a href="javascript:window.print()" class="btn-print">🖨️ Imprimir</a>

    <div class="header-info">
        <div style="margin-bottom: 10px;">
            <strong>Mes:</strong> <?= htmlspecialchars($formato['mes']) ?> &nbsp;&nbsp;
            <strong>Año:</strong> <?= htmlspecialchars($formato['anio']) ?> &nbsp;&nbsp;
            <strong>Sede:</strong> <?= htmlspecialchars($formato['sede']) ?> &nbsp;&nbsp;
            <strong>Móvil/Placa:</strong> <?= htmlspecialchars($formato['movil']) ?>
        </div>
        
        <?php if (!empty($formato['prod_limpieza']) || !empty($formato['prod_desinfectante'])): ?>
        <div style="border-top: 1px solid #ddd; padding-top: 10px; margin-top: 10px;">
            <strong>PRODUCTOS UTILIZADOS:</strong><br>
            <?php if (!empty($formato['prod_limpieza'])): ?>
                <strong>Limpieza:</strong> <?= htmlspecialchars($formato['prod_limpieza']) ?> 
                <?php if (!empty($formato['conc_limpieza'])): ?>
                    (<?= htmlspecialchars($formato['conc_limpieza']) ?>)
                <?php endif; ?>
                <br>
            <?php endif; ?>
            
            <?php if (!empty($formato['prod_desinfectante'])): ?>
                <strong>Desinfectante:</strong> <?= htmlspecialchars($formato['prod_desinfectante']) ?>
                <?php if (!empty($formato['conc_desinfectante'])): ?>
                    (<?= htmlspecialchars($formato['conc_desinfectante']) ?>)
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($formato['observaciones'])): ?>
        <div style="border-top: 1px solid #ddd; padding-top: 10px; margin-top: 10px;">
            <strong>OBSERVACIONES:</strong><br>
            <?= nl2br(htmlspecialchars($formato['observaciones'])) ?>
        </div>
        <?php endif; ?>
    </div>

    <h4>Control de Áreas</h4>

    <!-- Página 1: Días 1 - 15 -->
    <table class="print-page">
        <tr>
            <th rowspan="2">ÁREA</th>
            <th colspan="45">DÍAS DEL MES</th>
        </tr>
        <tr>
            <?php for ($d = 1; $d <= 15; $d++): ?>
                <th colspan="3"><?= $d ?></th>
            <?php endfor; ?>
        </tr>
        <tr>
            <th></th>
            <?php for ($d = 1; $d <= 15; $d++): ?>
                <th>M</th><th>T</th><th>N</th>
            <?php endfor; ?>
        </tr>

        <?php
        $areas = array(
            "Cajón de vía circulatoria",
            "Cajón de vía respiratoria",
            "Cajón de quirúrgicos",
            "Cajón de pediátricos",
            "Cajón de accesorios",
            "Estante donde van los equipos biomédicos",
            "Monitor de signos vitales",
            "Desfibrilador",
            "Aspirador de secreciones",
            "Bombas de infusión",
            "Ventilador",
            "Soporte y redes de oxígeno",
            "Sillas de acompañante",
            "Sillas de la tripulación",
            "Camilla",
            "Barandas de la camilla",
            "Atril portátil",
            "Silla de ruedas",
            "Paredes y piso de la ambulancia",
            "Canecas"
        );

        foreach ($areas as $area):
        ?>
        <tr>
            <td class="area-name"><?= $area ?></td>
            <?php for ($d = 1; $d <= 15; $d++): ?>
                <td><?= isset($control_data[$area][$d]['M']) ? '<span class="check-mark">✓</span>' : '' ?></td>
                <td><?= isset($control_data[$area][$d]['T']) ? '<span class="check-mark">✓</span>' : '' ?></td>
                <td><?= isset($control_data[$area][$d]['N']) ? '<span class="check-mark">✓</span>' : '' ?></td>
            <?php endfor; ?>
        </tr>
        <?php endforeach; ?>
    </table>

    <div class="page-break"></div>

    <!-- Página 2: Días 16 - 31 -->
    <table class="print-page">
        <tr>
            <th rowspan="2">ÁREA</th>
            <th colspan="48">DÍAS DEL MES</th>
        </tr>
        <tr>
            <?php for ($d = 16; $d <= 31; $d++): ?>
                <th colspan="3"><?= $d ?></th>
            <?php endfor; ?>
        </tr>
        <tr>
            <th></th>
            <?php for ($d = 16; $d <= 31; $d++): ?>
                <th>M</th><th>T</th><th>N</th>
            <?php endfor; ?>
        </tr>

        <?php foreach ($areas as $area): ?>
        <tr>
            <td class="area-name"><?= $area ?></td>
            <?php for ($d = 16; $d <= 31; $d++): ?>
                <td><?= isset($control_data[$area][$d]['M']) ? '<span class="check-mark">✓</span>' : '' ?></td>
                <td><?= isset($control_data[$area][$d]['T']) ? '<span class="check-mark">✓</span>' : '' ?></td>
                <td><?= isset($control_data[$area][$d]['N']) ? '<span class="check-mark">✓</span>' : '' ?></td>
            <?php endfor; ?>
        </tr>
        <?php endforeach; ?>
    </table>

    <h4>Control Diario - Personal</h4>

    <!-- Página 1: Días 1 - 15 -->
    <table class="print-page">
        <tr>
            <th>ROL</th>
            <?php for ($d = 1; $d <= 15; $d++): ?>
                <th colspan="3"><?= $d ?></th>
            <?php endfor; ?>
        </tr>
        <tr>
            <th></th>
            <?php for ($d = 1; $d <= 15; $d++): ?>
                <th>M</th><th>T</th><th>N</th>
            <?php endfor; ?>
        </tr>
        
        <tr>
            <td><strong>REALIZA</strong></td>
            <?php for ($d = 1; $d <= 15; $d++): ?>
                <td><?= isset($diario_data[$d]['M']['realiza']) ? htmlspecialchars($diario_data[$d]['M']['realiza']) : '' ?></td>
                <td><?= isset($diario_data[$d]['T']['realiza']) ? htmlspecialchars($diario_data[$d]['T']['realiza']) : '' ?></td>
                <td><?= isset($diario_data[$d]['N']['realiza']) ? htmlspecialchars($diario_data[$d]['N']['realiza']) : '' ?></td>
            <?php endfor; ?>
        </tr>

        <tr>
            <td><strong>SUPERVISA</strong></td>
            <?php for ($d = 1; $d <= 15; $d++): ?>
                <td><?= isset($diario_data[$d]['M']['supervisa']) ? htmlspecialchars($diario_data[$d]['M']['supervisa']) : '' ?></td>
                <td><?= isset($diario_data[$d]['T']['supervisa']) ? htmlspecialchars($diario_data[$d]['T']['supervisa']) : '' ?></td>
                <td><?= isset($diario_data[$d]['N']['supervisa']) ? htmlspecialchars($diario_data[$d]['N']['supervisa']) : '' ?></td>
            <?php endfor; ?>
        </tr>
    </table>

    <div class="page-break"></div>

    <!-- Página 2: Días 16 - 31 -->
    <table class="print-page">
        <tr>
            <th>ROL</th>
            <?php for ($d = 16; $d <= 31; $d++): ?>
                <th colspan="3"><?= $d ?></th>
            <?php endfor; ?>
        </tr>
        <tr>
            <th></th>
            <?php for ($d = 16; $d <= 31; $d++): ?>
                <th>M</th><th>T</th><th>N</th>
            <?php endfor; ?>
        </tr>
        
        <tr>
            <td><strong>REALIZA</strong></td>
            <?php for ($d = 16; $d <= 31; $d++): ?>
                <td><?= isset($diario_data[$d]['M']['realiza']) ? htmlspecialchars($diario_data[$d]['M']['realiza']) : '' ?></td>
                <td><?= isset($diario_data[$d]['T']['realiza']) ? htmlspecialchars($diario_data[$d]['T']['realiza']) : '' ?></td>
                <td><?= isset($diario_data[$d]['N']['realiza']) ? htmlspecialchars($diario_data[$d]['N']['realiza']) : '' ?></td>
            <?php endfor; ?>
        </tr>

        <tr>
            <td><strong>SUPERVISA</strong></td>
            <?php for ($d = 16; $d <= 31; $d++): ?>
                <td><?= isset($diario_data[$d]['M']['supervisa']) ? htmlspecialchars($diario_data[$d]['M']['supervisa']) : '' ?></td>
                <td><?= isset($diario_data[$d]['T']['supervisa']) ? htmlspecialchars($diario_data[$d]['T']['supervisa']) : '' ?></td>
                <td><?= isset($diario_data[$d]['N']['supervisa']) ? htmlspecialchars($diario_data[$d]['N']['supervisa']) : '' ?></td>
            <?php endfor; ?>
        </tr>
    </table>
</div>


    <!-- VER FORMATOS GUARDADOS -->
    <div class="section">
        <h2 class="section-title">
            <i class="fas fa-folder-open"></i>
            Ver Formatos Guardados
        </h2>
        <div class="cards-grid">
            <a href="ver_formatos.php" class="action-card">
                <div class="action-card-icon">
                    <i class="fas fa-broom"></i>
                </div>
                <h3>Limpieza y Desinfección</h3>
                <p><?= $stats['limpieza'] ?> formatos guardados</p>
            </a>

            <a href="ver_formatos_temp.php" class="action-card green">
                <div class="action-card-icon">
                    <i class="fas fa-thermometer-half"></i>
                </div>
                <h3>Control de Temperatura</h3>
                <p><?= $stats['temperatura'] ?> formatos guardados</p>
            </a>

            <a href="ver_formatos_verificacion.php" class="action-card orange">
                <div class="action-card-icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <h3>Verificación Diaria</h3>
                <p><?= $stats['verificacion'] ?> formatos guardados</p>
            </a>

            <a href="ver_formatos_epp.php" class="action-card red">
                <div class="action-card-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Relación de EPP</h3>
                <p><?= $stats['epp'] ?> formatos guardados</p>
            </a>
        </div>
    </div>
</body>
 <!-- By Luis Maldonado -->
</html>

<?php
odbc_close($con);
?>
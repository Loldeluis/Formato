<?php 
include("conexion.php");

$con = odbc_connect($dsn, $usudb, $pwdb);
if (!$con) die("Error de conexión");

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) die("ID no válido");

// Obtener formato
$sql_formato = "SELECT * FROM dbo.formatos_verificacion WHERE id_formato = $id";
$result_formato = odbc_exec($con, $sql_formato);
$formato = odbc_fetch_array($result_formato);
if (!$formato) die("Formato no encontrado");

// Función para obtener datos de una tabla
function obtenerDatos($con, $tabla, $id) {
    $sql = "SELECT * FROM dbo.$tabla WHERE id_formato = $id ORDER BY item_numero";
    $result = odbc_exec($con, $sql);
    $data = array();
    while ($row = odbc_fetch_array($result)) {
        $data[] = $row;
    }
    return $data;
}

$respiratorio = obtenerDatos($con, 'verificacion_respiratorio', $id);
$oximetro = obtenerDatos($con, 'verificacion_oximetro', $id);
$quirurgico = obtenerDatos($con, 'verificacion_quirurgico', $id);
$trauma = obtenerDatos($con, 'verificacion_trauma', $id);
$circulatorio = obtenerDatos($con, 'verificacion_circulatorio', $id);
$otros = obtenerDatos($con, 'verificacion_otros', $id);
$equipos = obtenerDatos($con, 'verificacion_equipos_biomedicos', $id);
$otros_eq = obtenerDatos($con, 'verificacion_otros_equipos', $id);

// Limpieza
$sql_limp = "SELECT * FROM dbo.verificacion_limpieza WHERE id_formato = $id";
$result_limp = odbc_exec($con, $sql_limp);
$limpieza = odbc_fetch_array($result_limp);

// Oxígeno
$sql_oxi = "SELECT * FROM dbo.verificacion_oxigeno WHERE id_formato = $id";
$result_oxi = odbc_exec($con, $sql_oxi);
$oxigeno = array();
while ($row = odbc_fetch_array($result_oxi)) {
    $oxigeno[] = $row;
}

// Gastos
$sql_gast = "SELECT * FROM dbo.verificacion_gastos WHERE id_formato = $id";
$result_gast = odbc_exec($con, $sql_gast);
$gastos = odbc_fetch_array($result_gast);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detalle Verificación</title>
<link href="css/bootstrap.min.css" rel="stylesheet" />
<style>
body { font-family: Arial; padding: 20px; }
.container { max-width: 1200px; margin: 0 auto; }
h3 { text-align: center; color: #a8814e; margin: 20px 0; }
.header-info { background: #f5f5f5; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 2px solid #205ca4; }
.header-info strong { color: #205ca4; }
table { width: 100%; border-collapse: collapse; margin: 20px 0; }
table, th, td { border: 1px solid #000; }
th, td { padding: 8px; text-align: left; font-size: 13px; }
th { background: #e0e0e0; font-weight: bold; text-align: center; }
.btn-back, .btn-print { padding: 10px 20px; border-radius: 5px; text-decoration: none; display: inline-block; margin: 10px 5px; }
.btn-back { background: #205ca4; color: white; }
.btn-print { background: #28a745; color: white; }
.check-yes { color: green; font-weight: bold; }
.check-no { color: red; font-weight: bold; }
@media print { .btn-back, .btn-print { display: none; } }
</style>
</head>
<body>

<div class="container">
    <div align="center">
        <img src="img/logo_dos.png" width="262" height="81" alt="Logo" />
    </div>

    <h3>LISTA DE VERIFICACIÓN DIARIA ASISTENCIAL DE AMBULANCIA</h3>

    <a href="ver_formatos_verificacion.php" class="btn-back">← Volver</a>
    <a href="javascript:window.print()" class="btn-print">🖨️ Imprimir</a>

    <div class="header-info">
        <strong>Nombre:</strong> <?= htmlspecialchars($formato['nombre']) ?> &nbsp;&nbsp;
        <strong>Fecha:</strong> <?= $formato['fecha'] ?> &nbsp;&nbsp;
        <strong>Placa:</strong> <?= htmlspecialchars($formato['placa']) ?>
    </div>

    <?php if (count($respiratorio) > 0): ?>
    <h4>RESPIRATORIO</h4>
    <table>
        <tr><th>N°</th><th>Descripción</th><th>Cantidad</th></tr>
        <?php foreach ($respiratorio as $item): ?>
        <tr>
            <td><?= $item['item_numero'] ?></td>
            <td><?= htmlspecialchars($item['descripcion']) ?></td>
            <td><?= htmlspecialchars($item['cantidad']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>

    <?php if (count($oximetro) > 0): ?>
    <h4>OXÍMETRO</h4>
    <table>
        <tr><th>N°</th><th>Descripción</th><th>Cantidad</th></tr>
        <?php foreach ($oximetro as $item): ?>
        <tr>
            <td><?= $item['item_numero'] ?></td>
            <td><?= htmlspecialchars($item['descripcion']) ?></td>
            <td><?= htmlspecialchars($item['cantidad']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>

    <?php if (count($quirurgico) > 0): ?>
    <h4>QUIRÚRGICO</h4>
    <table>
        <tr><th>N°</th><th>Descripción</th><th>Cantidad</th></tr>
        <?php foreach ($quirurgico as $item): ?>
        <tr>
            <td><?= $item['item_numero'] ?></td>
            <td><?= htmlspecialchars($item['descripcion']) ?></td>
            <td><?= htmlspecialchars($item['cantidad']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>

    <?php if (count($trauma) > 0): ?>
    <h4>TRAUMA</h4>
    <table>
        <tr><th>N°</th><th>Descripción</th><th>Cantidad</th></tr>
        <?php foreach ($trauma as $item): ?>
        <tr>
            <td><?= $item['item_numero'] ?></td>
            <td><?= htmlspecialchars($item['descripcion']) ?></td>
            <td><?= htmlspecialchars($item['cantidad']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>

    <?php if (count($circulatorio) > 0): ?>
    <h4>CIRCULATORIO</h4>
    <table>
        <tr><th>N°</th><th>Descripción</th><th>Cantidad</th></tr>
        <?php foreach ($circulatorio as $item): ?>
        <tr>
            <td><?= $item['item_numero'] ?></td>
            <td><?= htmlspecialchars($item['descripcion']) ?></td>
            <td><?= htmlspecialchars($item['cantidad']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>

    <?php if (count($otros) > 0): ?>
    <h4>OTROS</h4>
    <table>
        <tr><th>N°</th><th>Descripción</th><th>Cantidad</th></tr>
        <?php foreach ($otros as $item): ?>
        <tr>
            <td><?= $item['item_numero'] ?></td>
            <td><?= htmlspecialchars($item['descripcion']) ?></td>
            <td><?= htmlspecialchars($item['cantidad']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>

    <?php if (count($equipos) > 0): ?>
    <h4>EQUIPOS BIOMÉDICOS</h4>
    <table>
        <tr>
            <th>N°</th><th>Descripción</th><th>% Carga</th>
            <th>Funcional</th><th>Cable</th><th>Accesorios</th><th>Observaciones</th>
        </tr>
        <?php foreach ($equipos as $eq): ?>
        <tr>
            <td><?= $eq['item_numero'] ?></td>
            <td><?= htmlspecialchars($eq['descripcion']) ?></td>
            <td><?= htmlspecialchars($eq['porcentaje_carga']) ?></td>
            <td><?= $eq['funcional_si'] ? '<span class="check-yes">SI</span>' : ($eq['funcional_no'] ? '<span class="check-no">NO</span>' : '-') ?></td>
            <td><?= $eq['cable_si'] ? '<span class="check-yes">SI</span>' : ($eq['cable_no'] ? '<span class="check-no">NO</span>' : '-') ?></td>
            <td><?= $eq['accesorios_si'] ? '<span class="check-yes">SI</span>' : ($eq['accesorios_no'] ? '<span class="check-no">NO</span>' : '-') ?></td>
            <td><?= htmlspecialchars($eq['observaciones']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>

    <?php if (count($otros_eq) > 0): ?>
    <h4>OTROS EQUIPOS</h4>
    <table>
        <tr><th>N°</th><th>Descripción</th><th>Tiene</th><th>Observaciones</th></tr>
        <?php foreach ($otros_eq as $eq): ?>
        <tr>
            <td><?= $eq['item_numero'] ?></td>
            <td><?= htmlspecialchars($eq['descripcion']) ?></td>
            <td><?= $eq['tiene_si'] ? '<span class="check-yes">SI</span>' : ($eq['tiene_no'] ? '<span class="check-no">NO</span>' : '-') ?></td>
            <td><?= htmlspecialchars($eq['observaciones']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>

    <?php if ($limpieza): ?>
    <h4>ESTADO DE LIMPIEZA Y CANECAS</h4>
    <table>
        <tr>
            <th>Aspecto</th><th>Estado</th>
        </tr>
        <tr>
            <td><strong>Cabina</strong></td>
            <td><?= $limpieza['cabina_limpia'] ? '<span class="check-yes">LIMPIA</span>' : ($limpieza['cabina_sucia'] ? '<span class="check-no">SUCIA</span>' : '-') ?></td>
        </tr>
        <tr>
            <td><strong>Canecas - Estado</strong></td>
            <td><?= $limpieza['canecas_buen_estado'] ? '<span class="check-yes">BUEN ESTADO</span>' : ($limpieza['canecas_mal_estado'] ? '<span class="check-no">MAL ESTADO</span>' : '-') ?></td>
        </tr>
        <tr>
            <td><strong>Canecas - Nivel</strong></td>
            <td><?= $limpieza['canecas_vacias'] ? 'VACÍAS' : ($limpieza['canecas_llenas'] ? 'LLENAS' : '-') ?></td>
        </tr>
        <tr>
            <td><strong>Canecas - Bolsa</strong></td>
            <td><?= $limpieza['canecas_con_bolsa'] ? '<span class="check-yes">CON BOLSA</span>' : ($limpieza['canecas_sin_bolsa'] ? '<span class="check-no">SIN BOLSA</span>' : '-') ?></td>
        </tr>
        <tr>
            <td><strong>Alcohol</strong></td>
            <td><?= $limpieza['alcohol_si'] ? '<span class="check-yes">SI</span>' : ($limpieza['alcohol_no'] ? '<span class="check-no">NO</span>' : '-') ?></td>
        </tr>
    </table>
    <?php endif; ?>

    <?php if (count($oxigeno) > 0): ?>
    <h4>OXÍGENO</h4>
    <table>
        <tr><th>Tipo</th><th>N°</th><th>Funcional</th><th>Observaciones</th></tr>
        <?php foreach ($oxigeno as $oxi): ?>
        <tr>
            <td><?= strtoupper($oxi['tipo']) ?></td>
            <td><?= $oxi['numero'] ?></td>
            <td><?= $oxi['funcional_si'] ? '<span class="check-yes">SI</span>' : ($oxi['funcional_no'] ? '<span class="check-no">NO</span>' : '-') ?></td>
            <td><?= htmlspecialchars($oxi['observaciones']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>

    <?php if ($gastos && !empty($gastos['reporte'])): ?>
    <h4>REPORTE DE GASTOS</h4>
    <div style="border: 1px solid #000; padding: 15px; background: #f9f9f9;">
        <?= nl2br(htmlspecialchars($gastos['reporte'])) ?>
    </div>
    <?php endif; ?>

</div>

</body>
 <!-- By Luis Maldonado -->
</html>

<?php odbc_close($con); ?>
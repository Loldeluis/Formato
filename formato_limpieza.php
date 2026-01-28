<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

include("conexion.php");
$con = odbc_connect($dsn, $usudb, $pwdb);

$fechaactual = date("Y-m-d");
$mes_actual = date("n"); // 1-12
$anio_actual = date("Y");
$dia_actual = date("j"); // 1-31

// Variables para el formulario
$formato_existente = null;
$id_formato = null;
$datos_guardados = array();

// Si se especifica móvil, buscar formato existente
$movil_buscar = isset($_GET['movil']) ? trim($_GET['movil']) : '';

if ($movil_buscar) {
    $sql_buscar = "SELECT * FROM dbo.formatos_limpieza 
                   WHERE mes_numerico = $mes_actual 
                   AND anio_numerico = $anio_actual 
                   AND movil = '$movil_buscar'
                   AND estado = 'en_progreso'";
    
    $result = odbc_exec($con, $sql_buscar);
    
    if ($result && $row = odbc_fetch_array($result)) {
        $formato_existente = $row;
        $id_formato = $row['id_formato'];
        
        // Cargar datos guardados
        $sql_control = "SELECT * FROM control_limpieza WHERE id_formato = $id_formato";
        $result_control = odbc_exec($con, $sql_control);
        while ($ctrl = odbc_fetch_array($result_control)) {
            $datos_guardados[$ctrl['area']][$ctrl['dia']][$ctrl['turno']] = true;
        }
        
        $sql_diario = "SELECT * FROM control_diario WHERE id_formato = $id_formato";
        $result_diario = odbc_exec($con, $sql_diario);
        $datos_diario = array();
        while ($diario = odbc_fetch_array($result_diario)) {
            $datos_diario[$diario['dia']][$diario['turno']] = array(
                'realiza' => $diario['realiza'],
                'supervisa' => $diario['supervisa']
            );
        }
    }
}

$nombre_mes = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
                    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Control de Limpieza - <?= $nombre_mes[$mes_actual] ?> <?= $anio_actual ?></title>
<link href="css/bootstrap.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
body {
    font-family: Arial, sans-serif;
    padding: 20px;
    background: #f5f5f5;
}
.header {
    background: linear-gradient(135deg, #205ca4 0%, #164273 100%);
    color: white;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 30px;
}
.alert-info {
    background: #d1ecf1;
    border: 1px solid #bee5eb;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}
.dia-completado {
    background: #d4edda !important;
    pointer-events: none;
}
.dia-actual {
    background: #fff3cd !important;
}
.dia-futuro {
    background: #f8f9fa !important;
    opacity: 0.5;
    pointer-events: none;
}
table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}
th, td {
    border: 1px solid #000;
    padding: 8px;
    text-align: center;
}
input[type="checkbox"] {
    width: 20px;
    height: 20px;
    cursor: pointer;
}
input[type="text"] {
    width: 100%;
    border: none;
    padding: 4px;
}
.btn-back {
    background: #6c757d;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    display: inline-block;
    margin-bottom: 20px;
}
.btn-guardar {
    background: #28a745;
    color: white;
    padding: 12px 30px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    margin-top: 20px;
}
.btn-completar {
    background: #007bff;
    color: white;
    padding: 12px 30px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    margin-top: 20px;
    margin-left: 10px;
}
</style>
</head>
<body>

<a href="dashboard.php" class="btn-back">← Volver al Dashboard</a>

<div class="header">
    <h2><i class="fas fa-broom"></i> Control de Limpieza y Desinfección</h2>
    <p><?= $nombre_mes[$mes_actual] ?> <?= $anio_actual ?> - Día actual: <?= $dia_actual ?></p>
</div>

<?php if (!$movil_buscar): ?>
<!-- SELECCIÓN DE MÓVIL -->
<div style="background: white; padding: 30px; border-radius: 10px;">
    <h3>Selecciona o ingresa el móvil/placa de la ambulancia:</h3>
    <form method="GET">
        <input type="text" 
               name="movil" 
               placeholder="Ej: AMB-001" 
               style="padding: 10px; font-size: 16px; width: 300px; border: 2px solid #ddd; border-radius: 5px;"
               required>
        <button type="submit" class="btn-guardar">Continuar</button>
    </form>
</div>

<?php else: ?>

<?php if ($formato_existente): ?>
<div class="alert-info">
    <strong><i class="fas fa-info-circle"></i> Formato en progreso</strong><br>
    Continuando con el formato del mes de <strong><?= $nombre_mes[$mes_actual] ?> <?= $anio_actual ?></strong> 
    para el móvil <strong><?= htmlspecialchars($formato_existente['movil']) ?></strong><br>
    Último día editado: <strong><?= $formato_existente['ultimo_dia_editado'] ?></strong> | 
    Sede: <strong><?= htmlspecialchars($formato_existente['sede']) ?></strong>
</div>
<?php else: ?>
<div class="alert-info">
    <strong><i class="fas fa-plus-circle"></i> Nuevo formato</strong><br>
    Creando nuevo formato para <strong><?= $nombre_mes[$mes_actual] ?> <?= $anio_actual ?></strong> 
    - Móvil: <strong><?= htmlspecialchars($movil_buscar) ?></strong>
</div>
<?php endif; ?>

<form method="POST" action="guardar_limpieza_dia.php">
    <input type="hidden" name="id_formato" value="<?= $id_formato ?>">
    <input type="hidden" name="movil" value="<?= htmlspecialchars($movil_buscar) ?>">
    <input type="hidden" name="mes_numerico" value="<?= $mes_actual ?>">
    <input type="hidden" name="anio_numerico" value="<?= $anio_actual ?>">
    <input type="hidden" name="dia_actual" value="<?= $dia_actual ?>">

    <?php if (!$formato_existente): ?>
    <!-- Solo pedir estos datos si es nuevo -->
    <table style="margin-bottom: 20px;">
        <tr>
            <td><strong>Mes:</strong></td>
            <td><input type="text" name="mes" value="<?= $nombre_mes[$mes_actual] ?>" readonly></td>
            <td><strong>Año:</strong></td>
            <td><input type="text" name="anio" value="<?= $anio_actual ?>" readonly></td>
        </tr>
        <tr>
            <td><strong>Sede:</strong></td>
            <td colspan="3"><input type="text" name="sede" required></td>
        </tr>
        <tr>
            <td><strong>Móvil/Placa:</strong></td>
            <td colspan="3"><input type="text" name="movil_mostrar" value="<?= htmlspecialchars($movil_buscar) ?>" readonly></td>
        </tr>
    </table>
    <?php endif; ?>

    <table>
        <tr>
            <th>Área</th>
            <?php for ($d = 1; $d <= 31; $d++): 
                $es_completado = ($formato_existente && $d < $dia_actual);
                $es_actual = ($d == $dia_actual);
                $es_futuro = ($d > $dia_actual);
                $clase = $es_completado ? 'dia-completado' : ($es_actual ? 'dia-actual' : ($es_futuro ? 'dia-futuro' : ''));
            ?>
                <th colspan="3" class="<?= $clase ?>"><?= $d ?></th>
            <?php endfor; ?>
        </tr>
        <tr>
            <th></th>
            <?php for ($d = 1; $d <= 31; $d++): 
                $es_completado = ($formato_existente && $d < $dia_actual);
                $es_actual = ($d == $dia_actual);
                $es_futuro = ($d > $dia_actual);
                $clase = $es_completado ? 'dia-completado' : ($es_actual ? 'dia-actual' : ($es_futuro ? 'dia-futuro' : ''));
            ?>
                <th class="<?= $clase ?>">M</th>
                <th class="<?= $clase ?>">T</th>
                <th class="<?= $clase ?>">N</th>
            <?php endfor; ?>
        </tr>

        <?php
        $areas = [
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
            "Silla de ruedas"
        ];

        foreach ($areas as $i => $area):
        ?>
        <tr>
            <td style="text-align:left;"><strong><?= $area ?></strong></td>
            <?php for ($d = 1; $d <= 31; $d++): 
                $es_completado = ($formato_existente && $d < $dia_actual);
                $es_actual = ($d == $dia_actual);
                $es_futuro = ($d > $dia_actual);
                $clase = $es_completado ? 'dia-completado' : ($es_actual ? 'dia-actual' : ($es_futuro ? 'dia-futuro' : ''));
                
                $checked_m = isset($datos_guardados[$area][$d]['M']) ? 'checked' : '';
                $checked_t = isset($datos_guardados[$area][$d]['T']) ? 'checked' : '';
                $checked_n = isset($datos_guardados[$area][$d]['N']) ? 'checked' : '';
                
                $disabled = ($es_completado || $es_futuro) ? 'disabled' : '';
            ?>
                <td class="<?= $clase ?>">
                    <input type="checkbox" name="control[<?= $i ?>][<?= $d ?>][M]" <?= $checked_m ?> <?= $disabled ?>>
                </td>
                <td class="<?= $clase ?>">
                    <input type="checkbox" name="control[<?= $i ?>][<?= $d ?>][T]" <?= $checked_t ?> <?= $disabled ?>>
                </td>
                <td class="<?= $clase ?>">
                    <input type="checkbox" name="control[<?= $i ?>][<?= $d ?>][N]" <?= $checked_n ?> <?= $disabled ?>>
                </td>
            <?php endfor; ?>
        </tr>
        <?php endforeach; ?>
    </table>

    <h4>Control Diario - Día <?= $dia_actual ?></h4>
    <table style="width: 50%;">
        <tr>
            <th>Turno</th>
            <th>Realiza</th>
            <th>Supervisa</th>
        </tr>
        <tr>
            <td><strong>Mañana</strong></td>
            <td><input type="text" name="realiza[<?= $dia_actual ?>][M]" value="<?= isset($datos_diario[$dia_actual]['M']['realiza']) ? htmlspecialchars($datos_diario[$dia_actual]['M']['realiza']) : '' ?>"></td>
            <td><input type="text" name="supervisa[<?= $dia_actual ?>][M]" value="<?= isset($datos_diario[$dia_actual]['M']['supervisa']) ? htmlspecialchars($datos_diario[$dia_actual]['M']['supervisa']) : '' ?>"></td>
        </tr>
        <tr>
            <td><strong>Tarde</strong></td>
            <td><input type="text" name="realiza[<?= $dia_actual ?>][T]" value="<?= isset($datos_diario[$dia_actual]['T']['realiza']) ? htmlspecialchars($datos_diario[$dia_actual]['T']['realiza']) : '' ?>"></td>
            <td><input type="text" name="supervisa[<?= $dia_actual ?>][T]" value="<?= isset($datos_diario[$dia_actual]['T']['supervisa']) ? htmlspecialchars($datos_diario[$dia_actual]['T']['supervisa']) : '' ?>"></td>
        </tr>
        <tr>
            <td><strong>Noche</strong></td>
            <td><input type="text" name="realiza[<?= $dia_actual ?>][N]" value="<?= isset($datos_diario[$dia_actual]['N']['realiza']) ? htmlspecialchars($datos_diario[$dia_actual]['N']['realiza']) : '' ?>"></td>
            <td><input type="text" name="supervisa[<?= $dia_actual ?>][N]" value="<?= isset($datos_diario[$dia_actual]['N']['supervisa']) ? htmlspecialchars($datos_diario[$dia_actual]['N']['supervisa']) : '' ?>"></td>
        </tr>
    </table>

    <button type="submit" name="accion" value="guardar" class="btn-guardar">
        <i class="fas fa-save"></i> Guardar Día <?= $dia_actual ?>
    </button>

    <?php if ($dia_actual >= 28): ?>
    <button type="submit" name="accion" value="completar" class="btn-completar">
        <i class="fas fa-check-circle"></i> Marcar como Completado
    </button>
    <?php endif; ?>
</form>

<?php endif; ?>

</body>
</html>

<?php odbc_close($con); ?>
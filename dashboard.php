<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

include("conexion.php");

$con = odbc_connect($dsn, $usudb, $pwdb);

$mes_actual = date("n");
$anio_actual = date("Y");

$stats = array(
    'limpieza' => 0,
    'temperatura' => 0,
    'verificacion' => 0,
    'epp' => 0,
    'limpieza_activos' => 0,
    'temperatura_activos' => 0
);

if ($con) {
    // Total de formatos
    $sql_limpieza = "SELECT COUNT(*) as total FROM dbo.formatos_limpieza";
    $result = odbc_exec($con, $sql_limpieza);
    if ($result && $row = odbc_fetch_array($result)) {
        $stats['limpieza'] = $row['total'];
    }

    // Formatos activos del mes actual
    $sql_activos = "SELECT COUNT(*) as total FROM dbo.formatos_limpieza 
                    WHERE mes_numerico = $mes_actual 
                    AND anio_numerico = $anio_actual 
                    AND estado = 'en_progreso'";
    $result = odbc_exec($con, $sql_activos);
    if ($result && $row = odbc_fetch_array($result)) {
        $stats['limpieza_activos'] = $row['total'];
    }

    $sql_temp = "SELECT COUNT(*) as total FROM dbo.formatos_temperatura";
    $result = odbc_exec($con, $sql_temp);
    if ($result && $row = odbc_fetch_array($result)) {
        $stats['temperatura'] = $row['total'];
    }

    $sql_temp_activos = "SELECT COUNT(*) as total FROM dbo.formatos_temperatura 
                         WHERE mes_numerico = $mes_actual 
                         AND anio_numerico = $anio_actual 
                         AND estado = 'en_progreso'";
    $result = odbc_exec($con, $sql_temp_activos);
    if ($result && $row = odbc_fetch_array($result)) {
        $stats['temperatura_activos'] = $row['total'];
    }

    $sql_verif = "SELECT COUNT(*) as total FROM dbo.formatos_verificacion";
    $result = odbc_exec($con, $sql_verif);
    if ($result && $row = odbc_fetch_array($result)) {
        $stats['verificacion'] = $row['total'];
    }

    $sql_epp = "SELECT COUNT(*) as total FROM dbo.formatos_epp";
    $result = odbc_exec($con, $sql_epp);
    if ($result && $row = odbc_fetch_array($result)) {
        $stats['epp'] = $row['total'];
    }

    // Obtener formatos activos de limpieza
    $sql_formatos_activos = "SELECT id_formato, movil, sede, ultimo_dia_editado 
                             FROM dbo.formatos_limpieza 
                             WHERE mes_numerico = $mes_actual 
                             AND anio_numerico = $anio_actual 
                             AND estado = 'en_progreso'
                             ORDER BY ultimo_dia_editado DESC";
    $result_activos = odbc_exec($con, $sql_formatos_activos);
    $formatos_activos = array();
    while ($row = odbc_fetch_array($result_activos)) {
        $formatos_activos[] = $row;
    }

    odbc_close($con);
}

$total_formatos = array_sum($stats);
$nombre_mes = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
                    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Gestión</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f2f5; min-height: 100vh; }
        
        .header { background: linear-gradient(135deg, #205ca4 0%, #164273 100%); color: white; padding: 20px 0; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header-content { max-width: 1400px; margin: 0 auto; padding: 0 20px; display: flex; justify-content: space-between; align-items: center; }
        .logo-section { display: flex; align-items: center; gap: 20px; }
        .logo-section img { height: 60px; }
        .logo-section h1 { font-size: 24px; font-weight: 600; }
        .user-section { display: flex; align-items: center; gap: 15px; }
        .user-info { text-align: right; }
        .user-name { font-weight: 600; font-size: 16px; }
        .user-role { font-size: 13px; opacity: 0.9; }
        .btn-logout { background: rgba(255,255,255,0.2); color: white; border: 2px solid white; padding: 8px 20px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease; }
        .btn-logout:hover { background: white; color: #205ca4; }
        
        .container-main { max-width: 1400px; margin: 40px auto; padding: 0 20px; }
        
        .alert-success { background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 8px; margin-bottom: 20px; color: #155724; }
        
        .stats-section { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; margin-bottom: 40px; }
        .stat-card { background: white; padding: 25px; border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); display: flex; align-items: center; gap: 20px; transition: all 0.3s ease; animation: fadeIn 0.5s ease; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.12); }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        
        .stat-icon { width: 70px; height: 70px; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 32px; color: white; }
        .stat-icon.blue { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .stat-icon.green { background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%); }
        .stat-icon.orange { background: linear-gradient(135deg, #f2994a 0%, #f2c94c 100%); }
        .stat-icon.red { background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%); }
        
        .stat-info h3 { font-size: 32px; font-weight: 700; color: #333; margin-bottom: 5px; }
        .stat-info p { color: #666; font-size: 14px; margin: 0; }
        .stat-info small { color: #28a745; font-weight: 600; font-size: 12px; }
        
        .section { background: white; border-radius: 15px; padding: 30px; margin-bottom: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
        .section-title { font-size: 22px; font-weight: 600; color: #333; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid #f0f0f0; display: flex; align-items: center; gap: 10px; }
        .section-title i { color: #667eea; }
        
        .cards-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; }
        .action-card { border: 2px solid #e0e0e0; border-radius: 12px; padding: 25px; text-align: center; transition: all 0.3s ease; cursor: pointer; text-decoration: none; color: inherit; display: block; }
        .action-card:hover { border-color: #667eea; transform: translateY(-3px); box-shadow: 0 8px 15px rgba(102, 126, 234, 0.2); text-decoration: none; }
        
        .action-card-icon { width: 70px; height: 70px; margin: 0 auto 15px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 32px; color: white; }
        .action-card.green .action-card-icon { background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%); }
        .action-card.orange .action-card-icon { background: linear-gradient(135deg, #f2994a 0%, #f2c94c 100%); }
        .action-card.red .action-card-icon { background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%); }
        
        .action-card h3 { font-size: 18px; font-weight: 600; color: #333; margin-bottom: 8px; }
        .action-card p { font-size: 14px; color: #666; margin: 0; }
        
        .formatos-activos { margin-top: 20px; }
        .formato-item { background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; }
        .formato-item:hover { background: #e9ecef; }
        .formato-info { flex: 1; }
        .formato-info strong { display: block; color: #333; }
        .formato-info small { color: #666; }
        .btn-continuar { background: #28a745; color: white; padding: 8px 16px; border-radius: 5px; text-decoration: none; font-size: 14px; }
        .btn-continuar:hover { background: #218838; color: white; }
        
        @media (max-width: 768px) {
            .header-content { flex-direction: column; gap: 15px; text-align: center; }
            .user-section { flex-direction: column; }
            .stats-section { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<div class="header">
    <div class="header-content">
        <div class="logo-section">
            <img src="img/logo_dos.png" alt="Logo">
            <h1>Sistema de Gestión de Ambulancias</h1>
        </div>
        <div class="user-section">
            <div class="user-info">
                <div class="user-name">
                    <i class="fas fa-user-circle"></i> 
                    <?= htmlspecialchars($_SESSION['nombre_completo']) ?>
                </div>
                <div class="user-role">
                    <?= htmlspecialchars(ucfirst($_SESSION['rol'])) ?>
                </div>
            </div>
            <a href="logout.php" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
            </a>
        </div>
    </div>
</div>

<div class="container-main">

    <?php if (isset($_GET['guardado'])): ?>
    <div class="alert-success">
        <strong><i class="fas fa-check-circle"></i> ¡Guardado exitosamente!</strong> 
        Los datos del día han sido registrados correctamente.
    </div>
    <?php endif; ?>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'completado'): ?>
    <div class="alert-success">
        <strong><i class="fas fa-check-circle"></i> ¡Formato completado!</strong> 
        El formato del mes ha sido marcado como completado.
    </div>
    <?php endif; ?>

    <!-- ESTADÍSTICAS -->
    <div class="stats-section">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-info">
                <h3><?= $total_formatos ?></h3>
                <p>Total de Formatos</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-broom"></i>
            </div>
            <div class="stat-info">
                <h3><?= $stats['limpieza'] ?></h3>
                <p>Limpieza y Desinfección</p>
                <small><?= $stats['limpieza_activos'] ?> activos este mes</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="fas fa-thermometer-half"></i>
            </div>
            <div class="stat-info">
                <h3><?= $stats['temperatura'] ?></h3>
                <p>Control de Temperatura</p>
                <small><?= $stats['temperatura_activos'] ?> activos este mes</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon red">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <div class="stat-info">
                <h3><?= $stats['verificacion'] ?></h3>
                <p>Verificaciones Diarias</p>
            </div>
        </div>
    </div>

    <!-- FORMATOS ACTIVOS -->
    <?php if (count($formatos_activos) > 0): ?>
    <div class="section">
        <h2 class="section-title">
            <i class="fas fa-clock"></i>
            Formatos Activos de <?= $nombre_mes[$mes_actual] ?> <?= $anio_actual ?>
        </h2>
        <div class="formatos-activos">
            <?php foreach ($formatos_activos as $fmt): ?>
            <div class="formato-item">
                <div class="formato-info">
                    <strong><i class="fas fa-ambulance"></i> <?= htmlspecialchars($fmt['movil']) ?></strong>
                    <small>Sede: <?= htmlspecialchars($fmt['sede']) ?> | Último día editado: <?= $fmt['ultimo_dia_editado'] ?></small>
                </div>
                <a href="formato_limpieza.php?movil=<?= urlencode($fmt['movil']) ?>" class="btn-continuar">
                    <i class="fas fa-edit"></i> Continuar
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- CREAR NUEVO FORMATO -->
    <div class="section">
        <h2 class="section-title">
            <i class="fas fa-plus-circle"></i>
            Crear Nuevo Formato
        </h2>
        <div class="cards-grid">
            <a href="editar_limpieza.php" class="action-card">
                <div class="action-card-icon">
                    <i class="fas fa-broom"></i>
                </div>
                <h3>Limpieza y Desinfección</h3>
                <p>Control diario de limpieza de ambulancias</p>
            </a>

            <a href="control_temperatura.php" class="action-card green">
                <div class="action-card-icon">
                    <i class="fas fa-thermometer-half"></i>
                </div>
                <h3>Control de Temperatura</h3>
                <p>Temperatura, humedad y cadena de frío</p>
            </a>

            <a href="lista_verificacion.php" class="action-card orange">
                <div class="action-card-icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <h3>Verificación Diaria</h3>
                <p>Lista de verificación asistencial</p>
            </a>

            <a href="formato_epp.php" class="action-card red">
                <div class="action-card-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Relación de EPP</h3>
                <p>Gastos diarios de elementos de protección</p>
            </a>
        </div>
    </div>

    <!-- VER TODOS LOS FORMATOS -->
    <div class="section">
        <h2 class="section-title">
            <i class="fas fa-folder-open"></i>
            Ver Todos los Formatos
        </h2>
        <div class="cards-grid">
            <a href="ver_formatos.php" class="action-card">
                <div class="action-card-icon">
                    <i class="fas fa-broom"></i>
                </div>
                <h3>Limpieza y Desinfección</h3>
                <p>Ver todos los formatos de limpieza guardados</p>
            </a>

            <a href="ver_formatos_temp.php" class="action-card green">
                <div class="action-card-icon">
                    <i class="fas fa-thermometer-half"></i>
                </div>
                <h3>Control de Temperatura</h3>
                <p>Ver todos los controles de temperatura</p>
            </a>

            <a href="ver_formatos_verificacion.php" class="action-card orange">
                <div class="action-card-icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <h3>Verificaciones Diarias</h3>
                <p>Ver todas las listas de verificación</p>
            </a>

            <a href="ver_formatos_epp.php" class="action-card red">
                <div class="action-card-icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <h3>Relación de EPP</h3>
                <p>Ver todos los formatos de EPP guardados</p>
            </a>
        </div>
    </div>



</div>

</body>
</html>
<?php
include("conexion.php");

$con = odbc_connect($dsn, $usudb, $pwdb);
if (!$con) die("Error de conexión");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Formatos EPP</title>
<link href="css/bootstrap.min.css" rel="stylesheet" />
<style>
body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
.container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 1400px; margin: 0 auto; }
h2 { color: #a8814e; margin-bottom: 20px; text-align: center; }
.table { margin-top: 20px; }
.btn-ver { background: #205ca4; color: white; padding: 6px 12px; border-radius: 5px; text-decoration: none; }
.btn-ver:hover { background: #164273; color: white; }
.btn-nuevo { background: #28a745; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; display: inline-block; margin-bottom: 20px; }
.btn-nuevo:hover { background: #218838; color: white; }

.btn-volver {
    background: #6c757d;
    color: white;
    padding: 8px 16px;
    border-radius: 5px;
    text-decoration: none;
    display: inline-block;
    margin-bottom: 20px;
}
.btn-volver:hover {
    background: #5a6268;
    color: white;
}


</style>
</head>
<body>

<div class="container">
    <div align="center">
        <img src="img/logo_dos.png" width="262" height="81" alt="Logo" />
    </div>

    <h2><i class="fas fa-boxes"></i> Formatos de Relación de EPP</h2>

<a href="dashboard.php" class="btn-volver">⬅ Volver</a>

    <a href="formato_epp.php" class="btn-nuevo">➕ Crear Nuevo Formato</a>

    <table class="table table-striped table-bordered">
        <thead style="background: #205ca4; color: white;">
            <tr>
                <th>ID</th>
                <th>Servicio</th>
                <th>Sede</th>
                <th>Entregado por</th>
                <th>Fecha entrega</th>
                <th>Registro</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT id_formato, servicio, sede, entregado_por, fecha_entrega, fecha_registro FROM formatos_epp ORDER BY id_formato DESC";
            $res = odbc_exec($con, $sql);

            if (!$res) {
                echo "<tr><td colspan='7' class='text-center'>Error al consultar: " . odbc_errormsg($con) . "</td></tr>";
            } else {
                $hay = false;
                while ($row = odbc_fetch_array($res)) {
                    $hay = true;
                    ?>
                    <tr>
                        <td><?= $row['id_formato'] ?></td>
                        <td><?= htmlspecialchars($row['servicio']) ?></td>
                        <td><?= htmlspecialchars($row['sede']) ?></td>
                        <td><?= htmlspecialchars($row['entregado_por']) ?></td>
                        <td><?= $row['fecha_entrega'] ?></td>
                        <td><?= $row['fecha_registro'] ?></td>
                        <td>
                            <a href="ver_detalle_epp.php?id=<?= $row['id_formato'] ?>" class="btn-ver">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                        </td>
                    </tr>
                    <?php
                }
                if (!$hay) echo "<tr><td colspan='7' class='text-center'>No hay formatos guardados</td></tr>";
            }

            odbc_close($con);
            ?>
        </tbody>
    </table>
</div>

</body>
 <!-- By Luis Maldonado -->
</html>

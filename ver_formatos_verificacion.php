<?php 
include("conexion.php");

$con = odbc_connect($dsn, $usudb, $pwdb);

if (!$con) {
    die("Error de conexión");
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Listas de Verificación Guardadas</title>
<link href="css/bootstrap.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
body {
    font-family: Arial, sans-serif;
    padding: 20px;
    background: #f5f5f5;
}
.container {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    max-width: 1200px;
    margin: 0 auto;
}
h2 {
    color: #a8814e;
    margin-bottom: 30px;
    text-align: center;
}
.table {
    margin-top: 20px;
}
.btn-ver {
    background: #205ca4;
    color: white;
    padding: 5px 15px;
    border-radius: 5px;
    text-decoration: none;
}
.btn-ver:hover {
    background: #164273;
    color: white;
}
.btn-nuevo {
    background: #28a745;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    display: inline-block;
    margin-bottom: 20px;
}
.btn-nuevo:hover {
    background: #218838;
    color: white;
}

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
    
    <h2>
        <i class="fas fa-clipboard-check"></i> 
        Listas de Verificación Diaria de Ambulancia
    </h2>
    <a href="dashboard.php" class="btn-volver">⬅ Volver</a>

    <a href="lista_verificacion.php" class="btn-nuevo">
        <i class="fas fa-plus"></i> Crear Nueva Verificación
    </a>

    <table class="table table-striped table-bordered">
        <thead style="background: #205ca4; color: white;">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Fecha</th>
                <th>Placa</th>
                <th>Fecha Registro</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT id_formato, nombre, fecha, placa, fecha_registro 
                    FROM dbo.formatos_verificacion 
                    ORDER BY id_formato DESC";
            
            $result = odbc_exec($con, $sql);
            
            if (!$result) {
                echo "<tr><td colspan='6' class='text-center'>Error al consultar: " . odbc_errormsg($con) . "</td></tr>";
            } else {
                $hay_datos = false;
                
                while ($row = odbc_fetch_array($result)) {
                    $hay_datos = true;
                    ?>
                    <tr>
                        <td><?= $row['id_formato'] ?></td>
                        <td><?= htmlspecialchars($row['nombre']) ?></td>
                        <td><?= $row['fecha'] ?></td>
                        <td><?= htmlspecialchars($row['placa']) ?></td>
                        <td><?= $row['fecha_registro'] ?></td>
                        <td>
                            <a href="ver_detalle_verificacion.php?id=<?= $row['id_formato'] ?>" class="btn-ver">
                                <i class="fas fa-eye"></i> Ver Detalle
                            </a>
                        </td>
                    </tr>
                    <?php
                }
                
                if (!$hay_datos) {
                    echo "<tr><td colspan='6' class='text-center'>No hay verificaciones guardadas</td></tr>";
                }
            }
            
            odbc_close($con);
            ?>
        </tbody>
    </table>
</div>

</body>
 <!-- By Luis Maldonado -->
</html>
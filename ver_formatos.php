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
<title>Formatos Guardados</title>
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
.badge {
    padding: 5px 10px;
    border-radius: 4px;
}
.badge-success {
    background: #28a745;
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
        <i class="fas fa-file-alt"></i> 
        Formatos de Limpieza y Desinfección
    </h2>
    <a href="dashboard.php" class="btn-volver">⬅ Volver</a>

    <a href="expe_del_paciente.php" class="btn-nuevo">
        <i class="fas fa-plus"></i> Crear Nuevo Formato
    </a>

    <table class="table table-striped table-bordered">
        <thead style="background: #205ca4; color: white;">
            <tr>
                <th>ID</th>
                <th>Mes</th>
                <th>Año</th>
                <th>Sede</th>
                <th>Móvil/Placa</th>
                <th>Fecha Registro</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Primero verificar qué columnas existen
            $sql_check = "SELECT TOP 1 * FROM formatos_limpieza";
            $result_check = odbc_exec($con, $sql_check);
            
            // Obtener nombres de columnas
            $num_cols = odbc_num_fields($result_check);
            $columnas = array();
            for ($i = 1; $i <= $num_cols; $i++) {
                $columnas[] = odbc_field_name($result_check, $i);
            }
            
            // Determinar nombre de columna ID
            $col_id = 'id';
            if (in_array('ID', $columnas)) {
                $col_id = 'ID';
            } elseif (in_array('Id', $columnas)) {
                $col_id = 'Id';
            } elseif (in_array('id_formato', $columnas)) {
                $col_id = 'id_formato';
            }
            
            $sql = "SELECT $col_id as id, mes, anio, sede, movil, fecha_registro 
                    FROM formatos_limpieza 
                    ORDER BY $col_id DESC";
            
            $result = odbc_exec($con, $sql);
            
            if (!$result) {
                echo "<tr><td colspan='7' class='text-center'>Error al consultar: " . odbc_errormsg($con) . "</td></tr>";
            } else {
                $hay_datos = false;
                
                while ($row = odbc_fetch_array($result)) {
                    $hay_datos = true;
                    ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['mes']) ?></td>
                        <td><?= htmlspecialchars($row['anio']) ?></td>
                        <td><?= htmlspecialchars($row['sede']) ?></td>
                        <td><?= htmlspecialchars($row['movil']) ?></td>
                        <td><?= $row['fecha_registro'] ?></td>
                        <td>
                            <a href="ver_detalles.php?id=<?= $row['id'] ?>" class="btn-ver">
                                <i class="fas fa-eye"></i> Ver Detalle
                            </a>
                            <a href="editar_limpieza.php?id=<?= $row['id'] ?>" class="btn-ver" style="background: #ff9800; margin-left: 5px;">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                        </td>
                    </tr>
                    <?php
                }
                
                if (!$hay_datos) {
                    echo "<tr><td colspan='7' class='text-center'>No hay formatos guardados</td></tr>";
                }
            }
            
            odbc_close($con);
            ?>
        </tbody>
    </table>
</div>

        </body>
</html>
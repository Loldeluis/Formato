<?php
$serverName = "10.10.1.2\SQLEXPRESS";
$connectionInfo = array( "Database"=>"Calidad_Gestion");

// Datos ODBC
$dsn = "Driver={SQL Server};Server=10.10.1.2\SQLEXPRESS;Database=Calidad_Gestion;Integrated Security=SSPI;Persist Security Info=False;";
$usudb = 'sa';
$pwdb  = 'gs73136';

// Intentar la conexión
$con = odbc_connect($dsn, $usudb, $pwdb);

?>

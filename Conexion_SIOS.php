<?php
$serverName = "10.10.1.2\SQLEXPRESS";
$connectionInfo = array("Database" => "SIOS");

// Datos ODBC
$dsns  = "Driver={SQL Server};Server=10.10.1.2\SQLEXPRESS;Database=SIOS;Integrated Security=SSPI;Persist Security Info=False;";
$usudbs = 'sa';
$pwdbs  = 'gs73136';

// Intentar conexión
$cons = odbc_connect($dsns, $usudbs, $pwdbs);

?>

<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include("conexion.php");

$con = @odbc_connect($dsn, $usudb, $pwdb);

if (!$con) {
    die("Error de conexión: " . odbc_errormsg());
}

try {
    /* ================================
       1️⃣ DATOS PRINCIPALES
    ================================ */
    
    $nombre = isset($_POST['nombre']) ? str_replace("'", "''", trim($_POST['nombre'])) : '';
    $fecha  = isset($_POST['fecha'])  ? trim($_POST['fecha'])  : '';
    $placa  = isset($_POST['placa'])  ? str_replace("'", "''", trim($_POST['placa'])) : '';

    /* ================================
       2️⃣ INICIAR TRANSACCIÓN
    ================================ */
    
    $begin = @odbc_exec($con, "BEGIN TRANSACTION");
    if (!$begin) {
        throw new Exception("No se pudo iniciar la transacción");
    }

    /* ================================
       3️⃣ INSERT FORMATO PRINCIPAL
    ================================ */

    $sql_formato = "INSERT INTO dbo.formatos_verificacion 
                    (nombre, fecha, placa, fecha_registro)
                    VALUES ('$nombre', '$fecha', '$placa', GETDATE())";

    $resultado = @odbc_exec($con, $sql_formato);
    
    if (!$resultado) {
        throw new Exception("Error al insertar formato: " . odbc_errormsg($con));
    }

    /* ================================
       4️⃣ OBTENER ID
    ================================ */

    $sql_id = "SELECT CAST(IDENT_CURRENT('dbo.formatos_verificacion') AS INT) AS id_formato";
    $res_id = @odbc_exec($con, $sql_id);
    
    $id_formato = null;
    
    if ($res_id) {
        $row_id = @odbc_fetch_array($res_id);
        if ($row_id && isset($row_id['id_formato'])) {
            $id_formato = (int)$row_id['id_formato'];
        }
    }
    
    if (!$id_formato) {
        $sql_max = "SELECT MAX(id_formato) AS id_formato FROM dbo.formatos_verificacion";
        $res_max = @odbc_exec($con, $sql_max);
        if ($res_max) {
            $row_max = @odbc_fetch_array($res_max);
            if ($row_max && isset($row_max['id_formato'])) {
                $id_formato = (int)$row_max['id_formato'];
            }
        }
    }

    if (!$id_formato || $id_formato <= 0) {
        throw new Exception("No se pudo obtener el ID del formato");
    }

    /* ================================
       5️⃣ GUARDAR RESPIRATORIO
    ================================ */

    $respiratorio = [
        "AMBU ADULTO", "AMBU PEDIATRICO", "CANULA NASAL ADULTO", 
        "CANULA NASA PEDIATRICO", "APIRADORA NASAL", "ADITAMENTO SUPRAGLOTICO",
        "MASC OXIG. ADULTO", "MASC OXIG. PEDIATRICO", 
        "MASC OXIG. ADULTO CON RESERVORIO", "MASC OXIG. PEDIATRICO CON RESERVORIO",
        "MICRONEBULIZADOR ADULTO", "MICRONEBULIZADOR PEDIATRICO",
        "KIT VENTURI ADULTO", "KIT VENTURI PEDIATRICO",
        "CANULA DE GUEDEL", "PICO FLUJO"
    ];

    for ($i = 1; $i <= count($respiratorio); $i++) {
        $cant = isset($_POST["resp_cant_$i"]) ? trim($_POST["resp_cant_$i"]) : '';
        if ($cant !== '') {
            $desc = str_replace("'", "''", $respiratorio[$i-1]);
            $sql = "INSERT INTO dbo.verificacion_respiratorio (id_formato, item_numero, descripcion, cantidad)
                    VALUES ($id_formato, $i, '$desc', '$cant')";
            @odbc_exec($con, $sql);
        }
    }

    /* ================================
       6️⃣ GUARDAR OXÍMETRO
    ================================ */

    $oximetro = [
        "VASO HUMIFICADOR SIMPLE", "VASO HUMIFICADOR VENTURI",
        "FLUJOMETRO", "RACOR", "LLAVE EXPANSIVA"
    ];

    for ($i = 1; $i <= count($oximetro); $i++) {
        $cant = isset($_POST["oxi_cant_$i"]) ? trim($_POST["oxi_cant_$i"]) : '';
        if ($cant !== '') {
            $desc = str_replace("'", "''", $oximetro[$i-1]);
            $sql = "INSERT INTO dbo.verificacion_oximetro (id_formato, item_numero, descripcion, cantidad)
                    VALUES ($id_formato, $i, '$desc', '$cant')";
            @odbc_exec($con, $sql);
        }
    }

    /* ================================
       7️⃣ GUARDAR QUIRÚRGICO
    ================================ */

    $quirurgico = [
        "PINZAS DE MANGUIL", "MONOGAFAS", "GUANTES ESTERILES",
        "SONDA NASOGASTRICA", "SONDA NELATON", "SONDA DE SUCCION",
        "BAJALENGUAS", "ALGODÓN", "GASAS", "ESPARADRAPO BLANCO",
        "MICROPORE", "ISODINE ESPUMA", "ISODINE SOLUCION",
        "RIÑONERA", "TIJERAS CORTATODO", "CLAM UMBILICAL",
        "JABON QUIRURGICO", "GUARDIAN CORTOPUNZANTE", "ATRIL",
        "PATO", "PISINGO", "TERMOMETRO"
    ];

    for ($i = 1; $i <= count($quirurgico); $i++) {
        $cant = isset($_POST["quir_cant_$i"]) ? trim($_POST["quir_cant_$i"]) : '';
        if ($cant !== '') {
            $desc = str_replace("'", "''", $quirurgico[$i-1]);
            $sql = "INSERT INTO dbo.verificacion_quirurgico (id_formato, item_numero, descripcion, cantidad)
                    VALUES ($id_formato, $i, '$desc', '$cant')";
            @odbc_exec($con, $sql);
        }
    }

    /* ================================
       8️⃣ GUARDAR TRAUMA
    ================================ */

    $trauma = [
        "VENDA DE ALGODON", "VENDA DE TELA", "VENDA ELASTICA",
        "VENDA TRIANGULAR", "INMOVILIZADORES DE EXTREMIDADES",
        "COLLAR CERVICAL GRADUABLE ADULTO", "COLLAR CERVICAL GRADUABLE PEDIATRICO",
        "COLLAR CERVICAL PHILADELPHIA ADULTO", "COLLAR CERVICAL PHILADELPHIA PEDIATRICO",
        "INMOVILIZADOR LATERAL DE CABEZA", "COMPRESA LAPAROTOMIA"
    ];

    for ($i = 1; $i <= count($trauma); $i++) {
        $cant = isset($_POST["trauma_cant_$i"]) ? trim($_POST["trauma_cant_$i"]) : '';
        if ($cant !== '') {
            $desc = str_replace("'", "''", $trauma[$i-1]);
            $sql = "INSERT INTO dbo.verificacion_trauma (id_formato, item_numero, descripcion, cantidad)
                    VALUES ($id_formato, $i, '$desc', '$cant')";
            @odbc_exec($con, $sql);
        }
    }

    /* ================================
       9️⃣ GUARDAR CIRCULATORIO
    ================================ */

    $circulatorio = [
        "CATETER #14", "CATETER #16", "CATETER #18", "CATETER #20",
        "CATETER #22", "CATETER #24", "EQUIPO PERICRANEAL", "BURETROL",
        "EQUIOPO MICROGOTERO", "EQUIPO MACROGOTERO",
        "TORNIQUETE PARA ACCESO IV", "TORNIQUETE HEMORRAGIA",
        "JERINGA DESECHABLE", "SOLUCION SALINA 0.9% 500 ML",
        "LACTATO DE RINGER 500 ML", "DEXTROSA AL 5% 500 ML", "DEXTROSA AL 10% 500 ML"
    ];

    for ($i = 1; $i <= count($circulatorio); $i++) {
        $cant = isset($_POST["circ_cant_$i"]) ? trim($_POST["circ_cant_$i"]) : '';
        if ($cant !== '') {
            $desc = str_replace("'", "''", $circulatorio[$i-1]);
            $sql = "INSERT INTO dbo.verificacion_circulatorio (id_formato, item_numero, descripcion, cantidad)
                    VALUES ($id_formato, $i, '$desc', '$cant')";
            @odbc_exec($con, $sql);
        }
    }

    /* ================================
       🔟 GUARDAR OTROS
    ================================ */

    $otros = [
        "KIT DE DERRAME", "KIT DE AGENTES QUIMICOS", "SILLA DE RUEDAS",
        "TABLA ESPINAL CORTA", "TABLA ESPINAL LARGA", "CHALECOS REFLECTIVO"
    ];

    for ($i = 1; $i <= count($otros); $i++) {
        $cant = isset($_POST["otros_cant_$i"]) ? trim($_POST["otros_cant_$i"]) : '';
        if ($cant !== '') {
            $desc = str_replace("'", "''", $otros[$i-1]);
            $sql = "INSERT INTO dbo.verificacion_otros (id_formato, item_numero, descripcion, cantidad)
                    VALUES ($id_formato, $i, '$desc', '$cant')";
            @odbc_exec($con, $sql);
        }
    }

    /* ================================
       1️⃣1️⃣ EQUIPOS BIOMÉDICOS
    ================================ */

    $equipos_bio = [
        "Monitor de signos vitales", "Ventilador de traslado",
        "Bomba de infusión", "Desfribilador",
        "Apirador de secreciones", "Glucometro", "Equipo de organos"
    ];

    for ($i = 1; $i <= 7; $i++) {
        $carga = isset($_POST["item_10$i"]) ? trim($_POST["item_10$i"]) : '';
        
        $f_si = isset($_POST["f_{$i}_si"]) ? 1 : 0;
        $f_no = isset($_POST["f_{$i}_no"]) ? 1 : 0;
        $ce_si = isset($_POST["ce_{$i}_si"]) ? 1 : 0;
        $ce_no = isset($_POST["ce_{$i}_no"]) ? 1 : 0;
        $acc_si = isset($_POST["acc_{$i}_si"]) ? 1 : 0;
        $acc_no = isset($_POST["acc_{$i}_no"]) ? 1 : 0;
        $obs = isset($_POST["obs_$i"]) ? str_replace("'", "''", trim($_POST["obs_$i"])) : '';

        if ($carga !== '' || $f_si || $f_no || $ce_si || $ce_no || $acc_si || $acc_no || $obs !== '') {
            $desc = str_replace("'", "''", $equipos_bio[$i-1]);
            $sql = "INSERT INTO dbo.verificacion_equipos_biomedicos 
                    (id_formato, item_numero, descripcion, porcentaje_carga, 
                     funcional_si, funcional_no, cable_si, cable_no, 
                     accesorios_si, accesorios_no, observaciones)
                    VALUES ($id_formato, $i, '$desc', '$carga', 
                            $f_si, $f_no, $ce_si, $ce_no, 
                            $acc_si, $acc_no, '$obs')";
            @odbc_exec($con, $sql);
        }
    }

    /* ================================
       1️⃣2️⃣ OTROS EQUIPOS
    ================================ */

    $otros_equipos = [
        "Tensiometro adulto", "Fonendoscopio adulto",
        "Tensiometro pediátrico", "Fonendoscopio pediátrico"
    ];

    $base = 201;
    for ($i = 0; $i < 4; $i++) {
        $si = isset($_POST["item_" . ($base + $i*3)]) ? 1 : 0;
        $no = isset($_POST["item_" . ($base + $i*3 + 1)]) ? 1 : 0;
        $obs = isset($_POST["item_" . ($base + $i*3 + 2)]) ? str_replace("'", "''", trim($_POST["item_" . ($base + $i*3 + 2)])) : '';

        if ($si || $no || $obs !== '') {
            $desc = str_replace("'", "''", $otros_equipos[$i]);
            $sql = "INSERT INTO dbo.verificacion_otros_equipos 
                    (id_formato, item_numero, descripcion, tiene_si, tiene_no, observaciones)
                    VALUES ($id_formato, " . ($i+1) . ", '$desc', $si, $no, '$obs')";
            @odbc_exec($con, $sql);
        }
    }

    /* ================================
       1️⃣3️⃣ LIMPIEZA Y CANECAS
    ================================ */

    $limpia = isset($_POST['item_301']) ? 1 : 0;
    $sucia = isset($_POST['item_302']) ? 1 : 0;
    $can_buen = isset($_POST['item_303']) ? 1 : 0;
    $can_mal = isset($_POST['item_304']) ? 1 : 0;
    $can_vac = isset($_POST['item_305']) ? 1 : 0;
    $can_llen = isset($_POST['item_306']) ? 1 : 0;
    $can_con = isset($_POST['item_307']) ? 1 : 0;
    $can_sin = isset($_POST['item_308']) ? 1 : 0;
    $alc_si = isset($_POST['item_309']) ? 1 : 0;
    $alc_no = isset($_POST['item_310']) ? 1 : 0;

    $sql_limp = "INSERT INTO dbo.verificacion_limpieza 
                 (id_formato, cabina_limpia, cabina_sucia, 
                  canecas_buen_estado, canecas_mal_estado, 
                  canecas_vacias, canecas_llenas, 
                  canecas_con_bolsa, canecas_sin_bolsa, 
                  alcohol_si, alcohol_no)
                 VALUES ($id_formato, $limpia, $sucia, 
                         $can_buen, $can_mal, $can_vac, $can_llen, 
                         $can_con, $can_sin, $alc_si, $alc_no)";
    @odbc_exec($con, $sql_limp);

    /* ================================
       1️⃣4️⃣ OXÍGENO
    ================================ */

    // Oxígeno Central
    for ($i = 1; $i <= 2; $i++) {
        $base = 401 + ($i-1)*3;
        $si = isset($_POST["item_$base"]) ? 1 : 0;
        $no = isset($_POST["item_" . ($base+1)]) ? 1 : 0;
        $obs = isset($_POST["item_" . ($base+2)]) ? str_replace("'", "''", trim($_POST["item_" . ($base+2)])) : '';

        if ($si || $no || $obs !== '') {
            $sql = "INSERT INTO dbo.verificacion_oxigeno 
                    (id_formato, tipo, numero, funcional_si, funcional_no, observaciones)
                    VALUES ($id_formato, 'central', $i, $si, $no, '$obs')";
            @odbc_exec($con, $sql);
        }
    }

    // Oxígeno Portátil
    for ($i = 1; $i <= 2; $i++) {
        $base = 407 + ($i-1)*3;
        $si = isset($_POST["item_$base"]) ? 1 : 0;
        $no = isset($_POST["item_" . ($base+1)]) ? 1 : 0;
        $obs = isset($_POST["item_" . ($base+2)]) ? str_replace("'", "''", trim($_POST["item_" . ($base+2)])) : '';

        if ($si || $no || $obs !== '') {
            $sql = "INSERT INTO dbo.verificacion_oxigeno 
                    (id_formato, tipo, numero, funcional_si, funcional_no, observaciones)
                    VALUES ($id_formato, 'portatil', $i, $si, $no, '$obs')";
            @odbc_exec($con, $sql);
        }
    }

    /* ================================
       1️⃣5️⃣ GASTOS
    ================================ */

    $gastos = isset($_POST['item_501']) ? str_replace("'", "''", trim($_POST['item_501'])) : '';
    if ($gastos !== '') {
        $sql = "INSERT INTO dbo.verificacion_gastos (id_formato, reporte)
                VALUES ($id_formato, '$gastos')";
        @odbc_exec($con, $sql);
    }

    /* ================================
       1️⃣6️⃣ COMMIT
    ================================ */
    
    $commit = @odbc_exec($con, "COMMIT");
    if (!$commit) {
        throw new Exception("Error al confirmar la transacción");
    }

    @odbc_close($con);

    echo "<!DOCTYPE html>";
    echo "<html><head><meta charset='utf-8'><script>
    window.setTimeout(function() {
        window.location.href = 'ver_formatos_verificacion.php';
    }, 2000);
    </script></head><body>";
    echo "<h2>✅ Lista de verificación guardada exitosamente</h2>";
    echo "<p>ID del formato: <strong>$id_formato</strong></p>";
    echo "<p>Redirigiendo en 2 segundos...</p>";
    echo "<p><a href='ver_formatos_verificacion.php'>📋 O haz clic aquí para ver todas las verificaciones</a></p>";
    echo "</body></html>";
    exit;

} catch (Exception $e) {
    @odbc_exec($con, "ROLLBACK");
    @odbc_close($con);

    echo "<!DOCTYPE html>";
    echo "<html><head><meta charset='utf-8'><title>Error</title>";
    echo "<style>body{font-family:Arial;padding:20px;} .error{background:#ffebee;border:1px solid #f44336;padding:15px;border-radius:5px;}</style>";
    echo "</head><body>";
    echo "<div class='error'>";
    echo "<h3>❌ Error al guardar la verificación</h3>";
    echo "<p><strong>Mensaje:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div>";
    echo "<br><a href='javascript:history.back()'>← Volver al formulario</a>";
    echo "</body></html>";
}
?>
 <!-- By Luis Maldonado -->
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lista de Verificación Diaria Asistencial de Ambulancia</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
/* ========================= */
/*   ESTILOS ORIGINALES      */
/* ========================= */

.Estilo1 { 
    font-family: Arial, Helvetica, sans-serif; 
    font-weight: bold; 
}
.Estilo4 { 
    font-family: Arial, Helvetica, sans-serif; 
    font-weight: bold; 
    font-size: 20px; 
    height: 25px; 
}

table { 
    width: 100%; 
    border-collapse: collapse; 
    margin-bottom: 25px; 
}

table, th, td { 
    border: 1px solid black; 
}

th, td { 
    padding: 6px; 
    font-size: 14px; 
}

.checkbox { 
    width: 18px; 
    height: 18px; 
}

input[type="text"], 
input[type="date"], 
textarea {
    width: 100%; 
    border: none; 
    font-size: 14px; 
    padding: 4px;
}

.header-table td { 
    font-weight: bold; 
    background: #f2f2f2; 
}

h3 {
    text-align: center;
    margin-top: 30px;
    margin-bottom: 10px;
    font-weight: bold;
}

.side-box {
    width: 28%;
    float: right;
    border: 1px solid #000;
    margin-left: 15px;
}

.side-title {
    background: #f5f5f5;
    font-weight: bold;
    padding: 8px;
    text-align: center;
    border-bottom: 1px solid #000;
}

.side-box table td {
    text-align: center;
    padding: 8px;
}

.equipos-table td {
    padding: 4px;
    font-size: 13px;
}

.equipos-table th, 
.equipos-table td {
    text-align: center;
    vertical-align: middle;
    font-size: 13px;
    padding: 4px;
}

.equipos-table input[type="checkbox"] {
    width: 18px;
    height: 18px;
}

.equipos-table input[type="text"] {
    width: 100%;
    border: none;
    text-align: center;
}

tr, td, th {
    vertical-align: middle;
}

/* ========================= */
/*   ESTILOS NUEVOS          */
/* ========================= */

.nav-tabs-custom {
    background: white;
    border-radius: 8px;
    padding: 10px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.nav-tabs-custom .nav-link {
    color: #205ca4;
    font-weight: bold;
}

.nav-tabs-custom .nav-link.active {
    background: #205ca4;
    color: white;
}

.list-group-item {
    cursor: pointer;
    transition: all 0.2s;
}

.list-group-item:hover {
    background: #f8f9fa;
}

.list-group-item.active {
    background: #205ca4;
    border-color: #205ca4;
}

.modal-header {
    border-bottom: solid 2px #205ca4;
}

.alert-float {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    min-width: 300px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.section-collapsed {
    cursor: pointer;
    user-select: none;
}

.section-collapsed:hover {
    background-color: #e9ecef;
}

.collapsible-content {
    display: none;
}

.collapsible-content.show {
    display: block;
}
</style>

</head>
<body>

<div class="container">
    <div class="col-lg-12 col-sm-12 main-section">
        <div class="modal-content">

            <div align="center">
                <img src="img/logo_dos.png" class="img-fluid" width="262" height="81" alt="Logo" onerror="this.style.display='none'" />
            </div>

            <div class="modal-header">
                <h3 class="modal-title" style="color: #a8814e;">
                    <i class="fas fa-clipboard-check"></i> LISTA DE VERIFICACION DIARIA ASISTENCIAL DE AMBULANCIA
                </h3>
            </div>

            <!-- NAVEGACIÓN -->
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs" id="mainTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="nuevo-tab" data-bs-toggle="tab" data-bs-target="#nuevo" type="button">
                            <i class="fas fa-plus-circle"></i> Nuevo Registro
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="lista-tab" data-bs-toggle="tab" data-bs-target="#lista" type="button" onclick="cargarListaRegistros()">
                            <i class="fas fa-list"></i> Ver Registros (<span id="contadorRegistros">0</span>)
                        </button>
                    </li>
                </ul>
            </div>

            <!-- ALERTAS -->
            <div id="alertContainer"></div>

            <div class="tab-content" id="mainTabContent">

                <!-- ====================== TAB NUEVO REGISTRO ====================== -->
                <div class="tab-pane fade show active" id="nuevo" role="tabpanel">

                    <form class="container col-sm-12" name="form1" id="formPrincipal">
                        <input type="hidden" id="registroId" name="registroId" value="">

                        <!-- Encabezado -->
                        <table class="header-table">
                            <tr>
                                <td>Código: PPA-AI-FT11</td>
                                <td>Versión: 01</td>
                                <td>Fecha aprobación: 22/08/2025</td>
                            </tr>
                        </table>

                        <!-- Datos básicos -->
                        <table>
                            <tr>
                                <td><b>Nombre:</b></td>
                                <td><input type="text" name="nombre" id="nombre"></td>

                                <td><b>Fecha:</b></td>
                                <td><input type="date" name="fecha" id="fecha"></td>

                                <td><b>Placa:</b></td>
                                <td><input type="text" name="placa" id="placa"></td>
                            </tr>
                        </table>

                        <!-- ========== RESPIRATORIO ========== -->
                        <h3 class="section-collapsed" onclick="toggleSection('respiratorio')">
                            <i class="fas fa-chevron-down" id="icon-respiratorio"></i> RESPIRATORIO
                        </h3>
                        <div id="section-respiratorio" class="collapsible-content show">
                            <table id="tablaRespiratorio">
                                <tr>
                                    <th>Ítem</th>
                                    <th>Descripción</th>
                                    <th>CANT</th>
                                </tr>
                            </table>
                        </div>

                        <!-- ========== OXIMETRO ========== -->
                        <h3 class="section-collapsed" onclick="toggleSection('oximetro')">
                            <i class="fas fa-chevron-down" id="icon-oximetro"></i> OXIMETRO
                        </h3>
                        <div id="section-oximetro" class="collapsible-content show">
                            <table id="tablaOximetro">
                                <tr>
                                    <th>Ítem</th>
                                    <th>Descripción</th>
                                    <th>CANT</th>
                                </tr>
                            </table>
                        </div>

                        <!-- ========== QUIRURGICO ========== -->
                        <h3 class="section-collapsed" onclick="toggleSection('quirurgico')">
                            <i class="fas fa-chevron-down" id="icon-quirurgico"></i> QUIRURGICO
                        </h3>
                        <div id="section-quirurgico" class="collapsible-content show">
                            <table id="tablaQuirurgico">
                                <tr>
                                    <th>Ítem</th>
                                    <th>Descripción</th>
                                    <th>CANT</th>
                                </tr>
                            </table>
                        </div>

                        <!-- ========== TRAUMA ========== -->
                        <h3 class="section-collapsed" onclick="toggleSection('trauma')">
                            <i class="fas fa-chevron-down" id="icon-trauma"></i> TRAUMA
                        </h3>
                        <div id="section-trauma" class="collapsible-content show">
                            <table id="tablaTrauma">
                                <tr>
                                    <th>Ítem</th>
                                    <th>Descripción</th>
                                    <th>CANT</th>
                                </tr>
                            </table>
                        </div>

                        <!-- ========== CIRCULATORIO ========== -->
                        <h3 class="section-collapsed" onclick="toggleSection('circulatorio')">
                            <i class="fas fa-chevron-down" id="icon-circulatorio"></i> CIRCULATORIO
                        </h3>
                        <div id="section-circulatorio" class="collapsible-content show">
                            <table id="tablaCirculatorio">
                                <tr>
                                    <th>Ítem</th>
                                    <th>Descripción</th>
                                    <th>CANT</th>
                                </tr>
                            </table>
                        </div>

                        <!-- ========== OTROS ========== -->
                        <h3 class="section-collapsed" onclick="toggleSection('otros')">
                            <i class="fas fa-chevron-down" id="icon-otros"></i> OTROS
                        </h3>
                        <div id="section-otros" class="collapsible-content show">
                            <table id="tablaOtros">
                                <tr>
                                    <th>Ítem</th>
                                    <th>Descripción</th>
                                    <th>CANT</th>
                                </tr>
                            </table>
                        </div>

                        <!-- ========== EQUIPOS BIOMÉDICOS ========== -->
                        <h3>EQUIPOS BIOMÉDICOS</h3>
                        <table class="equipos-table">
                            <tr>
                                <th rowspan="2">N°</th>
                                <th rowspan="2">Descripción</th>
                                <th rowspan="2">% Carga</th>
                                <th colspan="2">Funcional</th>
                                <th colspan="2">Cable Energía</th>
                                <th colspan="2">Accesorios</th>
                                <th rowspan="2">Observaciones</th>
                            </tr>
                            <tr>
                                <th>SI</th><th>NO</th>
                                <th>SI</th><th>NO</th>
                                <th>SI</th><th>NO</th>
                            </tr>
                            <tbody id="tablaEquiposBiomedicos"></tbody>
                        </table>

                        <!-- ========== OTROS EQUIPOS ========== -->
                        <h3>OTROS EQUIPOS</h3>
                        <table id="tablaOtrosEquipos">
                            <tr>
                                <th>N°</th>
                                <th>Descripción</th>
                                <th>SI</th>
                                <th>NO</th>
                                <th>Observaciones</th>
                            </tr>
                        </table>

                        <!-- ========== LIMPIEZA/CANECAS/ALCOHOL ========== -->
                        <table style="width: 40%; float:right;" id="tablaEstadoCabina">
                            <tr><th>ESTADO LIMPIEZA CABINA</th></tr>
                            <tr>
                                <td>
                                    LIMPIA <input type="checkbox" name="item_301" id="item_301"> &nbsp;&nbsp;
                                    SUCIA <input type="checkbox" name="item_302" id="item_302">
                                </td>
                            </tr>

                            <tr><th>CANECAS</th></tr>
                            <tr>
                                <td>BUEN ESTADO <input type="checkbox" name="item_303" id="item_303">  
                                    MAL ESTADO <input type="checkbox" name="item_304" id="item_304"></td>
                            </tr>
                            <tr>
                                <td>VACÍAS <input type="checkbox" name="item_305" id="item_305">  
                                    LLENAS <input type="checkbox" name="item_306" id="item_306"></td>
                            </tr>
                            <tr>
                                <td>CON BOLSA <input type="checkbox" name="item_307" id="item_307">  
                                    SIN BOLSA <input type="checkbox" name="item_308" id="item_308"></td>
                            </tr>

                            <tr><th>ALCOHOL</th></tr>
                            <tr>
                                <td>SI <input type="checkbox" name="item_309" id="item_309">  
                                    NO <input type="checkbox" name="item_310" id="item_310"></td>
                            </tr>
                        </table>

                        <div style="clear: both;"></div>

                        <!-- ========== OXIGENO ========== -->
                        <h3>OXIGENO</h3>

                        <table style="width: 48%; float:left; margin-right: 2%;">
                            <tr>
                                <th>OXIGENO CENTRAL</th>
                                <th>Manómetro Central</th>
                                <th>Observaciones</th>
                            </tr>
                            <tr>
                                <td>#1</td>
                                <td>
                                    FUNCIONAL: 
                                    SI <input type="checkbox" name="item_401" id="item_401">
                                    NO <input type="checkbox" name="item_402" id="item_402">
                                </td>
                                <td><input type="text" name="item_403" id="item_403"></td>
                            </tr>
                            <tr>
                                <td>#2</td>
                                <td>
                                    FUNCIONAL:
                                    SI <input type="checkbox" name="item_404" id="item_404">
                                    NO <input type="checkbox" name="item_405" id="item_405">
                                </td>
                                <td><input type="text" name="item_406" id="item_406"></td>
                            </tr>
                        </table>

                        <table style="width: 48%; float:left;">
                            <tr>
                                <th>OXIGENO PORTATIL</th>
                                <th>Manómetro Portátil</th>
                                <th>Observaciones</th>
                            </tr>
                            <tr>
                                <td>#1</td>
                                <td>FUNCIONAL: 
                                    SI <input type="checkbox" name="item_407" id="item_407">
                                    NO <input type="checkbox" name="item_408" id="item_408">
                                </td>
                                <td><input type="text" name="item_409" id="item_409"></td>
                            </tr>
                            <tr>
                                <td>#2</td>
                                <td>FUNCIONAL:
                                    SI <input type="checkbox" name="item_410" id="item_410">
                                    NO <input type="checkbox" name="item_411" id="item_411">
                                </td>
                                <td><input type="text" name="item_412" id="item_412"></td>
                            </tr>
                        </table>

                        <div style="clear: both;"></div>

                        <!-- ========== REPORTE DE GASTOS ========== -->
                        <h3>REPORTE DE GASTOS</h3>
                        <table>
                            <tr>
                                <td><textarea name="item_501" id="item_501" style="width:100%; height:120px;"></textarea></td>
                            </tr>
                        </table>

                        <!-- BOTONES -->
                        <div class="d-flex gap-2 mb-4">
                            <button class="btn btn-primary" type="button" onclick="guardarFormulario()">
                                <i class="fas fa-save"></i> <span id="btnGuardarTexto">Guardar</span>
                            </button>
                            <button class="btn btn-secondary" type="button" onclick="limpiarFormulario()" id="btnLimpiar" style="display:none;">
                                <i class="fas fa-redo"></i> Limpiar
                            </button>
                            <button class="btn btn-info" type="button" onclick="abrirVistaPrevia()">
                                <i class="fas fa-print"></i> Imprimir
                            </button>
                        </div>

                    </form>
                </div>

                <!-- ====================== TAB LISTA REGISTROS ====================== -->
                <div class="tab-pane fade" id="lista" role="tabpanel">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <input type="text" class="form-control" id="buscarRegistro" placeholder="🔍 Buscar por nombre, fecha o placa..." onkeyup="filtrarRegistros()">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="list-group" id="listaRegistros">
                                <div class="text-center p-4 text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p>No hay registros guardados</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div id="detalleRegistro" class="card">
                                <div class="card-body text-center text-muted">
                                    <i class="fas fa-hand-pointer fa-3x mb-3"></i>
                                    <p>Selecciona un registro para ver los detalles</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// ========================= DATOS ESTÁTICOS =========================
const DATOS = {
    respiratorio: [
        "AMBU ADULTO", "AMBU PEDIATRICO", "CANULA NASAL ADULTO",
        "CANULA NASA PEDIATRICO", "APIRADORA NASAL", "ADITAMENTO SUPRAGLOTICO",
        "MASC OXIG. ADULTO", "MASC OXIG. PEDIATRICO", "MASC OXIG. ADULTO CON RESERVORIO",
        "MASC OXIG. PEDIATRICO CON RESERVORIO", "MICRONEBULIZADOR ADULTO",
        "MICRONEBULIZADOR PEDIATRICO", "KIT VENTURI ADULTO", "KIT VENTURI PEDIATRICO",
        "CANULA DE GUEDEL", "PICO FLUJO"
    ],
    oximetro: [
        "VASO HUMIFICADOR SIMPLE", "VASO HUMIFICADOR VENTURI", "FLUJOMETRO",
        "RACOR", "LLAVE EXPANSIVA"
    ],
    quirurgico: [
        "PINZAS DE MANGUIL", "MONOGAFAS", "GUANTES ESTERILES", "SONDA NASOGASTRICA",
        "SONDA NELATON", "SONDA DE SUCCION", "BAJALENGUAS", "ALGODÓN", "GASAS",
        "ESPARADRAPO BLANCO", "MICROPORE", "ISODINE ESPUMA", "ISODINE SOLUCION",
        "RIÑONERA", "TIJERAS CORTATODO", "CLAM UMBILICAL", "JABON QUIRURGICO",
        "GUARDIAN CORTOPUNZANTE", "ATRIL", "PATO", "PISINGO", "TERMOMETRO"
    ],
    trauma: [
        "VENDA DE ALGODON", "VENDA DE TELA", "VENDA ELASTICA", "VENDA TRIANGULAR",
        "INMOVILIZADORES DE EXTREMIDADES", "COLLAR CERVICAL GRADUABLE ADULTO",
        "COLLAR CERVICAL GRADUABLE PEDIATRICO", "COLLAR CERVICAL PHILADELPHIA ADULTO",
        "COLLAR CERVICAL PHILADELPHIA PEDIATRICO", "INMOVILIZADOR LATERAL DE CABEZA",
        "COMPRESA LAPAROTOMIA"
    ],
    circulatorio: [
        "CATETER #14", "CATETER #16", "CATETER #18", "CATETER #20", "CATETER #22",
        "CATETER #24", "EQUIPO PERICRANEAL", "BURETROL", "EQUIOPO MICROGOTERO",
        "EQUIPO MACROGOTERO", "TORNIQUETE PARA ACCESO IV", "TORNIQUETE HEMORRAGIA",
        "JERINGA DESECHABLE", "SOLUCION SALINA 0.9% 500 ML", "LACTATO DE RINGER 500 ML",
        "DEXTROSA AL 5% 500 ML", "DEXTROSA AL 10% 500 ML"
    ],
    otros: [
        "KIT DE DERRAME", "KIT DE AGENTES QUIMICOS", "SILLA DE RUEDAS",
        "TABLA ESPINAL CORTA", "TABLA ESPINAL LARGA", "CHALECOS REFLECTIVO"
    ],
    equiposBiomedicos: [
        "Monitor de signos vitales", "Ventilador de traslado", "Bomba de infusión",
        "Desfribilador", "Apirador de secreciones", "Glucometro", "Equipo de organos"
    ],
    otrosEquipos: [
        "Tensiometro adulto", "Fonendoscopio adulto", "Tensiometro pediátrico",
        "Fonendoscopio pediátrico"
    ]
};

let registrosGlobales = [];

// ========================= STORAGE WRAPPER =========================
const Storage = {
    set: function(key, value) {
        try {
            localStorage.setItem(key, value);
            return true;
        } catch (e) {
            console.error('Error al guardar:', e);
            return false;
        }
    },
    
    get: function(key) {
        try {
            return localStorage.getItem(key);
        } catch (e) {
            console.error('Error al obtener:', e);
            return null;
        }
    },
    
    delete: function(key) {
        try {
            localStorage.removeItem(key);
            return true;
        } catch (e) {
            console.error('Error al eliminar:', e);
            return false;
        }
    },
    
    list: function(prefix) {
        try {
            const keys = [];
            for (let i = 0; i < localStorage.length; i++) {
                const key = localStorage.key(i);
                if (key.startsWith(prefix)) {
                    keys.push(key);
                }
            }
            return keys;
        } catch (e) {
            console.error('Error al listar:', e);
            return [];
        }
    }
};

// ========================= INICIALIZACIÓN =========================
document.addEventListener('DOMContentLoaded', function() {
    generarTablas();
    const hoy = new Date().toISOString().split('T')[0];
    document.getElementById('fecha').value = hoy;
    cargarContadorRegistros();
});

function generarTablas() {
    // RESPIRATORIO
    let html = '';
    DATOS.respiratorio.forEach((item, i) => {
        html += `<tr>
            <td>${i+1}</td>
            <td>${item}</td>
            <td><input type="text" name="resp_cant_${i+1}" id="resp_cant_${i+1}"></td>
        </tr>`;
    });
    document.getElementById('tablaRespiratorio').innerHTML += html;

    // OXIMETRO
    html = '';
    DATOS.oximetro.forEach((item, i) => {
        html += `<tr>
            <td>${i+1}</td>
            <td>${item}</td>
            <td><input type="text" name="oxi_cant_${i+1}" id="oxi_cant_${i+1}"></td>
        </tr>`;
    });
    document.getElementById('tablaOximetro').innerHTML += html;

    // QUIRURGICO
    html = '';
    DATOS.quirurgico.forEach((item, i) => {
        html += `<tr>
            <td>${i+1}</td>
            <td>${item}</td>
            <td><input type="text" name="quir_cant_${i+1}" id="quir_cant_${i+1}"></td>
        </tr>`;
    });
    document.getElementById('tablaQuirurgico').innerHTML += html;

    // TRAUMA
    html = '';
    DATOS.trauma.forEach((item, i) => {
        html += `<tr>
            <td>${i+1}</td>
            <td>${item}</td>
            <td><input type="text" name="trauma_cant_${i+1}" id="trauma_cant_${i+1}"></td>
        </tr>`;
    });
    document.getElementById('tablaTrauma').innerHTML += html;

    // CIRCULATORIO
    html = '';
    DATOS.circulatorio.forEach((item, i) => {
        html += `<tr>
            <td>${i+1}</td>
            <td>${item}</td>
            <td><input type="text" name="circ_cant_${i+1}" id="circ_cant_${i+1}"></td>
        </tr>`;
    });
    document.getElementById('tablaCirculatorio').innerHTML += html;

    // OTROS
    html = '';
    DATOS.otros.forEach((item, i) => {
        html += `<tr>
            <td>${i+1}</td>
            <td>${item}</td>
            <td><input type="text" name="otros_cant_${i+1}" id="otros_cant_${i+1}"></td>
        </tr>`;
    });
    document.getElementById('tablaOtros').innerHTML += html;

    // EQUIPOS BIOMÉDICOS
    html = '';
    DATOS.equiposBiomedicos.forEach((item, i) => {
        const n = i + 1;
        html += `<tr>
            <td>${n}</td>
            <td>${item}</td>
            <td><input type="text" name="item_10${n}" id="item_10${n}"></td>
            <td><input type="checkbox" name="f_${n}_si" id="f_${n}_si"></td>
            <td><input type="checkbox" name="f_${n}_no" id="f_${n}_no"></td>
            <td><input type="checkbox" name="ce_${n}_si" id="ce_${n}_si"></td>
            <td><input type="checkbox" name="ce_${n}_no" id="ce_${n}_no"></td>
            <td><input type="checkbox" name="acc_${n}_si" id="acc_${n}_si"></td>
            <td><input type="checkbox" name="acc_${n}_no" id="acc_${n}_no"></td>
            <td><input type="text" name="obs_${n}" id="obs_${n}"></td>
        </tr>`;
    });
    document.getElementById('tablaEquiposBiomedicos').innerHTML = html;

    // OTROS EQUIPOS
    html = '';
    DATOS.otrosEquipos.forEach((item, i) => {
        const base = 201 + (i * 3);
        html += `<tr>
            <td>${i+1}</td>
            <td>${item}</td>
            <td><input type="checkbox" name="item_${base}" id="item_${base}"></td>
            <td><input type="checkbox" name="item_${base+1}" id="item_${base+1}"></td>
            <td><input type="text" name="item_${base+2}" id="item_${base+2}"></td>
        </tr>`;
    });
    document.getElementById('tablaOtrosEquipos').innerHTML += html;
}

// ========================= TOGGLE SECTIONS =========================
function toggleSection(section) {
    const content = document.getElementById('section-' + section);
    const icon = document.getElementById('icon-' + section);
    
    if (content.classList.contains('show')) {
        content.classList.remove('show');
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-right');
    } else {
        content.classList.add('show');
        icon.classList.remove('fa-chevron-right');
        icon.classList.add('fa-chevron-down');
    }
}

// ========================= GUARDAR FORMULARIO =========================
function guardarFormulario() {
    const registroId = document.getElementById('registroId').value || `verif_${Date.now()}`;
    
    const data = {
        id: registroId,
        fecha_guardado: new Date().toISOString(),
        nombre: document.getElementById('nombre').value,
        fecha: document.getElementById('fecha').value,
        placa: document.getElementById('placa').value,
        respiratorio: {},
        oximetro: {},
        quirurgico: {},
        trauma: {},
        circulatorio: {},
        otros: {},
        equiposBiomedicos: {},
        otrosEquipos: {},
        estadoCabina: {},
        oxigeno: {},
        reporteGastos: document.getElementById('item_501').value
    };

    // Recoger RESPIRATORIO
    DATOS.respiratorio.forEach((item, i) => {
        const val = document.getElementById(`resp_cant_${i+1}`)?.value || '';
        if (val) data.respiratorio[i+1] = val;
    });

    // Recoger OXIMETRO
    DATOS.oximetro.forEach((item, i) => {
        const val = document.getElementById(`oxi_cant_${i+1}`)?.value || '';
        if (val) data.oximetro[i+1] = val;
    });

    // Recoger QUIRURGICO
    DATOS.quirurgico.forEach((item, i) => {
        const val = document.getElementById(`quir_cant_${i+1}`)?.value || '';
        if (val) data.quirurgico[i+1] = val;
    });

    // Recoger TRAUMA
    DATOS.trauma.forEach((item, i) => {
        const val = document.getElementById(`trauma_cant_${i+1}`)?.value || '';
        if (val) data.trauma[i+1] = val;
    });

    // Recoger CIRCULATORIO
    DATOS.circulatorio.forEach((item, i) => {
        const val = document.getElementById(`circ_cant_${i+1}`)?.value || '';
        if (val) data.circulatorio[i+1] = val;
    });

    // Recoger OTROS
    DATOS.otros.forEach((item, i) => {
        const val = document.getElementById(`otros_cant_${i+1}`)?.value || '';
        if (val) data.otros[i+1] = val;
    });

    // Recoger EQUIPOS BIOMÉDICOS
    DATOS.equiposBiomedicos.forEach((item, i) => {
        const n = i + 1;
        data.equiposBiomedicos[n] = {
            carga: document.getElementById(`item_10${n}`)?.value || '',
            funcional_si: document.getElementById(`f_${n}_si`)?.checked || false,
            funcional_no: document.getElementById(`f_${n}_no`)?.checked || false,
            cable_si: document.getElementById(`ce_${n}_si`)?.checked || false,
            cable_no: document.getElementById(`ce_${n}_no`)?.checked || false,
            acc_si: document.getElementById(`acc_${n}_si`)?.checked || false,
            acc_no: document.getElementById(`acc_${n}_no`)?.checked || false,
            obs: document.getElementById(`obs_${n}`)?.value || ''
        };
    });

    // Recoger OTROS EQUIPOS
    DATOS.otrosEquipos.forEach((item, i) => {
        const base = 201 + (i * 3);
        data.otrosEquipos[i+1] = {
            si: document.getElementById(`item_${base}`)?.checked || false,
            no: document.getElementById(`item_${base+1}`)?.checked || false,
            obs: document.getElementById(`item_${base+2}`)?.value || ''
        };
    });

    // Recoger ESTADO CABINA
    for (let i = 301; i <= 310; i++) {
        const cb = document.getElementById(`item_${i}`);
        if (cb) {
            data.estadoCabina[i] = cb.checked;
        }
    }

    // Recoger OXIGENO
    for (let i = 401; i <= 412; i++) {
        const elem = document.getElementById(`item_${i}`);
        if (elem) {
            if (elem.type === 'checkbox') {
                data.oxigeno[i] = elem.checked;
            } else {
                data.oxigeno[i] = elem.value || '';
            }
        }
    }

    if (Storage.set(`verificacion:${registroId}`, JSON.stringify(data))) {
        mostrarAlerta('Registro guardado correctamente en el navegador. Enviando a la base de datos...', 'success');
        
        // Actualizar registrosGlobales con el nuevo dato
        registrosGlobales.unshift(data);
        
        limpiarFormulario();
        cargarContadorRegistros();
        
        // Automáticamente enviar a PHP después de 500ms
        setTimeout(() => {
            exportarRegistroPHP(registroId, true);
        }, 500);
    } else {
        mostrarAlerta('Error al guardar el registro', 'danger');
    }
}

// ========================= CARGAR REGISTROS =========================
function cargarListaRegistros() {
    const keys = Storage.list('verificacion:');
    
    if (keys.length > 0) {
        const registros = keys.map(key => {
            try {
                const data = Storage.get(key);
                return data ? JSON.parse(data) : null;
            } catch {
                return null;
            }
        }).filter(r => r !== null);
        
        registrosGlobales = registros;
        mostrarListaRegistros(registrosGlobales);
    } else {
        registrosGlobales = [];
        document.getElementById('listaRegistros').innerHTML = `
            <div class="text-center p-4 text-muted">
                <i class="fas fa-inbox fa-3x mb-3"></i>
                <p>No hay registros guardados</p>
            </div>
        `;
    }
}

function mostrarListaRegistros(registros) {
    const lista = document.getElementById('listaRegistros');
    
    if (registros.length === 0) {
        lista.innerHTML = `
            <div class="text-center p-4 text-muted">
                <i class="fas fa-inbox fa-3x mb-3"></i>
                <p>No hay registros guardados</p>
            </div>
        `;
        return;
    }

    let html = '';
    registros.forEach(reg => {
        html += `
            <a href="#" class="list-group-item list-group-item-action" onclick="verDetalleRegistro('${reg.id}'); return false;">
                <div class="d-flex w-100 justify-content-between">
                    <h6 class="mb-1">${reg.nombre || 'Sin nombre'}</h6>
                    <small>${new Date(reg.fecha_guardado).toLocaleDateString()}</small>
                </div>
                <p class="mb-1"><strong>Fecha:</strong> ${reg.fecha || 'N/A'}</p>
                <small><strong>Placa:</strong> ${reg.placa || 'N/A'}</small>
            </a>
        `;
    });
    
    lista.innerHTML = html;
}

// ========================= VER DETALLE =========================
function verDetalleRegistro(id) {
    const registro = registrosGlobales.find(r => r.id === id);
    if (!registro) return;

    // Contar items completados
    const totalResp = Object.keys(registro.respiratorio || {}).length;
    const totalOxi = Object.keys(registro.oximetro || {}).length;
    const totalQuir = Object.keys(registro.quirurgico || {}).length;

    const detalle = document.getElementById('detalleRegistro');
    detalle.innerHTML = `
        <div class="card-header bg-primary text-white">
            <h5><i class="fas fa-clipboard-check"></i> ${registro.nombre || 'Sin nombre'} - ${registro.fecha || ''}</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Nombre:</strong> ${registro.nombre || 'N/A'}</p>
                    <p><strong>Fecha:</strong> ${registro.fecha || 'N/A'}</p>
                    <p><strong>Placa:</strong> ${registro.placa || 'N/A'}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Items Respiratorio:</strong> ${totalResp}</p>
                    <p><strong>Items Oxímetro:</strong> ${totalOxi}</p>
                    <p><strong>Items Quirúrgico:</strong> ${totalQuir}</p>
                    <p><strong>Guardado:</strong> ${new Date(registro.fecha_guardado).toLocaleString()}</p>
                </div>
            </div>
            <div class="mb-3">
                <strong>Reporte de Gastos:</strong>
                <p>${registro.reporteGastos || 'Sin reporte'}</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-warning" onclick="editarRegistro('${id}')">
                    <i class="fas fa-edit"></i> Editar
                </button>
                <button class="btn btn-danger" onclick="eliminarRegistro('${id}')">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
                <button class="btn btn-success" onclick="exportarRegistroPHP('${id}')">
                    <i class="fas fa-file-export"></i> Enviar a PHP
                </button>
                <button class="btn btn-secondary" onclick="cerrarDetalle()">
                    <i class="fas fa-times"></i> Cerrar
                </button>
            </div>
        </div>
    `;
}

// ========================= EDITAR REGISTRO =========================
function editarRegistro(id) {
    const registro = registrosGlobales.find(r => r.id === id);
    if (!registro) return;

    // Cambiar a tab de formulario
    const nuevoTab = document.getElementById('nuevo-tab');
    nuevoTab.click();

    // Llenar campos básicos
    document.getElementById('registroId').value = registro.id;
    document.getElementById('nombre').value = registro.nombre || '';
    document.getElementById('fecha').value = registro.fecha || '';
    document.getElementById('placa').value = registro.placa || '';
    document.getElementById('item_501').value = registro.reporteGastos || '';

    // Llenar RESPIRATORIO
    if (registro.respiratorio) {
        Object.keys(registro.respiratorio).forEach(key => {
            const elem = document.getElementById(`resp_cant_${key}`);
            if (elem) elem.value = registro.respiratorio[key];
        });
    }

    // Llenar OXIMETRO
    if (registro.oximetro) {
        Object.keys(registro.oximetro).forEach(key => {
            const elem = document.getElementById(`oxi_cant_${key}`);
            if (elem) elem.value = registro.oximetro[key];
        });
    }

    // Llenar QUIRURGICO
    if (registro.quirurgico) {
        Object.keys(registro.quirurgico).forEach(key => {
            const elem = document.getElementById(`quir_cant_${key}`);
            if (elem) elem.value = registro.quirurgico[key];
        });
    }

    // Llenar TRAUMA
    if (registro.trauma) {
        Object.keys(registro.trauma).forEach(key => {
            const elem = document.getElementById(`trauma_cant_${key}`);
            if (elem) elem.value = registro.trauma[key];
        });
    }

    // Llenar CIRCULATORIO
    if (registro.circulatorio) {
        Object.keys(registro.circulatorio).forEach(key => {
            const elem = document.getElementById(`circ_cant_${key}`);
            if (elem) elem.value = registro.circulatorio[key];
        });
    }

    // Llenar OTROS
    if (registro.otros) {
        Object.keys(registro.otros).forEach(key => {
            const elem = document.getElementById(`otros_cant_${key}`);
            if (elem) elem.value = registro.otros[key];
        });
    }

    // Llenar EQUIPOS BIOMÉDICOS
    if (registro.equiposBiomedicos) {
        Object.keys(registro.equiposBiomedicos).forEach(key => {
            const equipo = registro.equiposBiomedicos[key];
            const carga = document.getElementById(`item_10${key}`);
            const fsi = document.getElementById(`f_${key}_si`);
            const fno = document.getElementById(`f_${key}_no`);
            const csi = document.getElementById(`ce_${key}_si`);
            const cno = document.getElementById(`ce_${key}_no`);
            const asi = document.getElementById(`acc_${key}_si`);
            const ano = document.getElementById(`acc_${key}_no`);
            const obs = document.getElementById(`obs_${key}`);
            
            if (carga) carga.value = equipo.carga || '';
            if (fsi) fsi.checked = equipo.funcional_si || false;
            if (fno) fno.checked = equipo.funcional_no || false;
            if (csi) csi.checked = equipo.cable_si || false;
            if (cno) cno.checked = equipo.cable_no || false;
            if (asi) asi.checked = equipo.acc_si || false;
            if (ano) ano.checked = equipo.acc_no || false;
            if (obs) obs.value = equipo.obs || '';
        });
    }

    // Llenar OTROS EQUIPOS
    if (registro.otrosEquipos) {
        Object.keys(registro.otrosEquipos).forEach(key => {
            const base = 201 + ((key - 1) * 3);
            const equipo = registro.otrosEquipos[key];
            const si = document.getElementById(`item_${base}`);
            const no = document.getElementById(`item_${base+1}`);
            const obs = document.getElementById(`item_${base+2}`);
            
            if (si) si.checked = equipo.si || false;
            if (no) no.checked = equipo.no || false;
            if (obs) obs.value = equipo.obs || '';
        });
    }

    // Llenar ESTADO CABINA
    if (registro.estadoCabina) {
        Object.keys(registro.estadoCabina).forEach(key => {
            const cb = document.getElementById(`item_${key}`);
            if (cb) cb.checked = registro.estadoCabina[key] || false;
        });
    }

    // Llenar OXIGENO
    if (registro.oxigeno) {
        Object.keys(registro.oxigeno).forEach(key => {
            const elem = document.getElementById(`item_${key}`);
            if (elem) {
                if (elem.type === 'checkbox') {
                    elem.checked = registro.oxigeno[key] || false;
                } else {
                    elem.value = registro.oxigeno[key] || '';
                }
            }
        });
    }

    document.getElementById('btnGuardarTexto').textContent = 'Actualizar';
    document.getElementById('btnLimpiar').style.display = 'inline-block';
    
    mostrarAlerta('Editando registro. Realiza los cambios y haz clic en Actualizar', 'info');
    
    window.scrollTo(0, 0);
}

// ========================= LIMPIAR FORMULARIO =========================
function limpiarFormulario() {
    document.getElementById('formPrincipal').reset();
    document.getElementById('registroId').value = '';
    document.getElementById('btnGuardarTexto').textContent = 'Guardar';
    document.getElementById('btnLimpiar').style.display = 'none';
    
    const hoy = new Date().toISOString().split('T')[0];
    document.getElementById('fecha').value = hoy;
}

// ========================= ELIMINAR REGISTRO =========================
function eliminarRegistro(id) {
    if (!confirm('¿Estás seguro de eliminar este registro? Esta acción no se puede deshacer.')) {
        return;
    }

    if (Storage.delete(`verificacion:${id}`)) {
        mostrarAlerta('Registro eliminado correctamente', 'success');
        cargarListaRegistros();
        cargarContadorRegistros();
        document.getElementById('detalleRegistro').innerHTML = `
            <div class="card-body text-center text-muted">
                <i class="fas fa-hand-pointer fa-3x mb-3"></i>
                <p>Selecciona un registro para ver los detalles</p>
            </div>
        `;
    } else {
        mostrarAlerta('Error al eliminar el registro', 'danger');
    }
}

// ========================= EXPORTAR A PHP =========================
function exportarRegistroPHP(id, autoEnviar = false) {
    const registro = registrosGlobales.find(r => r.id === id);
    if (!registro) return;

    // Crear formulario dinámico para enviar por POST
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'insertar_verificacion.php';

    // Función auxiliar para crear inputs
    function addInput(name, value) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value || '';
        form.appendChild(input);
    }

    // Campos básicos
    addInput('nombre', registro.nombre);
    addInput('fecha', registro.fecha);
    addInput('placa', registro.placa);

    // RESPIRATORIO
    if (registro.respiratorio) {
        Object.keys(registro.respiratorio).forEach(key => {
            addInput(`resp_cant_${key}`, registro.respiratorio[key]);
        });
    }

    // OXIMETRO
    if (registro.oximetro) {
        Object.keys(registro.oximetro).forEach(key => {
            addInput(`oxi_cant_${key}`, registro.oximetro[key]);
        });
    }

    // QUIRURGICO
    if (registro.quirurgico) {
        Object.keys(registro.quirurgico).forEach(key => {
            addInput(`quir_cant_${key}`, registro.quirurgico[key]);
        });
    }

    // TRAUMA
    if (registro.trauma) {
        Object.keys(registro.trauma).forEach(key => {
            addInput(`trauma_cant_${key}`, registro.trauma[key]);
        });
    }

    // CIRCULATORIO
    if (registro.circulatorio) {
        Object.keys(registro.circulatorio).forEach(key => {
            addInput(`circ_cant_${key}`, registro.circulatorio[key]);
        });
    }

    // OTROS
    if (registro.otros) {
        Object.keys(registro.otros).forEach(key => {
            addInput(`otros_cant_${key}`, registro.otros[key]);
        });
    }

    // EQUIPOS BIOMÉDICOS
    if (registro.equiposBiomedicos) {
        Object.keys(registro.equiposBiomedicos).forEach(key => {
            const equipo = registro.equiposBiomedicos[key];
            addInput(`item_10${key}`, equipo.carga);
            if (equipo.funcional_si) addInput(`f_${key}_si`, 'on');
            if (equipo.funcional_no) addInput(`f_${key}_no`, 'on');
            if (equipo.cable_si) addInput(`ce_${key}_si`, 'on');
            if (equipo.cable_no) addInput(`ce_${key}_no`, 'on');
            if (equipo.acc_si) addInput(`acc_${key}_si`, 'on');
            if (equipo.acc_no) addInput(`acc_${key}_no`, 'on');
            addInput(`obs_${key}`, equipo.obs);
        });
    }

    // OTROS EQUIPOS
    if (registro.otrosEquipos) {
        Object.keys(registro.otrosEquipos).forEach(key => {
            const base = 201 + ((key - 1) * 3);
            const equipo = registro.otrosEquipos[key];
            if (equipo.si) addInput(`item_${base}`, 'on');
            if (equipo.no) addInput(`item_${base+1}`, 'on');
            addInput(`item_${base+2}`, equipo.obs);
        });
    }

    // ESTADO CABINA
    if (registro.estadoCabina) {
        Object.keys(registro.estadoCabina).forEach(key => {
            if (registro.estadoCabina[key]) {
                addInput(`item_${key}`, 'on');
            }
        });
    }

    // OXIGENO
    if (registro.oxigeno) {
        Object.keys(registro.oxigeno).forEach(key => {
            const val = registro.oxigeno[key];
            if (typeof val === 'boolean') {
                if (val) addInput(`item_${key}`, 'on');
            } else {
                addInput(`item_${key}`, val);
            }
        });
    }

    // REPORTE DE GASTOS
    addInput('item_501', registro.reporteGastos);

    document.body.appendChild(form);
    
    if (autoEnviar || confirm('¿Enviar este registro a la base de datos PHP?')) {
        form.submit();
    } else {
        document.body.removeChild(form);
    }
}

// ========================= FILTRAR REGISTROS =========================
function filtrarRegistros() {
    const busqueda = document.getElementById('buscarRegistro').value.toLowerCase();
    
    const registrosFiltrados = registrosGlobales.filter(r => {
        return (r.nombre || '').toLowerCase().includes(busqueda) ||
               (r.fecha || '').toLowerCase().includes(busqueda) ||
               (r.placa || '').toLowerCase().includes(busqueda);
    });
    
    mostrarListaRegistros(registrosFiltrados);
}

// ========================= CONTADOR =========================
function cargarContadorRegistros() {
    const keys = Storage.list('verificacion:');
    document.getElementById('contadorRegistros').textContent = keys.length;
}

// ========================= CERRAR DETALLE =========================
function cerrarDetalle() {
    document.getElementById('detalleRegistro').innerHTML = `
        <div class="card-body text-center text-muted">
            <i class="fas fa-hand-pointer fa-3x mb-3"></i>
            <p>Selecciona un registro para ver los detalles</p>
        </div>
    `;
    mostrarAlerta('Detalle cerrado', 'info');
}

// ========================= ALERTAS =========================
function mostrarAlerta(mensaje, tipo) {
    const alertContainer = document.getElementById('alertContainer');
    const alerta = document.createElement('div');
    alerta.className = `alert alert-${tipo} alert-dismissible fade show alert-float`;
    alerta.innerHTML = `
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    alertContainer.appendChild(alerta);
    
    setTimeout(() => {
        alerta.remove();
    }, 4000);
}

// ========================= VISTA PREVIA E IMPRESIÓN =========================
function abrirVistaPrevia() {
    // Recopilar todos los datos del formulario actual
    const form = document.getElementById('formPrincipal');
    const formData = new FormData(form);
    
    const datos = {
        nombre: formData.get('nombre'),
        fecha: formData.get('fecha'),
        placa: formData.get('placa'),
        respiratorio: {},
        oximetro: {},
        quirurgico: {},
        trauma: {},
        circulatorio: {},
        otros: {},
        equiposBiomedicos: {},
        otrosEquipos: {},
        estadoCabina: {},
        oxigeno: {},
        reporteGastos: document.getElementById('item_501').value
    };
    
    // Recoger RESPIRATORIO
    DATOS.respiratorio.forEach((item, i) => {
        const val = document.getElementById(`resp_cant_${i+1}`)?.value || '';
        if (val) datos.respiratorio[i+1] = val;
    });
    
    // Recoger OXIMETRO
    DATOS.oximetro.forEach((item, i) => {
        const val = document.getElementById(`oxi_cant_${i+1}`)?.value || '';
        if (val) datos.oximetro[i+1] = val;
    });
    
    // Recoger QUIRURGICO
    DATOS.quirurgico.forEach((item, i) => {
        const val = document.getElementById(`quir_cant_${i+1}`)?.value || '';
        if (val) datos.quirurgico[i+1] = val;
    });
    
    // Recoger TRAUMA
    DATOS.trauma.forEach((item, i) => {
        const val = document.getElementById(`trauma_cant_${i+1}`)?.value || '';
        if (val) datos.trauma[i+1] = val;
    });
    
    // Recoger CIRCULATORIO
    DATOS.circulatorio.forEach((item, i) => {
        const val = document.getElementById(`circ_cant_${i+1}`)?.value || '';
        if (val) datos.circulatorio[i+1] = val;
    });
    
    // Recoger OTROS
    DATOS.otros.forEach((item, i) => {
        const val = document.getElementById(`otros_cant_${i+1}`)?.value || '';
        if (val) datos.otros[i+1] = val;
    });
    
    // Recoger EQUIPOS BIOMÉDICOS
    DATOS.equiposBiomedicos.forEach((item, i) => {
        const n = i + 1;
        datos.equiposBiomedicos[n] = {
            carga: document.getElementById(`item_10${n}`)?.value || '',
            funcional_si: document.getElementById(`f_${n}_si`)?.checked || false,
            funcional_no: document.getElementById(`f_${n}_no`)?.checked || false,
            cable_si: document.getElementById(`ce_${n}_si`)?.checked || false,
            cable_no: document.getElementById(`ce_${n}_no`)?.checked || false,
            acc_si: document.getElementById(`acc_${n}_si`)?.checked || false,
            acc_no: document.getElementById(`acc_${n}_no`)?.checked || false,
            obs: document.getElementById(`obs_${n}`)?.value || ''
        };
    });
    
    // Recoger OTROS EQUIPOS
    DATOS.otrosEquipos.forEach((item, i) => {
        const base = 201 + (i * 3);
        datos.otrosEquipos[i+1] = {
            si: document.getElementById(`item_${base}`)?.checked || false,
            no: document.getElementById(`item_${base+1}`)?.checked || false,
            obs: document.getElementById(`item_${base+2}`)?.value || ''
        };
    });
    
    // Recoger ESTADO CABINA
    for (let i = 301; i <= 310; i++) {
        const cb = document.getElementById(`item_${i}`);
        if (cb) {
            datos.estadoCabina[i] = cb.checked;
        }
    }
    
    // Recoger OXIGENO
    for (let i = 401; i <= 412; i++) {
        const elem = document.getElementById(`item_${i}`);
        if (elem) {
            if (elem.type === 'checkbox') {
                datos.oxigeno[i] = elem.checked;
            } else {
                datos.oxigeno[i] = elem.value || '';
            }
        }
    }
    
    // Guardar en sessionStorage para que preview_verificacion.php lo lea
    sessionStorage.setItem('previewVerificacion', JSON.stringify(datos));
    
    // Abrir preview_verificacion.php en nueva ventana
    window.open('preview_verificacion.php', 'preview', 'width=1400,height=900,resizable=yes,scrollbars=yes');
    
    mostrarAlerta('✓ Vista previa abierta en nueva ventana', 'info');
}
</script>

</body>
 <!-- By Luis Maldonado -->
</html>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Control Diario Ambulancias - Sistema Digital</title>
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

.header-table td:first-child,
.header-table td:nth-child(3) {
    font-size: 13px;
    white-space: nowrap;
}

.header-table input[type="text"] {
    height: 32px;
    font-size: 14px;
    padding: 4px 6px;
}

.header-table td {
    padding: 4px 6px;
}

.header-table table {
    height: 100%;
}

.header-table table input[type="text"] {
    height: 40px;
    font-size: 16px;
    padding: 6px 8px;
}

.header-table table th {
    font-size: 15px;
    background: #f2f2f2;
    font-weight: bold;
}

h3 {
    text-align: center;
    margin-top: 30px;
    margin-bottom: 10px;
    font-weight: bold;
}

tr, td, th {
    vertical-align: middle;
}

.temp-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 30px;
}

.temp-table th,
.temp-table td {
    border: 1px solid #000;
    text-align: center;
    padding: 2px;
}

.temp-table th.day {
    font-size: 11px;
    font-weight: bold;
}

.temp-table th.turno {
    font-size: 10px;
}

.temp-table input {
    width: 100%;
    border: none;
    text-align: center;
    font-size: 11px;
    padding: 2px;
}

.temp-wrapper {
    max-width: 4000px;
    margin: 0 auto;
    overflow-x: auto;
    padding-bottom: 15px;
}

.temp-table {
    min-width: 1600px;
}

.day-separator {
    background: #e6e6e6;
}

.temp-table td {
    height: 28px;
}

.temp-table input[type="checkbox"] {
    width: 18px;
    height: 18px;
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

.badge-status {
    padding: 5px 10px;
    border-radius: 12px;
    font-size: 11px;
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
</style>

</head>
<body>

<div class="container-fluid temp-wrapper">
    <div class="col-lg-12 col-sm-12 main-section">
        <div class="modal-content">

            <div align="center">
                <img src="img/logo_dos.png" class="img-fluid" width="262" height="81" alt="Logo" onerror="this.style.display='none'" />
            </div>

            <div class="modal-header">
                <h3 class="modal-title" style="color: #a8814e;">
                    <i class="fas fa-poll"></i> CONTROL DIARIO DE LIMPIEZA Y DESINFECCIÓN DE AMBULANCIAS
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
                        <input type="hidden" id="modoEdicion" name="modoEdicion" value="false">

                        <!-- Encabezado -->
                        <table class="header-table">
                            <tr>
                                <td style="width:15%">MES</td>
                                <td style="width:10%"><input type="text" name="mes" id="mes"></td>
                                <td style="width:10%">AÑO</td>
                                <td style="width:10%"><input type="text" name="anio" id="anio"></td>
                                <td rowspan="3" style="width:25%; padding:0">
                                    <table style="width:100%; border-collapse:collapse">
                                        <tr>
                                            <th colspan="2">PRODUCTOS UTILIZADOS</th>
                                        </tr>
                                        <tr>
                                            <td>LIMPIEZA</td>
                                            <td><input type="text" name="prod_limpieza" id="prod_limpieza"></td>
                                        </tr>
                                        <tr>
                                            <td>CONCENTRACIÓN</td>
                                            <td><input type="text" name="conc_limpieza" id="conc_limpieza"></td>
                                        </tr>
                                        <tr>
                                            <td>DESINFECTANTE</td>
                                            <td><input type="text" name="prod_desinfectante" id="prod_desinfectante"></td>
                                        </tr>
                                        <tr>
                                            <td>CONCENTRACIÓN</td>
                                            <td><input type="text" name="conc_desinfectante" id="conc_desinfectante"></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>SEDE</td>
                                <td colspan="3"><input type="text" name="sede" id="sede"></td>
                            </tr>
                            <tr>
                                <td>MÓVIL / PLACA</td>
                                <td colspan="3"><input type="text" name="movil" id="movil"></td>
                            </tr>
                        </table>

                        <div class="temp-wrapper">
                            <table class="temp-table">
                                <!-- CABECERA -->
                                <tr>
                                    <th rowspan="3">HAB / CUB / ÁREA</th>
                                    <th colspan="93">DÍAS DEL MES</th>
                                </tr>

                                <tr>
                                    <script>
                                        for (let d = 1; d <= 31; d++) {
                                            document.write('<th class="day" colspan="3">' + d + '</th>');
                                        }
                                    </script>
                                </tr>

                                <tr>
                                    <script>
                                        for (let d = 1; d <= 31; d++) {
                                            document.write('<th class="turno">M</th><th class="turno">T</th><th class="turno">N</th>');
                                        }
                                    </script>
                                </tr>

                                <tbody id="tablaAreas"></tbody>
                            </table>

                            <table class="temp-table" style="margin-top:20px;">
                                <tr>
                                    <th colspan="94">CONTROL DIARIO</th>
                                </tr>

                                <tr>
                                    <td style="text-align:left;font-weight:bold;">
                                        REALIZA: &nbsp; Paramédico / Aux. enf
                                    </td>
                                    <script>
                                        for (let d = 1; d <= 31; d++) {
                                            document.write(`
                                                <td style="width: 3%;"><input type="text" name="realiza[${d}][M]" id="realiza_${d}_M"></td>
                                                <td style="width: 3%;"><input type="text" name="realiza[${d}][T]" id="realiza_${d}_T"></td>
                                                <td class="day-separator" style="width: 3%;"><input type="text" name="realiza[${d}][N]" id="realiza_${d}_N"></td>
                                            `);
                                        }
                                    </script>
                                </tr>

                                <tr>
                                    <td style="text-align:left;font-weight:bold;">
                                        SUPERVISA: &nbsp; Aux. Referencia
                                    </td>
                                    <script>
                                        for (let d = 1; d <= 31; d++) {
                                            document.write(`
                                                <td><input type="text" name="supervisa[${d}][M]" id="supervisa_${d}_M"></td>
                                                <td><input type="text" name="supervisa[${d}][T]" id="supervisa_${d}_T"></td>
                                                <td class="day-separator"><input type="text" name="supervisa[${d}][N]" id="supervisa_${d}_N"></td>
                                            `);
                                        }
                                    </script>
                                </tr>
                            </table>

                            <table style="margin-top:20px;">
                                <tr>
                                    <td style="width:20%; font-weight:bold;">
                                        OBSERVACIONES:
                                    </td>
                                    <td style="height:80px;">
                                        <textarea name="observaciones" id="observaciones" rows="4"></textarea>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-- BOTONES -->
                        <div class="d-flex gap-2 mb-4 flex-wrap">
                            <button class="btn btn-primary" type="button" onclick="guardarFormulario()">
                                <i class="far fa-save"></i> <span id="btnGuardarTexto">Guardar Localmente</span>
                            </button>
                            <button class="btn btn-success" type="button" id="btnEnviarPHP" style="display:none;" onclick="enviarAPhpManual()">
                                <i class="fas fa-cloud-upload-alt"></i> Enviar a Base de Datos
                            </button>
                            <button class="btn btn-info" type="button" onclick="abrirVistaPrevia()">
                                <i class="fas fa-print"></i> Imprimir/Vista Previa
                            </button>
                            <button class="btn btn-secondary" type="button" onclick="limpiarFormulario()" id="btnLimpiar" style="display:none;">
                                <i class="fas fa-times"></i> Cancelar Edición
                            </button>
                        </div>
                        <div id="estadoSync" class="alert alert-info" style="display:none;margin-top:10px;">
                            <i class="fas fa-info-circle"></i> <span id="textoSync"></span>
                        </div>

                    </form>
                </div>

                <!-- ====================== TAB LISTA REGISTROS ====================== -->
                <div class="tab-pane fade" id="lista" role="tabpanel">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <input type="text" class="form-control" id="buscarRegistro" placeholder="🔍 Buscar por mes, año, sede o móvil..." onkeyup="filtrarRegistros()">
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
// ========================= SISTEMA DE ALMACENAMIENTO =========================
window.storage = {
    set: async function(key, value) {
        try {
            localStorage.setItem(key, value);
            return Promise.resolve();
        } catch (error) {
            console.error('Error al guardar en localStorage:', error);
            return Promise.reject(error);
        }
    },
    
    get: async function(key) {
        try {
            const value = localStorage.getItem(key);
            if (value !== null) {
                return Promise.resolve({ value: value });
            } else {
                return Promise.resolve(null);
            }
        } catch (error) {
            console.error('Error al leer de localStorage:', error);
            return Promise.reject(error);
        }
    },
    
    list: async function(prefix) {
        try {
            const keys = [];
            for (let i = 0; i < localStorage.length; i++) {
                const key = localStorage.key(i);
                if (key && key.startsWith(prefix)) {
                    keys.push(key);
                }
            }
            return Promise.resolve({ keys: keys });
        } catch (error) {
            console.error('Error al listar desde localStorage:', error);
            return Promise.reject(error);
        }
    },
    
    delete: async function(key) {
        try {
            localStorage.removeItem(key);
            return Promise.resolve();
        } catch (error) {
            console.error('Error al eliminar de localStorage:', error);
            return Promise.reject(error);
        }
    }
};

// ========================= VARIABLES GLOBALES =========================
const areas = [
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

const extras = [
    "Paredes y piso de la ambulancia",
    "Canecas"
];

let registrosGlobales = [];

// ========================= INICIALIZACIÓN =========================
document.addEventListener('DOMContentLoaded', function() {
    generarTablaAreas();
    cargarContadorRegistros();
    
    // Verificar si viene un ID en la URL (edición desde ver_formatos.php)
    const urlParams = new URLSearchParams(window.location.search);
    const idFormato = urlParams.get('id');
    
    if (idFormato) {
        cargarRegistroDesdeBD(idFormato);
    }
});

// Cargar registro desde la base de datos
async function cargarRegistroDesdeBD(idFormato) {
    try {
        const response = await fetch(`cargar_registro_limpieza.php?id=${idFormato}`);
        const data = await response.json();
        
        if (data.error) {
            mostrarAlerta('Error al cargar el registro: ' + data.error, 'danger');
            return;
        }
        
        console.log('Registro cargado de BD:', data);
        
        // Llenar campos básicos
        document.getElementById('registroId').value = data.id_formato;
        document.getElementById('modoEdicion').value = 'true';
        document.getElementById('mes').value = data.mes || '';
        document.getElementById('anio').value = data.anio || '';
        document.getElementById('sede').value = data.sede || '';
        document.getElementById('movil').value = data.movil || '';
        document.getElementById('prod_limpieza').value = data.prod_limpieza || '';
        document.getElementById('conc_limpieza').value = data.conc_limpieza || '';
        document.getElementById('prod_desinfectante').value = data.prod_desinfectante || '';
        document.getElementById('conc_desinfectante').value = data.conc_desinfectante || '';
        document.getElementById('observaciones').value = data.observaciones || '';
        
        // Limpiar todos los checkboxes primero
        document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
        
        // Llenar checkboxes de control_limpieza
        if (data.control && data.control.length > 0) {
            // Mapear áreas a índices
            const areasMap = {
                "Cajón de vía circulatoria": 0,
                "Cajón de vía respiratoria": 1,
                "Cajón de quirúrgicos": 2,
                "Cajón de pediátricos": 3,
                "Cajón de accesorios": 4,
                "Estante donde van los equipos biomédicos": 5,
                "Monitor de signos vitales": 6,
                "Desfibrilador": 7,
                "Aspirador de secreciones": 8,
                "Bombas de infusión": 9,
                "Ventilador": 10,
                "Soporte y redes de oxígeno": 11,
                "Sillas de acompañante": 12,
                "Sillas de la tripulación": 13,
                "Camilla": 14,
                "Barandas de la camilla": 15,
                "Atril portátil": 16,
                "Silla de ruedas": 17,
                "Paredes y piso de la ambulancia": 18,
                "Canecas": 19
            };
            
            data.control.forEach(item => {
                const indice = areasMap[item.area];
                const dia = item.dia;
                const turno = item.turno;
                
                if (indice !== undefined) {
                    const checkboxId = `control_${indice}_${dia}_${turno}`;
                    const checkbox = document.getElementById(checkboxId);
                    if (checkbox) {
                        checkbox.checked = true;
                    } else {
                        // Si es extra
                        const extraIndex = indice - 18;
                        const extraCheckboxId = `extra_${extraIndex}_${dia}_${turno}`;
                        const extraCheckbox = document.getElementById(extraCheckboxId);
                        if (extraCheckbox) {
                            extraCheckbox.checked = true;
                        }
                    }
                }
            });
        }
        
        // Llenar control_diario (realiza y supervisa)
        if (data.diario && data.diario.length > 0) {
            data.diario.forEach(item => {
                const dia = item.dia;
                const turno = item.turno;
                
                if (item.realiza) {
                    const realizaInput = document.getElementById(`realiza_${dia}_${turno}`);
                    if (realizaInput) realizaInput.value = item.realiza;
                }
                
                if (item.supervisa) {
                    const supervisaInput = document.getElementById(`supervisa_${dia}_${turno}`);
                    if (supervisaInput) supervisaInput.value = item.supervisa;
                }
            });
        }
        
        // Actualizar botón
        document.getElementById('btnGuardarTexto').textContent = 'Actualizar';
        document.getElementById('btnLimpiar').style.display = 'inline-block';
        document.getElementById('btnEnviarPHP').style.display = 'inline-block';
        
        // Mostrar estado de sincronización
        mostrarEstadoSync(data.id_formato, true);
        
        // Cambiar a tab de formulario
        const nuevoTab = document.getElementById('nuevo-tab');
        if (nuevoTab) nuevoTab.click();
        
        // Agregar registro a memoria para poder actualizarlo
        const registroParaMemoria = {
            id: data.id_formato,
            fecha_guardado: new Date().toISOString(),
            mes: data.mes,
            anio: data.anio,
            sede: data.sede,
            movil: data.movil,
            prod_limpieza: data.prod_limpieza,
            conc_limpieza: data.conc_limpieza,
            prod_desinfectante: data.prod_desinfectante,
            conc_desinfectante: data.conc_desinfectante,
            observaciones: data.observaciones,
            control: {},
            extra: {},
            realiza: {},
            supervisa: {}
        };
        
        registrosGlobales.unshift(registroParaMemoria);
        
        mostrarAlerta('Registro cargado. Puedes editar y guardar los cambios.', 'info');
        window.scrollTo(0, 0);
        
    } catch (error) {
        console.error('Error:', error);
        mostrarAlerta('Error al cargar el registro desde la BD: ' + error.message, 'danger');
    }
}

function generarTablaAreas() {
    const tbody = document.getElementById('tablaAreas');
    let html = '';

    areas.forEach((area, i) => {
        html += `<tr><td style="text-align:left;font-size:13px;">${area}</td>`;
        for (let d = 1; d <= 31; d++) {
            html += `
                <td><input type="checkbox" name="control[${i}][${d}][M]" id="control_${i}_${d}_M"></td>
                <td><input type="checkbox" name="control[${i}][${d}][T]" id="control_${i}_${d}_T"></td>
                <td class="day-separator"><input type="checkbox" name="control[${i}][${d}][N]" id="control_${i}_${d}_N"></td>
            `;
        }
        html += '</tr>';
    });

    extras.forEach((extra, i) => {
        html += `<tr><td style="text-align:left;font-size:13px;font-weight:bold;">${extra}</td>`;
        for (let d = 1; d <= 31; d++) {
            html += `
                <td><input type="checkbox" name="extra[${i}][${d}][M]" id="extra_${i}_${d}_M"></td>
                <td><input type="checkbox" name="extra[${i}][${d}][T]" id="extra_${i}_${d}_T"></td>
                <td class="day-separator"><input type="checkbox" name="extra[${i}][${d}][N]" id="extra_${i}_${d}_N"></td>
            `;
        }
        html += '</tr>';
    });

    tbody.innerHTML = html;
}

// ========================= GUARDAR FORMULARIO =========================
async function guardarFormulario() {
    const form = document.getElementById('formPrincipal');
    const formData = new FormData(form);
    const registroId = document.getElementById('registroId').value || `reg_${Date.now()}`;
    const modoEdicion = document.getElementById('modoEdicion').value === 'true';
    
    const data = {
        id: registroId,
        fecha_guardado: new Date().toISOString(),
        mes: formData.get('mes'),
        anio: formData.get('anio'),
        sede: formData.get('sede'),
        movil: formData.get('movil'),
        prod_limpieza: formData.get('prod_limpieza'),
        conc_limpieza: formData.get('conc_limpieza'),
        prod_desinfectante: formData.get('prod_desinfectante'),
        conc_desinfectante: formData.get('conc_desinfectante'),
        observaciones: formData.get('observaciones'),
        control: {},
        extra: {},
        realiza: {},
        supervisa: {}
    };

    // Recoger checkboxes de control
    areas.forEach((area, i) => {
        for (let d = 1; d <= 31; d++) {
            const cbM = document.getElementById(`control_${i}_${d}_M`);
            const cbT = document.getElementById(`control_${i}_${d}_T`);
            const cbN = document.getElementById(`control_${i}_${d}_N`);
            
            if (!data.control[i]) data.control[i] = {};
            if (!data.control[i][d]) data.control[i][d] = {};
            
            data.control[i][d].M = cbM ? cbM.checked : false;
            data.control[i][d].T = cbT ? cbT.checked : false;
            data.control[i][d].N = cbN ? cbN.checked : false;
        }
    });

    // Recoger checkboxes de extras
    extras.forEach((extra, i) => {
        for (let d = 1; d <= 31; d++) {
            const cbM = document.getElementById(`extra_${i}_${d}_M`);
            const cbT = document.getElementById(`extra_${i}_${d}_T`);
            const cbN = document.getElementById(`extra_${i}_${d}_N`);
            
            if (!data.extra[i]) data.extra[i] = {};
            if (!data.extra[i][d]) data.extra[i][d] = {};
            
            data.extra[i][d].M = cbM ? cbM.checked : false;
            data.extra[i][d].T = cbT ? cbT.checked : false;
            data.extra[i][d].N = cbN ? cbN.checked : false;
        }
    });

    // Recoger realiza y supervisa
    for (let d = 1; d <= 31; d++) {
        if (!data.realiza[d]) data.realiza[d] = {};
        if (!data.supervisa[d]) data.supervisa[d] = {};
        
        data.realiza[d].M = document.getElementById(`realiza_${d}_M`)?.value || '';
        data.realiza[d].T = document.getElementById(`realiza_${d}_T`)?.value || '';
        data.realiza[d].N = document.getElementById(`realiza_${d}_N`)?.value || '';
        
        data.supervisa[d].M = document.getElementById(`supervisa_${d}_M`)?.value || '';
        data.supervisa[d].T = document.getElementById(`supervisa_${d}_T`)?.value || '';
        data.supervisa[d].N = document.getElementById(`supervisa_${d}_N`)?.value || '';
    }

    try {
        await window.storage.set(`ambulancia:${registroId}`, JSON.stringify(data));
        mostrarAlerta('✓ Registro guardado localmente. Presiona "Enviar a Base de Datos" cuando estés listo.', 'success');
        
        // Actualizar registrosGlobales
        if (modoEdicion) {
            // Si es edición, reemplazar el registro existente
            const indice = registrosGlobales.findIndex(r => r.id === registroId);
            if (indice !== -1) {
                registrosGlobales[indice] = data;
            }
        } else {
            // Si es nuevo, agregar al inicio
            registrosGlobales.unshift(data);
        }
        
        cargarContadorRegistros();
        
        // Mostrar botón de envío a PHP
        document.getElementById('btnEnviarPHP').style.display = 'inline-block';
        mostrarEstadoSync(registroId, modoEdicion);
    } catch (error) {
        mostrarAlerta('Error al guardar el registro', 'danger');
        console.error(error);
    }
}

// ========================= CARGAR REGISTROS =========================
async function cargarListaRegistros() {
    try {
        const resultado = await window.storage.list('ambulancia:');
        
        if (resultado && resultado.keys && resultado.keys.length > 0) {
            const registros = await Promise.all(
                resultado.keys.map(async (key) => {
                    try {
                        const item = await window.storage.get(key);
                        return item ? JSON.parse(item.value) : null;
                    } catch {
                        return null;
                    }
                })
            );
            
            registrosGlobales = registros.filter(r => r !== null);
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
    } catch (error) {
        console.log('Iniciando sin registros previos');
        registrosGlobales = [];
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
                    <h6 class="mb-1">${reg.mes || 'Sin mes'} ${reg.anio || ''}</h6>
                    <small>${new Date(reg.fecha_guardado).toLocaleDateString()}</small>
                </div>
                <p class="mb-1"><strong>Sede:</strong> ${reg.sede || 'N/A'}</p>
                <small><strong>Móvil:</strong> ${reg.movil || 'N/A'}</small>
            </a>
        `;
    });
    
    lista.innerHTML = html;
}

// ========================= VER DETALLE =========================
function verDetalleRegistro(id) {
    const registro = registrosGlobales.find(r => r.id === id);
    if (!registro) return;

    const detalle = document.getElementById('detalleRegistro');
    detalle.innerHTML = `
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 style="margin:0;"><i class="fas fa-file-alt"></i> ${registro.mes || ''} ${registro.anio || ''} - ${registro.sede || ''}</h5>
            <button type="button" class="btn-close btn-close-white" onclick="cerrarDetalle()" title="Cerrar"></button>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Móvil/Placa:</strong> ${registro.movil || 'N/A'}</p>
                    <p><strong>Producto Limpieza:</strong> ${registro.prod_limpieza || 'N/A'}</p>
                    <p><strong>Concentración:</strong> ${registro.conc_limpieza || 'N/A'}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Desinfectante:</strong> ${registro.prod_desinfectante || 'N/A'}</p>
                    <p><strong>Concentración:</strong> ${registro.conc_desinfectante || 'N/A'}</p>
                    <p><strong>Guardado:</strong> ${new Date(registro.fecha_guardado).toLocaleString()}</p>
                </div>
            </div>
            <div class="mb-3">
                <strong>Observaciones:</strong>
                <p>${registro.observaciones || 'Sin observaciones'}</p>
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

// ========================= CERRAR DETALLE =========================
function cerrarDetalle() {
    document.getElementById('detalleRegistro').innerHTML = `
        <div class="card-body text-center text-muted">
            <i class="fas fa-hand-pointer fa-3x mb-3"></i>
            <p>Selecciona un registro para ver los detalles</p>
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
    document.getElementById('modoEdicion').value = 'true';
    document.getElementById('mes').value = registro.mes || '';
    document.getElementById('anio').value = registro.anio || '';
    document.getElementById('sede').value = registro.sede || '';
    document.getElementById('movil').value = registro.movil || '';
    document.getElementById('prod_limpieza').value = registro.prod_limpieza || '';
    document.getElementById('conc_limpieza').value = registro.conc_limpieza || '';
    document.getElementById('prod_desinfectante').value = registro.prod_desinfectante || '';
    document.getElementById('conc_desinfectante').value = registro.conc_desinfectante || '';
    document.getElementById('observaciones').value = registro.observaciones || '';

    // Llenar checkboxes de control
    if (registro.control) {
        areas.forEach((area, i) => {
            for (let d = 1; d <= 31; d++) {
                const cbM = document.getElementById(`control_${i}_${d}_M`);
                const cbT = document.getElementById(`control_${i}_${d}_T`);
                const cbN = document.getElementById(`control_${i}_${d}_N`);
                
                if (registro.control[i] && registro.control[i][d]) {
                    if (cbM) cbM.checked = registro.control[i][d].M || false;
                    if (cbT) cbT.checked = registro.control[i][d].T || false;
                    if (cbN) cbN.checked = registro.control[i][d].N || false;
                }
            }
        });
    }

    // Llenar checkboxes de extras
    if (registro.extra) {
        extras.forEach((extra, i) => {
            for (let d = 1; d <= 31; d++) {
                const cbM = document.getElementById(`extra_${i}_${d}_M`);
                const cbT = document.getElementById(`extra_${i}_${d}_T`);
                const cbN = document.getElementById(`extra_${i}_${d}_N`);
                
                if (registro.extra[i] && registro.extra[i][d]) {
                    if (cbM) cbM.checked = registro.extra[i][d].M || false;
                    if (cbT) cbT.checked = registro.extra[i][d].T || false;
                    if (cbN) cbN.checked = registro.extra[i][d].N || false;
                }
            }
        });
    }

    // Llenar realiza y supervisa
    if (registro.realiza) {
        for (let d = 1; d <= 31; d++) {
            if (registro.realiza[d]) {
                const inpM = document.getElementById(`realiza_${d}_M`);
                const inpT = document.getElementById(`realiza_${d}_T`);
                const inpN = document.getElementById(`realiza_${d}_N`);
                
                if (inpM) inpM.value = registro.realiza[d].M || '';
                if (inpT) inpT.value = registro.realiza[d].T || '';
                if (inpN) inpN.value = registro.realiza[d].N || '';
            }
        }
    }

    if (registro.supervisa) {
        for (let d = 1; d <= 31; d++) {
            if (registro.supervisa[d]) {
                const inpM = document.getElementById(`supervisa_${d}_M`);
                const inpT = document.getElementById(`supervisa_${d}_T`);
                const inpN = document.getElementById(`supervisa_${d}_N`);
                
                if (inpM) inpM.value = registro.supervisa[d].M || '';
                if (inpT) inpT.value = registro.supervisa[d].T || '';
                if (inpN) inpN.value = registro.supervisa[d].N || '';
            }
        }
    }

    document.getElementById('btnGuardarTexto').textContent = 'Actualizar';
    document.getElementById('btnLimpiar').style.display = 'inline-block';
    document.getElementById('btnEnviarPHP').style.display = 'inline-block';
    
    mostrarEstadoSync(registro.id, true);
    
    mostrarAlerta('Editando registro. Realiza los cambios y haz clic en Actualizar', 'info');
    
    window.scrollTo(0, 0);
}

// ========================= ENVIAR A PHP MANUALMENTE =========================
function enviarAPhpManual() {
    const registroId = document.getElementById('registroId').value;
    const modoEdicion = document.getElementById('modoEdicion').value === 'true';
    
    if (!registroId) {
        mostrarAlerta('⚠️ No hay registro guardado para enviar', 'warning');
        return;
    }
    
    exportarRegistroPHP(registroId, true, modoEdicion);
}

// ========================= MOSTRAR ESTADO DE SINCRONIZACIÓN =========================
function mostrarEstadoSync(registroId, esEdicion) {
    const estadoDiv = document.getElementById('estadoSync');
    const textoSync = document.getElementById('textoSync');
    
    const tipo = esEdicion ? 'actualización' : 'nuevo registro';
    textoSync.textContent = `Tienes un ${tipo} pendiente de enviar a la base de datos.`;
    estadoDiv.style.display = 'block';
}

// ========================= LIMPIAR FORMULARIO =========================
function limpiarFormulario() {
    document.getElementById('formPrincipal').reset();
    document.getElementById('registroId').value = '';
    document.getElementById('modoEdicion').value = 'false';
    document.getElementById('btnGuardarTexto').textContent = 'Guardar Localmente';
    document.getElementById('btnLimpiar').style.display = 'none';
    document.getElementById('btnEnviarPHP').style.display = 'none';
    document.getElementById('estadoSync').style.display = 'none';
    
    // Limpiar todos los checkboxes
    areas.forEach((area, i) => {
        for (let d = 1; d <= 31; d++) {
            const cbM = document.getElementById(`control_${i}_${d}_M`);
            const cbT = document.getElementById(`control_${i}_${d}_T`);
            const cbN = document.getElementById(`control_${i}_${d}_N`);
            if (cbM) cbM.checked = false;
            if (cbT) cbT.checked = false;
            if (cbN) cbN.checked = false;
        }
    });

    extras.forEach((extra, i) => {
        for (let d = 1; d <= 31; d++) {
            const cbM = document.getElementById(`extra_${i}_${d}_M`);
            const cbT = document.getElementById(`extra_${i}_${d}_T`);
            const cbN = document.getElementById(`extra_${i}_${d}_N`);
            if (cbM) cbM.checked = false;
            if (cbT) cbT.checked = false;
            if (cbN) cbN.checked = false;
        }
    });
}

// ========================= ELIMINAR REGISTRO =========================
async function eliminarRegistro(id) {
    if (!confirm('¿Estás seguro de eliminar este registro? Esta acción no se puede deshacer.')) {
        return;
    }

    try {
        await window.storage.delete(`ambulancia:${id}`);
        mostrarAlerta('Registro eliminado correctamente', 'success');
        cargarListaRegistros();
        cargarContadorRegistros();
        document.getElementById('detalleRegistro').innerHTML = `
            <div class="card-body text-center text-muted">
                <i class="fas fa-hand-pointer fa-3x mb-3"></i>
                <p>Selecciona un registro para ver los detalles</p>
            </div>
        `;
    } catch (error) {
        mostrarAlerta('Error al eliminar el registro', 'danger');
    }
}

// ========================= EXPORTAR A PHP =========================
function exportarRegistroPHP(id, autoEnviar = false, esEdicion = false) {
    const registro = registrosGlobales.find(r => r.id === id);
    if (!registro) return;

    // Determinar si es actualización o inserción basándose en si hay ID numérico
    const esActualizacion = esEdicion || (!isNaN(id) && !id.includes('reg_'));
    const endpoint = esActualizacion ? 'actualizar_limpieza.php' : 'insertar.php';

    // Crear formulario dinámico para enviar por POST
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = endpoint;

    // Función auxiliar para crear inputs
    function addInput(name, value) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value || '';
        form.appendChild(input);
    }

    // Campos básicos
    if (esActualizacion) {
        addInput('id_formato', id);
    }
    addInput('mes', registro.mes);
    addInput('anio', registro.anio);
    addInput('sede', registro.sede);
    addInput('movil', registro.movil);
    addInput('prod_limpieza', registro.prod_limpieza);
    addInput('conc_limpieza', registro.conc_limpieza);
    addInput('prod_desinfectante', registro.prod_desinfectante);
    addInput('conc_desinfectante', registro.conc_desinfectante);
    addInput('observaciones', registro.observaciones);

    // Control
    if (registro.control) {
        areas.forEach((area, i) => {
            for (let d = 1; d <= 31; d++) {
                if (registro.control[i] && registro.control[i][d]) {
                    if (registro.control[i][d].M) {
                        addInput(`control[${i}][${d}][M]`, 'on');
                    }
                    if (registro.control[i][d].T) {
                        addInput(`control[${i}][${d}][T]`, 'on');
                    }
                    if (registro.control[i][d].N) {
                        addInput(`control[${i}][${d}][N]`, 'on');
                    }
                }
            }
        });
    }

    // Extras
    if (registro.extra) {
        extras.forEach((extra, i) => {
            for (let d = 1; d <= 31; d++) {
                if (registro.extra[i] && registro.extra[i][d]) {
                    if (registro.extra[i][d].M) {
                        addInput(`extra[${i}][${d}][M]`, 'on');
                    }
                    if (registro.extra[i][d].T) {
                        addInput(`extra[${i}][${d}][T]`, 'on');
                    }
                    if (registro.extra[i][d].N) {
                        addInput(`extra[${i}][${d}][N]`, 'on');
                    }
                }
            }
        });
    }

    // Realiza
    if (registro.realiza) {
        for (let d = 1; d <= 31; d++) {
            if (registro.realiza[d]) {
                addInput(`realiza[${d}][M]`, registro.realiza[d].M);
                addInput(`realiza[${d}][T]`, registro.realiza[d].T);
                addInput(`realiza[${d}][N]`, registro.realiza[d].N);
            }
        }
    }

    // Supervisa
    if (registro.supervisa) {
        for (let d = 1; d <= 31; d++) {
            if (registro.supervisa[d]) {
                addInput(`supervisa[${d}][M]`, registro.supervisa[d].M);
                addInput(`supervisa[${d}][T]`, registro.supervisa[d].T);
                addInput(`supervisa[${d}][N]`, registro.supervisa[d].N);
            }
        }
    }

    document.body.appendChild(form);
    
    if (autoEnviar) {
        // Envío automático sin confirmación usando fetch
        const accion = esActualizacion ? 'actualizando' : 'guardando';
        console.log(`Iniciando envío automático (${accion}) a PHP (${endpoint})...`);
        
        const formDataToSend = new FormData(form);
        
        fetch(endpoint, {
            method: 'POST',
            body: formDataToSend
        })
        .then(response => {
            console.log('Respuesta del servidor (status):', response.status);
            return response.text();
        })
        .then(data => {
            console.log('Respuesta completa:', data);
            
            if (data.includes('✅') || data.includes('exitosamente') || data.includes('Guardado') || data.includes('actualizado')) {
                console.log('✅ Envío exitoso detectado');
                const msg = esActualizacion ? '✅ Registro actualizado correctamente' : '✅ Registro guardado en la base de datos';
                mostrarAlerta(msg, 'success');
            } else if (data.includes('❌') || data.includes('Error')) {
                console.error('❌ Error detectado en respuesta');
                mostrarAlerta('⚠️ Error al enviar. Verifica en ver_formatos.php', 'warning');
            } else {
                console.log('Respuesta no clasificada');
                mostrarAlerta('✅ Registro procesado', 'success');
            }
        })
        .catch(error => {
            console.error('❌ Error de red:', error);
            mostrarAlerta('❌ Error de conexión: ' + error.message, 'danger');
        })
        .finally(() => {
            if (document.body.contains(form)) {
                document.body.removeChild(form);
            }
            console.log('Envío completado');
        });
    } else {
        // Envío manual con confirmación
        const msg = esActualizacion ? '¿Actualizar este registro en la base de datos PHP?' : '¿Enviar este registro a la base de datos PHP?';
        if (confirm(msg)) {
            console.log('Envío manual confirmado');
            
            const formDataToSend = new FormData(form);
            fetch(endpoint, {
                method: 'POST',
                body: formDataToSend
            })
            .then(response => response.text())
            .then(data => {
                console.log('Respuesta:', data);
                if (data.includes('✅') || data.includes('exitosamente') || data.includes('actualizado')) {
                    const msg = esActualizacion ? '✅ Registro actualizado. Recargando...' : '✅ Registro enviado correctamente. Recargando...';
                    mostrarAlerta(msg, 'success');
                    setTimeout(() => {
                        window.location.href = 'ver_formatos.php';
                    }, 2000);
                } else if (data.includes('❌') || data.includes('Error')) {
                    mostrarAlerta('⚠️ Error al enviar. Intenta de nuevo.', 'warning');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarAlerta('❌ Error de red: ' + error.message, 'danger');
            })
            .finally(() => {
                if (document.body.contains(form)) {
                    document.body.removeChild(form);
                }
            });
        } else {
            if (document.body.contains(form)) {
                document.body.removeChild(form);
            }
        }
    }
}

// ========================= FILTRAR REGISTROS =========================
function filtrarRegistros() {
    const busqueda = document.getElementById('buscarRegistro').value.toLowerCase();
    
    const registrosFiltrados = registrosGlobales.filter(r => {
        return (r.mes || '').toLowerCase().includes(busqueda) ||
               (r.anio || '').toLowerCase().includes(busqueda) ||
               (r.sede || '').toLowerCase().includes(busqueda) ||
               (r.movil || '').toLowerCase().includes(busqueda);
    });
    
    mostrarListaRegistros(registrosFiltrados);
}

// ========================= CONTADOR =========================
async function cargarContadorRegistros() {
    try {
        const resultado = await window.storage.list('ambulancia:');
        const count = resultado && resultado.keys ? resultado.keys.length : 0;
        document.getElementById('contadorRegistros').textContent = count;
    } catch {
        document.getElementById('contadorRegistros').textContent = '0';
    }
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

// ========================= VISTA PREVIA =========================
function abrirVistaPrevia() {
    // Recopilar todos los datos del formulario actual
    const form = document.getElementById('formPrincipal');
    const formData = new FormData(form);
    
    const datos = {
        mes: formData.get('mes'),
        anio: formData.get('anio'),
        sede: formData.get('sede'),
        movil: formData.get('movil'),
        prod_limpieza: formData.get('prod_limpieza'),
        conc_limpieza: formData.get('conc_limpieza'),
        prod_desinfectante: formData.get('prod_desinfectante'),
        conc_desinfectante: formData.get('conc_desinfectante'),
        observaciones: formData.get('observaciones'),
        control: {},
        extra: {},
        realiza: {},
        supervisa: {}
    };
    
    // Recoger checkboxes de control
    areas.forEach((area, i) => {
        for (let d = 1; d <= 31; d++) {
            const cbM = document.getElementById(`control_${i}_${d}_M`);
            const cbT = document.getElementById(`control_${i}_${d}_T`);
            const cbN = document.getElementById(`control_${i}_${d}_N`);
            
            if (!datos.control[i]) datos.control[i] = {};
            if (!datos.control[i][d]) datos.control[i][d] = {};
            
            datos.control[i][d].M = cbM ? cbM.checked : false;
            datos.control[i][d].T = cbT ? cbT.checked : false;
            datos.control[i][d].N = cbN ? cbN.checked : false;
        }
    });
    
    // Recoger checkboxes de extras
    extras.forEach((extra, i) => {
        for (let d = 1; d <= 31; d++) {
            const cbM = document.getElementById(`extra_${i}_${d}_M`);
            const cbT = document.getElementById(`extra_${i}_${d}_T`);
            const cbN = document.getElementById(`extra_${i}_${d}_N`);
            
            if (!datos.extra[i]) datos.extra[i] = {};
            if (!datos.extra[i][d]) datos.extra[i][d] = {};
            
            datos.extra[i][d].M = cbM ? cbM.checked : false;
            datos.extra[i][d].T = cbT ? cbT.checked : false;
            datos.extra[i][d].N = cbN ? cbN.checked : false;
        }
    });
    
    // Recoger realiza y supervisa
    for (let d = 1; d <= 31; d++) {
        if (!datos.realiza[d]) datos.realiza[d] = {};
        if (!datos.supervisa[d]) datos.supervisa[d] = {};
        
        datos.realiza[d].M = document.getElementById(`realiza_${d}_M`)?.value || '';
        datos.realiza[d].T = document.getElementById(`realiza_${d}_T`)?.value || '';
        datos.realiza[d].N = document.getElementById(`realiza_${d}_N`)?.value || '';
        
        datos.supervisa[d].M = document.getElementById(`supervisa_${d}_M`)?.value || '';
        datos.supervisa[d].T = document.getElementById(`supervisa_${d}_T`)?.value || '';
        datos.supervisa[d].N = document.getElementById(`supervisa_${d}_N`)?.value || '';
    }
    
    // Guardar en sessionStorage para que preview_limpieza.php lo lea
    sessionStorage.setItem('previewFormato', JSON.stringify(datos));
    
    // Abrir preview_limpieza.php en nueva ventana
    window.open('preview_limpieza.php', 'preview', 'width=1400,height=900,resizable=yes,scrollbars=yes');
    
    mostrarAlerta('✓ Vista previa abierta en nueva ventana', 'info');
}
</script>

</body>
</html>
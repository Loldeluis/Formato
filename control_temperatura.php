<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Control de Temperatura, Humedad y Cadena de Frío</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

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

.vertical-text {
    writing-mode: vertical-rl;
    transform: rotate(180deg);
    font-weight: bold;
    text-align: center;
}

.day-separator {
    background: #e6e6e6;
}

.table-scroll {
    width: 100%;
    overflow-x: auto;
    overflow-y: hidden;
    -webkit-overflow-scrolling: touch;
    margin-bottom: 25px;
    border: 1px solid #000;
}

.temp-table {
    width: max-content;
    min-width: 2800px;
    table-layout: fixed;
}

.temp-table th,
.temp-table td {
    min-width: 28px;
    max-width: 28px;
    padding: 2px;
}

.temp-table input[type="radio"] {
    width: 14px;
    height: 14px;
}

.vertical-text {
    min-width: 40px;
    font-size: 12px;
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
</style>

</head>
<body>

<div class="container-fluid">
    <div class="col-lg-12 col-sm-12 main-section">
        <div class="modal-content">

            <div align="center">
                <img src="img/logo_dos.png" class="img-fluid" width="262" height="81" alt="Logo" onerror="this.style.display='none'" />
            </div>

            <div class="modal-header">
                <h3 class="modal-title" style="color: #a8814e;">
                    <i class="fas fa-thermometer-half"></i> CONTROL DE TEMPERATURA, HUMEDAD RELATIVA Y CADENA DE FRIO
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

                        <div class="table-scroll">
                            <table class="temp-table">
                                <tr>
                                    <th colspan="3">MES:</th>
                                    <td colspan="6"><input type="text" name="mes" id="mes" value="OCTUBRE"></td>
                                    <th colspan="2">AÑO:</th>
                                    <td colspan="4"><input type="text" name="anio" id="anio" value="2025"></td>
                                    <th colspan="3">SERVICIO:</th>
                                    <td colspan="6"><input type="text" name="servicio" id="servicio" value="Ambulancia"></td>
                                </tr>
                            </table>

                            <!-- TEMPERATURA AMBIENTE -->
                            <table class="temp-table" id="tablaTemperatura">
                                <tr>
                                    <th rowspan="2" class="vertical-text">TEMPERATURA AMBIENTE</th>
                                    <script>
                                        for (let d = 1; d <= 30; d++) {
                                            document.write('<th colspan="3">' + d + '</th>');
                                        }
                                    </script>
                                </tr>
                                <tr>
                                    <script>
                                        for (let d = 1; d <= 30; d++) {
                                            document.write('<th>M</th><th>T</th><th>N</th>');
                                        }
                                    </script>
                                </tr>
                                <tbody id="tempBody"></tbody>
                            </table>

                            <br><br>

                            <!-- HUMEDAD RELATIVA -->
                            <table class="temp-table" id="tablaHumedad">
                                <tr>
                                    <th rowspan="2" class="vertical-text">HUMEDAD RELATIVA (%)</th>
                                    <script>
                                        for (let d = 1; d <= 30; d++) {
                                            document.write('<th colspan="3">' + d + '</th>');
                                        }
                                    </script>
                                </tr>
                                <tr>
                                    <script>
                                        for (let d = 1; d <= 30; d++) {
                                            document.write('<th>M</th><th>T</th><th>N</th>');
                                        }
                                    </script>
                                </tr>
                                <tbody id="humBody"></tbody>
                            </table>

                            <br><br>

                            <!-- CADENA DE FRÍO -->
                            <table class="temp-table" id="tablaCadenaFrio">
                                <tr>
                                    <th rowspan="2" class="vertical-text">CADENA DE FRÍO (°C)</th>
                                    <script>
                                        for (let d = 1; d <= 30; d++) {
                                            document.write('<th colspan="3">' + d + '</th>');
                                        }
                                    </script>
                                </tr>
                                <tr>
                                    <script>
                                        for (let d = 1; d <= 30; d++) {
                                            document.write('<th>M</th><th>T</th><th>N</th>');
                                        }
                                    </script>
                                </tr>
                                <tbody id="frioBody"></tbody>
                            </table>

                            <br><br>

                            <!-- HORA, FIRMA, Vo.Bo -->
                            <table class="temp-table">
                                <tr>
                                    <th class="vertical-text">HORA</th>
                                    <script>
                                        for (let d = 1; d <= 30; d++) {
                                            document.write('<th>6</th><th>14</th><th class="day-separator">22</th>');
                                        }
                                    </script>
                                </tr>

                                <tr>
                                    <th class="vertical-text">FIRMA</th>
                                    <script>
                                        for (let d = 1; d <= 30; d++) {
                                            document.write(`
                                                <td><input type="text" name="firma[${d}][6]" id="firma_${d}_6"></td>
                                                <td><input type="text" name="firma[${d}][14]" id="firma_${d}_14"></td>
                                                <td class="day-separator"><input type="text" name="firma[${d}][22]" id="firma_${d}_22"></td>
                                            `);
                                        }
                                    </script>
                                </tr>

                                <tr>
                                    <th class="vertical-text">Vo.Bo</th>
                                    <script>
                                        for (let d = 1; d <= 30; d++) {
                                            document.write(`
                                                <td style="width: 10%;"><input type="text" name="vobo[${d}][6]" id="vobo_${d}_6"></td>
                                                <td style="width: 10%;"><input type="text" name="vobo[${d}][14]" id="vobo_${d}_14"></td>
                                                <td class="day-separator" style="width: 10%;"><input type="text" name="vobo[${d}][22]" id="vobo_${d}_22"></td>
                                            `);
                                        }
                                    </script>
                                </tr>
                            </table>
                        </div>

                        <!-- BOTONES -->
                        <div class="d-flex gap-2 mb-4">
                            <button class="btn btn-primary" type="button" onclick="guardarFormulario()">
                                <i class="fas fa-save"></i> <span id="btnGuardarTexto">Guardar</span>
                            </button>
                            <button class="btn btn-secondary" type="button" id="btnLimpiar" onclick="limpiarFormulario()" style="display: none;">
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
                            <input type="text" class="form-control" id="buscarRegistro" placeholder="🔍 Buscar por nombre, mes, año o placa..." onkeyup="filtrarRegistros()">
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
// ========================= VARIABLES GLOBALES =========================
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
    generarTablasTemperatura();
    const hoy = new Date().toISOString().split('T')[0];
    document.getElementById('fecha').value = hoy;
    cargarContadorRegistros();
});

function generarTablasTemperatura() {
    // TEMPERATURA AMBIENTE (30 a 14)
    let htmlTemp = '';
    for (let t = 30; t >= 14; t--) {
        htmlTemp += `<tr><th>${t}</th>`;
        for (let d = 1; d <= 30; d++) {
            htmlTemp += `
                <td><input type="radio" name="temp[${d}][M]" id="temp_${d}_M_${t}" value="${t}"></td>
                <td><input type="radio" name="temp[${d}][T]" id="temp_${d}_T_${t}" value="${t}"></td>
                <td class="day-separator"><input type="radio" name="temp[${d}][N]" id="temp_${d}_N_${t}" value="${t}"></td>
            `;
        }
        htmlTemp += '</tr>';
    }
    document.getElementById('tempBody').innerHTML = htmlTemp;

    // HUMEDAD RELATIVA (69 a 41)
    let htmlHum = '';
    for (let h = 69; h >= 41; h--) {
        htmlHum += `<tr><th>${h}</th>`;
        for (let d = 1; d <= 30; d++) {
            htmlHum += `
                <td><input type="radio" name="hum[${d}][M]" id="hum_${d}_M_${h}" value="${h}"></td>
                <td><input type="radio" name="hum[${d}][T]" id="hum_${d}_T_${h}" value="${h}"></td>
                <td class="day-separator"><input type="radio" name="hum[${d}][N]" id="hum_${d}_N_${h}" value="${h}"></td>
            `;
        }
        htmlHum += '</tr>';
    }
    document.getElementById('humBody').innerHTML = htmlHum;

    // CADENA DE FRÍO (10 a 0)
    let htmlFrio = '';
    for (let c = 10; c >= 0; c--) {
        htmlFrio += `<tr><th>${c}</th>`;
        for (let d = 1; d <= 30; d++) {
            htmlFrio += `
                <td><input type="radio" name="frio[${d}][M]" id="frio_${d}_M_${c}" value="${c}"></td>
                <td><input type="radio" name="frio[${d}][T]" id="frio_${d}_T_${c}" value="${c}"></td>
                <td class="day-separator"><input type="radio" name="frio[${d}][N]" id="frio_${d}_N_${c}" value="${c}"></td>
            `;
        }
        htmlFrio += '</tr>';
    }
    document.getElementById('frioBody').innerHTML = htmlFrio;
}

// ========================= GUARDAR FORMULARIO =========================
function guardarFormulario() {
    const form = document.getElementById('formPrincipal');
    const registroId = document.getElementById('registroId').value || `temp_${Date.now()}`;
    
    const data = {
        id: registroId,
        fecha_guardado: new Date().toISOString(),
        nombre: document.getElementById('nombre').value,
        fecha: document.getElementById('fecha').value,
        placa: document.getElementById('placa').value,
        mes: document.getElementById('mes').value,
        anio: document.getElementById('anio').value,
        servicio: document.getElementById('servicio').value,
        temp: {},
        hum: {},
        frio: {},
        firma: {},
        vobo: {}
    };

    // Validar que haya datos básicos
    if (!data.nombre || !data.fecha) {
        mostrarAlerta('Por favor completa los campos obligatorios (Nombre y Fecha)', 'warning');
        return;
    }

    // Recoger temperaturas
    for (let d = 1; d <= 30; d++) {
        data.temp[d] = {
            M: obtenerRadioSeleccionado(`temp[${d}][M]`),
            T: obtenerRadioSeleccionado(`temp[${d}][T]`),
            N: obtenerRadioSeleccionado(`temp[${d}][N]`)
        };
    }

    // Recoger humedad
    for (let d = 1; d <= 30; d++) {
        data.hum[d] = {
            M: obtenerRadioSeleccionado(`hum[${d}][M]`),
            T: obtenerRadioSeleccionado(`hum[${d}][T]`),
            N: obtenerRadioSeleccionado(`hum[${d}][N]`)
        };
    }

    // Recoger cadena de frío
    for (let d = 1; d <= 30; d++) {
        data.frio[d] = {
            M: obtenerRadioSeleccionado(`frio[${d}][M]`),
            T: obtenerRadioSeleccionado(`frio[${d}][T]`),
            N: obtenerRadioSeleccionado(`frio[${d}][N]`)
        };
    }

    // Recoger firmas
    for (let d = 1; d <= 30; d++) {
        data.firma[d] = {
            6: document.getElementById(`firma_${d}_6`)?.value || '',
            14: document.getElementById(`firma_${d}_14`)?.value || '',
            22: document.getElementById(`firma_${d}_22`)?.value || ''
        };
    }

    // Recoger Vo.Bo
    for (let d = 1; d <= 30; d++) {
        data.vobo[d] = {
            6: document.getElementById(`vobo_${d}_6`)?.value || '',
            14: document.getElementById(`vobo_${d}_14`)?.value || '',
            22: document.getElementById(`vobo_${d}_22`)?.value || ''
        };
    }

    // Guardar localmente primero
    if (Storage.set(`temperatura:${registroId}`, JSON.stringify(data))) {
        mostrarAlerta('Registro guardado en navegador. Enviando a base de datos...', 'info');
        
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

function obtenerRadioSeleccionado(name) {
    const radios = document.getElementsByName(name);
    for (let radio of radios) {
        if (radio.checked) {
            return radio.value;
        }
    }
    return '';
}

// ========================= CARGAR REGISTROS =========================
function cargarListaRegistros() {
    const keys = Storage.list('temperatura:');
    
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
                    <h6 class="mb-1">${reg.mes || 'Sin mes'} ${reg.anio || ''}</h6>
                    <small>${new Date(reg.fecha_guardado).toLocaleDateString()}</small>
                </div>
                <p class="mb-1"><strong>Nombre:</strong> ${reg.nombre || 'N/A'}</p>
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

    const detalle = document.getElementById('detalleRegistro');
    detalle.innerHTML = `
        <div class="card-header bg-primary text-white">
            <h5><i class="fas fa-thermometer-half"></i> ${registro.mes || ''} ${registro.anio || ''} - ${registro.nombre || ''}</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Nombre:</strong> ${registro.nombre || 'N/A'}</p>
                    <p><strong>Fecha:</strong> ${registro.fecha || 'N/A'}</p>
                    <p><strong>Placa:</strong> ${registro.placa || 'N/A'}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Servicio:</strong> ${registro.servicio || 'N/A'}</p>
                    <p><strong>Guardado:</strong> ${new Date(registro.fecha_guardado).toLocaleString()}</p>
                </div>
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
    document.getElementById('mes').value = registro.mes || '';
    document.getElementById('anio').value = registro.anio || '';
    document.getElementById('servicio').value = registro.servicio || '';

    // Llenar temperaturas
    if (registro.temp) {
        for (let d = 1; d <= 30; d++) {
            if (registro.temp[d]) {
                marcarRadio(`temp[${d}][M]`, registro.temp[d].M);
                marcarRadio(`temp[${d}][T]`, registro.temp[d].T);
                marcarRadio(`temp[${d}][N]`, registro.temp[d].N);
            }
        }
    }

    // Llenar humedad
    if (registro.hum) {
        for (let d = 1; d <= 30; d++) {
            if (registro.hum[d]) {
                marcarRadio(`hum[${d}][M]`, registro.hum[d].M);
                marcarRadio(`hum[${d}][T]`, registro.hum[d].T);
                marcarRadio(`hum[${d}][N]`, registro.hum[d].N);
            }
        }
    }

    // Llenar cadena de frío
    if (registro.frio) {
        for (let d = 1; d <= 30; d++) {
            if (registro.frio[d]) {
                marcarRadio(`frio[${d}][M]`, registro.frio[d].M);
                marcarRadio(`frio[${d}][T]`, registro.frio[d].T);
                marcarRadio(`frio[${d}][N]`, registro.frio[d].N);
            }
        }
    }

    // Llenar firmas
    if (registro.firma) {
        for (let d = 1; d <= 30; d++) {
            if (registro.firma[d]) {
                const inp6 = document.getElementById(`firma_${d}_6`);
                const inp14 = document.getElementById(`firma_${d}_14`);
                const inp22 = document.getElementById(`firma_${d}_22`);
                if (inp6) inp6.value = registro.firma[d][6] || '';
                if (inp14) inp14.value = registro.firma[d][14] || '';
                if (inp22) inp22.value = registro.firma[d][22] || '';
            }
        }
    }

    // Llenar Vo.Bo
    if (registro.vobo) {
        for (let d = 1; d <= 30; d++) {
            if (registro.vobo[d]) {
                const inp6 = document.getElementById(`vobo_${d}_6`);
                const inp14 = document.getElementById(`vobo_${d}_14`);
                const inp22 = document.getElementById(`vobo_${d}_22`);
                if (inp6) inp6.value = registro.vobo[d][6] || '';
                if (inp14) inp14.value = registro.vobo[d][14] || '';
                if (inp22) inp22.value = registro.vobo[d][22] || '';
            }
        }
    }

    document.getElementById('btnGuardarTexto').textContent = 'Actualizar';
    document.getElementById('btnLimpiar').style.display = 'inline-block';
    
    mostrarAlerta('Editando registro. Realiza los cambios y haz clic en Actualizar', 'info');
    
    window.scrollTo(0, 0);
}

function marcarRadio(name, value) {
    if (!value) return;
    const radios = document.getElementsByName(name);
    for (let radio of radios) {
        if (radio.value === value) {
            radio.checked = true;
            break;
        }
    }
}

// ========================= LIMPIAR FORMULARIO =========================
function limpiarFormulario() {
    document.getElementById('formPrincipal').reset();
    document.getElementById('registroId').value = '';
    document.getElementById('btnGuardarTexto').textContent = 'Guardar';
    document.getElementById('btnLimpiar').style.display = 'none';
    
    const hoy = new Date().toISOString().split('T')[0];
    document.getElementById('fecha').value = hoy;
    
    // Desmarcar todos los radios
    const radios = document.querySelectorAll('input[type="radio"]');
    radios.forEach(radio => radio.checked = false);
}

// ========================= ELIMINAR REGISTRO =========================
function eliminarRegistro(id) {
    if (!confirm('¿Estás seguro de eliminar este registro? Esta acción no se puede deshacer.')) {
        return;
    }

    if (Storage.delete(`temperatura:${id}`)) {
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
    form.action = 'insertar_temp.php';

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
    addInput('mes', registro.mes);
    addInput('anio', registro.anio);
    addInput('servicio', registro.servicio);

    // Temperatura
    if (registro.temp) {
        for (let d = 1; d <= 30; d++) {
            if (registro.temp[d]) {
                if (registro.temp[d].M) addInput(`temp[${d}][M]`, registro.temp[d].M);
                if (registro.temp[d].T) addInput(`temp[${d}][T]`, registro.temp[d].T);
                if (registro.temp[d].N) addInput(`temp[${d}][N]`, registro.temp[d].N);
            }
        }
    }

    // Humedad
    if (registro.hum) {
        for (let d = 1; d <= 30; d++) {
            if (registro.hum[d]) {
                if (registro.hum[d].M) addInput(`hum[${d}][M]`, registro.hum[d].M);
                if (registro.hum[d].T) addInput(`hum[${d}][T]`, registro.hum[d].T);
                if (registro.hum[d].N) addInput(`hum[${d}][N]`, registro.hum[d].N);
            }
        }
    }

    // Cadena de frío
    if (registro.frio) {
        for (let d = 1; d <= 30; d++) {
            if (registro.frio[d]) {
                if (registro.frio[d].M) addInput(`frio[${d}][M]`, registro.frio[d].M);
                if (registro.frio[d].T) addInput(`frio[${d}][T]`, registro.frio[d].T);
                if (registro.frio[d].N) addInput(`frio[${d}][N]`, registro.frio[d].N);
            }
        }
    }

    // Firmas
    if (registro.firma) {
        for (let d = 1; d <= 30; d++) {
            if (registro.firma[d]) {
                addInput(`firma[${d}][6]`, registro.firma[d][6]);
                addInput(`firma[${d}][14]`, registro.firma[d][14]);
                addInput(`firma[${d}][22]`, registro.firma[d][22]);
            }
        }
    }

    // Vo.Bo
    if (registro.vobo) {
        for (let d = 1; d <= 30; d++) {
            if (registro.vobo[d]) {
                addInput(`vobo[${d}][6]`, registro.vobo[d][6]);
                addInput(`vobo[${d}][14]`, registro.vobo[d][14]);
                addInput(`vobo[${d}][22]`, registro.vobo[d][22]);
            }
        }
    }

    document.body.appendChild(form);
    
    if (autoEnviar) {
        // Envío automático sin confirmación usando fetch
        console.log('Iniciando envío automático a PHP...');
        
        // Convertir FormData a objeto para logging
        const formDataObj = new FormData(form);
        console.log('Datos siendo enviados:', Object.fromEntries(formDataObj));
        
        const formDataToSend = new FormData(form);
        
        fetch('insertar_temp.php', {
            method: 'POST',
            body: formDataToSend
        })
        .then(response => {
            console.log('Respuesta del servidor (status):', response.status);
            return response.text();
        })
        .then(data => {
            console.log('Respuesta completa:', data);
            
            if (data.includes('✅') || data.includes('exitosamente') || data.includes('ID del formato')) {
                console.log('✅ Envío exitoso detectado');
                mostrarAlerta('✅ Datos guardados en la base de datos correctamente', 'success');
            } else if (data.includes('❌') || data.includes('Error')) {
                console.error('❌ Error detectado en respuesta');
                mostrarAlerta('⚠️ Error al enviar. Verifica en ver_formatos_temp.php', 'warning');
            } else {
                console.log('Respuesta no clasificada, podría ser exitosa');
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
        Swal.fire({
            icon: 'question',
            title: '¿Enviar a la base de datos?',
            text: 'Este registro será enviado a la base de datos PHP',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, enviar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                console.log('Envío manual confirmado');
                
                const formDataToSend = new FormData(form);
                fetch('insertar_temp.php', {
                    method: 'POST',
                    body: formDataToSend
                })
                .then(response => response.text())
                .then(data => {
                    console.log('Respuesta:', data);
                    if (data.includes('✅') || data.includes('exitosamente')) {
                        mostrarAlerta('✅ Registro enviado correctamente. Recargando...', 'success');
                        setTimeout(() => {
                            window.location.href = 'ver_formatos_temp.php';
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
        });
    }
}

// ========================= FILTRAR REGISTROS =========================
function filtrarRegistros() {
    const busqueda = document.getElementById('buscarRegistro').value.toLowerCase();
    
    const registrosFiltrados = registrosGlobales.filter(r => {
        return (r.nombre || '').toLowerCase().includes(busqueda) ||
               (r.mes || '').toLowerCase().includes(busqueda) ||
               (r.anio || '').toLowerCase().includes(busqueda) ||
               (r.placa || '').toLowerCase().includes(busqueda);
    });
    
    mostrarListaRegistros(registrosFiltrados);
}

// ========================= CONTADOR =========================
function cargarContadorRegistros() {
    const keys = Storage.list('temperatura:');
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
        mes: formData.get('mes'),
        anio: formData.get('anio'),
        servicio: formData.get('servicio'),
        temp: {},
        hum: {},
        frio: {},
        firma: {},
        vobo: {}
    };
    
    // Recoger temperaturas
    for (let d = 1; d <= 30; d++) {
        datos.temp[d] = {
            M: obtenerRadioSeleccionado(`temp[${d}][M]`),
            T: obtenerRadioSeleccionado(`temp[${d}][T]`),
            N: obtenerRadioSeleccionado(`temp[${d}][N]`)
        };
    }
    
    // Recoger humedad
    for (let d = 1; d <= 30; d++) {
        datos.hum[d] = {
            M: obtenerRadioSeleccionado(`hum[${d}][M]`),
            T: obtenerRadioSeleccionado(`hum[${d}][T]`),
            N: obtenerRadioSeleccionado(`hum[${d}][N]`)
        };
    }
    
    // Recoger cadena de frío
    for (let d = 1; d <= 30; d++) {
        datos.frio[d] = {
            M: obtenerRadioSeleccionado(`frio[${d}][M]`),
            T: obtenerRadioSeleccionado(`frio[${d}][T]`),
            N: obtenerRadioSeleccionado(`frio[${d}][N]`)
        };
    }
    
    // Recoger firmas
    for (let d = 1; d <= 30; d++) {
        datos.firma[d] = {
            6: document.getElementById(`firma_${d}_6`)?.value || '',
            14: document.getElementById(`firma_${d}_14`)?.value || '',
            22: document.getElementById(`firma_${d}_22`)?.value || ''
        };
    }
    
    // Recoger Vo.Bo
    for (let d = 1; d <= 30; d++) {
        datos.vobo[d] = {
            6: document.getElementById(`vobo_${d}_6`)?.value || '',
            14: document.getElementById(`vobo_${d}_14`)?.value || '',
            22: document.getElementById(`vobo_${d}_22`)?.value || ''
        };
    }
    
    // Guardar en sessionStorage para que preview_temperatura.php lo lea
    sessionStorage.setItem('previewTemperatura', JSON.stringify(datos));
    
    // Abrir preview_temperatura.php en nueva ventana
    window.open('preview_temperatura.php', 'preview', 'width=1400,height=900,resizable=yes,scrollbars=yes');
    
    mostrarAlerta('✓ Vista previa abierta en nueva ventana', 'info');
}
</script>

</body>
</html>
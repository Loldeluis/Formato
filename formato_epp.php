<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Formato de Relación de Gastos Diarios de EPP</title>
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

.epp-table th,
.epp-table td {
    border: 1px solid #000;
    text-align: center;
    font-size: 13px;
    padding: 6px;
    height: 38px;
}

.epp-table th {
    font-weight: bold;
}

.epp-table input[type="text"],
.epp-table input[type="date"] {
    width: 100%;
    border: none;
    text-align: center;
    font-size: 13px;
}

.epp-header td {
    font-weight: bold;
    text-align: left;
}

.col-epp {
    width: 6%;
}

.col-obs {
    width: 250px;
    min-width: 200px;
}

.col-firma {
    width: 200px;
    min-width: 150px;
}

.table-scroll {
    width: 100%;
    overflow-x: auto;
    overflow-y: hidden;
    -webkit-overflow-scrolling: touch;
    margin-bottom: 25px;
    border: 1px solid #000;
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

.total-row {
    background: #f5f5f5;
    font-weight: bold;
}

.btn-add-row {
    margin-bottom: 10px;
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
                    <i class="fas fa-shield-alt"></i> FORMATO DE RELACION DE GASTOS DIARIOS DE EPP
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

                        <div class="table-scroll">
                            <table class="epp-header">
                                <tr>
                                    <td>Servicio:</td>
                                    <td><input type="text" name="servicio" id="servicio"></td>
                                    <td>Sede:</td>
                                    <td><input type="text" name="sede" id="sede"></td>
                                </tr>
                                <tr>
                                    <td>Entregado por:</td>
                                    <td><input type="text" name="entregado_por" id="entregado_por"></td>
                                    <td>Fecha de entrega:</td>
                                    <td><input type="date" name="fecha_entrega" id="fecha_entrega"></td>
                                </tr>
                            </table>

                            <button type="button" class="btn btn-sm btn-success btn-add-row" onclick="agregarFila()">
                                <i class="fas fa-plus"></i> Agregar Fila
                            </button>

                            <table class="epp-table">
                                <thead>
                                    <tr>
                                        <th rowspan="2">Fecha</th>
                                        <th rowspan="2">Turno</th>
                                        <th rowspan="2">Nombre</th>
                                        <th colspan="7">Elementos de Protección Personal</th>
                                        <th rowspan="2" class="col-obs">Observación</th>
                                        <th rowspan="2" class="col-firma">Firma</th>
                                        <th rowspan="2">Acción</th>
                                    </tr>
                                    <tr>
                                        <th class="col-epp">Gorros</th>
                                        <th class="col-epp">Batas</th>
                                        <th class="col-epp">Pijamas</th>
                                        <th class="col-epp">Mascarilla<br>Quirúrgica</th>
                                        <th class="col-epp">Mascarilla<br>N95</th>
                                        <th class="col-epp">Polainas</th>
                                        <th class="col-epp">Overoles</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaFilas">
                                    <!-- Se generan dinámicamente -->
                                </tbody>
                                <tfoot>
                                    <tr class="total-row">
                                        <td colspan="3"><b>Total:</b></td>
                                        <td id="total_gorros">0</td>
                                        <td id="total_batas">0</td>
                                        <td id="total_pijamas">0</td>
                                        <td id="total_masc_q">0</td>
                                        <td id="total_masc_n95">0</td>
                                        <td id="total_polainas">0</td>
                                        <td id="total_overoles">0</td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>

                            <table class="epp-table">
                                <tr>
                                    <td style="height: 60px;"></td>
                                </tr>
                                <tr>
                                    <td style="border-top:1px solid #000; text-align:left; padding-top:5px;">
                                        Firma Jefe del Servicio
                                    </td>
                                </tr>
                            </table>
                        </div>

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
                            <input type="text" class="form-control" id="buscarRegistro" placeholder="🔍 Buscar por servicio, sede o entregado por..." onkeyup="filtrarRegistros()">
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
// ========================= VARIABLES GLOBALES =========================
let registrosGlobales = [];
let contadorFilas = 0;

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
    const hoy = new Date().toISOString().split('T')[0];
    document.getElementById('fecha_entrega').value = hoy;
    
    // Generar 15 filas iniciales
    for (let i = 0; i < 15; i++) {
        agregarFila();
    }
    
    cargarContadorRegistros();
});

// ========================= AGREGAR/ELIMINAR FILAS =========================
function agregarFila() {
    contadorFilas++;
    const tbody = document.getElementById('tablaFilas');
    const tr = document.createElement('tr');
    tr.id = `fila_${contadorFilas}`;
    
    tr.innerHTML = `
        <td><input type="date" name="epp[${contadorFilas}][fecha]" id="epp_${contadorFilas}_fecha"></td>
        <td><input type="text" name="epp[${contadorFilas}][turno]" id="epp_${contadorFilas}_turno"></td>
        <td><input type="text" name="epp[${contadorFilas}][nombre]" id="epp_${contadorFilas}_nombre"></td>
        
        <td><input type="text" name="epp[${contadorFilas}][gorros]" id="epp_${contadorFilas}_gorros" onchange="calcularTotales()"></td>
        <td><input type="text" name="epp[${contadorFilas}][batas]" id="epp_${contadorFilas}_batas" onchange="calcularTotales()"></td>
        <td><input type="text" name="epp[${contadorFilas}][pijamas]" id="epp_${contadorFilas}_pijamas" onchange="calcularTotales()"></td>
        <td><input type="text" name="epp[${contadorFilas}][masc_q]" id="epp_${contadorFilas}_masc_q" onchange="calcularTotales()"></td>
        <td><input type="text" name="epp[${contadorFilas}][masc_n95]" id="epp_${contadorFilas}_masc_n95" onchange="calcularTotales()"></td>
        <td><input type="text" name="epp[${contadorFilas}][polainas]" id="epp_${contadorFilas}_polainas" onchange="calcularTotales()"></td>
        <td><input type="text" name="epp[${contadorFilas}][overoles]" id="epp_${contadorFilas}_overoles" onchange="calcularTotales()"></td>
        
        <td><input type="text" name="epp[${contadorFilas}][obs]" id="epp_${contadorFilas}_obs"></td>
        <td></td>
        <td>
            <button type="button" class="btn btn-sm btn-danger" onclick="eliminarFila(${contadorFilas})">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(tr);
}

function eliminarFila(id) {
    const fila = document.getElementById(`fila_${id}`);
    if (fila) {
        fila.remove();
        calcularTotales();
    }
}

// ========================= CALCULAR TOTALES =========================
function calcularTotales() {
    const campos = ['gorros', 'batas', 'pijamas', 'masc_q', 'masc_n95', 'polainas', 'overoles'];
    
    campos.forEach(campo => {
        let total = 0;
        
        for (let i = 1; i <= contadorFilas; i++) {
            const elem = document.getElementById(`epp_${i}_${campo}`);
            if (elem && elem.offsetParent !== null) { // Verificar que la fila existe y es visible
                const valor = parseInt(elem.value) || 0;
                total += valor;
            }
        }
        
        document.getElementById(`total_${campo}`).textContent = total;
    });
}

// ========================= GUARDAR FORMULARIO =========================
function guardarFormulario() {
    const registroId = document.getElementById('registroId').value || `epp_${Date.now()}`;
    
    const data = {
        id: registroId,
        fecha_guardado: new Date().toISOString(),
        servicio: document.getElementById('servicio').value,
        sede: document.getElementById('sede').value,
        entregado_por: document.getElementById('entregado_por').value,
        fecha_entrega: document.getElementById('fecha_entrega').value,
        filas: [],
        totales: {
            gorros: document.getElementById('total_gorros').textContent,
            batas: document.getElementById('total_batas').textContent,
            pijamas: document.getElementById('total_pijamas').textContent,
            masc_q: document.getElementById('total_masc_q').textContent,
            masc_n95: document.getElementById('total_masc_n95').textContent,
            polainas: document.getElementById('total_polainas').textContent,
            overoles: document.getElementById('total_overoles').textContent
        }
    };

    // Recoger todas las filas
    for (let i = 1; i <= contadorFilas; i++) {
        const fila = document.getElementById(`fila_${i}`);
        if (fila && fila.offsetParent !== null) {
            const filaData = {
                id: i,
                fecha: document.getElementById(`epp_${i}_fecha`)?.value || '',
                turno: document.getElementById(`epp_${i}_turno`)?.value || '',
                nombre: document.getElementById(`epp_${i}_nombre`)?.value || '',
                gorros: document.getElementById(`epp_${i}_gorros`)?.value || '',
                batas: document.getElementById(`epp_${i}_batas`)?.value || '',
                pijamas: document.getElementById(`epp_${i}_pijamas`)?.value || '',
                masc_q: document.getElementById(`epp_${i}_masc_q`)?.value || '',
                masc_n95: document.getElementById(`epp_${i}_masc_n95`)?.value || '',
                polainas: document.getElementById(`epp_${i}_polainas`)?.value || '',
                overoles: document.getElementById(`epp_${i}_overoles`)?.value || '',
                obs: document.getElementById(`epp_${i}_obs`)?.value || ''
            };
            
            // Solo guardar filas con algún dato
            if (filaData.fecha || filaData.turno || filaData.nombre || 
                filaData.gorros || filaData.batas || filaData.pijamas || 
                filaData.masc_q || filaData.masc_n95 || filaData.polainas || 
                filaData.overoles) {
                data.filas.push(filaData);
            }
        }
    }

    if (Storage.set(`epp:${registroId}`, JSON.stringify(data))) {
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
    const keys = Storage.list('epp:');
    
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
        const totalRegistros = reg.filas ? reg.filas.length : 0;
        html += `
            <a href="#" class="list-group-item list-group-item-action" onclick="verDetalleRegistro('${reg.id}'); return false;">
                <div class="d-flex w-100 justify-content-between">
                    <h6 class="mb-1">${reg.servicio || 'Sin servicio'} - ${reg.sede || 'Sin sede'}</h6>
                    <small>${new Date(reg.fecha_guardado).toLocaleDateString()}</small>
                </div>
                <p class="mb-1"><strong>Entregado por:</strong> ${reg.entregado_por || 'N/A'}</p>
                <small><strong>Registros:</strong> ${totalRegistros} | <strong>Fecha entrega:</strong> ${reg.fecha_entrega || 'N/A'}</small>
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
    
    let htmlFilas = '';
    if (registro.filas && registro.filas.length > 0) {
        htmlFilas = '<table class="table table-sm table-bordered mt-3"><thead><tr><th>Fecha</th><th>Turno</th><th>Nombre</th><th>Gorros</th><th>Batas</th><th>Pijamas</th></tr></thead><tbody>';
        registro.filas.forEach(f => {
            htmlFilas += `<tr>
                <td>${f.fecha || '-'}</td>
                <td>${f.turno || '-'}</td>
                <td>${f.nombre || '-'}</td>
                <td>${f.gorros || '0'}</td>
                <td>${f.batas || '0'}</td>
                <td>${f.pijamas || '0'}</td>
            </tr>`;
        });
        htmlFilas += '</tbody></table>';
    }

    detalle.innerHTML = `
        <div class="card-header bg-primary text-white">
            <h5><i class="fas fa-shield-alt"></i> ${registro.servicio || 'Sin servicio'} - ${registro.sede || 'Sin sede'}</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Servicio:</strong> ${registro.servicio || 'N/A'}</p>
                    <p><strong>Sede:</strong> ${registro.sede || 'N/A'}</p>
                    <p><strong>Entregado por:</strong> ${registro.entregado_por || 'N/A'}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Fecha entrega:</strong> ${registro.fecha_entrega || 'N/A'}</p>
                    <p><strong>Total Gorros:</strong> ${registro.totales?.gorros || '0'}</p>
                    <p><strong>Total Batas:</strong> ${registro.totales?.batas || '0'}</p>
                    <p><strong>Guardado:</strong> ${new Date(registro.fecha_guardado).toLocaleString()}</p>
                </div>
            </div>
            ${htmlFilas}
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
    document.getElementById('servicio').value = registro.servicio || '';
    document.getElementById('sede').value = registro.sede || '';
    document.getElementById('entregado_por').value = registro.entregado_por || '';
    document.getElementById('fecha_entrega').value = registro.fecha_entrega || '';

    // Limpiar filas existentes
    document.getElementById('tablaFilas').innerHTML = '';
    contadorFilas = 0;

    // Recrear filas con datos
    if (registro.filas && registro.filas.length > 0) {
        registro.filas.forEach(filaData => {
            agregarFila();
            
            document.getElementById(`epp_${contadorFilas}_fecha`).value = filaData.fecha || '';
            document.getElementById(`epp_${contadorFilas}_turno`).value = filaData.turno || '';
            document.getElementById(`epp_${contadorFilas}_nombre`).value = filaData.nombre || '';
            document.getElementById(`epp_${contadorFilas}_gorros`).value = filaData.gorros || '';
            document.getElementById(`epp_${contadorFilas}_batas`).value = filaData.batas || '';
            document.getElementById(`epp_${contadorFilas}_pijamas`).value = filaData.pijamas || '';
            document.getElementById(`epp_${contadorFilas}_masc_q`).value = filaData.masc_q || '';
            document.getElementById(`epp_${contadorFilas}_masc_n95`).value = filaData.masc_n95 || '';
            document.getElementById(`epp_${contadorFilas}_polainas`).value = filaData.polainas || '';
            document.getElementById(`epp_${contadorFilas}_overoles`).value = filaData.overoles || '';
            document.getElementById(`epp_${contadorFilas}_obs`).value = filaData.obs || '';
        });
    }

    // Agregar algunas filas vacías adicionales
    for (let i = 0; i < 5; i++) {
        agregarFila();
    }

    calcularTotales();

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
    document.getElementById('fecha_entrega').value = hoy;
    
    // Limpiar filas y regenerar 15 nuevas
    document.getElementById('tablaFilas').innerHTML = '';
    contadorFilas = 0;
    
    for (let i = 0; i < 15; i++) {
        agregarFila();
    }
    
    calcularTotales();
}

// ========================= ELIMINAR REGISTRO =========================
function eliminarRegistro(id) {
    if (!confirm('¿Estás seguro de eliminar este registro? Esta acción no se puede deshacer.')) {
        return;
    }

    if (Storage.delete(`epp:${id}`)) {
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
    form.action = 'insertar_epp.php';

    // Función auxiliar para crear inputs
    function addInput(name, value) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value || '';
        form.appendChild(input);
    }

    // Campos básicos
    addInput('servicio', registro.servicio);
    addInput('sede', registro.sede);
    addInput('entregado_por', registro.entregado_por);
    addInput('fecha_entrega', registro.fecha_entrega);

    // Filas de EPP
    if (registro.filas && registro.filas.length > 0) {
        registro.filas.forEach((fila, index) => {
            const i = index + 1;
            addInput(`epp[${i}][fecha]`, fila.fecha);
            addInput(`epp[${i}][turno]`, fila.turno);
            addInput(`epp[${i}][nombre]`, fila.nombre);
            addInput(`epp[${i}][gorros]`, fila.gorros);
            addInput(`epp[${i}][batas]`, fila.batas);
            addInput(`epp[${i}][pijamas]`, fila.pijamas);
            addInput(`epp[${i}][masc_q]`, fila.masc_q);
            addInput(`epp[${i}][masc_n95]`, fila.masc_n95);
            addInput(`epp[${i}][polainas]`, fila.polainas);
            addInput(`epp[${i}][overoles]`, fila.overoles);
            addInput(`epp[${i}][obs]`, fila.obs);
        });
    }

    // Totales
    if (registro.totales) {
        addInput('total_gorros', registro.totales.gorros);
        addInput('total_batas', registro.totales.batas);
        addInput('total_pijamas', registro.totales.pijamas);
        addInput('total_masc_q', registro.totales.masc_q);
        addInput('total_masc_n95', registro.totales.masc_n95);
        addInput('total_polainas', registro.totales.polainas);
        addInput('total_overoles', registro.totales.overoles);
    }

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
        return (r.servicio || '').toLowerCase().includes(busqueda) ||
               (r.sede || '').toLowerCase().includes(busqueda) ||
               (r.entregado_por || '').toLowerCase().includes(busqueda);
    });
    
    mostrarListaRegistros(registrosFiltrados);
}

// ========================= CONTADOR =========================
function cargarContadorRegistros() {
    const keys = Storage.list('epp:');
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
    const datos = {
        servicio: document.getElementById('servicio').value,
        sede: document.getElementById('sede').value,
        entregado_por: document.getElementById('entregado_por').value,
        fecha_entrega: document.getElementById('fecha_entrega').value,
        filas: [],
        totales: {
            gorros: document.getElementById('total_gorros').textContent,
            batas: document.getElementById('total_batas').textContent,
            pijamas: document.getElementById('total_pijamas').textContent,
            masc_q: document.getElementById('total_masc_q').textContent,
            masc_n95: document.getElementById('total_masc_n95').textContent,
            polainas: document.getElementById('total_polainas').textContent,
            overoles: document.getElementById('total_overoles').textContent
        }
    };
    
    // Recoger todas las filas
    for (let i = 1; i <= contadorFilas; i++) {
        const fila = document.getElementById(`fila_${i}`);
        if (fila && fila.offsetParent !== null) {
            const filaData = {
                id: i,
                fecha: document.getElementById(`epp_${i}_fecha`)?.value || '',
                turno: document.getElementById(`epp_${i}_turno`)?.value || '',
                nombre: document.getElementById(`epp_${i}_nombre`)?.value || '',
                gorros: document.getElementById(`epp_${i}_gorros`)?.value || '',
                batas: document.getElementById(`epp_${i}_batas`)?.value || '',
                pijamas: document.getElementById(`epp_${i}_pijamas`)?.value || '',
                masc_q: document.getElementById(`epp_${i}_masc_q`)?.value || '',
                masc_n95: document.getElementById(`epp_${i}_masc_n95`)?.value || '',
                polainas: document.getElementById(`epp_${i}_polainas`)?.value || '',
                overoles: document.getElementById(`epp_${i}_overoles`)?.value || '',
                obs: document.getElementById(`epp_${i}_obs`)?.value || ''
            };
            
            // Solo guardar filas con algún dato
            if (filaData.fecha || filaData.turno || filaData.nombre || 
                filaData.gorros || filaData.batas || filaData.pijamas || 
                filaData.masc_q || filaData.masc_n95 || filaData.polainas || 
                filaData.overoles) {
                datos.filas.push(filaData);
            }
        }
    }
    
    // Guardar en sessionStorage para que preview_epp.php lo lea
    sessionStorage.setItem('previewEpp', JSON.stringify(datos));
    
    // Abrir preview_epp.php en nueva ventana
    window.open('preview_epp.php', 'preview', 'width=1400,height=900,resizable=yes,scrollbars=yes');
    
    mostrarAlerta('✓ Vista previa abierta en nueva ventana', 'info');
}
</script>

</body>
</html>
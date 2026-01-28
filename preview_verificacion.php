<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Vista Previa - Lista de Verificación</title>
<link href="css/bootstrap.min.css" rel="stylesheet" />
<style>
body {
    font-family: Arial, sans-serif;
    padding: 20px;
    background: white;
}
.container {
    max-width: 1400px;
    margin: 0 auto;
}
h3 {
    text-align: center;
    color: #a8814e;
    margin: 20px 0;
}
h4 {
    color: #205ca4;
    margin-top: 30px;
    margin-bottom: 15px;
    font-weight: bold;
}
h5 {
    color: #333;
    margin-top: 20px;
    margin-bottom: 10px;
    font-weight: bold;
    background: #f5f5f5;
    padding: 8px;
    border-left: 4px solid #205ca4;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}
table, th, td {
    border: 1px solid #000;
}
th, td {
    padding: 8px;
    text-align: center;
    font-size: 12px;
}
th {
    background: #f2f2f2;
    font-weight: bold;
}
.header-info {
    background: #f5f5f5;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
}
.header-info strong {
    color: #205ca4;
}
.item-name {
    text-align: left;
    font-weight: bold;
    font-size: 12px;
}
.check-mark {
    color: #28a745;
    font-size: 16px;
    font-weight: bold;
}
.btn-group {
    margin-bottom: 20px;
    text-align: center;
}
.btn-print {
    background: #28a745;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    display: inline-block;
    border: none;
    cursor: pointer;
    font-size: 14px;
}
.btn-print:hover {
    background: #218838;
}
.btn-close {
    background: #6c757d;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    font-size: 14px;
    margin-left: 10px;
}
.btn-close:hover {
    background: #5a6268;
}
.section-container {
    margin-bottom: 30px;
}
.two-column {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}
.biomedico-table {
    font-size: 11px;
}
.biomedico-table th,
.biomedico-table td {
    padding: 5px;
}
.checkbox-col {
    width: 40px;
}
.obs-col {
    text-align: left;
    font-size: 11px;
}
.report-box {
    border: 1px solid #000;
    padding: 10px;
    min-height: 100px;
    background: #fafafa;
}
@media print {
    .btn-group {
        display: none;
    }
    body {
        padding: 0;
    }
}
.page-break { 
    page-break-after: always; 
}
.print-page { 
    page-break-inside: avoid; 
    width: 100%; 
}
</style>
</head>
<body>

<div class="container">
    <div align="center">
        <img src="img/logo_dos.png" width="262" height="81" alt="Logo" />
    </div>

    <h3>LISTA DE VERIFICACIÓN DIARIA ASISTENCIAL DE AMBULANCIA</h3>

    <div class="btn-group">
        <button class="btn-print" onclick="window.print()">🖨️ Imprimir</button>
        <button class="btn-close" onclick="window.close()">❌ Cerrar</button>
    </div>

    <div class="header-info">
        <div style="margin-bottom: 10px;">
            <strong>Nombre:</strong> <span id="nombre">N/A</span> &nbsp;&nbsp;
            <strong>Fecha:</strong> <span id="fecha">N/A</span> &nbsp;&nbsp;
            <strong>Placa:</strong> <span id="placa">N/A</span>
        </div>
    </div>

    <!-- SECCIONES DE ITEMS -->
    <div class="section-container" id="seccionRespiratorio">
        <h4>📌 RESPIRATORIO</h4>
        <table class="print-page">
            <tr>
                <th>N°</th>
                <th>Descripción</th>
                <th>Cantidad</th>
            </tr>
            <tbody id="tableRespiratorio"></tbody>
        </table>
    </div>

    <div class="section-container" id="seccionOximetro">
        <h4>📌 OXIMETRO</h4>
        <table class="print-page">
            <tr>
                <th>N°</th>
                <th>Descripción</th>
                <th>Cantidad</th>
            </tr>
            <tbody id="tableOximetro"></tbody>
        </table>
    </div>

    <div class="section-container" id="seccionQuirurgico">
        <h4>📌 QUIRURGICO</h4>
        <table class="print-page">
            <tr>
                <th>N°</th>
                <th>Descripción</th>
                <th>Cantidad</th>
            </tr>
            <tbody id="tableQuirurgico"></tbody>
        </table>
    </div>

    <div class="section-container" id="seccionTrauma">
        <h4>📌 TRAUMA</h4>
        <table class="print-page">
            <tr>
                <th>N°</th>
                <th>Descripción</th>
                <th>Cantidad</th>
            </tr>
            <tbody id="tableTrauma"></tbody>
        </table>
    </div>

    <div class="section-container" id="seccionCirculatorio">
        <h4>📌 CIRCULATORIO</h4>
        <table class="print-page">
            <tr>
                <th>N°</th>
                <th>Descripción</th>
                <th>Cantidad</th>
            </tr>
            <tbody id="tableCirculatorio"></tbody>
        </table>
    </div>

    <div class="section-container" id="seccionOtros">
        <h4>📌 OTROS</h4>
        <table class="print-page">
            <tr>
                <th>N°</th>
                <th>Descripción</th>
                <th>Cantidad</th>
            </tr>
            <tbody id="tableOtros"></tbody>
        </table>
    </div>

    <!-- EQUIPOS BIOMÉDICOS -->
    <div class="section-container">
        <h4>⚙️ EQUIPOS BIOMÉDICOS</h4>
        <table class="biomedico-table print-page">
            <tr>
                <th>N°</th>
                <th>Descripción</th>
                <th>% Carga</th>
                <th colspan="2">Funcional</th>
                <th colspan="2">Cable Energía</th>
                <th colspan="2">Accesorios</th>
                <th class="obs-col">Observaciones</th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th class="checkbox-col">SI</th>
                <th class="checkbox-col">NO</th>
                <th class="checkbox-col">SI</th>
                <th class="checkbox-col">NO</th>
                <th class="checkbox-col">SI</th>
                <th class="checkbox-col">NO</th>
                <th class="obs-col"></th>
            </tr>
            <tbody id="tableEquiposBiomedicos"></tbody>
        </table>
    </div>

    <!-- OTROS EQUIPOS -->
    <div class="section-container">
        <h4>🔧 OTROS EQUIPOS</h4>
        <table class="print-page">
            <tr>
                <th>N°</th>
                <th>Descripción</th>
                <th colspan="2">Disponible</th>
                <th>Observaciones</th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th>SI</th>
                <th>NO</th>
                <th></th>
            </tr>
            <tbody id="tableOtrosEquipos"></tbody>
        </table>
    </div>

    <!-- ESTADO DE LIMPIEZA Y CANECAS -->
    <div class="section-container">
        <h4>🧹 ESTADO DE LIMPIEZA Y CANECAS</h4>
        <table class="print-page">
            <tr>
                <th>ITEM</th>
                <th>Estado</th>
            </tr>
            <tr>
                <td><strong>Limpieza Cabina</strong></td>
                <td id="estadoLimpieza">N/A</td>
            </tr>
            <tr>
                <td><strong>Canecas - Estado</strong></td>
                <td id="estadoCanecas">N/A</td>
            </tr>
            <tr>
                <td><strong>Canecas - Nivel</strong></td>
                <td id="nivelCanecas">N/A</td>
            </tr>
            <tr>
                <td><strong>Canecas - Bolsa</strong></td>
                <td id="bolsaCanecas">N/A</td>
            </tr>
            <tr>
                <td><strong>Alcohol</strong></td>
                <td id="alcohol">N/A</td>
            </tr>
        </table>
    </div>

    <!-- OXÍGENO -->
    <div class="section-container">
        <h4>🔵 OXÍGENO</h4>
        <div class="two-column">
            <div>
                <h5>Oxígeno Central</h5>
                <table class="print-page">
                    <tr>
                        <th>Item</th>
                        <th>Manómetro</th>
                        <th>Observaciones</th>
                    </tr>
                    <tr>
                        <td><strong>#1</strong></td>
                        <td id="oxy_central_1">N/A</td>
                        <td id="obs_oxy_central_1">-</td>
                    </tr>
                    <tr>
                        <td><strong>#2</strong></td>
                        <td id="oxy_central_2">N/A</td>
                        <td id="obs_oxy_central_2">-</td>
                    </tr>
                </table>
            </div>
            <div>
                <h5>Oxígeno Portátil</h5>
                <table class="print-page">
                    <tr>
                        <th>Item</th>
                        <th>Manómetro</th>
                        <th>Observaciones</th>
                    </tr>
                    <tr>
                        <td><strong>#1</strong></td>
                        <td id="oxy_port_1">N/A</td>
                        <td id="obs_oxy_port_1">-</td>
                    </tr>
                    <tr>
                        <td><strong>#2</strong></td>
                        <td id="oxy_port_2">N/A</td>
                        <td id="obs_oxy_port_2">-</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- REPORTE DE GASTOS -->
    <div class="section-container">
        <h4>📝 REPORTE DE GASTOS</h4>
        <div class="report-box" id="reporteGastos">
            Sin reporte
        </div>
    </div>
</div>

<script>
// Definiciones estáticas de items (deben coincidir con lista_verificacion.php)
const DATOS_ITEMS = {
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

document.addEventListener('DOMContentLoaded', function() {
    cargarDatos();
});

function cargarDatos() {
    try {
        const datosJSON = sessionStorage.getItem('previewVerificacion');
        
        if (!datosJSON) {
            alert('No hay datos para mostrar');
            return;
        }
        
        const datos = JSON.parse(datosJSON);
        console.log('Datos cargados:', datos);
        
        // Llenar header
        document.getElementById('nombre').textContent = datos.nombre || 'N/A';
        document.getElementById('fecha').textContent = datos.fecha || 'N/A';
        document.getElementById('placa').textContent = datos.placa || 'N/A';
        
        // Llenar secciones de items
        llenarTablaItems('respiratorio', datos.respiratorio);
        llenarTablaItems('oximetro', datos.oximetro);
        llenarTablaItems('quirurgico', datos.quirurgico);
        llenarTablaItems('trauma', datos.trauma);
        llenarTablaItems('circulatorio', datos.circulatorio);
        llenarTablaItems('otros', datos.otros);
        
        // Llenar equipos biomédicos
        llenarEquiposBiomedicos(datos.equiposBiomedicos);
        
        // Llenar otros equipos
        llenarOtrosEquipos(datos.otrosEquipos);
        
        // Llenar estado de cabina
        llenarEstadoCabina(datos.estadoCabina);
        
        // Llenar oxígeno
        llenarOxigeno(datos.oxigeno);
        
        // Llenar reporte de gastos
        if (datos.reporteGastos) {
            const reporte = escapeHtml(datos.reporteGastos).replace(/\n/g, '<br>');
            document.getElementById('reporteGastos').innerHTML = reporte;
        }
        
    } catch (error) {
        console.error('Error al cargar datos:', error);
        alert('Error al cargar los datos: ' + error.message);
    }
}

function llenarTablaItems(seccion, datos) {
    const items = DATOS_ITEMS[seccion];
    let html = '';
    
    if (!items || !datos || Object.keys(datos).length === 0) {
        document.getElementById(`seccion${seccion.charAt(0).toUpperCase() + seccion.slice(1)}`).style.display = 'none';
        return;
    }
    
    items.forEach((item, idx) => {
        const cant = datos[idx + 1] || '';
        if (cant) {
            html += `<tr>
                <td>${idx + 1}</td>
                <td class="item-name">${item}</td>
                <td>${escapeHtml(cant)}</td>
            </tr>`;
        }
    });
    
    if (html) {
        document.getElementById(`table${seccion.charAt(0).toUpperCase() + seccion.slice(1)}`).innerHTML = html;
    } else {
        document.getElementById(`seccion${seccion.charAt(0).toUpperCase() + seccion.slice(1)}`).style.display = 'none';
    }
}

function llenarEquiposBiomedicos(datos) {
    let html = '';
    
    DATOS_ITEMS.equiposBiomedicos.forEach((item, idx) => {
        const n = idx + 1;
        const equipo = datos && datos[n] ? datos[n] : {};
        
        html += `<tr>
            <td>${n}</td>
            <td class="item-name">${item}</td>
            <td>${equipo.carga ? escapeHtml(equipo.carga) : '-'}</td>
            <td class="checkbox-col">${equipo.funcional_si ? '<span class="check-mark">✓</span>' : ''}</td>
            <td class="checkbox-col">${equipo.funcional_no ? '<span class="check-mark">✓</span>' : ''}</td>
            <td class="checkbox-col">${equipo.cable_si ? '<span class="check-mark">✓</span>' : ''}</td>
            <td class="checkbox-col">${equipo.cable_no ? '<span class="check-mark">✓</span>' : ''}</td>
            <td class="checkbox-col">${equipo.acc_si ? '<span class="check-mark">✓</span>' : ''}</td>
            <td class="checkbox-col">${equipo.acc_no ? '<span class="check-mark">✓</span>' : ''}</td>
            <td class="obs-col">${equipo.obs ? escapeHtml(equipo.obs) : '-'}</td>
        </tr>`;
    });
    
    document.getElementById('tableEquiposBiomedicos').innerHTML = html;
}

function llenarOtrosEquipos(datos) {
    let html = '';
    
    DATOS_ITEMS.otrosEquipos.forEach((item, idx) => {
        const equipo = datos && datos[idx + 1] ? datos[idx + 1] : {};
        
        html += `<tr>
            <td>${idx + 1}</td>
            <td class="item-name">${item}</td>
            <td>${equipo.si ? '<span class="check-mark">✓</span>' : ''}</td>
            <td>${equipo.no ? '<span class="check-mark">✓</span>' : ''}</td>
            <td>${equipo.obs ? escapeHtml(equipo.obs) : '-'}</td>
        </tr>`;
    });
    
    document.getElementById('tableOtrosEquipos').innerHTML = html;
}

function llenarEstadoCabina(datos) {
    const items = {
        301: 'Limpia',
        302: 'Sucia',
        303: 'Buen estado',
        304: 'Mal estado',
        305: 'Vacías',
        306: 'Llenas',
        307: 'Con bolsa',
        308: 'Sin bolsa',
        309: 'Sí',
        310: 'No'
    };
    
    let limpieza = '';
    let canecas = '';
    let alcohol = '';
    
    if (datos) {
        if (datos[301]) limpieza = 'Limpia';
        if (datos[302]) limpieza = 'Sucia';
        
        let canecasEstado = '';
        if (datos[303]) canecasEstado = 'Buen estado';
        if (datos[304]) canecasEstado = 'Mal estado';
        
        let canecasNivel = '';
        if (datos[305]) canecasNivel = 'Vacías';
        if (datos[306]) canecasNivel = 'Llenas';
        
        let canecasBolsa = '';
        if (datos[307]) canecasBolsa = 'Con bolsa';
        if (datos[308]) canecasBolsa = 'Sin bolsa';
        
        canecas = `${canecasEstado} - ${canecasNivel} - ${canecasBolsa}`.replace(/^ - | - $/g, '');
        
        if (datos[309]) alcohol = 'Sí';
        if (datos[310]) alcohol = 'No';
    }
    
    document.getElementById('estadoLimpieza').textContent = limpieza || 'N/A';
    document.getElementById('estadoCanecas').textContent = canecas || 'N/A';
    document.getElementById('nivelCanecas').innerHTML = '&nbsp;';
    document.getElementById('bolsaCanecas').innerHTML = '&nbsp;';
    document.getElementById('alcohol').textContent = alcohol || 'N/A';
}

function llenarOxigeno(datos) {
    if (datos) {
        // Central #1
        let central1 = '';
        if (datos[401]) central1 = 'Funcional';
        if (datos[402]) central1 = 'No funcional';
        document.getElementById('oxy_central_1').textContent = central1 || 'N/A';
        document.getElementById('obs_oxy_central_1').textContent = (datos[403] ? escapeHtml(datos[403]) : '-');
        
        // Central #2
        let central2 = '';
        if (datos[404]) central2 = 'Funcional';
        if (datos[405]) central2 = 'No funcional';
        document.getElementById('oxy_central_2').textContent = central2 || 'N/A';
        document.getElementById('obs_oxy_central_2').textContent = (datos[406] ? escapeHtml(datos[406]) : '-');
        
        // Portátil #1
        let port1 = '';
        if (datos[407]) port1 = 'Funcional';
        if (datos[408]) port1 = 'No funcional';
        document.getElementById('oxy_port_1').textContent = port1 || 'N/A';
        document.getElementById('obs_oxy_port_1').textContent = (datos[409] ? escapeHtml(datos[409]) : '-');
        
        // Portátil #2
        let port2 = '';
        if (datos[410]) port2 = 'Funcional';
        if (datos[411]) port2 = 'No funcional';
        document.getElementById('oxy_port_2').textContent = port2 || 'N/A';
        document.getElementById('obs_oxy_port_2').textContent = (datos[412] ? escapeHtml(datos[412]) : '-');
    }
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}
</script>

</body>
</html>

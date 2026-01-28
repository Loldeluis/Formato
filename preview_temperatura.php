<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Vista Previa - Control de Temperatura</title>
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
    font-size: 11px;
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

    <h3>CONTROL DE TEMPERATURA, HUMEDAD RELATIVA Y CADENA DE FRÍO</h3>

    <div class="btn-group">
        <button class="btn-print" onclick="window.print()">🖨️ Imprimir</button>
        <button class="btn-close" onclick="window.close()">❌ Cerrar</button>
    </div>

    <div class="header-info" id="headerInfo">
        <div style="margin-bottom: 10px;">
            <strong>Nombre:</strong> <span id="nombre">N/A</span> &nbsp;&nbsp;
            <strong>Fecha:</strong> <span id="fecha">N/A</span> &nbsp;&nbsp;
            <strong>Placa:</strong> <span id="placa">N/A</span>
        </div>
        
        <div style="margin-top: 10px;">
            <strong>Mes:</strong> <span id="mes">N/A</span> &nbsp;&nbsp;
            <strong>Año:</strong> <span id="anio">N/A</span> &nbsp;&nbsp;
            <strong>Servicio:</strong> <span id="servicio">N/A</span>
        </div>
    </div>

    <h4>Control de Temperatura Ambiente (°C)</h4>
    <div style="overflow-x: auto; margin-bottom: 30px;">
        <table class="print-page" id="tablaTemperatura">
            <thead>
                <tr>
                    <th rowspan="2">GRADOS</th>
                    <th colspan="90">DÍAS DEL MES</th>
                </tr>
                <tr id="daysHeaderTemp"></tr>
            </thead>
            <tbody id="tempBody">
                <!-- Se llenará con JavaScript -->
            </tbody>
        </table>
    </div>

    <h4>Control de Humedad Relativa (%)</h4>
    <div style="overflow-x: auto; margin-bottom: 30px;">
        <table class="print-page" id="tablaHumedad">
            <thead>
                <tr>
                    <th rowspan="2">%</th>
                    <th colspan="90">DÍAS DEL MES</th>
                </tr>
                <tr id="daysHeaderHum"></tr>
            </thead>
            <tbody id="humBody">
                <!-- Se llenará con JavaScript -->
            </tbody>
        </table>
    </div>

    <h4>Control de Cadena de Frío (°C)</h4>
    <div style="overflow-x: auto; margin-bottom: 30px;">
        <table class="print-page" id="tablaCadenaFrio">
            <thead>
                <tr>
                    <th rowspan="2">GRADOS</th>
                    <th colspan="90">DÍAS DEL MES</th>
                </tr>
                <tr id="daysHeaderFrio"></tr>
            </thead>
            <tbody id="frioBody">
                <!-- Se llenará con JavaScript -->
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    cargarDatos();
});

function cargarDatos() {
    try {
        // Obtener datos del sessionStorage
        const datosJSON = sessionStorage.getItem('previewTemperatura');
        
        if (!datosJSON) {
            alert('No hay datos para mostrar');
            return;
        }
        
        const datos = JSON.parse(datosJSON);
        
        // Llenar información del encabezado
        document.getElementById('nombre').textContent = datos.nombre || 'N/A';
        document.getElementById('fecha').textContent = datos.fecha || 'N/A';
        document.getElementById('placa').textContent = datos.placa || 'N/A';
        document.getElementById('mes').textContent = datos.mes || 'N/A';
        document.getElementById('anio').textContent = datos.anio || 'N/A';
        document.getElementById('servicio').textContent = datos.servicio || 'N/A';
        
        // Llenar encabezados de días
        let headerTemp = '';
        let headerHum = '';
        let headerFrio = '';
        for (let d = 1; d <= 30; d++) {
            headerTemp += `<th colspan="3">${d}</th>`;
            headerHum += `<th colspan="3">${d}</th>`;
            headerFrio += `<th colspan="3">${d}</th>`;
        }
        headerTemp += `<th colspan="3">M</th><th colspan="3">T</th><th colspan="3">N</th>`;
        document.getElementById('daysHeaderTemp').innerHTML = headerTemp;
        document.getElementById('daysHeaderHum').innerHTML = headerHum;
        document.getElementById('daysHeaderFrio').innerHTML = headerFrio;
        
        // Llenar tabla de temperaturas
        llenarTablaTemperatura(datos);
        
        // Llenar tabla de humedad
        llenarTablaHumedad(datos);
        
        // Llenar tabla de cadena de frío
        llenarTablaCadenaFrio(datos);
        
    } catch (error) {
        console.error('Error al cargar datos:', error);
        alert('Error al cargar los datos: ' + error.message);
    }
}

function llenarTablaTemperatura(datos) {
    let html = '';
    
    // Filas de temperaturas (30 a 14)
    for (let t = 30; t >= 14; t--) {
        html += `<tr><td style="background: #f2f2f2; font-weight: bold;">${t}</td>`;
        
        // Días 1-30
        for (let d = 1; d <= 30; d++) {
            const valueM = datos.temp && datos.temp[d] && datos.temp[d].M === String(t) ? '✓' : '';
            const valueT = datos.temp && datos.temp[d] && datos.temp[d].T === String(t) ? '✓' : '';
            const valueN = datos.temp && datos.temp[d] && datos.temp[d].N === String(t) ? '✓' : '';
            
            html += `<td>${valueM}</td><td>${valueT}</td><td style="background: #e6e6e6;">${valueN}</td>`;
        }
        
        html += '</tr>';
    }
    
    document.getElementById('tempBody').innerHTML = html;
}

function llenarTablaHumedad(datos) {
    let html = '';
    
    // Filas de humedad (69 a 41)
    for (let h = 69; h >= 41; h--) {
        html += `<tr><td style="background: #f2f2f2; font-weight: bold;">${h}</td>`;
        
        // Días 1-30
        for (let d = 1; d <= 30; d++) {
            const valueM = datos.hum && datos.hum[d] && datos.hum[d].M === String(h) ? '✓' : '';
            const valueT = datos.hum && datos.hum[d] && datos.hum[d].T === String(h) ? '✓' : '';
            const valueN = datos.hum && datos.hum[d] && datos.hum[d].N === String(h) ? '✓' : '';
            
            html += `<td>${valueM}</td><td>${valueT}</td><td style="background: #e6e6e6;">${valueN}</td>`;
        }
        
        html += '</tr>';
    }
    
    document.getElementById('humBody').innerHTML = html;
}

function llenarTablaCadenaFrio(datos) {
    let html = '';
    
    // Filas de cadena de frío (10 a 0)
    for (let c = 10; c >= 0; c--) {
        html += `<tr><td style="background: #f2f2f2; font-weight: bold;">${c}</td>`;
        
        // Días 1-30
        for (let d = 1; d <= 30; d++) {
            const valueM = datos.frio && datos.frio[d] && datos.frio[d].M === String(c) ? '✓' : '';
            const valueT = datos.frio && datos.frio[d] && datos.frio[d].T === String(c) ? '✓' : '';
            const valueN = datos.frio && datos.frio[d] && datos.frio[d].N === String(c) ? '✓' : '';
            
            html += `<td>${valueM}</td><td>${valueT}</td><td style="background: #e6e6e6;">${valueN}</td>`;
        }
        
        html += '</tr>';
    }
    
    document.getElementById('frioBody').innerHTML = html;
}
</script>

</body>
</html>

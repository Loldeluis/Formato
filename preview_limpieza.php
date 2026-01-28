<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Vista Previa - Formato de Limpieza</title>
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
    font-size: 12px;
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
.check-mark {
    color: #28a745;
    font-size: 18px;
    font-weight: bold;
}
.area-name {
    text-align: left;
    font-weight: bold;
    font-size: 12px;
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

    <h3>CONTROL DIARIO DE LIMPIEZA Y DESINFECCIÓN DE AMBULANCIAS</h3>

    <div class="btn-group">
        <button class="btn-print" onclick="window.print()">🖨️ Imprimir</button>
        <button class="btn-close" onclick="window.close()">❌ Cerrar</button>
    </div>

    <div class="header-info" id="headerInfo">
        <div style="margin-bottom: 10px;">
            <strong>Mes:</strong> <span id="mes">N/A</span> &nbsp;&nbsp;
            <strong>Año:</strong> <span id="anio">N/A</span> &nbsp;&nbsp;
            <strong>Sede:</strong> <span id="sede">N/A</span> &nbsp;&nbsp;
            <strong>Móvil/Placa:</strong> <span id="movil">N/A</span>
        </div>
        
        <div id="productosDiv" style="display:none; border-top: 1px solid #ddd; padding-top: 10px; margin-top: 10px;">
            <strong>PRODUCTOS UTILIZADOS:</strong><br>
            <span id="productosContent"></span>
        </div>
        
        <div id="observacionesDiv" style="display:none; border-top: 1px solid #ddd; padding-top: 10px; margin-top: 10px;">
            <strong>OBSERVACIONES:</strong><br>
            <span id="observacionesContent"></span>
        </div>
    </div>

    <h4>Control de Áreas</h4>

    <!-- Página 1: Días 1 - 15 -->
    <table class="print-page">
        <tr>
            <th rowspan="2">ÁREA</th>
            <th colspan="45">DÍAS DEL MES</th>
        </tr>
        <tr>
            <?php for ($d = 1; $d <= 15; $d++): ?>
                <th colspan="3"><?= $d ?></th>
            <?php endfor; ?>
        </tr>
        <tr>
            <th></th>
            <?php for ($d = 1; $d <= 15; $d++): ?>
                <th>M</th><th>T</th><th>N</th>
            <?php endfor; ?>
        </tr>
        <tbody id="areasTable1">
            <!-- Se llenará con JavaScript -->
        </tbody>
    </table>

    <div class="page-break"></div>

    <!-- Página 2: Días 16 - 31 -->
    <table class="print-page">
        <tr>
            <th rowspan="2">ÁREA</th>
            <th colspan="48">DÍAS DEL MES</th>
        </tr>
        <tr>
            <?php for ($d = 16; $d <= 31; $d++): ?>
                <th colspan="3"><?= $d ?></th>
            <?php endfor; ?>
        </tr>
        <tr>
            <th></th>
            <?php for ($d = 16; $d <= 31; $d++): ?>
                <th>M</th><th>T</th><th>N</th>
            <?php endfor; ?>
        </tr>
        <tbody id="areasTable2">
            <!-- Se llenará con JavaScript -->
        </tbody>
    </table>

    <h4>Control Diario - Personal</h4>

    <!-- Página 1: Días 1 - 15 -->
    <table class="print-page">
        <tr>
            <th>ROL</th>
            <?php for ($d = 1; $d <= 15; $d++): ?>
                <th colspan="3"><?= $d ?></th>
            <?php endfor; ?>
        </tr>
        <tr>
            <th></th>
            <?php for ($d = 1; $d <= 15; $d++): ?>
                <th>M</th><th>T</th><th>N</th>
            <?php endfor; ?>
        </tr>
        
        <tr>
            <td><strong>REALIZA</strong></td>
            <?php for ($d = 1; $d <= 15; $d++): ?>
                <td id="realiza_1_<?= $d ?>_M"></td>
                <td id="realiza_1_<?= $d ?>_T"></td>
                <td id="realiza_1_<?= $d ?>_N"></td>
            <?php endfor; ?>
        </tr>

        <tr>
            <td><strong>SUPERVISA</strong></td>
            <?php for ($d = 1; $d <= 15; $d++): ?>
                <td id="supervisa_1_<?= $d ?>_M"></td>
                <td id="supervisa_1_<?= $d ?>_T"></td>
                <td id="supervisa_1_<?= $d ?>_N"></td>
            <?php endfor; ?>
        </tr>
    </table>

    <div class="page-break"></div>

    <!-- Página 2: Días 16 - 31 -->
    <table class="print-page">
        <tr>
            <th>ROL</th>
            <?php for ($d = 16; $d <= 31; $d++): ?>
                <th colspan="3"><?= $d ?></th>
            <?php endfor; ?>
        </tr>
        <tr>
            <th></th>
            <?php for ($d = 16; $d <= 31; $d++): ?>
                <th>M</th><th>T</th><th>N</th>
            <?php endfor; ?>
        </tr>
        
        <tr>
            <td><strong>REALIZA</strong></td>
            <?php for ($d = 16; $d <= 31; $d++): ?>
                <td id="realiza_2_<?= $d ?>_M"></td>
                <td id="realiza_2_<?= $d ?>_T"></td>
                <td id="realiza_2_<?= $d ?>_N"></td>
            <?php endfor; ?>
        </tr>

        <tr>
            <td><strong>SUPERVISA</strong></td>
            <?php for ($d = 16; $d <= 31; $d++): ?>
                <td id="supervisa_2_<?= $d ?>_M"></td>
                <td id="supervisa_2_<?= $d ?>_T"></td>
                <td id="supervisa_2_<?= $d ?>_N"></td>
            <?php endfor; ?>
        </tr>
    </table>
</div>

<script>
// Definición de áreas (debe coincidir con editar_limpieza.php)
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
    "Silla de ruedas",
    "Paredes y piso de la ambulancia",
    "Canecas"
];

document.addEventListener('DOMContentLoaded', function() {
    cargarDatos();
});

function cargarDatos() {
    try {
        // Obtener datos del sessionStorage (pasados desde editar_limpieza.php)
        const datosJSON = sessionStorage.getItem('previewFormato');
        
        if (!datosJSON) {
            alert('No hay datos para mostrar');
            return;
        }
        
        const datos = JSON.parse(datosJSON);
        console.log('Datos cargados:', datos);
        
        // Llenar header
        document.getElementById('mes').textContent = datos.mes || 'N/A';
        document.getElementById('anio').textContent = datos.anio || 'N/A';
        document.getElementById('sede').textContent = datos.sede || 'N/A';
        document.getElementById('movil').textContent = datos.movil || 'N/A';
        
        // Llenar productos
        let productosHTML = '';
        if (datos.prod_limpieza) {
            productosHTML += `<strong>Limpieza:</strong> ${escapeHtml(datos.prod_limpieza)}`;
            if (datos.conc_limpieza) {
                productosHTML += ` (${escapeHtml(datos.conc_limpieza)})`;
            }
            productosHTML += '<br>';
        }
        if (datos.prod_desinfectante) {
            productosHTML += `<strong>Desinfectante:</strong> ${escapeHtml(datos.prod_desinfectante)}`;
            if (datos.conc_desinfectante) {
                productosHTML += ` (${escapeHtml(datos.conc_desinfectante)})`;
            }
        }
        
        if (productosHTML) {
            document.getElementById('productosDiv').style.display = 'block';
            document.getElementById('productosContent').innerHTML = productosHTML;
        }
        
        // Llenar observaciones
        if (datos.observaciones && datos.observaciones.trim() !== '') {
            document.getElementById('observacionesDiv').style.display = 'block';
            // Primero escapar HTML, luego reemplazar saltos de línea
            const obsEscapada = escapeHtml(datos.observaciones);
            const obsConBreaks = obsEscapada.replace(/\n/g, '<br>').replace(/\r/g, '');
            document.getElementById('observacionesContent').innerHTML = obsConBreaks;
        }
        
        // Llenar tabla de áreas
        llenarTablasAreas(datos);
        
        // Llenar tabla de personal
        llenarTablaPersonal(datos);
        
    } catch (error) {
        console.error('Error al cargar datos:', error);
        alert('Error al cargar los datos: ' + error.message);
    }
}

function llenarTablasAreas(datos) {
    const tabla1 = document.getElementById('areasTable1');
    const tabla2 = document.getElementById('areasTable2');
    
    let html1 = '';
    let html2 = '';
    
    areas.forEach((area, indice) => {
        // Tabla 1: Días 1-15
        let row1 = `<tr><td class="area-name">${area}</td>`;
        for (let d = 1; d <= 15; d++) {
            const checked_M = datos.control && datos.control[indice] && datos.control[indice][d] && datos.control[indice][d].M;
            const checked_T = datos.control && datos.control[indice] && datos.control[indice][d] && datos.control[indice][d].T;
            const checked_N = datos.control && datos.control[indice] && datos.control[indice][d] && datos.control[indice][d].N;
            
            row1 += `<td>${checked_M ? '<span class="check-mark">✓</span>' : ''}</td>`;
            row1 += `<td>${checked_T ? '<span class="check-mark">✓</span>' : ''}</td>`;
            row1 += `<td>${checked_N ? '<span class="check-mark">✓</span>' : ''}</td>`;
        }
        row1 += '</tr>';
        html1 += row1;
        
        // Tabla 2: Días 16-31
        let row2 = `<tr><td class="area-name">${area}</td>`;
        for (let d = 16; d <= 31; d++) {
            const checked_M = datos.control && datos.control[indice] && datos.control[indice][d] && datos.control[indice][d].M;
            const checked_T = datos.control && datos.control[indice] && datos.control[indice][d] && datos.control[indice][d].T;
            const checked_N = datos.control && datos.control[indice] && datos.control[indice][d] && datos.control[indice][d].N;
            
            row2 += `<td>${checked_M ? '<span class="check-mark">✓</span>' : ''}</td>`;
            row2 += `<td>${checked_T ? '<span class="check-mark">✓</span>' : ''}</td>`;
            row2 += `<td>${checked_N ? '<span class="check-mark">✓</span>' : ''}</td>`;
        }
        row2 += '</tr>';
        html2 += row2;
    });
    
    tabla1.innerHTML = html1;
    tabla2.innerHTML = html2;
}

function llenarTablaPersonal(datos) {
    // Llenar realiza - Tabla 1 (días 1-15)
    for (let d = 1; d <= 15; d++) {
        const valM = datos.realiza && datos.realiza[d] && datos.realiza[d].M ? escapeHtml(datos.realiza[d].M) : '';
        const valT = datos.realiza && datos.realiza[d] && datos.realiza[d].T ? escapeHtml(datos.realiza[d].T) : '';
        const valN = datos.realiza && datos.realiza[d] && datos.realiza[d].N ? escapeHtml(datos.realiza[d].N) : '';
        
        document.getElementById(`realiza_1_${d}_M`).textContent = valM;
        document.getElementById(`realiza_1_${d}_T`).textContent = valT;
        document.getElementById(`realiza_1_${d}_N`).textContent = valN;
    }
    
    // Llenar realiza - Tabla 2 (días 16-31)
    for (let d = 16; d <= 31; d++) {
        const valM = datos.realiza && datos.realiza[d] && datos.realiza[d].M ? escapeHtml(datos.realiza[d].M) : '';
        const valT = datos.realiza && datos.realiza[d] && datos.realiza[d].T ? escapeHtml(datos.realiza[d].T) : '';
        const valN = datos.realiza && datos.realiza[d] && datos.realiza[d].N ? escapeHtml(datos.realiza[d].N) : '';
        
        document.getElementById(`realiza_2_${d}_M`).textContent = valM;
        document.getElementById(`realiza_2_${d}_T`).textContent = valT;
        document.getElementById(`realiza_2_${d}_N`).textContent = valN;
    }
    
    // Llenar supervisa - Tabla 1 (días 1-15)
    for (let d = 1; d <= 15; d++) {
        const valM = datos.supervisa && datos.supervisa[d] && datos.supervisa[d].M ? escapeHtml(datos.supervisa[d].M) : '';
        const valT = datos.supervisa && datos.supervisa[d] && datos.supervisa[d].T ? escapeHtml(datos.supervisa[d].T) : '';
        const valN = datos.supervisa && datos.supervisa[d] && datos.supervisa[d].N ? escapeHtml(datos.supervisa[d].N) : '';
        
        document.getElementById(`supervisa_1_${d}_M`).textContent = valM;
        document.getElementById(`supervisa_1_${d}_T`).textContent = valT;
        document.getElementById(`supervisa_1_${d}_N`).textContent = valN;
    }
    
    // Llenar supervisa - Tabla 2 (días 16-31)
    for (let d = 16; d <= 31; d++) {
        const valM = datos.supervisa && datos.supervisa[d] && datos.supervisa[d].M ? escapeHtml(datos.supervisa[d].M) : '';
        const valT = datos.supervisa && datos.supervisa[d] && datos.supervisa[d].T ? escapeHtml(datos.supervisa[d].T) : '';
        const valN = datos.supervisa && datos.supervisa[d] && datos.supervisa[d].N ? escapeHtml(datos.supervisa[d].N) : '';
        
        document.getElementById(`supervisa_2_${d}_M`).textContent = valM;
        document.getElementById(`supervisa_2_${d}_T`).textContent = valT;
        document.getElementById(`supervisa_2_${d}_N`).textContent = valN;
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

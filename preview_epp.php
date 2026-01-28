<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista Previa - Relación de Gastos de EPP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }
        
        .print-container {
            background-color: white;
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        
        .preview-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            gap: 20px;
        }
        
        .preview-header h2 {
            flex: 1;
            text-align: center;
            color: #333;
            font-size: 22px;
            font-weight: bold;
        }
        
        .logo-area {
            text-align: center;
            width: 100px;
        }
        
        .logo-area i {
            font-size: 48px;
            color: #0d47a1;
        }
        
        .button-group {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            justify-content: center;
        }
        
        .button-group button {
            padding: 8px 20px;
            font-size: 14px;
            border-radius: 5px;
        }
        
        .btn-print {
            background-color: #0d47a1;
            color: white;
            border: none;
            cursor: pointer;
        }
        
        .btn-print:hover {
            background-color: #0a3d91;
        }
        
        .btn-close-window {
            background-color: #6c757d;
            color: white;
            border: none;
            cursor: pointer;
        }
        
        .btn-close-window:hover {
            background-color: #5a6268;
        }
        
        .info-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        
        .info-item {
            display: flex;
            flex-direction: column;
        }
        
        .info-item label {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
            font-size: 12px;
        }
        
        .info-item span {
            color: #666;
            font-size: 14px;
            padding: 8px;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 3px;
        }
        
        .table-section {
            margin-bottom: 30px;
        }
        
        .table-section h4 {
            color: #0d47a1;
            font-size: 16px;
            margin-bottom: 15px;
            font-weight: bold;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 13px;
        }
        
        thead {
            background-color: #0d47a1;
            color: white;
        }
        
        thead th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #0d47a1;
        }
        
        tbody td {
            padding: 10px 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        tbody tr:hover {
            background-color: #f0f0f0;
        }
        
        .text-left {
            text-align: left !important;
        }
        
        .total-row {
            font-weight: bold;
            background-color: #e8eaf6;
        }
        
        .total-row td {
            border-top: 2px solid #0d47a1;
            padding: 12px;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
            font-style: italic;
        }
        
        .empty-message {
            text-align: center;
            padding: 20px;
            color: #666;
            background-color: #f9f9f9;
            border-radius: 5px;
            margin: 20px 0;
        }
        
        @media print {
            body {
                background-color: white;
                padding: 0;
            }
            
            .print-container {
                max-width: 100%;
                padding: 0;
                box-shadow: none;
                border-radius: 0;
            }
            
            .button-group {
                display: none;
            }
            
            table {
                page-break-inside: avoid;
            }
            
            tbody tr {
                page-break-inside: avoid;
            }
            
            .page-break {
                page-break-after: always;
            }
            
            .preview-header {
                margin-bottom: 20px;
                border-bottom: 2px solid #0d47a1;
                padding-bottom: 10px;
            }
        }
        
        .no-content-section {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="print-container">
        <div class="preview-header">
            <div class="logo-area">
                <i class="fas fa-heartbeat"></i>
            </div>
            <h2>RELACIÓN DE GASTOS DIARIOS DE EPP</h2>
            <div style="width: 100px;"></div>
        </div>
        
        <div class="button-group">
            <button class="btn-print" onclick="window.print()">
                <i class="fas fa-print"></i> Imprimir
            </button>
            <button class="btn-close-window" onclick="window.close()">
                <i class="fas fa-times"></i> Cerrar
            </button>
        </div>
        
        <div id="content">
            <!-- El contenido se cargará aquí con JavaScript -->
        </div>
    </div>
    
    <script>
        // Función para escapar HTML y evitar XSS
        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        // Cargar datos desde sessionStorage y mostrar
        function cargarDatos() {
            const datos = sessionStorage.getItem('previewEpp');
            
            if (!datos) {
                document.getElementById('content').innerHTML = `
                    <div class="no-content-section">
                        <i class="fas fa-exclamation-triangle"></i>
                        No hay datos disponibles. Por favor, vuelva a intentar desde el formulario principal.
                    </div>
                `;
                return;
            }
            
            const dataObj = JSON.parse(datos);
            let html = '';
            
            // Sección de información general
            html += `
                <div class="info-section">
                    <div class="info-item">
                        <label>Servicio:</label>
                        <span>${escapeHtml(dataObj.servicio)}</span>
                    </div>
                    <div class="info-item">
                        <label>Sede:</label>
                        <span>${escapeHtml(dataObj.sede)}</span>
                    </div>
                    <div class="info-item">
                        <label>Entregado por:</label>
                        <span>${escapeHtml(dataObj.entregado_por)}</span>
                    </div>
                    <div class="info-item">
                        <label>Fecha de Entrega:</label>
                        <span>${escapeHtml(dataObj.fecha_entrega)}</span>
                    </div>
                </div>
            `;
            
            // Sección de tabla con detalles
            if (dataObj.filas && dataObj.filas.length > 0) {
                html += `
                    <div class="table-section">
                        <h4><i class="fas fa-list"></i> Detalle de EPP Entregado</h4>
                        <table>
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Turno</th>
                                    <th>Nombre</th>
                                    <th>Gorros</th>
                                    <th>Batas</th>
                                    <th>Pijamas</th>
                                    <th>M. Quirúrgica</th>
                                    <th>M. N95</th>
                                    <th>Polainas</th>
                                    <th>Overoles</th>
                                    <th>Observación</th>
                                </tr>
                            </thead>
                            <tbody>
                `;
                
                // Agregar filas de datos
                dataObj.filas.forEach((fila, index) => {
                    html += `
                        <tr>
                            <td>${escapeHtml(fila.fecha)}</td>
                            <td>${escapeHtml(fila.turno)}</td>
                            <td class="text-left">${escapeHtml(fila.nombre)}</td>
                            <td>${escapeHtml(fila.gorros)}</td>
                            <td>${escapeHtml(fila.batas)}</td>
                            <td>${escapeHtml(fila.pijamas)}</td>
                            <td>${escapeHtml(fila.masc_q)}</td>
                            <td>${escapeHtml(fila.masc_n95)}</td>
                            <td>${escapeHtml(fila.polainas)}</td>
                            <td>${escapeHtml(fila.overoles)}</td>
                            <td class="text-left">${escapeHtml(fila.obs)}</td>
                        </tr>
                    `;
                });
                
                // Fila de totales
                html += `
                        <tr class="total-row">
                            <td colspan="3">TOTAL</td>
                            <td>${escapeHtml(dataObj.totales.gorros)}</td>
                            <td>${escapeHtml(dataObj.totales.batas)}</td>
                            <td>${escapeHtml(dataObj.totales.pijamas)}</td>
                            <td>${escapeHtml(dataObj.totales.masc_q)}</td>
                            <td>${escapeHtml(dataObj.totales.masc_n95)}</td>
                            <td>${escapeHtml(dataObj.totales.polainas)}</td>
                            <td>${escapeHtml(dataObj.totales.overoles)}</td>
                            <td>-</td>
                        </tr>
                            </tbody>
                        </table>
                    </div>
                `;
            } else {
                html += `
                    <div class="empty-message">
                        <i class="fas fa-inbox fa-3x mb-3" style="color: #ccc;"></i>
                        <p>No hay registros de EPP para mostrar</p>
                    </div>
                `;
            }
            
            // Sección de firma
            html += `
                <div class="table-section" style="margin-top: 50px;">
                    <table>
                        <tbody>
                            <tr style="height: 80px;">
                                <td style="text-align: center; border: none;">
                                    <div style="border-top: 1px solid #333; padding-top: 5px;">
                                        Firma y Aclaración de Firma
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div style="text-align: center; color: #999; font-size: 12px; margin-top: 40px;">
                    Documento generado automáticamente - ${new Date().toLocaleString()}
                </div>
            `;
            
            document.getElementById('content').innerHTML = html;
        }
        
        // Cargar datos cuando se abre la página
        window.addEventListener('load', cargarDatos);
    </script>
</body>
</html>

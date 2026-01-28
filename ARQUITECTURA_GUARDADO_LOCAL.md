# 📱 Arquitectura: Guardado Local + Envío a PHP

## Resumen
El sistema ahora funciona en **2 pasos independientes**:

### ✅ PASO 1: Guardar Localmente (Sin Conexión a Internet)
- **Botón:** "Guardar Localmente"
- **Almacenamiento:** localStorage del navegador
- **Ventajas:**
  - Funciona sin internet
  - Datos persisten incluso si cierras el navegador
  - Respuesta instantánea
  - El usuario puede seguir editando

### 🌐 PASO 2: Enviar a Base de Datos (Cuando tengas conexión)
- **Botón:** "Enviar a Base de Datos" (aparece después de guardar)
- **Acción:** Envía el registro a PHP (insertar.php o actualizar_limpieza.php)
- **Ventajas:**
  - Separado del guardado local
  - El usuario controla cuándo enviar
  - Puedes intentar enviar varias veces si falla
  - Mejor UX

---

## 📊 Flujo de Uso

```
1. Llenar formulario
   ↓
2. Click "Guardar Localmente"
   └─→ Se guarda en localStorage ✓
   └─→ Aparece botón "Enviar a Base de Datos"
   └─→ Se muestra alerta de estado
   ↓
3. (Opcional) Editar más datos
   └─→ Click "Guardar Localmente" de nuevo (actualiza)
   ↓
4. Click "Enviar a Base de Datos"
   └─→ Se envía a PHP
   └─→ Se inserta/actualiza en BD SQL Server
   └─→ Mensaje de confirmación
```

---

## 🔧 Cambios Realizados

### 1️⃣ Interfaz (HTML)
```html
<!-- Botón cambió de -->
<button onclick="guardarFormulario()">Guardar</button>

<!-- A -->
<button onclick="guardarFormulario()">Guardar Localmente</button>
<button id="btnEnviarPHP" style="display:none;" onclick="enviarAPhpManual()">
  Enviar a Base de Datos
</button>

<!-- Se agregó indicador de estado -->
<div id="estadoSync" class="alert alert-info" style="display:none;">
  Tienes un registro pendiente de enviar a la base de datos.
</div>
```

### 2️⃣ JavaScript
Se eliminó:
```javascript
// ❌ ANTES: Enviaba automáticamente a PHP
setTimeout(() => {
    exportarRegistroPHP(registroId, true, modoEdicion);
}, 500);
```

Se agregó:
```javascript
// ✅ NUEVO: Solo muestra botón de envío
document.getElementById('btnEnviarPHP').style.display = 'inline-block';
mostrarEstadoSync(registroId, modoEdicion);

// ✅ NUEVA FUNCIÓN: Envía cuando el usuario lo decide
function enviarAPhpManual() {
    const registroId = document.getElementById('registroId').value;
    const modoEdicion = document.getElementById('modoEdicion').value === 'true';
    
    if (!registroId) {
        mostrarAlerta('⚠️ No hay registro guardado para enviar', 'warning');
        return;
    }
    
    exportarRegistroPHP(registroId, true, modoEdicion);
}

// ✅ NUEVA FUNCIÓN: Muestra estado de sincronización
function mostrarEstadoSync(registroId, esEdicion) {
    const estadoDiv = document.getElementById('estadoSync');
    const textoSync = document.getElementById('textoSync');
    
    const tipo = esEdicion ? 'actualización' : 'nuevo registro';
    textoSync.textContent = `Tienes un ${tipo} pendiente de enviar a la base de datos.`;
    estadoDiv.style.display = 'block';
}
```

---

## 💾 Dónde se guardan los datos

### localStorage (Navegador)
```javascript
localStorage.setItem('ambulancia:reg_1234567890', JSON.stringify(datos));
```
- **Ubicación:** En el navegador del usuario
- **Límite:** ~5-10 MB por dominio
- **Duración:** Indefinida (hasta que el usuario limpie caché)

### SQL Server (Base de Datos)
```php
INSERT INTO formatos_limpieza (mes, anio, sede, movil, ...) VALUES (...)
```
- **Ubicación:** Servidor
- **Límite:** Ilimitado
- **Duración:** Permanente

---

## 🎯 Casos de Uso

### Caso 1: Usuario sin internet
```
1. Llena formulario
2. Click "Guardar Localmente" ✓ (funciona sin internet)
3. Se va del lugar
4. (Después) Vuelve a casa con internet
5. Click "Enviar a Base de Datos" ✓ (se envía)
```

### Caso 2: Edición progresiva
```
1. Llenar formulario
2. "Guardar Localmente" (guarda en localStorage)
3. Click "Lista de Registros"
4. Seguir editando otros registros
5. Cuando termina todo, "Enviar a Base de Datos" para cada uno
```

### Caso 3: Revisión antes de enviar
```
1. "Guardar Localmente"
2. Click "Imprimir/Vista Previa" (revisa los datos)
3. Si está bien → "Enviar a Base de Datos"
4. Si hay errores → edita y "Guardar Localmente" de nuevo
```

---

## ⚙️ Configuración (Si necesitas cambiar)

### Para volver al envío automático:
En `guardarFormulario()`, reemplaza:
```javascript
// Líneas actuales:
document.getElementById('btnEnviarPHP').style.display = 'inline-block';
mostrarEstadoSync(registroId, modoEdicion);
```

Por:
```javascript
// Automático (como antes):
setTimeout(() => {
    exportarRegistroPHP(registroId, true, modoEdicion);
}, 500);
```

### Para cambiar el almacenamiento:
- localStorage → sessionStorage (se borra al cerrar navegador)
- Cambiar en `window.storage.set()` de la línea ~759

---

## 🚨 Cosas Importantes

1. **localStorage tiene límite de espacio:** Si guardas muchos registros grandes, puede llenar
   - Solución: Limpiar registros antiguos regularmente
   - O: Enviar a PHP regularmente

2. **Si el usuario limpia caché/cookies:** Pierde los datos no enviados
   - Por eso es importante enviar a PHP pronto
   - El botón avisa que hay datos pendientes

3. **El botón "Enviar a PHP" solo aparece después de "Guardar Localmente"**
   - Esto es intencional (evita errores de envío vacío)
   - Si accidentalmente lo cierras, vuelve a abrir el registro de la lista

4. **Si falla el envío a PHP:**
   - Los datos siguen en localStorage
   - El usuario puede intentar de nuevo
   - Se muestra alerta con el error

---

## 📋 Resumen de Ventajas

| Aspecto | Antes | Ahora |
|--------|-------|-------|
| **Sin Internet** | ❌ No funciona | ✅ Funciona |
| **Control del Usuario** | ❌ Automático | ✅ Manual |
| **Reintentos** | ❌ Una sola vez | ✅ Múltiples veces |
| **Revisión Previa** | ⚠️ Difícil | ✅ Fácil |
| **UX** | ⚠️ Confusa | ✅ Clara |

---

## 🔍 Debugging

Si necesitas ver qué hay en localStorage:
```javascript
// En la consola del navegador (F12)
localStorage.getItem('ambulancia:reg_1234567890')
// O todos:
Object.keys(localStorage).filter(k => k.startsWith('ambulancia:'))
```

Para limpiar un registro:
```javascript
localStorage.removeItem('ambulancia:reg_1234567890')
```

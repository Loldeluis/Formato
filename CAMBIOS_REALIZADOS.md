# ✅ Cambios Realizados - Guardado Local vs Envío a PHP

## 🎯 Objetivo Cumplido

**Antes:** Click en Guardar → Automáticamente se enviaba a PHP (sin control del usuario)

**Ahora:** 
1. Click "Guardar Localmente" → Se guarda en localStorage (funciona sin internet)
2. Click "Enviar a Base de Datos" → Se envía a PHP (cuando el usuario lo decide)

---

## 📝 Archivos Modificados

### editar_limpieza.php

#### ✏️ Cambio 1: Botones (línea ~370)

**Antes:**
```html
<button onclick="guardarFormulario()">Guardar</button>
<button onclick="abrirVistaPrevia()">Imprimir/Vista Previa</button>
<button onclick="limpiarFormulario()" id="btnLimpiar" style="display:none;">...</button>
```

**Ahora:**
```html
<button onclick="guardarFormulario()">Guardar Localmente</button>
<button id="btnEnviarPHP" style="display:none;" onclick="enviarAPhpManual()">
  Enviar a Base de Datos
</button>
<button onclick="abrirVistaPrevia()">Imprimir/Vista Previa</button>
<button onclick="limpiarFormulario()" id="btnLimpiar" style="display:none;">...</button>

<!-- Nuevo indicador de estado -->
<div id="estadoSync" class="alert alert-info" style="display:none;">
  Tienes un registro pendiente de enviar a la base de datos.
</div>
```

#### ✏️ Cambio 2: Guardar Formulario (línea ~757)

**Antes:**
```javascript
mostrarAlerta('Registro guardado. Enviando a base de datos...', 'info');
// ... código ...
limpiarFormulario();
cargarContadorRegistros();

// ❌ Enviaba automáticamente
setTimeout(() => {
    exportarRegistroPHP(registroId, true, modoEdicion);
}, 500);
```

**Ahora:**
```javascript
mostrarAlerta('✓ Registro guardado localmente. Presiona "Enviar a Base de Datos" cuando estés listo.', 'success');
// ... código ...
cargarContadorRegistros();

// ✅ Solo muestra botón, NO envía automáticamente
document.getElementById('btnEnviarPHP').style.display = 'inline-block';
mostrarEstadoSync(registroId, modoEdicion);
```

#### ✏️ Cambio 3: Nueva Función `enviarAPhpManual()` (línea ~997)

```javascript
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
```

**¿Qué hace?**
- Obtiene el ID del registro guardado localmente
- Verifica que exista
- Llama a `exportarRegistroPHP()` para enviarlo a PHP

#### ✏️ Cambio 4: Nueva Función `mostrarEstadoSync()` (línea ~1009)

```javascript
// ========================= MOSTRAR ESTADO DE SINCRONIZACIÓN =========================
function mostrarEstadoSync(registroId, esEdicion) {
    const estadoDiv = document.getElementById('estadoSync');
    const textoSync = document.getElementById('textoSync');
    
    const tipo = esEdicion ? 'actualización' : 'nuevo registro';
    textoSync.textContent = `Tienes un ${tipo} pendiente de enviar a la base de datos.`;
    estadoDiv.style.display = 'block';
}
```

**¿Qué hace?**
- Muestra una alerta informando que hay datos pendientes de enviar
- Diferencia entre nuevo registro y actualización

#### ✏️ Cambio 5: Actualizar `limpiarFormulario()` (línea ~1021)

**Antes:**
```javascript
function limpiarFormulario() {
    document.getElementById('formPrincipal').reset();
    // ... código ...
    document.getElementById('btnGuardarTexto').textContent = 'Guardar';
    document.getElementById('btnLimpiar').style.display = 'none';
    // Resto del código...
}
```

**Ahora:**
```javascript
function limpiarFormulario() {
    document.getElementById('formPrincipal').reset();
    // ... código ...
    document.getElementById('btnGuardarTexto').textContent = 'Guardar Localmente';
    document.getElementById('btnLimpiar').style.display = 'none';
    document.getElementById('btnEnviarPHP').style.display = 'none';      // ← Nuevo
    document.getElementById('estadoSync').style.display = 'none';       // ← Nuevo
    // Resto del código...
}
```

#### ✏️ Cambio 6: Cargar Registro desde BD (línea ~622)

**Se agregó:**
```javascript
document.getElementById('btnEnviarPHP').style.display = 'inline-block';
mostrarEstadoSync(data.id_formato, true);
```

**¿Por qué?**
- Cuando cargas un registro existente para editarlo, aparece el botón de envío
- Así puedes enviar cambios cuando termines

#### ✏️ Cambio 7: Editar Registro (línea ~991)

**Se agregó:**
```javascript
document.getElementById('btnEnviarPHP').style.display = 'inline-block';
mostrarEstadoSync(registro.id, true);
```

**¿Por qué?**
- Mismo que arriba, permite enviar cambios después de editar

---

## 🔄 Flujo de Datos Ahora

```
┌─────────────────────────────────────────────────────────────┐
│                     FORMULARIO (Navegador)                  │
└─────────────────────────────────────────────────────────────┘
                            │
                            │ Llenar datos
                            ↓
                    ┌───────────────┐
                    │ Click Guardar │
                    └───────────────┘
                            │
                ┌───────────┴───────────┐
                ↓                       ↓
         ┌─────────────┐      ┌──────────────────┐
         │ localStorage│      │   Alerta: OK ✓   │
         │ (Datos con  │      │  Botón: Enviar   │
         │  ID: 5678)  │      │  se muestra       │
         └─────────────┘      └──────────────────┘
                                      │
                         (Usuario decide cuándo)
                                      │
                            ┌─────────────────┐
                            │ Click Enviar    │
                            │ a Base de Datos │
                            └─────────────────┘
                                      │
                 ┌────────────────────┼────────────────────┐
                 ↓                    ↓                    ↓
         ┌──────────────┐     ┌──────────────┐     ┌──────────────┐
         │ insertar.php │ O   │actualizar.php│ O   │  Error Red   │
         │ (POST datos) │     │ (POST datos) │     │ (Reintentar) │
         └──────────────┘     └──────────────┘     └──────────────┘
                 │                    │
                 └────────┬───────────┘
                          ↓
            ┌──────────────────────────┐
            │ SQL Server (BD)          │
            │ INSERT/UPDATE            │
            │ formatos_limpieza        │
            └──────────────────────────┘
                          │
                          ↓
                ┌──────────────────────┐
                │ Alerta: Enviado ✓    │
                │ Botón: Desaparece    │
                └──────────────────────┘
```

---

## 🧪 Cómo Probar

### Test 1: Guardar sin internet
1. Abre DevTools (F12) → Network → Offline
2. Llena el formulario
3. Click "Guardar Localmente"
   - ✅ Debe funcionar (aparece alerta verde)
   - ✅ Debe aparecer botón "Enviar a Base de Datos"
4. Recarga la página
   - ✅ Los datos deben seguir ahí (en localStorage)

### Test 2: Enviar a PHP
1. Restablece conexión (DevTools → Online)
2. Click "Enviar a Base de Datos"
   - ✅ Debe enviar a insertar.php o actualizar_limpieza.php
   - ✅ Debe mostrar alerta de confirmación
   - ✅ Debe aparecer en `ver_formatos.php`

### Test 3: localStorage
1. Abre DevTools (F12) → Application → Local Storage
2. Busca claves que comiencen con "ambulancia:"
3. Click en una para ver el JSON guardado
   - ✅ Deben estar todos los datos del formulario

---

## ⚙️ Archivos sin Cambios

- `insertar.php` - Sigue igual ✓
- `actualizar_limpieza.php` - Sigue igual ✓
- `conexion.php` - Sigue igual ✓
- Otras páginas - Sin cambios ✓

**Los scripts PHP NO necesitan cambios porque seguimos enviando los mismos datos por POST.**

---

## 🎓 Ventajas de Esta Arquitectura

| Ventaja | Descripción |
|---------|-------------|
| **Sin Internet** | Puedes llenar y guardar sin conexión a internet |
| **Control Total** | Tú decides cuándo enviar a la BD |
| **Reintentos** | Si falla, puedes reintentar sin perder datos |
| **Revisión Previa** | Puedes ver vista previa antes de enviar |
| **UX Mejor** | El usuario no se confunde viendo "Enviando..." |
| **Respuesta Rápida** | Guardar es instantáneo (no espera al servidor) |
| **Backup Local** | Incluso si falla PHP, los datos están en localStorage |

---

## ⚠️ Limitaciones

| Limitación | Solución |
|------------|----------|
| localStorage ~5-10MB | Limpiar registros enviados regularmente |
| Se borra al limpiar caché | Recordar enviar a PHP antes de limpiar caché |
| No sincroniza entre navegadores | Cada navegador tiene su propio localStorage |

---

## 🚀 Próximas Mejoras (Opcionales)

1. **Sincronización automática cuando haya internet**
   ```javascript
   if (navigator.onLine) {
       enviarAPhpManual();
   }
   ```

2. **Mostrar registros pendientes en el dashboard**
   ```javascript
   const pendientes = Object.keys(localStorage)
       .filter(k => k.startsWith('ambulancia:'))
       .length;
   // Mostrar badge con número
   ```

3. **Botón "Enviar Todo" para enviar múltiples registros**

4. **Indicador visual de sincronización en tiempo real**

---

## 📞 Soporte

Si tienes preguntas sobre cómo usar o modificar:
1. Ver documento `ARQUITECTURA_GUARDADO_LOCAL.md`
2. Revisar las funciones JavaScript nuevas
3. Probar con DevTools (F12) → Console

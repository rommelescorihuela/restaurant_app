# Módulos Operativos — Mesoneros y Cocina

Construido sobre la base definida en `inicial.md` y las funcionalidades de restaurante en `avanzado.md`.

> ✅ = Implementado  |  ⬜ = Pendiente

---

## 1. Módulo de Mesoneros

### 1.1 Comandas rápidas ✅
- El mesonero registra el pedido (items del menú, cantidades, modificadores) desde la mesa
- La comanda llega automáticamente a cocina (dashboard + impresión)
- La cajera ve el pedido completo al momento de cerrar la cuenta

### 1.2 Historial mesonero-mesa ✅
- Cada atención queda registrada en `table_histories` (quién atendió, qué mesa, qué pedido)
- El jefe consulta histórico por mesonero y por mesa

### 1.3 Alertas de servicio ✅
- Botón "Pedir ayuda" en cada mesa del dashboard → crea incidente + notifica a super_admin/admin
- Botón "Atender" en la alerta y en la mesa → cierra incidente y limpia estado
- Comando `php artisan mesoneros:detect-abandoned --minutes=15` para detectar mesas sin actividad
- Programado cada 5 minutos vía Schedule en `routes/console.php`
- Sección "Alertas activas" en el dashboard con todas las alertas abiertas
- Tipos: `needs_help` (morado), `table_abandoned` (rojo) en incidentes

### 1.4 Relevo y cierre de turno ✅
- `WaiterShift` registra inicio/fin del turno
- Reporte de cierre: mesas atendidas vs total asignado

### 1.5 Dashboard en vivo del jefe de mesoneros ✅
- Página Filament `/admin/mesoneros-dashboard`
- Tablero con mesas asignadas a cada mesonero, tiempo desde último pedido
- Prioridad por color: normal, sin atender (naranja), listo por entregar (rojo)
- Mesoneros activos con conteo de mesas

### 1.6 Corte individual al cierre de turno ✅
- Página `admin/waiter-shifts/{record}` con resumen completo del turno
- Cards: ventas totales, pedidos tomados, mesas atendidas, promedios
- Botón "Cerrar turno" en turnos activos → finaliza turno + libera asignaciones
- Tabla detallada de todos los pedidos del turno con montos y estados

### 1.7 Prioridad de atención (colores en dashboard) ✅
- Mesa sin pedido después de 10 min → naranja
- Pedido listo y no entregado después de 5 min → rojo

### 1.8 Zonas del salón ✅
- Modelo `Zone` con CRUD desde el panel admin
- Tablas agrupables por zona (Terraza, VIP, Barra, etc.)
- Dashboard filtra/agrupa por zona

### 1.9 Estadísticas semanales ✅
- Página `admin/estadisticas-semanales` con ranking completo
- Navegación por semana (anterior/siguiente/esta semana)
- Cards de totales: ventas, pedidos, mesas, mesoneros activos
- Ranking con barras proporcionales: ventas, pedidos, mesas atendidas, promedio por pedido

### 1.10 Nota interna del mesonero en el pedido ✅
- Campo `internal_note` en modelo `Order`
- Visible en formulario de pedido y comanda

### 1.11 Asignación automática al primer pedido ✅
- Al crear un pedido en una mesa sin asignación activa, se asigna automáticamente al mesonero
- Queda registrado en `table_histories` con acción `auto_assigned`

### 1.12 Perfil del mesonero ✅
- Modelo `WaiterProfile` con CRUD desde panel admin
- Foto, teléfono, activo/inactivo

### 1.13 Botón "Llamar mesonero" desde menú QR ✅
- Botón flotante en `/menu?table={id}` que envía POST al backend
- Crea un Incident tipo `needs_help` y setea `help_requested_at` en la mesa
- Notifica al mesonero asignado + admins vía ServiceAlert
- Feedback visual: botón dorado → spinner → check verde "Mesonero en camino"

### 1.14 Fusión y división de mesas ✅
- Columna `merged_into_id` en `tables` para agrupar mesas
- Botón "Fusionar" en cada mesa → selector de mesa destino
- Al fusionar: la mesa origen queda oculta en el dashboard, se muestra como `+ N` en la mesa destino
- Se libera la asignación del mesonero de la mesa fusionada
- Botón "Separar" en mesas con fusiones → restaura todas las mesas hijas
- Todo registrado en `table_histories`

### 1.15 Pedidos combinados entre mesoneros ✅
- Botón "Reasignar" en cada mesa del dashboard → selector de mesonero activo
- Al transferir: se cierra la asignación anterior, se crea una nueva, se registra en `table_histories` como `transferred`
- Los pedidos existentes quedan visibles por mesa (no por mesonero), el nuevo responsable los ve automáticamente

### 1.16 Tiempo promedio de atención ✅
- Calculado en Estadísticas Semanales: minutos desde que se toma el pedido hasta que se marca como servido
- Card global con promedio general de la semana
- Por mesonero en ranking: promedio, mínimo y máximo de la semana

### 1.17 Modo pausa / break ✅
- `WaiterShift.is_on_break` + `break_started_at` para tracking
- Dashboard excluye mesoneros en break del conteo activo

### 1.18 Reporte de incidencias ✅
- Modelo `Incident` con CRUD desde panel admin
- Tipos: derrame, cliente molesto, mesa dañada, otro
- Estados: abierto ↔ resuelto

---

## 2. Módulo de Cocina

### 2.1 Dashboard de comandas entrantes ✅
- Pedidos ordenados por hora de entrada con temporizador
- Color por urgencia (amarillo > 10 min, rojo > 15 min, animación parpadeo)
- Auto-refresh cada 15s cuando la pestaña está visible

### 2.2 Estados de comanda (pantalla táctil) ✅
- Botones táctiles: Preparar, Listo, Cancelar por cada item
- Al marcar "Preparar" → status `preparing`, registra `started_at`
- Al marcar "Listo" → status `ready`, registra `prepared_at`
- Si todos los items de un pedido están listos, el pedido pasa a `ready`
- Panel de cocina en `/admin/cocina` con navegación "Cocina"

### 2.3 Comanda impresa automática 🔲
- *Pendiente: requiere integración con impresora térmica (cola de impresión)*

### 2.4 Modificadores y alergias visibles ✅
- Campo `modifiers` en `OrderItem`
- Campo `notes` en `OrderItem`

### 2.5 Cancelación de items desde cocina ✅
- Botón "Cancelar" en cada item pending/preparing
- Registra `cancelled_at` + `cancel_reason`
- El pedido se actualiza si todos los items restantes están cancelados

### 2.6 Tiempo real de demora por plato ✅
- Timer en minutos desde `created_at` (pendientes) o desde `started_at` (preparando)
- Colores por nivel de demora: normal, amarillo, rojo
- Card "En demora" en resumen con items > 15 min

### 2.7 Vista agrupada por mesa vs secuencial ✅
- Toggle "Por mesa" (agrupado por pedido con header) / "Secuencial" (lista plana ordenada por prioridad + tiempo)

### 2.8 Personalización/devolución desde cocina ✅
- Campo de "Nota de cocina" inline en items en preparación
- Botón "Devolver" en items listos → input de motivo + confirmación
- Al devolver: status vuelve a `preparing`, `started_at` se reinicia, se registra `return_reason` y `returned_at`
- El pedido retrocede a `preparing` si estaba `ready`
- Badge naranja en items devueltos con motivo visible

### 2.9 Notificaciones al jefe de cocina ✅
- Notificación `KitchenAlert` vía BD a super_admin/admin
- Disparada al cancelar items, devolver items a cocina
- Comando `cocina:check-overdue --minutes=15` detecta items >15 min en preparación
- Schedule cada 5 minutos en `routes/console.php`

### 2.10 Priorización manual ✅
- Selector de prioridad (Normal / Urgente / VIP) en el footer de cada pedido del dashboard de cocina
- Ordenamiento: VIP primero, luego Urgente, luego Normal
- Badge de prioridad visible en el header del pedido
- Fondo morado para VIP, rojo para Urgente

### 2.11 Vista por estaciones de preparación ✅
- Tercer modo de visualización en el dashboard: "Por estación"
- Los items se agrupan por categoría del plato (Parrilla, Ensaladas, Postres, etc.)
- Cada estación en su propia columna/card con contador de items
- Scroll independiente por estación
- Botones Preparar/Listo inline en cada item

### 2.12 Producción lote (batch) ✅
- Cuarta vista en dashboard: "Por lote"
- Agrupa items pending/preparing por nombre de plato
- Muestra cantidad total, mesas destino, foto del plato
- Botón "Listos todos" marca todo el lote como ready
- Ordenado por cantidad descendente (más pedidos primero)

### 2.13 Registro de merma ✅
- Modelo `WasteRecord` con migración (dish_id, quantity, reason, notes, registered_by)
- CRUD Filament en navegación Cocina
- Botón "Merma" (ícono 🗑️) en cada item pending/preparing del dashboard
- Menú contextual con motivos: Sobre cocción, Error preparación, Ingrediente mal estado, Sobreproducción, Otro
- Al registrar: se crea WasteRecord + se cancela el item automáticamente
- Notifica al jefe de cocina

### 2.14 Comanda con foto del plato ✅
- Thumbnail del plato (vía Spatie MediaLibrary) en cada item del dashboard
- Visible en vista Por mesa, Secuencial y Por estación

### 2.15 Pantalla TV pública (solo lectura) ✅
- Ruta pública `/cocina/tv` sin autenticación
- Vista full-screen con fondo oscuro optimizada para TV
- Grid responsivo de pedidos con prioridad, timer y colores
- Badges de estado (pend/prep) + fotos de platos
- Auto-refresh cada 30 segundos vía JS
- Scroll suave, barra de resumen en la parte superior

---

## 3. Roles involucrados

| Rol | Acceso | Permisos Shield |
|---|---|---|
| **super_admin** | Bypass total | Todos (no requiere permisos explícitos) |
| **admin** | Admin panel completo | 147 permisos (todos los CRUD + páginas) |
| **jefe_mesoneros** | Dashboard mesoneros, estadísticas, zonas, mesas, turnos, perfiles, incidencias | View:MesonerosDashboard, View:EstadisticasSemanales, CRUD Orders/Tables/Zones/WaiterShifts/WaiterProfiles/Incidents |
| **mesonero** | Comandas básicas | ViewAny + View + Create + Update:Order |
| **jefe_cocina** | Dashboard cocina, pedidos, merma | View:CocinaDashboard, CRUD Orders + WasteRecords |
| **cocinero** | Pantalla TV/dashboard solo lectura | View:CocinaDashboard |
| **cajera** | Cierre de cuenta, visualización de pedidos | (pendiente) |

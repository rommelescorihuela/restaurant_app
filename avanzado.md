# Especificaciones del Proyecto: Sistema de Gestión para Restaurantes (SaaS)

Este documento detalla las funcionalidades específicas para el producto SaaS de gestión de restaurantes, construido sobre la base definida en `BASE_FILAMENT_SAAS.md`.

## 1. Módulo de Menú Digital
*   **Gestion de Platos:**
    *   CRUD completo de platos (nombre, descripción, precio, categoría, foto).
    *   Categorización por tipo (Entradas, Platos Fuertes, Postres, Bebidas, etc.).
    *   Control de disponibilidad (activar/desactivar un plato).
*   **Visualización Pública:** Interfaz atractiva para que los clientes vean el menú en la web o vía QR.
*   **Tecnología:** Uso de **Spatie Media Library** para la gestión de imágenes de los platos[reference:20].

## 2. Módulo de Reservas
*   **Gestión de Reservas (CRUD):** Creación, lectura, actualización y cancelación de reservas.
*   **Disponibilidad en Tiempo Real:**
    *   Sistema que muestra los horarios disponibles (06:30, 12:00, 13:00) basado en la capacidad del restaurante (ej. número de mesas).
    *   Validación para evitar reservas duplicadas o sobre-aforo.
*   **Asignación de Mesas:** El administrador puede asignar una reserva a una mesa específica.
*   **Calendario Visual:** Vista de calendario para gestionar las reservas del día, semana o mes.
*   **Gestión de Clientes:** Almacenar historial de clientes (nombre, email, teléfono, preferencias) para futuras campañas.

## 3. Panel Administrativo (Filament v5)
*   **Dashboard Principal:**
    *   Widgets con métricas clave: Número de reservas hoy, mesas ocupadas, ingresos del día (si aplica), platos más vendidos.
    *   Gráficos de ocupación (reservas por hora/día) usando **Filament Charts**[reference:21].
*   **Gestión de Usuarios y Roles:**
    *   **Súper Admin:** Control total del sistema.
    *   **Admin del Restaurante:** Gestión de su propio menú, reservas y configuración.
    *   **Recepcionista/Mesero:** Visión limitada a las reservas del día.
    *   Implementado con **Filament Shield** para una gestión visual de permisos[reference:22].
*   **Configuración del Restaurante (White-label):**
    *   Personalización de la marca: Logo, colores, nombre del restaurante.
    *   Configuración de horarios de apertura y capacidad (número de mesas por franja horaria).
    *   Implementado con un paquete de Settings para Filament, como `bambamboole/filament-settings`[reference:23].
*   **Gestión de Medios:**
    *   Biblioteca de imágenes para el menú, logos y otros recursos, con **Spatie Media Library**[reference:24].
*   **Auditoría y Registros (Logs):**
    *   Seguimiento de todas las acciones importantes (crear, editar, eliminar reservas, cambios en el menú).
    *   Visualización de logs en el panel con **Filament Activity Log**[reference:25][reference:26].
*   **Exportación de Datos:**
    *   Exportar listados de reservas, platos o clientes a Excel o CSV con **Filament Excel**[reference:27][reference:28].

## 4. Módulo de Notificaciones
*   **Confirmación de Reserva:** Envío automático de un email de confirmación al cliente.
*   **Recordatorios:** Envío de un recordatorio (email o SMS) 24 horas antes de la reserva.
*   **Alertas Internas:** Notificación en tiempo real al personal del restaurante cuando se realiza una nueva reserva.
*   **Tecnología:** Uso de **Filament Notifications** para notificaciones en el panel[reference:29] y Laravel Notifications para emails/SMS.

## 5. Módulo de Facturación y Planes (SaaS)
*   **Planes de Suscripción:** Definición de planes (ej. Básico, Pro, Empresarial) con diferentes límites de reservas, funcionalidades y soporte.
*   **Gestión de Pagos:** Integración con **Laravel Cashier** (Stripe o Paddle) para manejar suscripciones, pagos recurrentes y facturación[reference:30].
*   **Portal del Cliente:** Área donde el dueño del restaurante puede gestionar su suscripción, ver facturas y actualizar su método de pago.

## 6. Infraestructura y Despliegue (Multi-tenant)
*   **Arquitectura:** Un único código base sirviendo a múltiples clientes (restaurantes).
*   **Aislamiento de Datos:** Cada restaurante tiene sus propios datos (menú, reservas, clientes, configuraciones) aislados mediante **Spatie Multitenancy**[reference:31].
*   **Despliegue:** Automatizado con servicios como Laravel Forge, Envoyer o GitHub Actions en un servidor cloud (AWS, DigitalOcean).
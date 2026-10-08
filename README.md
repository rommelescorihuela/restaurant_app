# Restaurant SaaS — proyecto

Repositorio **paraguas** del sistema de gestión para restaurantes. Contiene la aplicación Laravel y la documentación de diseño/planificación.

> La aplicación real vive en **[`restaurant-app/`](restaurant-app/)**. Ese directorio es un repositorio Git independiente y es donde se desarrolla y se despliega el producto. Su documentación completa (funcionalidades, arquitectura, instalación y módulos) está en [`restaurant-app/README.md`](restaurant-app/README.md).

---

## ¿Qué es?

SaaS de gestión de restaurantes: menú digital por **QR**, reservas, **comandas** en tiempo real entre mesoneros y cocina, control de mesas/zonas/turnos, mermas, incidencias y estadísticas. Está pensado como **multi-tenant** (varios restaurantes sobre la misma instalación).

## Contenido del repositorio

| Ruta | Descripción |
|---|---|
| `restaurant-app/` | **Aplicación Laravel** (Laravel 13 + Filament 5). Código real. |
| `inicial.md` | Especificación base del stack Laravel + Filament v5 SaaS. |
| `avanzado.md` | Requisitos de funcionalidades del restaurante (menú, reservas, facturación, multi-tenant). |
| `modulos-operativos.md` | Módulos operativos (mesoneros, cocina), roles y flujos. |
| `AGENTS.md` | Notas para agentes/desarrolladores. |

> Los documentos de la raíz describen features **planificadas**; consulta el código de `restaurant-app/` para el estado real.

## Instalación rápida

```bash
cd restaurant-app
composer setup      # install + .env + key:generate + migrate + npm install + npm run build
# o paso a paso:
composer install
cp .env.example .env
php artisan key:generate
# configurar PostgreSQL en .env (DB_*)
php artisan migrate --seed
npm install && npm run build
php artisan storage:link
php artisan shield:generate --all --panel=admin
composer dev        # desarrollo
```

Requisitos: **PHP 8.3+**, **Composer 2**, **Node.js 20+**, **PostgreSQL**. Ver
[`restaurant-app/README.md`](restaurant-app/README.md) y
[`restaurant-app/.env.example`](restaurant-app/.env.example) para el detalle completo.

## Licencia

Software propietario. © Rommel Escorihuela.

# Auditoria de seguridad y preparacion productiva

Fecha: 2026-07-03

## Resumen ejecutivo

El proyecto esta funcionalmente amplio y ya cuenta con protecciones base importantes:

- Rutas administrativas protegidas con `auth` y middleware de rol.
- Rutas POS protegidas con `auth` y roles de administracion/vendedor.
- API v1 protegida por middleware `api.client`.
- Login con rate limiting.
- CSRF activo en rutas web.
- Headers de seguridad configurados.
- Subidas de imagen con validacion de MIME/tamano en productos.
- Auditoria en acciones clave.
- Dependencias PHP auditadas sin advisories conocidas.
- Dependencias NPM auditadas sin vulnerabilidades conocidas.

## Hallazgos corregidos

### Credenciales en plantillas

`.env.example` contenia credenciales reales de base de datos/correo. Se saneo la plantilla y se creo `.env.production.example` con placeholders seguros.

### Backups de archivos

El backup de archivos incluia `.env`. Se quito del ZIP para reducir riesgo de fuga de secretos. La recuperacion de secretos debe gestionarse fuera del backup descargable.

### Importaciones de tarifas

Las tarifas de envio importadas se guardaban en disco publico. Ahora se guardan en disco privado `local`, y el reporte de errores se descarga por ruta admin protegida.

### Headers de seguridad

Se ajusto Content Security Policy para retirar `unsafe-eval`. En produccion se agrega HSTS y `upgrade-insecure-requests`.

### Hosting compartido

Se agrego `.htaccess` en raiz para negar acceso directo a archivos/directorios privados si el hosting apunta por error a la raiz del proyecto y no a `public`.

## Hallazgos pendientes o a validar en hosting

### Entorno actual de desarrollo

`php artisan about` mostro:

- `APP_ENV=local`
- `APP_DEBUG=true`
- config/rutas/eventos sin cache

Esto es correcto en local, pero en produccion debe cambiar a:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tudominio.cl
```

### Webhooks

Los webhooks son publicos y tienen throttle. En produccion, activar solo integraciones reales con `webhook_secret` y validar firmas por proveedor antes de procesar eventos productivos.

### HTML en ficha tecnica

La ficha tecnica permite HTML limitado y remueve eventos `on*`, `style` y `javascript:`. Para una tienda publica real con contenido de muchos usuarios o proveedores, se recomienda instalar un purificador HTML robusto como HTMLPurifier.

### Logs y backups

Confirmar que:

- `storage/logs` no sea publico.
- `storage/app/private` no sea publico.
- backups no se descarguen fuera del panel admin.
- el servidor web apunte a `public`.

## Comandos ejecutados

```bash
php artisan about
php artisan route:list --except-vendor
php composer.phar audit
npm audit --audit-level=moderate
```

Resultados:

- Composer audit: sin advisories conocidas.
- NPM audit: cero vulnerabilidades.

## Recomendacion de despliegue

Usar el archivo `.env.production.example` como base del `.env` real del hosting, completar credenciales, ejecutar migraciones con `--force`, generar build de Vite y cachear configuracion/rutas/vistas.

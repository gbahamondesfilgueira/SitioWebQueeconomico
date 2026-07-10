# Arquitectura

El sistema usa Laravel, Blade, Bootstrap 5, MySQL y Vite.

Capas principales:
- Controladores HTTP.
- Servicios de dominio: inventario, precios, pedidos, caja, reportes, integraciones, backups y caché.
- Jobs para procesos pesados.
- Modelos Eloquent.
- Vistas Blade.

La lógica crítica no debe duplicarse en controladores.

# Jobs y queues

Configurar `QUEUE_CONNECTION=database`, Redis o supervisor según servidor.

Worker recomendado:
`php artisan queue:work --tries=3 --timeout=120`

Jobs preparados:
- Webhooks.
- Sincronización stock/precios.
- Importación/exportación de pedidos.
- Expiración carritos.
- Expiración reservas POS.
- Exportación de reportes.
- Limpieza de logs.
- Notificaciones.

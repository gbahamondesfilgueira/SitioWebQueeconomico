# Operación de stock regional

## Configuración de bodegas

- Debe existir una sola bodega con `is_central=true`, región `metropolitana` y al menos una ubicación activa y vendible.
- Las bodegas regionales usan tipo `branch`, una región canónica y una prioridad de despacho.
- `php artisan db:seed --class=InventorySeeder --force` crea o actualiza de forma idempotente `ECOM` y `BIOBIO`; no carga cantidades de stock.
- Merma y devoluciones deben mantenerse con `is_sellable=false`.

## Reglas de asignación

- La compra requiere una sesión autenticada y una dirección principal de despacho con región válida.
- La región se obtiene exclusivamente de la dirección principal de la cuenta; no existe un selector temporal en la tienda.
- Los invitados pueden navegar y ver precios, pero no consultan stock ni pueden crear un carrito.
- El visitante puede autorizar la geolocalización para consultar el stock físico de la bodega correspondiente a su ubicación actual. Esta vista local no incluye el fallback central y vence después de 30 minutos.
- La geolocalización nunca cambia la dirección ni la región de despacho: la reserva continúa usando la dirección principal confirmada en la cuenta.
- Las coordenadas se comparan dentro del servidor con una capa regional referencial de la Biblioteca del Congreso Nacional de Chile (`resources/data/chile-regions.geojson`) y no se almacenan en la sesión ni se envían a un geocodificador externo.
- El navegador sólo permite geolocalización en HTTPS (o `localhost`) y siempre requiere consentimiento del usuario.
- El carrito reserva una sola fuente exacta por producto: bodega, ubicación, producto y variante.
- Para una región se intenta primero su bodega regional. Si no alcanza toda la cantidad, se intenta la central.
- No se suman ubicaciones para aparentar disponibilidad.
- Un pack completo sale desde una sola bodega.
- Biobío local informa 1 a 3 días hábiles; si usa la central informa 1 a 5 días hábiles.
- Si un carrito usa más de una bodega, la cotización suma un paquete por origen y no ofrece retiro en tienda.

## Conciliación y alertas

El scheduler ejecuta cada hora:

```bash
php artisan inventory:reconcile
```

El comando compara `stock_levels.reserved_stock` con las reservas activas de la misma fuente. El resultado queda en salud del sistema y las inconsistencias aparecen en el dashboard.

Después de revisar la alerta, se pueden corregir contadores y liberar reservas vencidas:

```bash
php artisan inventory:reconcile --fix --release-expired
```

## Prueba de concurrencia MySQL

La prueba real de bloqueos está desactivada por defecto y sólo se ejecuta contra una base MySQL cuyo nombre incluya `test`:

```bash
RUN_MYSQL_CONCURRENCY_TESTS=true php artisan test --filter=MySqlStockConcurrencyTest
```

La prueba inicia dos procesos que intentan reservar la última unidad y exige que sólo uno tenga éxito.

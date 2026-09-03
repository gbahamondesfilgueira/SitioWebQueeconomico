# Deploy producción

Checklist rápido:
1. Subir código.
2. Configurar `.env` seguro.
3. `composer install --no-dev --optimize-autoloader`
4. `php artisan migrate --force`
5. `php artisan storage:link`
6. `npm ci && npm run build`
7. `php artisan system:optimize-production`
8. Configurar cron y queue worker.
9. Validar `php artisan system:health-check`.
10. Ejecutar `php artisan db:seed --class=InventorySeeder --force` para asegurar las estructuras `ECOM` y `BIOBIO` sin alterar cantidades.
11. Validar `php artisan inventory:reconcile` y revisar que no existan alertas de stock.

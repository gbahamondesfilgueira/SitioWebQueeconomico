# Base de datos

Usar migraciones para todo cambio estructural.

Tablas críticas:
- `users`, `roles`, `permissions`
- `products`, `product_variants`
- `stock_levels`, `stock_movements`
- `orders`, `order_items`
- `cash_register_sessions`, `cash_movements`
- `integrations`, `integration_logs`
- `audit_logs`

Fase 15 agrega índices operativos y preparación para `companies`, `branches` y `currencies`.

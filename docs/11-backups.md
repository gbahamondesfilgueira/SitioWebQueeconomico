# Backups

Backups se guardan en `storage/app/backups`.

Comando:
`php artisan system:backup-database`

Recomendaciones:
- Copiar backups fuera del servidor.
- Retención mínima: 30 días.
- Validar restauración periódicamente.
- No exponer `storage/app/backups` vía web.

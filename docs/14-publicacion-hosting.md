# Publicacion en hosting de produccion

## Requisitos minimos

- PHP 8.2 o superior.
- Extensiones PHP comunes de Laravel: `openssl`, `pdo_mysql`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`, `curl`, `zip`.
- MySQL/MariaDB.
- Composer disponible localmente o en hosting.
- Node.js 20+ para compilar assets, o subir `public/build` ya compilado.
- Cron disponible para `php artisan schedule:run`.

## Estructura recomendada

La raiz publica del hosting debe apuntar a:

```text
/ruta-del-proyecto/public
```

Si el hosting compartido no permite cambiar document root, se agrego un `.htaccess` en la raiz que bloquea carpetas privadas y redirige a `public`. Aun asi, la opcion segura sigue siendo apuntar el dominio directamente a `public`.

## Preparar `.env`

Copiar:

```bash
cp .env.production.example .env
```

Completar:

```env
APP_KEY=
APP_URL=https://tudominio.cl
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
MAIL_HOST=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS=
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
```

Generar clave si esta vacia:

```bash
php artisan key:generate --force
```

## Instalacion

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

Si el hosting no permite Node.js, ejecutar `npm ci && npm run build` localmente y subir `public/build`.

## Permisos

Dar escritura solo a:

```text
storage
bootstrap/cache
```

No dar escritura publica a todo el proyecto.

## Cron

Configurar cada minuto:

```bash
* * * * * cd /ruta-del-proyecto && php artisan schedule:run >> /dev/null 2>&1
```

Si no hay scheduler configurado para todos los comandos, programar manualmente:

```bash
php artisan system:clear-expired-carts
php artisan system:clear-expired-reservations
php artisan system:cleanup-logs
php artisan system:backup-database
```

## Queue worker

Para hosting con supervisor:

```bash
php artisan queue:work --tries=3 --timeout=90
```

En hosting compartido sin supervisor, usar cron cada minuto:

```bash
php artisan queue:work --stop-when-empty --tries=3 --timeout=90
```

## Verificacion final

```bash
php artisan about
php artisan system:security-check
php artisan test
```

Validar manualmente:

- Home carga por HTTPS.
- Login funciona.
- Panel admin exige autenticacion.
- POS exige rol.
- API rechaza requests sin token.
- Storage muestra imagenes publicas, pero no expone backups/importaciones privadas.
- `APP_DEBUG=false`.

## Instalacion automatizada cPanel

En este hosting se esta usando:

```text
Laravel completo: /home/webstati/sitiowebqueeconomico
Public del dominio: /home/webstati/queeconomico.queeconomico.cl
```

Primero entra a la raiz Laravel:

```bash
cd /home/webstati/sitiowebqueeconomico
```

Si la base ya existe, por ejemplo `webstati_queeconomico`, ejecuta:

```bash
php artisan app:install-production \
  --public-path=/home/webstati/queeconomico.queeconomico.cl \
  --admin-email=gbahamondesfilgueira@gmail.com \
  --admin-password='Guillermo#1712' \
  --seed \
  --force
```

Si quieres que el instalador intente crear la base de datos automaticamente, agrega `--create-database`:

```bash
php artisan app:install-production \
  --create-database \
  --db-admin-user=webstati_nameuser \
  --public-path=/home/webstati/queeconomico.queeconomico.cl \
  --admin-email=gbahamondesfilgueira@gmail.com \
  --admin-password='Guillermo#1712' \
  --seed \
  --force
```

El comando pedira el password MySQL solo si lo pasas en `.env` o como opcion. Por seguridad, en cPanel es preferible dejar `DB_PASSWORD` correcto en `.env` y no escribir passwords en la linea de comandos.

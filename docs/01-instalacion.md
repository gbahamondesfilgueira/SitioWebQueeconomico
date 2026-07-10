# Instalación

Requisitos: PHP 8.x, Composer, MySQL, Node.js, npm y extensión PHP para MySQL/ZIP.

Pasos:
1. Configurar `.env`.
2. Ejecutar `composer install`.
3. Ejecutar `npm install`.
4. Ejecutar `php artisan key:generate`.
5. Ejecutar `php artisan migrate --seed`.
6. Ejecutar `php artisan storage:link`.
7. Ejecutar `npm run build`.

Usuario base: `admin.qe@erp.local` / `AdminQE1234`.

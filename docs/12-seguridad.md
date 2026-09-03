# Seguridad

Medidas base:
- Auth y middleware por rol.
- Permisos granulares preparados.
- CSRF en formularios.
- API por token.
- Headers de seguridad.
- Credenciales cifradas.
- Backups fuera de `public`.
- `APP_DEBUG=false` en producción.
- Bloqueo temporal por intentos fallidos.

## Variables de entorno

El archivo `.env` no debe versionarse. Si alguna versión anterior fue publicada en un repositorio remoto, coordinar una ventana para:

1. Rotar `APP_KEY` considerando previamente los campos cifrados y las sesiones activas.
2. Rotar credenciales de base de datos, correo, Google y cualquier integración presente en versiones antiguas.
3. Reescribir el historial con una herramienta como `git filter-repo` y forzar la actualización del remoto.
4. Pedir a todos los colaboradores que vuelvan a clonar el repositorio.

La reescritura del historial no debe ejecutarse como parte de un deploy normal.

# Despliegue a producción — KAMJuridica

Stack: **Vue 3 + Vite** (SPA compilada) servida por **Laravel** (API REST + Sanctum) con **MySQL**.

> **Enfoque elegido (Opción A):** compilar el frontend en tu PC y subir el proyecto
> ya construido al hosting por File Manager / FTP. GitHub se usa como repositorio
> de código (respaldo y versiones), no como mecanismo de deploy.

---

## 1. Requisitos del hosting (ColombiaHosting / cPanel)

- **PHP 8.3 o superior** (Laravel 13 lo exige). En cPanel se selecciona en *"Select PHP Version"*.
- **MySQL**.
- Poder **cambiar el document root** del dominio para que apunte a la carpeta `public/`.
- Extensiones PHP: `pdo_mysql`, `mbstring`, `openssl`, `gd`, `fileinfo`, `zip`, `xml`.

---

## 2. Preparar el proyecto en tu PC

Desde la carpeta `backend/`:

```bash
# Dependencias de PHP (genera /vendor)
composer install --no-dev

# Generar una APP_KEY y copiarla (NO modifica el .env local)
php artisan key:generate --show
```

Desde la carpeta `frontend/`:

```bash
npm install
npm run build
```

Esto genera `backend/public/index.html` y `backend/public/assets/*`.

> Antes de un build nuevo conviene limpiar `backend/public/assets` para no acumular
> archivos con hash antiguos (el `emptyOutDir` está en `false`).

---

## 3. Subir al hosting (File Manager / FTP)

Lo que se sube es el **contenido de la carpeta `backend/`** (la carpeta `frontend/`
se queda en tu PC y en GitHub, solo sirve para compilar).

Estructura recomendada en el servidor:

```
/home/tu_usuario/kamjuridica/     <- contenido de backend/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/          <- aquí debe apuntar el document root del dominio
├── routes/
├── storage/
├── vendor/
├── .env             <- lo creas en el paso 4
├── artisan
└── composer.json
```

1. Crea la carpeta `kamjuridica` en el **home** de tu usuario (fuera de `public_html`).
2. Sube ahí el contenido de `backend/` (incluye `vendor/`, `public/index.html` y `public/assets/`).
3. En cPanel → *Domains*, cambia el **document root** del dominio a `/home/tu_usuario/kamjuridica/public`.
   - Si tu plan no permite cambiarlo, pídeselo a soporte o usa un subdominio con ese document root.

---

## 4. Configurar el `.env`

1. Sube `backend/.env.example` a `/home/tu_usuario/kamjuridica/` y renómbralo a `.env`.
2. Edítalo (con el editor del File Manager) y rellena:

| Variable | Valor |
| --- | --- |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_URL` | `https://tu-dominio.com` |
| `APP_KEY` | la que generaste en el paso 2 (`base64:...`) |
| `DB_DATABASE` | nombre de la base creada en cPanel |
| `DB_USERNAME` | usuario de la base |
| `DB_PASSWORD` | contraseña de la base |
| `SESSION_SECURE_COOKIE` | `true` (con HTTPS) |
| `MAIL_*` | datos SMTP de tu correo |
| `SANCTUM_STATEFUL_DOMAINS` | `tu-dominio.com` (si usas login por cookies) |

Nunca subas el `.env` a GitHub (ya está en `.gitignore`).

---

## 5. Base de datos (sin SSH)

La forma más simple es exportar tu base local (ya tiene migraciones y datos de prueba) e importarla:

1. En tu PC: `mysqldump -u root kamjuridica > kamjuridica.sql`.
2. En cPanel → *MySQL Databases*, crea la base y un usuario con contraseña fuerte.
3. En cPanel → *phpMyAdmin*, importa `kamjuridica.sql` en esa base.

> La contraseña del usuario administrador queda con la que usabas en desarrollo.
> Cámbiala tras el primer login (o actualiza el hash en la tabla `users`).

Si tu plan tiene **Terminal** en cPanel, puedes en su lugar ejecutar:
`php artisan migrate --force` y `php artisan db:seed --force`.

---

## 6. Enlazar `storage` (para las imágenes)

Las imágenes subidas desde el panel van a `storage/app/public` y se sirven en `/storage`.
Ese enlace (`public/storage`) es un symlink que normalmente se crea con `php artisan storage:link`.

Sin SSH tienes dos opciones:

- **Terminal de cPanel** (si existe): `php artisan storage:link`.
- **Script temporal** (si no hay terminal): crea `public/link.php` con:

  ```php
  <?php symlink('../storage/app/public', __DIR__ . '/storage');
  ```

  Visítalo una vez desde el navegador y luego **bórralo**.

---

## 7. PHP 8.3, dominio y HTTPS en cPanel

1. *Select PHP Version* → elige **8.3** y activa las extensiones del punto 1.
2. Apunta el dominio (registro A) al servidor.
3. Activa **SSL** (AutoSSL / Let's Encrypt desde cPanel).
4. Descomenta la línea de HSTS en `public/.htaccess`:
   ```apache
   Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
   ```

---

## 8. Backups

Para hosting compartido (sin SSH) usa el script PHP: [scripts/backup.php](scripts/backup.php).
Hace volcado SQL con PDO (sin depender de `mysqldump`), copia las imágenes y el `.env`, y rota los antiguos.

Programarlo en cPanel → *Cron Jobs* (diario a las 02:30):

```
30 2 * * * /usr/local/bin/ea-php83 /home/tu_usuario/kamjuridica/scripts/backup.php >> /home/tu_usuario/kamjuridica/storage/logs/backup.log 2>&1
```

Ajusta la ruta del binario (`ea-php83` = PHP 8.3) y la ruta del proyecto.

Complemento recomendado: descargar periódicamente un **backup completo de cPanel**
(*Backup* → descarga completa) que incluye base de datos, archivos y correos.

---

## 9. Verificación final

- [ ] `APP_DEBUG=false` y sin stack traces visibles en `/api/`.
- [ ] El sitio carga por HTTPS y redirige desde HTTP.
- [ ] `robots.txt` bloquea `/api/` y `/admin`.
- [ ] Formulario de contacto funciona con el SMTP real.
- [ ] Imágenes subidas se sirven en `/storage`.
- [ ] Backup programado en cron y probado (restaurar uno de prueba).

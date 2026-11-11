# San Plácido: e-commerce y gestión para carpintería

## Tecnologías

| Capa | Herramientas |
|---|---|
| Backend | PHP 8, PDO, MVC propio |
| Base de datos | MySQL / MariaDB |
| Frontend | HTML, CSS, JavaScript, Chart.js |
| Pagos | Mercado Pago SDK (`mercadopago/dx-php`) |
| Correo | PHPMailer + SMTP de Brevo |
| IA | API de Groq (compatible con OpenAI) |
| Reportes | PhpSpreadsheet |
| Servidor | Apache con `mod_rewrite` |

## Arquitectura

```
index.php ──► Core::run()
               ├─ carga app/config/core_config.php (+ secrets.php)
               ├─ registra el Autoloader
               └─ /{controlador}/{método}/{parámetros}
                       │
                       ▼
          app/controllers/XController ──► app/models/XModel ──► Db (PDO)
                       │
                       ▼
          templates/views/{controlador}/{vista}.php
```

```
app/
├── classes/      Núcleo: Core, Controller, Model, View, Db, Autoloader, Mailer…
├── config/       core_config.php y secrets.php (vacío, ver Instalación)
├── controllers/  Un controlador por sección (28)
├── functions/    Helpers y reglas de permisos por rol
├── models/       Acceso a datos con SQL explícito (27)
└── servicios/    Integración con Groq, moderación de reseñas, consumo de stock
templates/
├── assets/       CSS, JS, imágenes
├── includes/     Header, footer, sidebar, topbar
└── views/        Vistas por controlador
cron/             Tareas programadas
database/         Esquema SQL y datos de catálogo
collect.php       Endpoint de analítica (vistas, búsquedas, eventos)
```

## Instalación local

**Requisitos:** PHP 8.0 o superior, MySQL/MariaDB, Apache con `mod_rewrite` (XAMPP sirve) y [Composer](https://getcomposer.org/).

1. **Clonar el repositorio** dentro de `htdocs`:
   ```bash
   git clone  SanPlacido
   cd SanPlacido
   ```
2. **Instalar dependencias:**
   ```bash
   composer install
   ```
3. **Crear la base de datos** e importar el esquema y los catálogos:
   ```bash
   mysql -u root -e "CREATE DATABASE sanplacido CHARACTER SET utf8mb4"
   mysql -u root sanplacido < database/schema.sql
   mysql -u root sanplacido < database/seed_catalogos.sql
   ```
4. **Configurar las credenciales.** El repositorio trae `app/config/secrets.php` con los campos vacíos. Antes de completarlo, hay que decirle a git que ignore los cambios de ese archivo, así las claves de cada uno no terminan en el repositorio:
   ```bash
   git update-index --skip-worktree app/config/secrets.php
   ```
   Después se completan los valores. El archivo tiene esta forma:
   ```php
   <?php
   return [
       // Base de datos local (XAMPP)
       'db_local' => [
           'host' => 'localhost:3307',
           'name' => 'sanplacido',
           'user' => 'root',
           'pass' => '',
       ],
       // Base de datos de producción (datos del panel del hosting)
       'db_prod' => [
           'host' => '',
           'name' => '',
           'user' => '',
           'pass' => '',
       ],
       // Clave de Groq: https://console.groq.com/keys
       'groq_api_key' => '',
       // Clave que manda el cron externo para ejecutar cron/tareas_programadas.php
       'cron_secret'  => '',
       // Credenciales de Mercado Pago (usar las de prueba)
       'mercadopago'  => [
           'test_public_key'   => '',
           'test_access_token' => '',
           'prod_public_key'   => '',
           'prod_access_token' => '',
           'webhook_secret'    => '',
       ],
       // Correo SMTP de Brevo: https://app.brevo.com/settings/keys/smtp
       'smtp' => [
           'host' => 'smtp-relay.brevo.com',
           'port' => 587,
           'user' => '',
           'pass' => '',
           'from' => '',
       ],
   ];
   ```
5. Si la carpeta no se llama `SanPlacido`, ajustar `BASEPATH` y `URL` en `app/config/core_config.php`.
6. Abrir `http://localhost/SanPlacido/`.

## Seguridad

- Las credenciales **no están en el repositorio**: `secrets.php` se sube vacío (ver el paso 4 de la instalación).
- Todas las consultas usan sentencias preparadas de PDO.
- Las contraseñas se guardan con `password_hash()` y los códigos de verificación vencen.
- El `.htaccess` bloquea el acceso web a `app/`, `vendor/`, `database/`, los volcados `.sql`, los archivos de configuración y los scripts de prueba.
- Las rutas del panel validan la sesión y los permisos por rol (`app/functions/permisos.php`).
- Las visitas del personal no se cuentan en las métricas de tráfico.

## Autores

Gino Romano Tomas Ruffino Martina Parejo Cristopher Gigena Valdez Matias Iralde.
# Laravel 11 con una Imagen Docker de PHP

Un repositorio de demostración para desplegar una aplicación PHP de Laravel en [Render](https://render.com) usando Docker. Puedes seguir el tutorial de inicio [aquí](https://render.com/docs/deploy-php-laravel-docker).

## Despliegue

1. [Crea](https://dashboard.render.com/new/database) una nueva base de datos PostgreSQL en Render y copia la URL interna de la base de datos para usarla a continuación.

2. [Crea](https://dashboard.render.com/new/redis) un nuevo servicio Redis en Render y copia la URL interna del servicio para usarla a continuación.

3. Haz un fork de este repositorio en tu propia cuenta de GitHub.

4. Crea un nuevo **Servicio Web** en Render y da permiso a la aplicación de GitHub de Render para acceder a tu nuevo repositorio.

5. Selecciona `Docker` para el entorno y agrega las siguientes variables de entorno en la sección *Avanzada*:

   | Clave           | Valor           |
   | --------------- | --------------- |
   | `APP_KEY`       | Copia el resultado de `php artisan key:generate --show` |
   | `DATABASE_URL`  | La **URL interna de la base de datos** PostgreSQL que creaste anteriormente. |
   | `DB_CONNECTION` | `pgsql` |
   | `REDIS_URL`     | La **URL interna del servicio Redis** que creaste anteriormente. |

¡Eso es todo! Tu aplicación Laravel 11 estará en vivo en tu URL de Render tan pronto como termine la compilación. Puedes probarla registrándote e iniciando sesión.
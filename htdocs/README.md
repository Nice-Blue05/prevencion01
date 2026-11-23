# 🛡️ Voz Segura

Canal digital seguro para **reportar violencia** y acceder a **apoyo psicológico y legal**.

## 👥 Equipo
| Nombre | Rol |
|---|---|
| _Completar_ | _Completar_ |

## 🎯 Problema
Muchas víctimas de violencia no denuncian por miedo, vergüenza o desconocimiento de los servicios disponibles. Necesitan un canal confidencial, accesible desde el celular y sin trámites.

## 💡 Solución
Aplicación web responsive que permite:
- Reportar un caso de forma **anónima** o con datos de contacto.
- Solicitar **apoyo psicológico** y/o **orientación legal**.
- Recibir un **código de seguimiento** y consultar el estado del caso sin crear cuenta.
- Recibir **correos de confirmación y actualización** (API de Brevo).
- **Botón de salida rápida** para proteger a la persona usuaria.
- **Panel del equipo** para revisar reportes, cambiar su estado y derivar casos.

## 🌍 ODS
**ODS 5 – Igualdad de género**, meta 5.2: eliminar todas las formas de violencia contra mujeres y niñas.

## 🛠️ Tecnologías
PHP 8 · MySQL/MariaDB (PDO con consultas preparadas) · HTML5 + CSS3 responsive · JavaScript · API de Brevo (correo transaccional) · XAMPP

## ⚙️ Instalación (XAMPP)
1. Copia la carpeta del proyecto en `C:\xampp\htdocs\voz-segura`.
2. Abre XAMPP e inicia **Apache** y **MySQL**.
3. En `http://localhost/phpmyadmin` importa `schema.sql` (crea la base `voz_segura` y la tabla `reportes`).
4. Copia `config.example.php` como `config.php` y completa:
   - Datos de MySQL (por defecto `root` sin contraseña).
   - `BREVO_API_KEY`, `BREVO_SENDER_EMAIL` (remitente verificado en Brevo) y `NOTIFY_EMAIL`.
   - `ADMIN_PASSWORD` para el panel.
5. Verifica que `extension=curl` esté activa en `php.ini` (XAMPP → Apache → Config → PHP).
6. Abre `http://localhost/voz-segura/`.

> Si el envío de correos falla por un error SSL en Windows, descarga `cacert.pem` y configúralo en `curl.cainfo` de `php.ini`. Solo para pruebas locales puedes poner `BREVO_VERIFY_SSL` en `false`.

## ▶️ Uso
- **Usuaria/o:** `index.php` → *Reportar* → recibe un código → *Seguimiento* para ver el estado.
- **Equipo:** `admin.php` (contraseña definida en `config.php`) → ver reportes y cambiar estado.

## 🔒 Privacidad y seguridad
- No se guarda la IP ni datos del dispositivo.
- Consultas preparadas (PDO), tokens CSRF y escape de salida contra XSS.
- Los correos nunca incluyen el contenido del reporte, solo el código.
- `config.php` (claves) está en `.gitignore`.

## 📁 Estructura
```
index.php  reportar.php  gracias.php  seguimiento.php  apoyo.php  admin.php
config.example.php  schema.sql
partials/ (header, footer)   assets/ (style.css, app.js, img/)
```

## 📈 Impacto esperado
Reducir barreras para denunciar, acelerar la derivación a apoyo psicológico y legal, y generar datos agregados por zona para orientar la prevención.

# prevencion01
Voz Segura 🛡️
Voz Segura es una plataforma web desarrollada en PHP orientada a la recepción, gestión y seguimiento confidencial o anónimo de reportes y denuncias. El proyecto está alineado con el Objetivo de Desarrollo Sostenible 5 (ODS 5: Igualdad de Género) de la ONU, ofreciendo un espacio seguro de canalización, escucha y apoyo a usuarios en situaciones de vulnerabilidad o violencia.
________________


🚀 Características Principales
* Envío de Reportes: Formulario seguro para registrar denuncias o solicitudes de ayuda (reportar.php).
* Seguimiento en Tiempo Real: Consulta del estado de cada reporte mediante un código o token único (seguimiento.php).
* Red de Apoyo: Sección con líneas de ayuda, guías e información de soporte (apoyo.php).
* Panel de Administración: Gestión, clasificación y cambio de estado de los casos por parte de administradores (admin.php).
* Arquitectura Ligera: Construida con PHP modular, CSS personalizado y JavaScript vainilla.
* Confirmación de Envío: Flujo claro para otorgar al usuario su código de seguimiento (gracias.php).
________________


📂 Estructura del Proyecto
voz-segura/
├── assets/
│   ├── img/
│   │   ├── favicon.png      # Icono del sitio
│   │   ├── hero.png         # Ilustración principal / Banner
│   │   ├── logo.png         # Logotipo del proyecto
│   │   └── ods5.png         # Distintivo del ODS 5 (Igualdad de Género)
│   ├── app.js               # Lógica interactiva del cliente
│   └── style.css            # Hojas de estilo globales
├── partials/
│   ├── header.php           # Encabezado modular y navegación
│   └── footer.php           # Pie de página común
├── admin.php                # Panel de administración para gestión de casos
├── apoyo.php                # Página con información de soporte y ayuda
├── config.example.php       # Plantilla de configuración (variables globales y DB)
├── config.php               # Archivo de configuración local (no incluir en Git)
├── gracias.php              # Confirmación tras la recepción del reporte
├── index.php                # Página de inicio / landing page
├── reportar.php             # Formulario de registro de reportes
├── schema.sql               # Estructura e inicialización de la base de datos
├── seguimiento.php          # Módulo de consulta de estado por folio/token
├── .gitignore               # Archivos omitidos por Git
└── README.md                # Documentación del proyecto

________________
🛠️ Requisitos del Sistema
* Servidor Web: Apache 2.4+ / Nginx
* PHP: Versión 7.4 o superior (con extensión pdo_mysql habilitada)
* Base de Datos: MySQL 5.7+ / MariaDB 10.3+
________________


⚙️ Instalación y Configuración
1. Clonar o extraer el repositorio: Ubica la carpeta voz-segura en el directorio público de tu servidor web (ej. htdocs en XAMPP o /var/www/html/ en Linux).
2. Crear la Base de Datos:
   * Abre tu gestor de base de datos (phpMyAdmin, MySQL CLI, DBeaver, etc.).
   * Crea una base de datos nueva (ej. voz_segura).
   * Importa el archivo schema.sql para crear las tablas necesarias:
mysql -u tu_usuario -p voz_segura < schema.sql
3. Configurar Variables de Entorno:
   * Duplica el archivo config.example.php y renómbralo a config.php:
cp config.example.php config.php
   * Abre config.php en tu editor de código y define los credenciales de la base de datos y la URL base:
define('DB_HOST', 'localhost');
define('DB_NAME', 'voz_segura');
define('DB_USER', 'tu_usuario');
define('DB_PASS', 'tu_contraseña');
define('BASE_URL', 'http://localhost/voz-segura/');
4. Iniciar la Aplicación: Abre tu navegador y navega hacia http://localhost/voz-segura/.
________________


🔒 Seguridad e Integridad
* El archivo config.php contiene credenciales sensibles y está incluido en .gitignore para evitar su publicación en repositorios públicos.
* Asegúrate de configurar contraseñas seguras para las cuentas administradoras y de activar HTTPS en el entorno de producción para garantizar el cifrado del tráfico.
________________


🌐 Impacto Social (ODS 5)
Este software ha sido concebido para contribuir activamente a la eliminación de la violencia y discriminación, facilitando canales accesibles, anónimos y directos para el reporte e intervención rápida.
________________


📝 Licencia
Este proyecto se distribuye bajo la licencia MIT (o la licencia que aplique a tu organización). Consulta el archivo de licencia para más información.

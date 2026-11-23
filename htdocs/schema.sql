-- Voz Segura · esquema de base de datos (MySQL / MariaDB en XAMPP)
-- Impórtalo desde phpMyAdmin (pestaña "Importar") o adapta tus propias tablas.
CREATE DATABASE IF NOT EXISTS voz_segura CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE voz_segura;

CREATE TABLE IF NOT EXISTS reportes (
  id                 INT AUTO_INCREMENT PRIMARY KEY,
  codigo             VARCHAR(12)  NOT NULL UNIQUE,
  tipo_violencia     VARCHAR(40)  NOT NULL,
  descripcion        TEXT         NOT NULL,
  fecha_hecho        DATE         NULL,
  distrito           VARCHAR(100) NOT NULL,
  anonimo            TINYINT(1)   NOT NULL DEFAULT 1,
  nombre             VARCHAR(100) NULL,
  email              VARCHAR(150) NULL,
  telefono           VARCHAR(20)  NULL,
  apoyo_psicologico  TINYINT(1)   NOT NULL DEFAULT 0,
  apoyo_legal        TINYINT(1)   NOT NULL DEFAULT 0,
  estado             ENUM('recibido','en_revision','derivado','cerrado') NOT NULL DEFAULT 'recibido',
  creado_en          TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

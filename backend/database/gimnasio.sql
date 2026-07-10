-- ============================================================
-- FORJA GYM — Script de base de datos MySQL
-- Importar en phpMyAdmin o con: mysql -u root -p < gimnasio.sql
--
-- El sitio usa UN SOLO formulario público (planes.html), por lo
-- que toda la información del cliente vive en una única tabla:
-- `socios`. La tabla `administradores` es para el panel privado.
-- ============================================================

CREATE DATABASE IF NOT EXISTS forja_gym
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE forja_gym;

-- ------------------------------------------------------------
-- Tabla: socios  (único formulario del sitio, en planes.html)
-- Reúne datos personales + preferencias de clase/entrenador.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS socios (
  id_socio            INT AUTO_INCREMENT PRIMARY KEY,

  -- Datos personales
  nombre              VARCHAR(80)  NOT NULL,
  apellido            VARCHAR(80)  NOT NULL,
  email               VARCHAR(120) NOT NULL,
  telefono            VARCHAR(30)  NOT NULL,
  fecha_nacimiento    DATE         NOT NULL,
  direccion           VARCHAR(160),
  genero              VARCHAR(20),

  -- Membresía
  plan                VARCHAR(20)  NOT NULL,        -- Basico | Forja | Elite
  nivel_experiencia   VARCHAR(20)  DEFAULT 'Principiante',
  objetivo            VARCHAR(160) NOT NULL,

  -- Preferencias (opcionales)
  clase_interes       VARCHAR(40),                  -- Funcional | Spinning | Fuerza guiada | Movilidad | Boxeo | Yoga
  entrenador_interes  VARCHAR(80),                   -- Diego Ramírez | Valentina Cruz | Julián Ortiz
  horario_preferido   VARCHAR(20)  DEFAULT 'Mañana',

  mensaje             TEXT,
  fecha_registro      DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabla: administradores  (login del panel admin/)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS administradores (
  id_admin    INT AUTO_INCREMENT PRIMARY KEY,
  nombre      VARCHAR(80)  NOT NULL,
  usuario     VARCHAR(40)  NOT NULL UNIQUE,
  password    VARCHAR(255) NOT NULL,   -- hash generado con password_hash()
  fecha_alta  DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Administrador de ejemplo:
--   usuario:    admin
--   contraseña: forja2026
-- (cámbiala en producción; el hash ya está listo para password_verify())
INSERT INTO administradores (nombre, usuario, password) VALUES
('Administrador Forja', 'admin', '$2b$10$.DzjKMvwKm.U/uoo5WAWpO7AyHqbxYPN57RV7FoSgHZmTBOVyncsG')
ON DUPLICATE KEY UPDATE usuario = usuario;

-- ============================================================
-- FORJA GYM — Script de base de datos PostgreSQL (para Render)
--
-- Es el equivalente de backend/database/gimnasio.sql pero en
-- sintaxis PostgreSQL (Render solo ofrece PostgreSQL gratis, no
-- MySQL). No necesitas correr esto a mano: basta con visitar
-- una vez, después del primer deploy, la URL:
--   https://tu-app.onrender.com/backend/setup/inicializar.php
-- y este mismo contenido se ejecuta automáticamente.
--
-- Si prefieres correrlo tú mismo (con psql o el "Connect" de
-- Render), también puedes usar este archivo directamente.
-- ============================================================

-- ------------------------------------------------------------
-- Tabla: socios  (único formulario del sitio, en planes.html)
-- Reúne datos personales + preferencias de clase/entrenador.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS socios (
  id_socio            SERIAL PRIMARY KEY,

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
  fecha_registro      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------------------------------
-- Tabla: administradores  (login del panel admin/)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS administradores (
  id_admin    SERIAL PRIMARY KEY,
  nombre      VARCHAR(80)  NOT NULL,
  usuario     VARCHAR(40)  NOT NULL UNIQUE,
  password    VARCHAR(255) NOT NULL,   -- hash generado con password_hash()
  fecha_alta  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Administrador de ejemplo:
--   usuario:    admin
--   contraseña: forja2026
-- (cámbiala en producción; el hash ya está listo para password_verify(),
--  es el mismo hash bcrypt que en gimnasio.sql — PHP verifica $2a$/$2b$/$2y$ por igual)
INSERT INTO administradores (nombre, usuario, password)
VALUES ('Administrador Forja', 'admin', '$2b$10$.DzjKMvwKm.U/uoo5WAWpO7AyHqbxYPN57RV7FoSgHZmTBOVyncsG')
ON CONFLICT (usuario) DO NOTHING;

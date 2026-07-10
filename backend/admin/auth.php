<?php
/* ============================================================
   FORJA GYM — admin/auth.php
   Se incluye al inicio de cada página protegida del panel.
   Si no hay sesión activa, redirige al login.
   ============================================================ */
session_start();

if (empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

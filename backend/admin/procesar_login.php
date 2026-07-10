<?php
/* ============================================================
   FORJA GYM — admin/procesar_login.php
   Valida usuario/contraseña contra la tabla `administradores`
   usando password_verify() (hash bcrypt) e inicia sesión.
   ============================================================ */
session_start();
require_once '../api/conexion.php';

$usuario   = trim($_POST['usuario'] ?? '');
$password  = trim($_POST['password'] ?? '');

if (!$usuario || !$password) {
    header('Location: login.php?error=' . urlencode('Ingresa usuario y contraseña.'));
    exit;
}

$stmt = $conexion->prepare("SELECT id_admin, nombre, password FROM administradores WHERE usuario = :usuario LIMIT 1");
$stmt->execute([':usuario' => $usuario]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if ($admin && password_verify($password, $admin['password'])) {
    $_SESSION['admin_id']     = $admin['id_admin'];
    $_SESSION['admin_nombre'] = $admin['nombre'];
    header('Location: panel.php');
    exit;
}

header('Location: login.php?error=' . urlencode('Usuario o contraseña incorrectos.'));
exit;

<?php
session_start();
if (!empty($_SESSION['admin_id'])) {
    header('Location: panel.php');
    exit;
}
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Acceso administrador — Forja Gym</title>
<link rel="stylesheet" href="../../assets/css/style.css">
<link rel="stylesheet" href="css/admin.css">
</head>
<body>

<div class="login-wrap">
  <div class="login-box">
    <div class="brand">FORJA<span>GYM</span></div>
    <span class="eyebrow">Panel de administración</span>

    <form method="POST" action="procesar_login.php">
      <div class="field">
        <label for="usuario">Usuario *</label>
        <input type="text" id="usuario" name="usuario" required autofocus placeholder="admin">
      </div>
      <div class="field">
        <label for="password">Contraseña *</label>
        <input type="password" id="password" name="password" required placeholder="••••••••">
      </div>
      <button type="submit" class="btn solid" style="width:100%; justify-content:center;">Ingresar</button>

      <?php if ($error): ?>
        <div class="login-error"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>
    </form>

    <p class="text-xs" style="text-align:center; margin-top:20px;">
      <a href="../../paginas/index.html">« Volver al sitio</a>
    </p>
  </div>
</div>

</body>
</html>

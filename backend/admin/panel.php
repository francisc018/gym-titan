<?php
require_once 'auth.php';
require_once '../api/conexion.php';

$total = 0;
$r = $conexion->query("SELECT COUNT(*) AS total FROM socios");
if ($r) $total = (int) $r->fetch(PDO::FETCH_ASSOC)['total'];

$hoy = 0;
$r2 = $conexion->query("SELECT COUNT(*) AS total FROM socios WHERE DATE(fecha_registro) = CURRENT_DATE");
if ($r2) $hoy = (int) $r2->fetch(PDO::FETCH_ASSOC)['total'];

$porPlan = [];
$r3 = $conexion->query("SELECT plan, COUNT(*) AS total FROM socios GROUP BY plan");
if ($r3) foreach ($r3->fetchAll(PDO::FETCH_ASSOC) as $fila) $porPlan[$fila['plan']] = (int) $fila['total'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Panel de administración — Forja Gym</title>
<link rel="stylesheet" href="../../assets/css/style.css">
<link rel="stylesheet" href="css/admin.css">
</head>
<body>
<div class="admin-shell">

  <div class="admin-topbar">
    <div class="brand" style="font-size:1.2rem;">FORJA<span>GYM</span> · ADMIN</div>
    <div class="who">Sesión: <b><?php echo htmlspecialchars($_SESSION['admin_nombre']); ?></b>
      &nbsp;·&nbsp; <a href="logout.php" class="text-xs">Cerrar sesión</a>
    </div>
  </div>

  <main class="admin-main">
    <div class="wrap" style="padding:0;">
      <span class="eyebrow">Panel de administración</span>
      <h2>Inscripciones recibidas</h2>
      <p>El sitio usa un único formulario (en planes.html), así que toda la información de tus clientes vive en una sola tabla: <b style="color:var(--bone);">socios</b>.</p>

      <div class="admin-cards" style="margin-top:30px;">
        <a href="listado_socios.php" class="admin-card">
          <span class="text-xs">Total de inscripciones</span>
          <b class="count"><?php echo $total; ?></b>
          <span class="text-sm" style="color:var(--bone);">Ver listado completo »</span>
        </a>
        <div class="admin-card">
          <span class="text-xs">Inscripciones de hoy</span>
          <b class="count"><?php echo $hoy; ?></b>
        </div>
        <div class="admin-card">
          <span class="text-xs">Plan básico</span>
          <b class="count"><?php echo $porPlan['Basico'] ?? 0; ?></b>
        </div>
        <div class="admin-card">
          <span class="text-xs">Plan Forja</span>
          <b class="count"><?php echo $porPlan['Forja'] ?? 0; ?></b>
        </div>
        <div class="admin-card">
          <span class="text-xs">Plan élite</span>
          <b class="count"><?php echo $porPlan['Elite'] ?? 0; ?></b>
        </div>
      </div>
    </div>
  </main>

</div>
</body>
</html>

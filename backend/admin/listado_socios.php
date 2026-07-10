<?php
require_once 'auth.php';
require_once '../api/conexion.php';

$stmt = $conexion->query("SELECT * FROM socios ORDER BY fecha_registro DESC");
$socios = $stmt->fetchAll(PDO::FETCH_ASSOC);
$total_registros = count($socios);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Listado de socios — Panel Forja Gym</title>
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
      <a href="panel.php" class="text-xs no-print">« Volver al panel</a>
      <span class="eyebrow" style="display:block; margin-top:14px;">Tabla: socios</span>
      <h2>Listado de inscripciones</h2>

      <div class="table-toolbar no-print">
        <p class="text-sm" style="margin:0;">Total de registros: <?php echo $total_registros; ?></p>
        <button class="btn" onclick="window.print()">🖶 Imprimir listado</button>
      </div>

      <?php if ($total_registros === 0): ?>
        <div class="empty-state">Todavía no hay socios registrados.</div>
      <?php else: ?>
      <div class="table-scroll">
        <table class="data">
          <thead>
            <tr>
              <th>#</th><th>Nombre</th><th>Apellido</th><th>Email</th><th>Teléfono</th>
              <th>Nacimiento</th><th>Género</th><th>Dirección</th>
              <th>Plan</th><th>Nivel</th><th>Objetivo</th>
              <th>Clase interés</th><th>Entrenador</th><th>Horario</th>
              <th>Comentarios</th><th>Registrado</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($socios as $fila): ?>
            <tr>
              <td><?php echo $fila['id_socio']; ?></td>
              <td><?php echo htmlspecialchars($fila['nombre']); ?></td>
              <td><?php echo htmlspecialchars($fila['apellido']); ?></td>
              <td><?php echo htmlspecialchars($fila['email']); ?></td>
              <td><?php echo htmlspecialchars($fila['telefono']); ?></td>
              <td><?php echo htmlspecialchars($fila['fecha_nacimiento']); ?></td>
              <td><?php echo htmlspecialchars($fila['genero']); ?></td>
              <td><?php echo htmlspecialchars($fila['direccion']); ?></td>
              <td><?php echo htmlspecialchars($fila['plan']); ?></td>
              <td><?php echo htmlspecialchars($fila['nivel_experiencia']); ?></td>
              <td><?php echo htmlspecialchars($fila['objetivo']); ?></td>
              <td><?php echo htmlspecialchars($fila['clase_interes']); ?></td>
              <td><?php echo htmlspecialchars($fila['entrenador_interes']); ?></td>
              <td><?php echo htmlspecialchars($fila['horario_preferido']); ?></td>
              <td><?php echo htmlspecialchars($fila['mensaje']); ?></td>
              <td><?php echo htmlspecialchars($fila['fecha_registro']); ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php endif; ?>
    </div>
  </main>

</div>
</body>
</html>

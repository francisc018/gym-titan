<?php
/* ============================================================
   FORJA GYM — backend/setup/inicializar.php
   Crea las tablas (socios, administradores) y el usuario admin
   de ejemplo en la base de datos conectada. Es seguro visitarlo
   más de una vez: usa IF NOT EXISTS / ON CONFLICT, así que nunca
   duplica ni borra nada que ya exista.

   Úsalo así, UNA VEZ, después de tu primer deploy en Render:
     https://tu-app.onrender.com/backend/setup/inicializar.php
   ============================================================ */

require_once '../api/conexion.php';

$sql = file_get_contents(__DIR__ . '/../database/postgres.sql');

// Quita los comentarios de línea (--) ANTES de partir por punto y coma:
// si no se hace así, un punto y coma que aparezca dentro del texto de un
// comentario (por ejemplo "en producción; el hash...") rompe mal la
// separación de sentencias.
$lineas_sin_comentarios = array_map(function ($linea) {
    return preg_replace('/--.*$/', '', $linea);
}, explode("\n", $sql));
$sql_limpio = implode("\n", $lineas_sin_comentarios);

$sentencias = array_filter(array_map('trim', explode(';', $sql_limpio)));

$resultados = [];
try {
    foreach ($sentencias as $sentencia) {
        if ($sentencia === '') continue;
        $conexion->exec($sentencia);
        $resumen = preg_replace('/\s+/', ' ', $sentencia);
        $resultados[] = "OK: " . substr($resumen, 0, 60) . "...";
    }
    $exito = true;
} catch (PDOException $e) {
    $exito = false;
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Inicializar base de datos — Forja Gym</title>
<style>
  body{ font-family:system-ui,sans-serif; background:#12141a; color:#f2efe9; padding:40px; max-width:760px; margin:0 auto; }
  h1{ color:#ff5722; }
  .ok{ color:#8fd694; }
  .fail{ color:#ff6b6b; }
  code{ background:#1c1f27; padding:2px 6px; border-radius:3px; }
  ul{ line-height:1.7; }
  a{ color:#ff8a5c; }
</style>
</head>
<body>
<h1>FORJA<span style="color:#f2efe9;">GYM</span> — Inicializar base de datos</h1>

<?php if ($exito): ?>
  <p class="ok">✅ Listo. La base de datos quedó creada/actualizada correctamente.</p>
  <ul>
    <?php foreach ($resultados as $r): ?>
      <li><?php echo htmlspecialchars($r); ?></li>
    <?php endforeach; ?>
  </ul>
  <p>Ya puedes:</p>
  <ul>
    <li>Ir al sitio: <a href="../../index.html">Volver al inicio</a></li>
    <li>Probar el formulario de inscripción en la página de Planes</li>
    <li>Entrar al panel admin: <a href="../admin/login.php">/backend/admin/login.php</a>
      (usuario <code>admin</code>, contraseña <code>forja2026</code>)</li>
  </ul>
  <p style="opacity:.7; font-size:.85rem;">Puedes borrar o dejar esta página — es segura de visitar de nuevo, no duplica datos.</p>
<?php else: ?>
  <p class="fail">❌ Hubo un problema creando las tablas.</p>
  <p><code><?php echo htmlspecialchars($error); ?></code></p>
  <p>Revisa que la base de datos PostgreSQL esté conectada a este servicio en Render
     (variable de entorno <code>DATABASE_URL</code>).</p>
<?php endif; ?>

</body>
</html>

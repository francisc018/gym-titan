<?php
/* ============================================================
   FORJA GYM — conexion.php
   Conexión centralizada a la base de datos, usando PDO.
   Todos los demás archivos PHP incluyen este archivo e insertan
   como variable disponible: $conexion (objeto PDO).

   Este único archivo funciona en LOS TRES entornos del proyecto,
   sin tocar nada más:

   1) TU PC CON XAMPP (MySQL local)
   2) INFINITYFREE (hosting gratuito, MySQL)
   3) RENDER (hosting gratuito, PostgreSQL vía Docker)

   ¿Cómo sabe cuál usar? Si existe la variable de entorno
   DATABASE_URL (Render la crea sola al conectar una base de
   datos Postgres a este servicio), usa PostgreSQL. Si no existe,
   usa los 4 datos de MySQL de abajo (XAMPP o InfinityFree).
   ============================================================ */

$DATABASE_URL = getenv('DATABASE_URL');

try {
    if ($DATABASE_URL) {
        // ---------- RENDER (PostgreSQL) ----------
        // DATABASE_URL llega con este formato:
        //   postgres://usuario:contraseña@host:puerto/nombre_basededatos
        $partes = parse_url($DATABASE_URL);
        $host   = $partes['host'];
        $puerto = $partes['port'] ?? 5432;
        $db     = ltrim($partes['path'], '/');
        $user   = $partes['user'];
        $pass   = $partes['pass'];

        $dsn = "pgsql:host=$host;port=$puerto;dbname=$db;sslmode=require";
        $conexion = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
    } else {
        // ---------- XAMPP (local) o INFINITYFREE (MySQL) ----------
        // EN TU PC CON XAMPP, normalmente es:
        //   $DB_HOST = "localhost";  $DB_USER = "root";  $DB_PASS = "";  $DB_NAME = "forja_gym";
        //
        // EN INFINITYFREE, los 4 datos los da SU panel, sección "MySQL Databases":
        //   $DB_HOST = "sqlXXX.infinityfree.com";
        //   $DB_USER = "epiz_XXXXXXXX_forjagym";
        //   $DB_PASS = "la-contraseña-que-tú-elegiste";
        //   $DB_NAME = "epiz_XXXXXXXX_forjagym";
        //
        // Ver docs/DESPLIEGUE-INFINITYFREE.md para el paso a paso completo.

        $DB_HOST = "localhost";
        $DB_USER = "root";
        $DB_PASS = "";
        $DB_NAME = "forja_gym";

        $dsn = "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4";
        $conexion = new PDO($dsn, $DB_USER, $DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
    }
} catch (PDOException $e) {
    // Si falla la conexión, respondemos en JSON para que el fetch() del front lo entienda
    header('Content-Type: application/json');
    echo json_encode([
        "ok" => false,
        "message" => "No se pudo conectar a la base de datos. Si estás en local, verifica que MySQL esté activo y que 'forja_gym' exista (importa backend/database/gimnasio.sql). Si estás en Render, verifica que la base de datos Postgres esté conectada al servicio y que hayas corrido backend/setup/inicializar.php una vez."
    ]);
    exit;
}

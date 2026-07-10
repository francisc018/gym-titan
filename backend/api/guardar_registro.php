<?php
/* ============================================================
   FORJA GYM — guardar_registro.php
   Recibe el ÚNICO formulario del sitio (planes.html) e inserta
   toda la información del cliente en la tabla `socios`:
   datos personales, membresía y preferencias de clase/entrenador.
   ============================================================ */

header('Content-Type: application/json');
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["ok" => false, "message" => "Método no permitido."]);
    exit;
}

// --- Datos personales ---
$nombre           = trim($_POST['nombre'] ?? '');
$apellido         = trim($_POST['apellido'] ?? '');
$email            = trim($_POST['email'] ?? '');
$telefono         = trim($_POST['telefono'] ?? '');
$fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? '');
$direccion        = trim($_POST['direccion'] ?? '');
$genero           = trim($_POST['genero'] ?? '');

// --- Membresía ---
$plan              = trim($_POST['plan'] ?? '');
$nivel_experiencia = trim($_POST['nivel_experiencia'] ?? 'Principiante');
$objetivo          = trim($_POST['objetivo'] ?? '');

// --- Preferencias (opcionales) ---
$clase_interes      = trim($_POST['clase_interes'] ?? '');
$entrenador_interes = trim($_POST['entrenador_interes'] ?? '');
$horario_preferido  = trim($_POST['horario_preferido'] ?? 'Mañana');
$mensaje            = trim($_POST['mensaje'] ?? '');

// --- Validación de campos obligatorios ---
if (!$nombre || !$apellido || !$email || !$telefono || !$fecha_nacimiento || !$plan || !$objetivo) {
    echo json_encode(["ok" => false, "message" => "Faltan campos obligatorios."]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["ok" => false, "message" => "El correo electrónico no es válido."]);
    exit;
}

$planesValidos = ["Basico", "Forja", "Elite"];
if (!in_array($plan, $planesValidos, true)) {
    echo json_encode(["ok" => false, "message" => "Plan seleccionado no válido."]);
    exit;
}

if (empty($_POST['acepta_terminos'])) {
    echo json_encode(["ok" => false, "message" => "Debes aceptar el reglamento interno para continuar."]);
    exit;
}

// --- Inserción con sentencia preparada (previene inyección SQL) ---
$sql = "INSERT INTO socios
        (nombre, apellido, email, telefono, fecha_nacimiento, direccion, genero,
         plan, nivel_experiencia, objetivo, clase_interes, entrenador_interes, horario_preferido, mensaje)
        VALUES (:nombre, :apellido, :email, :telefono, :fecha_nacimiento, :direccion, :genero,
                :plan, :nivel_experiencia, :objetivo, :clase_interes, :entrenador_interes, :horario_preferido, :mensaje)";

try {
    $stmt = $conexion->prepare($sql);
    $stmt->execute([
        ':nombre'             => $nombre,
        ':apellido'           => $apellido,
        ':email'              => $email,
        ':telefono'           => $telefono,
        ':fecha_nacimiento'   => $fecha_nacimiento,
        ':direccion'          => $direccion,
        ':genero'             => $genero,
        ':plan'               => $plan,
        ':nivel_experiencia'  => $nivel_experiencia,
        ':objetivo'           => $objetivo,
        ':clase_interes'      => $clase_interes,
        ':entrenador_interes' => $entrenador_interes,
        ':horario_preferido'  => $horario_preferido,
        ':mensaje'            => $mensaje,
    ]);

    echo json_encode([
        "ok" => true,
        "message" => "¡Bienvenido a Forja Gym, $nombre! Tu membresía ($plan) fue creada correctamente."
    ]);
} catch (PDOException $e) {
    echo json_encode(["ok" => false, "message" => "Error al guardar el registro: " . $e->getMessage()]);
}

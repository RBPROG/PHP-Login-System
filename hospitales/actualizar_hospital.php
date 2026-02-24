<?php
require '../assets/setup/env.php';
require '../assets/setup/db.inc.php';

// Obtener datos POST

$Hospitalid = intval($_POST['HospitalId']);
$nombre = !empty($_POST['Nombre']) ? trim($_POST['Nombre']) : null;
$codigo = !empty($_POST['Codigo']) ? trim($_POST['Codigo']) : null;
$xmlHospital = !empty($_POST['XmlHospitalName']) ? trim($_POST['XmlHospitalName']) : null;
$xmlUnit = !empty($_POST['XmlUnitName']) ? trim($_POST['XmlUnitName']) : null;


// Validar campos obligatorios
if (empty($nombre)) {
    http_response_code(400);
    echo json_encode(['error' => 'El nombre es obligatorio']);
    exit;
}

// Preparar sentencia segura con mysqli
$stmt = mysqli_prepare($conn, "
    UPDATE hospitales SET Nombre = ?, Codigo = ?, UltMod = NOW(), XmlHospitalName = ?, XmlUnitName = ?, NumMaxCertificados = 0, CertificadosEmitidos = 0
    WHERE HospitalId = ?
");

if (!$stmt) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Error al preparar la consulta',
        'mysqli_error' => mysqli_error($conn)
    ]);
    exit;
}

// Vincular parámetros
mysqli_stmt_bind_param($stmt, "ssssi", $nombre, $codigo, $xmlHospital, $xmlUnit, $Hospitalid);

// Ejecutar y depurar error real
if (mysqli_stmt_execute($stmt)) {
    $hospitalId = mysqli_insert_id($conn);
    echo json_encode(['success' => true, 'HospitalId' => $hospitalId]);
} else {
    http_response_code(500);
    echo json_encode([
        'error' => 'Error al guardar el hospital',
        'stmt_error' => mysqli_stmt_error($stmt),
        'errno' => mysqli_stmt_errno($stmt)
    ]);
}

// Cerrar conexión
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
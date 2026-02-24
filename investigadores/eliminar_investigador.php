<?php
require '../assets/setup/env.php';
require '../assets/setup/db.inc.php';

// Obtener datos POST

$Hospitalid = intval($_POST['HospitalId']);


// Preparar sentencia segura con mysqli
$stmt = mysqli_prepare($conn, "
    DELETE FROM hospitales WHERE HospitalId = ?	");

if (!$stmt) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Error al preparar la consulta',
        'mysqli_error' => mysqli_error($conn)
    ]);
    exit;
}

// Vincular parámetros
mysqli_stmt_bind_param($stmt, "i", $Hospitalid);

// Ejecutar y depurar error real
if (mysqli_stmt_execute($stmt)) {
    $hospitalId = mysqli_insert_id($conn);
    echo json_encode(['success' => true, 'HospitalId' => $hospitalId]);
} else {
    http_response_code(500);
    echo json_encode([
        'error' => 'Error al eliminar el hospital',
        'stmt_error' => mysqli_stmt_error($stmt),
        'errno' => mysqli_stmt_errno($stmt)
    ]);
}

// Cerrar conexión
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
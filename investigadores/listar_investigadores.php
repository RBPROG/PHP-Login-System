<?php
require '../assets/setup/env.php';
require '../assets/setup/db.inc.php';

// Parámetros enviados por DataTables
$draw        = intval($_GET['draw'] ?? 0);
$start       = intval($_GET['start'] ?? 0);
$length      = intval($_GET['length'] ?? 10);
$searchValue = $_GET['search']['value'] ?? '';

// 1. Total de registros sin filtros
$sqlTotal = "SELECT COUNT(*) AS total FROM hospitales";
$resTotal = mysqli_query($conn, $sqlTotal);
$recordsTotal = ($row = mysqli_fetch_assoc($resTotal)) ? intval($row['total']) : 0;

// 2. Total de registros con filtro (si hay búsqueda)
$where = "";
$params = [];
if (!empty($searchValue)) {
    $search = "%" . mysqli_real_escape_string($conn, $searchValue) . "%";
    $where = " WHERE Nombre LIKE '$search' ";
}

$sqlFiltered = "SELECT COUNT(*) AS total FROM hospitales $where";
$resFiltered = mysqli_query($conn, $sqlFiltered);
$recordsFiltered = ($row = mysqli_fetch_assoc($resFiltered)) ? intval($row['total']) : 0;

// 3. Obtener datos paginados
$sqlData = "
    SELECT HospitalId , Nombre, Codigo, XmlHospitalName, XmlUnitName, UltMod 
    FROM hospitales
    $where
    ORDER BY HospitalId  DESC
    LIMIT $start, $length
";
$resData = mysqli_query($conn, $sqlData);

$data = [];
while ($row = mysqli_fetch_assoc($resData)) {
    $data[] = $row;
}

// Respuesta en formato JSON
$response = [
    "draw" => $draw,
    "recordsTotal" => $recordsTotal,
    "recordsFiltered" => $recordsFiltered,
    "data" => $data
];

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);

mysqli_close($conn);
?>
<?php

require '../assets/setup/env.php';
require '../assets/setup/db.inc.php';

$id = intval($_GET['id']);
$sql = "SELECT * FROM hospitales WHERE HospitalId = $id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(['success' => true, 'data' => $row]);
} else {
    echo json_encode(['success' => false]);
}
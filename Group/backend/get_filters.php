<?php
include 'db_connect.php';

header('Content-Type: application/json');

$queryDifficulties = "SELECT DISTINCT difficulty FROM training_sessions ORDER BY difficulty";
$querySessionTypes = "SELECT DISTINCT session_type FROM training_sessions ORDER BY session_type";

$resultDifficulties = pg_query($conn, $queryDifficulties);
$resultSessionTypes = pg_query($conn, $querySessionTypes);

if (!$resultDifficulties || !$resultSessionTypes) {
    echo json_encode(["error" => "Error fetching filters"]);
    exit;
}

$difficulties = pg_fetch_all_columns($resultDifficulties) ?: [];
$sessionTypes = pg_fetch_all_columns($resultSessionTypes) ?: [];

echo json_encode([
    "difficulties" => $difficulties,
    "session_types" => $sessionTypes
]);

pg_close($conn);
?>

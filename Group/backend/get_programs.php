<?php
include 'db_connect.php';

header('Content-Type: application/json');

$difficulty = $_GET['difficulty'] ?? 'all';
$session_type = $_GET['session_type'] ?? 'all';

$query = "SELECT session_id, title, description, difficulty, session_type, duration, likes 
          FROM training_sessions 
          WHERE 1=1";

$params = [];
$paramIndex = 1;

if ($difficulty !== 'all') {
    $query .= " AND difficulty = $" . $paramIndex++;
    $params[] = $difficulty;
}

if ($session_type !== 'all') {
    $query .= " AND session_type = $" . $paramIndex++;
    $params[] = $session_type;
}

$query .= " ORDER BY title";

$result = pg_query_params($conn, $query, $params);

if (!$result) {
    echo json_encode(["error" => "Error fetching programs"]);
    exit;
}

$programs = pg_fetch_all($result) ?: [];
echo json_encode($programs);

pg_close($conn);
?>

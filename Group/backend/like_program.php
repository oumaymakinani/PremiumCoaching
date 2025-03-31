<?php
include 'db_connect.php';
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$session_id = $input['session_id'] ?? null;
$action = $input['action'] ?? null; 

if (!$session_id || !in_array($action, ['like', 'unlike'])) {
    echo json_encode(["success" => false, "error" => "Missing session_id or action"]);
    exit;
}

if ($action === 'like') {
    $query = "UPDATE training_sessions SET likes = COALESCE(likes, 0) + 1 WHERE session_id = $1";
} elseif ($action === 'unlike') {
    $query = "UPDATE training_sessions SET likes = GREATEST(COALESCE(likes, 0) - 1, 0) WHERE session_id = $1";
}

$result = pg_query_params($conn, $query, [$session_id]);

if ($result) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => pg_last_error($conn)]);
}

pg_close($conn);

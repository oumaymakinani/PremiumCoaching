<?php
include 'db_connect.php';

if (!isset($_GET['coach_id'])) {
    echo json_encode(["error" => "Missing coach_id"]);
    exit;
}

$coach_id = $_GET['coach_id'];

$query = "SELECT session_id, title, description 
          FROM training_sessions 
          WHERE coach_id = $1 
          ORDER BY title";

$result = pg_query_params($conn, $query, [$coach_id]);

if (!$result) {
    echo json_encode(["error" => "Error fetching training sessions"]);
    exit;
}

$sessions = pg_fetch_all($result);
echo json_encode($sessions);
?>

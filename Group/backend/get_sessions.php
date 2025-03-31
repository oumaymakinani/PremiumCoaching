<?php
include 'db_connect.php';
header('Content-Type: application/json');

if (!isset($_GET['coach_id'])) {
    echo json_encode(["error" => "Missing coach_id"]);
    exit;
}

$coach_id = $_GET['coach_id'];

$query = "SELECT session_id, title, description, duration
          FROM training_sessions 
          WHERE coach_id = $1 
          ORDER BY title";

$result = pg_query_params($conn, $query, [$coach_id]);

if (!$result) {
    echo json_encode(["error" => "Error fetching training sessions"]);
    exit;
}

$sessions = [];
while ($row = pg_fetch_assoc($result)) {
    $sessions[] = [
        "session_id" => $row["session_id"],
        "title" => $row["title"],
        "description" => $row["description"],
        "duration" => (int)$row["duration"]
    ];
}

echo json_encode($sessions);
pg_close($conn);
?>

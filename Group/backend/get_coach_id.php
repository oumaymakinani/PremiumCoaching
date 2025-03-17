<?php
session_start();
include 'db_connect.php';
header('Content-Type: application/json');

// Ensure user is logged in
$user_id = $_GET['user_id'] ?? $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

// Fetch coach_id linked to user_id
$query = "SELECT coach_id FROM coaches WHERE user_id = $1";
$result = pg_query_params($conn, $query, [$user_id]);

if (!$result || pg_num_rows($result) == 0) {
    echo json_encode(["error" => "Coach ID not found"]);
    exit;
}

$coach_data = pg_fetch_assoc($result);
$_SESSION['coach_id'] = $coach_data['coach_id']; // Store in session for persistence

echo json_encode(["coach_id" => $coach_data['coach_id']]);

pg_close($conn);
?>

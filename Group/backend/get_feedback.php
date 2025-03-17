<?php
include 'db_connect.php';

// Set content type to JSON
header('Content-Type: application/json');

// Build base query
$query = "SELECT f.feedback_id, f.user_id, f.coach_id, f.rating, f.comments, f.created_at, u.full_name AS coach_name 
          FROM feedback f
          JOIN coaches c ON f.coach_id = c.coach_id
          JOIN users u ON c.user_id = u.user_id";

$conditions = [];
$params = [];
$paramTypes = "";

// Check filters
if (isset($_GET['coach_id']) && !empty($_GET['coach_id'])) {
    $conditions[] = "f.coach_id = $1";
    $params[] = $_GET['coach_id'];
}

if (isset($_GET['rating']) && !empty($_GET['rating'])) {
    $conditions[] = "f.rating = $2";
    $params[] = $_GET['rating'];
}

if (isset($_GET['date']) && !empty($_GET['date'])) {
    $conditions[] = "DATE(f.created_at) = $3";
    $params[] = $_GET['date'];
}

// Append conditions if filters exist
if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

$query .= " ORDER BY f.created_at DESC"; // Order by latest feedback

// Execute query
$result = pg_query_params($conn, $query, $params);

if (!$result) {
    echo json_encode(["error" => "Failed to fetch feedback: " . pg_last_error($conn)]);
    exit;
}

// Fetch feedback
$feedbackData = pg_fetch_all($result) ?: [];

echo json_encode($feedbackData);
pg_close($conn);
?>

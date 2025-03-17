<?php
session_start();
include 'db_connect.php';
header('Content-Type: application/json');

// Ensure user is logged in
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

// Ensure coach_id is set in session
$coach_id = $_SESSION['coach_id'] ?? null;
if (!$coach_id) {
    echo json_encode(["error" => "Coach ID not found in session"]);
    exit;
}

// Extract form fields
$title = $_POST['session-type'] ?? null;
$description = "Training session on " . $title;
$difficulty = $_POST['session-difficulty'] ?? null;
$session_type = $_POST['session-type-mode'] ?? null;
$duration = $_POST['session-duration'] ?? null;

// Validate required fields
if (!$title || !$difficulty || !$session_type || !$duration) {
    echo json_encode(["error" => "Missing required fields"]);
    exit;
}

// Ensure duration is within an acceptable range
if ($duration < 30 || $duration > 120) {
    echo json_encode(["error" => "Invalid session duration. Must be between 30 and 120 minutes."]);
    exit;
}

// Insert into training_sessions
$query = "INSERT INTO training_sessions (coach_id, title, description, difficulty, session_type, duration)
          VALUES ($1, $2, $3, $4, $5, $6) RETURNING session_id";

$result = pg_query_params($conn, $query, [$coach_id, $title, $description, $difficulty, $session_type, $duration]);

if ($result) {
    $session = pg_fetch_assoc($result);
    echo json_encode([
        "success" => "Training session scheduled successfully",
        "session_id" => $session['session_id']
    ]);
} else {
    echo json_encode(["error" => "Error scheduling session: " . pg_last_error($conn)]);
}

pg_close($conn);
?>

<?php
session_start();
include 'db_connect.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $query = "SELECT f.feedback_id, f.user_id, f.coach_id, f.rating, f.comments, f.created_at, u.full_name AS coach_name 
              FROM feedback f
              JOIN coaches c ON f.coach_id = c.coach_id
              JOIN users u ON c.user_id = u.user_id";
    
    $conditions = [];
    $params = [];
    $paramIndex = 1;

    if (!empty($_GET['coach_id'])) {
        $conditions[] = "f.coach_id = $" . $paramIndex++;
        $params[] = $_GET['coach_id'];
    }

    if (!empty($_GET['rating'])) {
        $conditions[] = "f.rating = $" . $paramIndex++;
        $params[] = $_GET['rating'];
    }

    if (!empty($_GET['date'])) {
        $conditions[] = "DATE(f.created_at) = $" . $paramIndex++;
        $params[] = $_GET['date'];
    }

    if (!empty($conditions)) {
        $query .= " WHERE " . implode(" AND ", $conditions);
    }

    $query .= " ORDER BY f.created_at DESC";

    $result = pg_query_params($conn, $query, $params);

    if (!$result) {
        echo json_encode(["error" => "Failed to fetch feedback: " . pg_last_error($conn)]);
        exit;
    }

    echo json_encode(pg_fetch_all($result) ?: []);
}

if ($method === 'POST') {
    $user_id = $_SESSION['user_id'] ?? null;
    $coach_id = $_POST['coach'] ?? null;
    $rating = $_POST['rating'] ?? null;
    $comments = $_POST['comments'] ?? '';

    if (!$user_id || !$coach_id || !$rating) {
        echo json_encode(["error" => "Missing required fields"]);
        exit;
    }

    $insertQuery = "INSERT INTO feedback (user_id, coach_id, rating, comments, created_at) 
                    VALUES ($1, $2, $3, $4, NOW()) RETURNING feedback_id";

    $result = pg_query_params($conn, $insertQuery, [$user_id, $coach_id, $rating, $comments]);

    if ($result) {
        echo json_encode(["success" => "Feedback submitted successfully"]);
    } else {
        echo json_encode(["error" => "Failed to submit feedback: " . pg_last_error($conn)]);
    }
}

pg_close($conn);
?>

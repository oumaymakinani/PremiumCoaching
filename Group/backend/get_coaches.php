<?php
include 'db_connect.php'; // Ensure database connection

// Query to fetch all coaches
$query = "SELECT coach_id, (SELECT full_name FROM users WHERE users.user_id = coaches.user_id) AS name FROM coaches";
$result = pg_query($conn, $query);

if (!$result) {
    echo json_encode(["error" => "Failed to retrieve coaches."]);
    exit;
}

$coaches = pg_fetch_all($result);

echo json_encode($coaches);
?>

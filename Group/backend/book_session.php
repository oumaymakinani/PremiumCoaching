<?php
include 'db_connect.php';

$user_id = $_POST['user_id'];
$session_id = $_POST['session'];

if (!$user_id || !$session_id) {
    echo json_encode(["error" => "Missing required fields"]);
    exit;
}

// Insert booking into the database
$query = "INSERT INTO bookings (user_id, session_id, status, booked_at) 
          VALUES ($1, $2, 'confirmed', NOW()) RETURNING booking_id";

$result = pg_query_params($conn, $query, [$user_id, $session_id]);

if ($result) {
    echo json_encode(["success" => "Booking successfully added"]);
} else {
    echo json_encode(["error" => "Failed to book session"]);
}
?>

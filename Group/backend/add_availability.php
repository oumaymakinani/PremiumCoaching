<?php
session_start();
include 'db_connect.php';

// Only allow coaches to add availability
$coach_id = $_SESSION['user_id'] ?? null;
if (!$coach_id) {
    echo json_encode(["error" => "You must be logged in as a coach"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$available_date = $data['available_date'];
$start_time = $data['start_time'];
$end_time = $data['end_time'];

$query = "INSERT INTO availability (coach_id, available_date, start_time, end_time) 
          VALUES ($coach_id, '$available_date', '$start_time', '$end_time')";

$result = pg_query($conn, $query);

if ($result) {
    echo json_encode(["success" => "Availability added successfully"]);
} else {
    echo json_encode(["error" => "Failed to add availability: " . pg_last_error($conn)]);
}
?>

<?php
include 'db_connect.php';

// Fetch available coaches and sessions
$query = "SELECT ca.availability_id, c.full_name AS coach_name, ca.available_date, ca.start_time, ca.end_time 
          FROM coach_availability ca
          JOIN coaches co ON ca.coach_id = co.coach_id
          JOIN users c ON co.user_id = c.user_id
          WHERE ca.status = 'available'
          ORDER BY ca.available_date, ca.start_time";

$result = pg_query($conn, $query);

if (!$result) {
    echo json_encode(["error" => "Error fetching bookings: " . pg_last_error($conn)]);
    exit;
}

$availabilities = pg_fetch_all($result);
echo json_encode($availabilities ? $availabilities : []);
?>

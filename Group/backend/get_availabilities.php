<?php
include 'db_connect.php';

if (!isset($_GET['coach_id'])) {
    echo json_encode(["error" => "Missing coach_id"]);
    exit;
}

$coach_id = $_GET['coach_id'];

$query = "SELECT ca.availability_id, ca.available_date, ca.start_time, ca.end_time 
          FROM coach_availability ca
          WHERE ca.coach_id = $1 AND ca.status = 'available'
          ORDER BY ca.available_date, ca.start_time";

$result = pg_query_params($conn, $query, [$coach_id]);

if (!$result) {
    echo json_encode(["error" => "Error fetching availabilities"]);
    exit;
}

$availabilities = pg_fetch_all($result);
echo json_encode($availabilities);
?>

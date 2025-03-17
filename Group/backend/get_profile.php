<?php
session_start();
include 'db_connect.php';

header('Content-Type: application/json'); 

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

// Fetch user details
$userQuery = "SELECT user_id, full_name, email, phone, role FROM users WHERE user_id = $1";
$userResult = pg_query_params($conn, $userQuery, [$user_id]);

if (!$userResult) {
    echo json_encode(["error" => "Failed to fetch user details: " . pg_last_error($conn)]);
    exit;
}

$userData = pg_fetch_assoc($userResult);

// Fetch booked coach details safely
$coachQuery = "SELECT c.coach_id, u.full_name AS name, c.specialization, c.profile_image, b.booked_at AS session_date, ts.session_type
               FROM bookings b
               JOIN training_sessions ts ON b.session_id = ts.session_id
               JOIN coaches c ON ts.coach_id = c.coach_id
               JOIN users u ON c.user_id = u.user_id
               WHERE b.user_id = $1 LIMIT 1";
$coachResult = pg_query_params($conn, $coachQuery, [$user_id]);

if (!$coachResult) {
    echo json_encode(["error" => "Failed to fetch coach details: " . pg_last_error($conn)]);
    exit;
}

$bookedCoach = pg_fetch_assoc($coachResult);

// Fetch upcoming training sessions safely
$sessionsQuery = "SELECT ts.title, b.booked_at AS session_date
                  FROM bookings b
                  JOIN training_sessions ts ON b.session_id = ts.session_id
                  WHERE b.user_id = $1
                  ORDER BY b.booked_at ASC";
$sessionsResult = pg_query_params($conn, $sessionsQuery, [$user_id]);

if (!$sessionsResult) {
    echo json_encode(["error" => "Failed to fetch training sessions: " . pg_last_error($conn)]);
    exit;
}

$upcomingSessions = pg_fetch_all($sessionsResult) ?: [];

// Fetch all coaches for feedback dropdown safely
$coachesQuery = "SELECT c.coach_id, u.full_name AS name 
                 FROM coaches c 
                 JOIN users u ON c.user_id = u.user_id";
$coachesResult = pg_query($conn, $coachesQuery);

if (!$coachesResult) {
    echo json_encode(["error" => "Failed to fetch coaches: " . pg_last_error($conn)]);
    exit;
}

$coaches = pg_fetch_all($coachesResult) ?: [];

// Construct JSON response
$response = [
    "user" => $userData,
    "booked_coach" => $bookedCoach,
    "upcoming_sessions" => $upcomingSessions,
    "coaches" => $coaches
];

echo json_encode($response);
pg_close($conn);
?>

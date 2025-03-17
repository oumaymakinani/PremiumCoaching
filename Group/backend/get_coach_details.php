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

// Fetch user details from `users` table
$query_user = "SELECT full_name, email, phone FROM users WHERE user_id = $1";
$result_user = pg_query_params($conn, $query_user, [$user_id]);

if (!$result_user || pg_num_rows($result_user) == 0) {
    echo json_encode(["error" => "User not found"]);
    exit;
}

$user_data = pg_fetch_assoc($result_user);

// Fetch coach details from `coaches` table using `user_id`
$query_coach = "SELECT coach_id, specialization, certification, bio, profile_image FROM coaches WHERE user_id = $1";
$result_coach = pg_query_params($conn, $query_coach, [$user_id]);

if (!$result_coach || pg_num_rows($result_coach) == 0) {
    echo json_encode([
        "full_name" => $user_data['full_name'],
        "email" => $user_data['email'],
        "phone" => $user_data['phone'],
        "specialization" => "Not specified",
        "certifications" => "N/A",
        "bio" => "No bio available",
        "profile_image" => "default.jpg",
        "coach_id" => null
    ]);
    exit;
}

$coach_data = pg_fetch_assoc($result_coach);
$_SESSION['coach_id'] = $coach_data['coach_id'];

echo json_encode([
    "full_name" => $user_data['full_name'],
    "email" => $user_data['email'],
    "phone" => $user_data['phone'],
    "specialization" => $coach_data['specialization'] ?? "Not specified",
    "certifications" => $coach_data['certification'] ?? "N/A",
    "bio" => $coach_data['bio'] ?? "No bio available",
    "profile_image" => $coach_data['profile_image'] ?? "default.jpg",
    "coach_id" => $coach_data['coach_id']
]);

pg_close($conn);
?>

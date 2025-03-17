<?php
include 'db_connect.php';
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT); 
    $phone = trim($_POST['phone']);
    $role = trim($_POST['role']);  

    // Prevent SQL Injection
    $email = pg_escape_string($conn, $email);

    // Check if email already exists
    $checkQuery = "SELECT * FROM users WHERE email = $1";
    $checkResult = pg_query_params($conn, $checkQuery, [$email]);

    if (pg_num_rows($checkResult) > 0) {
        echo json_encode(["error" => "❌ Email already exists. Please use a different email."]);
        exit;
    }

    $query = "INSERT INTO users (full_name, email, password, phone, role) 
              VALUES ($1, $2, $3, $4, $5) RETURNING user_id";

    $result = pg_query_params($conn, $query, [$full_name, $email, $password, $phone, $role]);

    if ($result) {
        $user = pg_fetch_assoc($result);
        $user_id = $user['user_id'];

        // If the role is 'coach', generate a new coach_id
        if ($role === 'coach') {
            // Get the last coach_id and increment it
            $lastCoachQuery = "SELECT MAX(coach_id) AS last_coach_id FROM coaches";
            $lastCoachResult = pg_query($conn, $lastCoachQuery);
            $lastCoachRow = pg_fetch_assoc($lastCoachResult);
            $new_coach_id = ($lastCoachRow['last_coach_id'] ?? 6) + 1; 

            // Insert into `coaches` table
            $coachQuery = "INSERT INTO coaches (coach_id, user_id, specialization, experience_years, certification, bio, profile_image) 
                           VALUES ($1, $2, 'Not specified', 0, 'Not specified', 'No bio yet', 'default.jpg')";
            pg_query_params($conn, $coachQuery, [$new_coach_id, $user_id]);
        }

        echo json_encode(["success" => "Registration successful"]);
    } else {
        echo json_encode(["error" => "Error: " . pg_last_error($conn)]);
    }
}

pg_close($conn);
?>

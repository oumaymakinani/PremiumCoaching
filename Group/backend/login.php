<?php
session_start();
include 'db_connect.php';

header('Content-Type: application/json'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        echo json_encode(["success" => false, "error" => "Email and password are required"]);
        exit;
    }
        
    $query = "SELECT user_id, full_name, role, password FROM users WHERE email = $1";
    $result = pg_query_params($conn, $query, [$email]);

    if ($result && pg_num_rows($result) > 0) {
        $user = pg_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];

            $coach_id = null;
            if ($user['role'] === "coach") {
                $coachQuery = "SELECT coach_id FROM coaches WHERE user_id = $1";
                $coachResult = pg_query_params($conn, $coachQuery, [$user['user_id']]);

                if ($coachResult && pg_num_rows($coachResult) > 0) {
                    $coachData = pg_fetch_assoc($coachResult);
                    $_SESSION['coach_id'] = $coachData['coach_id'];
                    $coach_id = $coachData['coach_id'];
                }
            }

            echo json_encode([
                "success" => true,
                "user_id" => $user['user_id'],
                "role" => $user['role'],
                "coach_id" => $coach_id
            ]);
            exit;
        } else {
            echo json_encode(["success" => false, "error" => "Incorrect password"]);
            exit;
        }
    } else {
        echo json_encode(["success" => false, "error" => "No account found with this email"]);
        exit;
    }
}

pg_close($conn);
?>

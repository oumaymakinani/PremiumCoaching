<?php
$host = "localhost"; 
$port = "5433"; 
$dbname = "Coaching";  
$user = "postgres"; 
$password = "1234";  

// Connect to PostgreSQL
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

// Check connection
if (!$conn) {
    die("Connection failed: " . pg_last_error());
}
?>

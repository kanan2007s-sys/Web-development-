<?php

$host = "localhost";
$dbname = "studenthub";
$username = "root";
$password = "";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        )
    );

} catch (PDOException $e) {
    error_log("StudentHub database connection failed: " . $e->getMessage());
    http_response_code(500);
    exit("Database connection failed. Import database/studenthub.sql and make sure MySQL is running.");
}

?>
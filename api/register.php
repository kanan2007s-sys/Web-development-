<?php

header("Content-Type: application/json; charset=utf-8");

function sendJson($statusCode, $data)
{
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    sendJson(405, array("error" => "Use POST to register."));
}

$data = json_decode(file_get_contents("php://input"), true);

if (!is_array($data)) {
    sendJson(400, array("error" => "Invalid JSON request."));
}

$requiredFields = array(
    "name", "enrollment", "email", "mobile",
    "password", "course", "year", "gender"
);

foreach ($requiredFields as $field) {
    if (
        !isset($data[$field]) ||
        !is_string($data[$field]) ||
        trim($data[$field]) === ""
    ) {
        sendJson(400, array("error" => "Please complete all registration fields."));
    }
}

$name = trim($data["name"]);
$enrollment = strtoupper(trim($data["enrollment"]));
$email = strtolower(trim($data["email"]));
$mobile = trim($data["mobile"]);
$password = $data["password"];
$course = $data["course"];
$year = $data["year"];
$gender = $data["gender"];

if (!preg_match("/^[A-Za-z ]{3,}$/", $name)) {
    sendJson(400, array("error" => "Enter a valid name."));
}

if (!preg_match("/^D?\\d{2}DCE\\d{3}$/i", $enrollment)) {
    sendJson(400, array("error" => "Invalid enrollment ID."));
}

if (!preg_match("/^[a-zA-Z0-9]+@charusat\\.edu\\.in$/i", $email)) {
    sendJson(400, array("error" => "Enter a valid CHARUSAT email."));
}

if (!preg_match("/^[6-9]\\d{9}$/", $mobile)) {
    sendJson(400, array("error" => "Enter a valid 10 digit mobile number."));
}

if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@$!%*?&]).{8,}$/", $password)) {
    sendJson(400, array(
        "error" => "Password must contain uppercase, lowercase, number and special character."
    ));
}

if (!in_array($course, array("CE", "CSE", "IT", "ME"), true)) {
    sendJson(400, array("error" => "Select a valid course."));
}

if (!in_array($year, array("1", "2", "3", "4"), true)) {
    sendJson(400, array("error" => "Select a valid year."));
}

if (!in_array($gender, array("Male", "Female", "Other"), true)) {
    sendJson(400, array("error" => "Select a valid gender."));
}

if (!isset($data["termsAccepted"]) || $data["termsAccepted"] !== true) {
    sendJson(400, array("error" => "Accept the terms and conditions."));
}

require_once __DIR__ . "/db_mysqli.php";

try {
    $check = $conn->prepare(
        "SELECT enrollment, email FROM users
         WHERE enrollment = ? OR email = ?"
    );

    $check->bind_param("ss", $enrollment, $email);
    $check->execute();
    $result = $check->get_result();

    while ($row = $result->fetch_assoc()) {
        if (strtoupper($row["enrollment"]) === $enrollment) {
            $check->close();
            sendJson(409, array("error" => "Enrollment ID is already registered."));
        }

        if (strtolower($row["email"]) === $email) {
            $check->close();
            sendJson(409, array("error" => "Email is already registered."));
        }
    }

    $check->close();

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare(
        "INSERT INTO users
         (name, enrollment, email, mobile, password, course, year, gender, termsAccepted)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );

    $yearNumber = (int) $year;
    $termsAccepted = 1;

    $stmt->bind_param(
        "ssssssisi",
        $name,
        $enrollment,
        $email,
        $mobile,
        $hashedPassword,
        $course,
        $yearNumber,
        $gender,
        $termsAccepted
    );

    $stmt->execute();
    $stmt->close();
    $conn->close();

    sendJson(201, array(
        "success" => true,
        "loginId" => $enrollment,
        "message" => "Registration successful."
    ));

} catch (mysqli_sql_exception $e) {
    error_log("Registration database error: " . $e->getMessage());

    if (isset($conn)) {
        $conn->close();
    }

    if ((int) $e->getCode() === 1062) {
        sendJson(409, array(
            "error" => "Enrollment ID or email is already registered."
        ));
    }

    sendJson(500, array(
        "error" => "Registration could not be completed. Please try again."
    ));
}

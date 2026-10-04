<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . "common.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    sendJson(405, array("error" => "Use POST to register."));
}

$data = readRequestJson();
$requiredFields = array("name", "enrollment", "email", "mobile", "password", "course", "year", "gender");
foreach ($requiredFields as $field) {
    if (!isset($data[$field]) || !is_string($data[$field]) || trim($data[$field]) === "") {
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
    sendJson(400, array("error" => "Name must contain at least three letters and spaces only."));
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
    sendJson(400, array("error" => "Password must contain uppercase, lowercase, number and special character."));
}
if (!in_array($course, array("CE", "CSE", "IT", "ME"), true)) {
    sendJson(400, array("error" => "Please select a valid course."));
}
if (!in_array($year, array("1", "2", "3", "4"), true)) {
    sendJson(400, array("error" => "Please select a valid year."));
}
if (!in_array($gender, array("Male", "Female", "Other"), true)) {
    sendJson(400, array("error" => "Please select a valid gender."));
}
if (!isset($data["termsAccepted"]) || $data["termsAccepted"] !== true) {
    sendJson(400, array("error" => "Please accept the terms and conditions."));
}

$handle = fopen(registrationsFile(), "c+");
if ($handle === false) {
    sendJson(500, array("error" => "Could not open registration.json. Check Apache file permissions."));
}
if (!flock($handle, LOCK_EX)) {
    fclose($handle);
    sendJson(500, array("error" => "Could not lock registration.json for saving."));
}

$registrations = readRegistrationsFromHandle($handle);
foreach ($registrations as $registration) {
    if (
        (isset($registration["enrollment"]) && strtoupper($registration["enrollment"]) === $enrollment) ||
        (isset($registration["email"]) && strtolower($registration["email"]) === $email)
    ) {
        flock($handle, LOCK_UN);
        fclose($handle);
        sendJson(409, array("error" => "That enrollment ID or email is already registered."));
    }
}

$highestId = 0;
foreach ($registrations as $registration) {
    $highestId = max($highestId, isset($registration["id"]) ? (int) $registration["id"] : 0);
}

$loginId = $enrollment;
$registrations[] = array(
    "id" => $highestId + 1,
    "loginId" => $loginId,
    "password" => $password,
    "name" => $name,
    "enrollment" => $enrollment,
    "email" => $email,
    "mobile" => $mobile,
    "course" => $course,
    "year" => (int) $year,
    "gender" => $gender,
    "termsAccepted" => true
);

$json = json_encode($registrations, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
if ($json === false || !ftruncate($handle, 0) || !rewind($handle) || fwrite($handle, $json . PHP_EOL) === false || !fflush($handle)) {
    flock($handle, LOCK_UN);
    fclose($handle);
    sendJson(500, array("error" => "The registration could not be saved. Check Apache file permissions."));
}

flock($handle, LOCK_UN);
fclose($handle);
sendJson(201, array("success" => true, "loginId" => $loginId));

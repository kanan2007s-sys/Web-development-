<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . "common.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    sendJson(405, array("error" => "Use POST to log in."));
}

$data = readRequestJson();
if (!isset($data["loginId"], $data["password"]) || !is_string($data["loginId"]) || !is_string($data["password"])) {
    sendJson(400, array("error" => "Login ID and password are required."));
}

$handle = fopen(registrationsFile(), "r");
if ($handle === false) {
    sendJson(500, array("error" => "Could not read registration.json."));
}
if (!flock($handle, LOCK_SH)) {
    fclose($handle);
    sendJson(500, array("error" => "Could not read registration data."));
}

$registrations = readRegistrationsFromHandle($handle);
flock($handle, LOCK_UN);
fclose($handle);

foreach ($registrations as $registration) {
    if (
        isset($registration["loginId"], $registration["password"]) &&
        $registration["loginId"] === trim($data["loginId"]) &&
        $registration["password"] === $data["password"]
    ) {
        sendJson(200, array("success" => true));
    }
}

sendJson(401, array("error" => "Invalid Login ID or Password."));

<?php
function sendJson($statusCode, $data)
{
    http_response_code($statusCode);
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($data);
    exit;
}

function readRequestJson()
{
    $data = json_decode(file_get_contents("php://input"), true);
    if (!is_array($data)) {
        sendJson(400, array("error" => "Request body must be valid JSON."));
    }
    return $data;
}

function registrationsFile()
{
    return dirname(__DIR__) . DIRECTORY_SEPARATOR . "JSON" . DIRECTORY_SEPARATOR . "registration.json";
}

function readRegistrationsFromHandle($handle)
{
    rewind($handle);
    $contents = stream_get_contents($handle);
    $registrations = json_decode($contents, true);
    if (!is_array($registrations)) {
        sendJson(500, array("error" => "registration.json must contain a valid JSON array."));
    }
    return $registrations;
}

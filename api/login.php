
<?php

require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/db_mysqli.php";

header("Content-Type: application/json; charset=utf-8");

function loginResponse($status, $data)
{
    http_response_code($status);
    echo json_encode($data);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    loginResponse(405, [
        "error" => "Use POST to log in."
    ]);
}

$data = json_decode(file_get_contents("php://input"), true);

if (
    !is_array($data) ||
    !isset($data["loginId"], $data["password"]) ||
    !is_string($data["loginId"]) ||
    !is_string($data["password"]) ||
    trim($data["loginId"]) === "" ||
    $data["password"] === ""
) {
    loginResponse(400, [
        "error" => "Please enter your Login ID and Password."
    ]);
}

$loginId = trim($data["loginId"]);
$password = $data["password"];

try {
    $stmt = $conn->prepare(
        "SELECT id, name, enrollment, email, password, role
         FROM users
         WHERE enrollment = ? OR email = ?
         LIMIT 1"
    );

    $stmt->bind_param("ss", $loginId, $loginId);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    $stmt->close();

    if (!$user) {
        $conn->close();

        loginResponse(401, [
            "error" => "Account not found in the MySQL users table."
        ]);
    }

    $storedPassword = $user["password"];

    // Verify the entered password against the stored hash.
    $passwordMatches = password_verify(
        $password,
        $storedPassword
    );

    if (!$passwordMatches) {
        $conn->close();

        loginResponse(401, [
            "error" => "Password does not match the registered account."
        ]);
    }

    if (!in_array($user["role"], ["student", "admin"], true)) {
        $conn->close();

        loginResponse(403, [
            "error" => "Invalid account role."
        ]);
    }

    startSecureSession();
    session_regenerate_id(true);

    $_SESSION["user_id"] = (int) $user["id"];
    $_SESSION["name"] = $user["name"];
    $_SESSION["role"] = $user["role"];
    $_SESSION["last_activity"] = time();

    $dashboard = $user["role"] === "admin"
        ? "/Webdev/HTML/admin-dashboard.php"
        : "/Webdev/api/Studentdashboard.php";

    $conn->close();

    loginResponse(200, [
        "success" => true,
        "role" => $user["role"],
        "redirect" => $dashboard
    ]);

} catch (mysqli_sql_exception $e) {
    error_log("Login database error: " . $e->getMessage());

    if (isset($stmt) && $stmt instanceof mysqli_stmt) {
        $stmt->close();
    }

    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }

    loginResponse(500, [
        "error" => "Database error. Check your database connection and users table."
    ]);
}

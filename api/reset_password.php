
<?php
require_once __DIR__ . "/db_mysqli.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $loginId = trim($_POST["loginId"] ?? "");
    $newPassword = $_POST["newPassword"] ?? "";

    if ($loginId === "" || $newPassword === "") {
        $message = "Please fill in all fields.";
    } elseif (strlen($newPassword) < 8) {
        $message = "Password must be at least 8 characters.";
    } else {
        $stmt = $conn->prepare(
            "SELECT id FROM users WHERE enrollment = ? OR email = ? LIMIT 1"
        );
        $stmt->bind_param("ss", $loginId, $loginId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if (!$user) {
            $message = "Account not found.";
        } else {
            $hash = password_hash($newPassword, PASSWORD_DEFAULT);

            $stmt = $conn->prepare(
                "UPDATE users SET password = ? WHERE id = ?"
            );
            $stmt->bind_param("si", $hash, $user["id"]);
            $stmt->execute();
            $stmt->close();

            $message = "Password reset successful. You can now log in.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset StudentHub Password</h2>

    <form method="POST">
        <label>Enrollment or Email:</label><br>
        <input type="text" name="loginId" required><br><br>

        <label>New Password:</label><br>
        <input type="password" name="newPassword" minlength="8" required><br><br>

        <button type="submit">Reset Password</button>
    </form>

    <p><?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?></p>
</body>
</html>

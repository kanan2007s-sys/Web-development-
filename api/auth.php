
<?php

function startSecureSession()
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    ini_set("session.use_strict_mode", "1");
    ini_set("session.use_only_cookies", "1");

    session_set_cookie_params([
        "lifetime" => 0,
        "path" => "/",
        "secure" => false,
        "httponly" => true,
        "samesite" => "Lax"
    ]);

    session_start();
}

function requireLogin()
{
    startSecureSession();

    $timeout = 900; // 15 minutes

    if (
        !isset($_SESSION["user_id"]) ||
        !isset($_SESSION["role"]) ||
        !isset($_SESSION["last_activity"])
    ) {
header("Location: /Webdev/HTML/firstpage.html");        exit;
    }

    if (time() - $_SESSION["last_activity"] >= $timeout) {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                "",
                [
                    "expires" => time() - 42000,
                    "path" => $params["path"],
                    "domain" => $params["domain"],
                    "secure" => $params["secure"],
                    "httponly" => $params["httponly"],
                    "samesite" => "Lax"
                ]
            );
        }

        session_destroy();

header("Location: /Webdev/HTML/firstpage.html?timeout=1");        exit;
    }

    $_SESSION["last_activity"] = time();
}

function requireRole($role)
{
    if (
        !isset($_SESSION["role"]) ||
        $_SESSION["role"] !== $role
    ) {
        http_response_code(403);
        exit("403 Forbidden: You are not authorized to access this page.");
    }
}

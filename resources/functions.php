<?php
session_start();

function startMySQL(): bool|mysqli
{
    $mysqlHostname = "";
    $mysqlUser = "";
    $mysqlPass = "";
    $mysqlDB = "";

    return mysqli_connect($mysqlHostname, $mysqlUser, $mysqlPass, $mysqlDB);
}

function isPost()
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        return true;
    }
}

function preliminaryLoginCheck($email, $password): bool
{
    if (strlen($email) > 254 || strlen($email) < 3 || preg_match("/^[a-z0-9!#$%&'*+\\/=?^_`{|}~-]+(?:\\.[a-z0-9!#$%&'*+\\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/", $email) != 1 || strlen($password) < 8 || strlen($password) > 64) {
        return false;
    }
    return true;
}

function preliminarySignUpCheck($email, $password, $rpassword): string
{
    if (strlen($email) > 254 || strlen($email) < 3 || preg_match("/^[a-z0-9!#$%&'*+\\/=?^_`{|}~-]+(?:\\.[a-z0-9!#$%&'*+\\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/", $email) != 1) {
        return "Error: Email address is invalid";
    } elseif (strlen($password) < 8 || strlen($password) > 64) {
        return "Error: Password must be between 8 characters and 64 characters";
    } elseif ($password != $rpassword) {
        return "Error: Passwords do not match";
    }
    return "Success";
}

function isEmailUsed($emailAddress)
{
    $mysqlConn = startMySQL();
    if ($mysqlConn === false) {
        die("ERROR");
    }
    $sql = "SELECT acc_id FROM users WHERE email=?";
    $stmt = $mysqlConn->prepare($sql);
    $stmt->bind_param("s", $emailAddress);
    $stmt->execute();
    $emailFound = (bool)$stmt->get_result()->fetch_row();
    if ($emailFound) {
        return true;
    } else {
        return false;
    }
    mysqli_stmt_close($stmt);
    mysqli_close($mysqlConn);
}

function createAccount($emailAddress, $password)
{
    $mysqlConn = startMySQL();
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $creationTime = (string)time();
    $creationIP = ip2long($_SERVER["REMOTE_ADDR"]);
    if ($mysqlConn === false) {
        die("ERROR");
    }
    $sql = "INSERT INTO users (email, password, creation_time, creation_ip) VALUES (?, ?, ?, ?)";
    if ($stmt = mysqli_prepare($mysqlConn, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssi", $emailAddress, $hashedPassword, $creationTime, $creationIP);
        mysqli_stmt_execute($stmt);
        return true;
    } else {
        return false;
    }
    mysqli_stmt_close($stmt);
    mysqli_close($mysqlConn);
}

function accountLogin($emailAddress, $password)
{
    $mysqlConn = startMySQL();
    if ($mysqlConn === false) {
        die("ERROR");
    }
    $sql = "SELECT acc_id, email, password FROM users WHERE email=?";
    $stmt = $mysqlConn->prepare($sql);
    $stmt->bind_param("s", $emailAddress);
    $stmt->execute();
    $result = $stmt->get_result();
    $retrievedAccount = $result->fetch_assoc();
    if (password_verify($password, $retrievedAccount["password"])) {
        loginAttempt((string)$retrievedAccount["acc_id"], "true");
        $_SESSION["email"] = $emailAddress;
        $_SESSION["acc_id"] = $retrievedAccount["acc_id"];
        header("Location: /account/index.php");
        return true;
    } else {
        loginAttempt($retrievedAccount["acc_id"], "false");
        return false;
    }
    mysqli_stmt_close($stmt);
    mysqli_close($mysqlConn);
}

function getCreationIP($acc_id)
{
    $mysqlConn = startMySQL();
    if ($mysqlConn === false) {
        die("ERROR");
    }
    $sql = "SELECT creation_ip FROM users WHERE acc_id=?";
    $stmt = $mysqlConn->prepare($sql);
    $stmt->bind_param("i", $acc_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $retrievedAccount = $result->fetch_assoc();
    return $retrievedAccount["creation_ip"];
    mysqli_stmt_close($stmt);
    mysqli_close($mysqlConn);
}

function getCreationTime($acc_id)
{
    $mysqlConn = startMySQL();
    if ($mysqlConn === false) {
        die("ERROR");
    }
    $sql = "SELECT creation_time FROM users WHERE acc_id=?";
    $stmt = $mysqlConn->prepare($sql);
    $stmt->bind_param("s", $acc_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $retrievedAccount = $result->fetch_assoc();
    return $retrievedAccount["creation_time"];
    mysqli_stmt_close($stmt);
    mysqli_close($mysqlConn);
}

function getLoginAttempts($acc_id)
{
    $mysqlConn = startMySQL();
    if ($mysqlConn === false) {
        die("ERROR");
    }
    $sql = "SELECT * FROM login_attempts WHERE acc_id=?";
    $stmt = $mysqlConn->prepare($sql);
    $stmt->bind_param("s", $acc_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    mysqli_close($mysqlConn);
}


function loginAttempt($acc_id, $is_successful): void
{
    $mysqlConn = startMySQL();
    $loginTime = (string)time();
    $loginIP = ip2long($_SERVER["REMOTE_ADDR"]);
    if ($mysqlConn === false) {
        die("ERROR");
    }
    $sql = "INSERT INTO login_attempts (acc_id, is_successful, login_time, login_ip) VALUES (?, ?, ?, ?)";
    if ($stmt = mysqli_prepare($mysqlConn, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssi", $acc_id, $is_successful, $loginTime, $loginIP);
        mysqli_stmt_execute($stmt);
    }
    mysqli_stmt_close($stmt);
    mysqli_close($mysqlConn);
}

function isLoggedIn(): bool
{
    if (isset($_SESSION["acc_id"])) {
        return true;
    }
    return false;
}

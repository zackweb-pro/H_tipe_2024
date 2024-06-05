<?php
session_start(); // Start the session
include 'conn_db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usr = $_POST['username'];
    $pwd = $_POST['password'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT id, pwd, poste FROM users WHERE usr = ?");
    $stmt->bind_param("s", $usr);
    $stmt->execute();
    $stmt->bind_result($id, $hashedPwd, $poste);
    $stmt->fetch();

    if (password_verify($pwd, $hashedPwd)) {
        // Store user ID in session
        $_SESSION['user_id'] = $id;

        // Redirect based on the poste value
        if ($poste == "c") {
            header("Location: dashboard.php");
        } else if ($poste == "s") {
            header("Location: getplace.php");
        }
        exit();
    } else {
        // Redirect with error message
        header("Location: login.html?status=error");
        exit();
    }

    $stmt->close();
}

$conn->close();
?>

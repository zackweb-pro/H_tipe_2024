<?php
include 'conn_db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $usr = $_POST['usr'];
    $pwd = password_hash($_POST['pwd'], PASSWORD_BCRYPT);
    $poste = $_POST['poste'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO users (prenom, nom, email, usr, pwd, poste) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $prenom, $nom, $email, $usr, $pwd, $poste);

    if ($stmt->execute()) {
        header("Location: register.html?status=success");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

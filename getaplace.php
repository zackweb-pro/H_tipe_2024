<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // Redirect to login if user ID is not set
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
// Fetch and display user-specific data using $user_id
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HIBA TIPE 2024</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="imgs/67675.png">

</head>
<body>
    <nav>
        <h1>Hiba @ TIPE 2024</h1>
    </nav>
    <img src="imgs/Valfréjus télécabine.jpeg" alt="">
    <form action="" style="margin: auto;">
        <select name="nom_station">
            <option value="agadir">telephirique agadir</option>
            <option value="tobqal">telephirique tobqal</option>
        </select>
        <button>choisir</button>
        <!-- <a href="login.html" style="text-align: center; display: block;">vous avez déjà un compte?</a> -->
    </form>
    <script src="app.js"></script>
</body>
</html>
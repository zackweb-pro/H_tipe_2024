<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    // Redirect to login if user ID is not set
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
include 'conn_db.php';

// Fetch user state
$stmt = $conn->prepare("SELECT state FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($state);
$stmt->fetch();
$stmt->close();

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $station_name = $_POST['telesiege_name'];
    $nbr_t = $_POST['nbr_t'];
    $nbr_p = $_POST['nbr_p'];

    if ($state == 0) {
        // Insert new station and update user state to 1
        $stmt = $conn->prepare("INSERT INTO stations (user_id, station_name, nbr_t, nbr_p) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isii", $user_id, $station_name, $nbr_t, $nbr_p);
        $stmt->execute();
        $stmt->close();

        // Get the station ID
        $stmt = $conn->prepare("SELECT id_station FROM stations WHERE user_id = ? ORDER BY id_station DESC LIMIT 1");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($station_id);
        $stmt->fetch();
        $stmt->close();

        // Insert télésièges and places
        for ($i = 1; $i <= $nbr_t; $i++) {
            $stmt = $conn->prepare("INSERT INTO telesieges (id_station, id_telesiege, nbr_places) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $station_id, $i, $nbr_p);
            $stmt->execute();
            $telesiege_id = $stmt->insert_id;
            $stmt->close();

            for ($j = 1; $j <= $nbr_p; $j++) {
                $stmt = $conn->prepare("INSERT INTO places (id_station, id_telesiege, state) VALUES (?, ?, 0)");
                $stmt->bind_param("ii", $station_id, $telesiege_id);
                $stmt->execute();
                $stmt->close();
            }
        }

        $stmt = $conn->prepare("UPDATE users SET state = 1 WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();

        // Redirect to refresh the state with a success message
        header("Location: dashboard.php?message=added");
        exit();
    } else {
        // Update existing station
        $stmt = $conn->prepare("UPDATE stations SET station_name = ?, nbr_t = ?, nbr_p = ? WHERE user_id = ?");
        $stmt->bind_param("siii", $station_name, $nbr_t, $nbr_p, $user_id);
        $stmt->execute();
        $stmt->close();

        // Get the station ID
        $stmt = $conn->prepare("SELECT id_station FROM stations WHERE user_id = ? ORDER BY id_station DESC LIMIT 1");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($station_id);
        $stmt->fetch();
        $stmt->close();

        // Delete existing télésièges and places
        $stmt = $conn->prepare("DELETE FROM telesieges WHERE id_station = ?");
        $stmt->bind_param("i", $station_id);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM places WHERE id_station = ?");
        $stmt->bind_param("i", $station_id);
        $stmt->execute();
        $stmt->close();

        // Insert new télésièges and places
        for ($i = 1; $i <= $nbr_t; $i++) {
            $stmt = $conn->prepare("INSERT INTO telesieges (id_station, id_telesiege, nbr_places) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $station_id, $i, $nbr_p);
            $stmt->execute();
            $telesiege_id = $stmt->insert_id;
            $stmt->close();

            for ($j = 1; $j <= $nbr_p; $j++) {
                $stmt = $conn->prepare("INSERT INTO places (id_station, id_telesiege, state) VALUES (?, ?, 0)");
                $stmt->bind_param("ii", $station_id, $telesiege_id);
                $stmt->execute();
                $stmt->close();
            }
        }

        // Redirect to refresh the state with a success message
        header("Location: dashboard.php?message=updated");
        exit();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HIBA TIPE 2024</title>
    <link rel="icon" href="imgs/67675.png">
    <link rel="stylesheet" href="style.css">

    <style>
        p.success-message {
                color: green;
                margin: auto;
                display: block;
            }
    </style>
</head>
<body>
    <nav>
        <h1>Hiba @ TIPE 2024</h1>
        <button style="position: absolute; top: 25px; right: 5px;  background: red; border: none; cursor:pointer; padding: .4rem .7rem; border-radius: 7px;"><a href="logout.php" style="color: white">Logout</a></button>
    </nav>

    <?php if ($state == 0): ?>
        <form action="dashboard.php" method="POST" style="margin: auto;">
        <?php
                if (isset($_GET['message'])) {
                    if ($_GET['message'] == 'added') {
                        echo "<p class='success-message'>Station ajoutée avec succès !</p>";
                    } elseif ($_GET['message'] == 'updated') {
                        echo "<p class='success-message'>Station mise à jour avec succès !</p>";
                    }
                }
                ?>
            <input type="text" name="telesiege_name" placeholder="le nom de station" required>
            <input type="number" name="nbr_t" placeholder="le nombre des telesiege" required>
            <input type="number" name="nbr_p" placeholder="le nombre des places dans chaque telesiege" required>
            <button type="submit">Ajouter</button>
            <a href="reservations.php">voullez-vous contrôler les reservation?</a>
        </form>
    <?php else: ?>
        <form action="dashboard.php" method="POST" style="margin: auto;">
        <?php
                if (isset($_GET['message'])) {
                    if ($_GET['message'] == 'added') {
                        echo "<p class='success-message'>Station ajoutée avec succès !</p>";
                    } elseif ($_GET['message'] == 'updated') {
                        echo "<p class='success-message'>Station mise à jour avec succès !</p>";
                    }
                }
                ?>
            <input type="text" name="telesiege_name" placeholder="modifier le nom de station" required>
            <input type="number" name="nbr_t" placeholder="modifier le nombre des telesiege" required>
            <input type="number" name="nbr_p" placeholder="modifier le nombre des places dans chaque telesiege" required>
            <button type="submit">Modifier</button>
            <a href="reservations.php">voullez-vous contrôler les reservation?</a>
        </form>
    <?php endif; ?>
    <script src="app.js"></script>
</body>
</html>

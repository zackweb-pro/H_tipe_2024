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

// Fetch reserved places
$stmt = $conn->prepare("SELECT p.id, p.unique_code, p.id_telesiege, u.nom, u.prenom 
                        FROM places p 
                        JOIN users u ON p.reserved_by = u.id 
                        WHERE p.id_station = (SELECT id_station FROM stations WHERE user_id = ?) AND p.state = 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$reservations = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_attendance'])) {
    $place_id = $_POST['place_id'];
    // Remove the reservation by setting the state to 0 and clearing reserved_by and unique_code
    $stmt = $conn->prepare("UPDATE places SET state = 0, reserved_by = NULL, unique_code = NULL WHERE id = ?");
    $stmt->bind_param("i", $place_id);
    $stmt->execute();
    $stmt->close();
    // Redirect to refresh the page after removing the reservation
    header("Location: reservations.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_reservation'])) {
    $place_id = $_POST['place_id'];
    // Remove the reservation by setting the state to 0 and clearing reserved_by and unique_code
    $stmt = $conn->prepare("UPDATE places SET state = 0, reserved_by = NULL, unique_code = NULL WHERE id = ?");
    $stmt->bind_param("i", $place_id);
    $stmt->execute();
    $stmt->close();
    // Redirect to refresh the page after removing the reservation
    header("Location: reservations.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserved Places</title>
    <script src="https://kit.fontawesome.com/0a18d87a1d.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="styleres.css">
    <style>

        @media only screen and (max-width: 600px) {
            .styled-table{
                font-size: 12px;
                width: 400px !important;
            }
            h1{
                font-size: 12px;
            }
            
        }
    </style>
</head>
<body>
    <nav>
        <button onmouseover="this.style.border='none';" style="position: absolute; top: 25px; left: 10px; border: none; background: none; font-size: 25px;"><a href="dashboard.php" style="color: white; ">&larr;</a></button>
        
        <h1>Hiba's TIPE 2024</h1>
        <button style="position: absolute; top: 25px; right: 5px;  background: red; border: none; cursor:pointer; padding: .4rem .7rem; border-radius: 7px;"><a href="logout.php" style="color: white">Logout</a></button>
    </nav>
    <div class="reserved-places-container">
        <?php if (!empty($reservations)): ?>
            <table class="styled-table">
                <thead>
                <tr>
                    <th>Telesiege n°</th>
                    <th>Unique Code</th>
                    <th>Nom et Prénom</th>
                    <th>Confirmer la présence</th>
                    <th>supprimer la réservation</th>
                </tr>
                </thead>
                <?php foreach ($reservations as $reservation): ?>
                    <tr>
                        <td><?php echo $reservation['id_telesiege']; ?></td>
                        <td><?php echo $reservation['unique_code']; ?></td>
                        <td><?php echo $reservation['prenom'] . " " . $reservation['nom']; ?></td>
                        <td>
                            <form action="reservations.php" method="POST">
                                <input type="hidden" name="place_id" value="<?php echo $reservation['id']; ?>">
                                <button onmouseover="this.style.border='none';" style="background: none; max-width: 55px; color: green; font-size: 25px;" type="submit" name="confirm_attendance"><i class="fa-solid fa-check"></i></button>
                            </form>
                        </td>
                        <td>
                            <form action="reservations.php" method="POST">
                                <input type="hidden" name="place_id" value="<?php echo $reservation['id']; ?>">
                                <button onmouseover="this.style.border='none';" style="background: none; max-width: 55px; color: red; font-size: 25px;" type="submit" name="remove_reservation"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>vous avez pas du réservations.</p>
        <?php endif; ?>
    </div>
</body>
</html>

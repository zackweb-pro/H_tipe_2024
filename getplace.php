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

// Fetch user's reserved place if any
$stmt = $conn->prepare("SELECT p.unique_code, s.station_name, t.id
                        FROM places p
                        JOIN stations s ON p.id_station = s.id_station
                        JOIN telesieges t ON p.id_telesiege = t.id
                        WHERE p.reserved_by = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($unique_code, $station_name, $telesiege_id);
$reserved = $stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reserve_place'])) {
    $place_id = $_POST['place_id'];
    $unique_code = uniqid();

    // Check if the place is still available
    $stmt = $conn->prepare("SELECT state FROM places WHERE id = ?");
    $stmt->bind_param("i", $place_id);
    $stmt->execute();
    $stmt->bind_result($state);
    $stmt->fetch();
    $stmt->close();

    if ($state == 0 && !$reserved) {
        // Reserve the place
        $stmt = $conn->prepare("UPDATE places SET state = 1, unique_code = ?, reserved_by = ? WHERE id = ?");
        $stmt->bind_param("sii", $unique_code, $user_id, $place_id);
        $stmt->execute();
        $stmt->close();
        header("Location: getplace.php?message=reserved");
        exit();
    } else {
        header("Location: getplace.php?message=unavailable");
        exit();
    }
}

// Fetch available stations
$stations = [];
if (!$reserved) {
    $result = $conn->query("SELECT id_station, station_name FROM stations");
    while ($row = $result->fetch_assoc()) {
        $stations[] = $row;
    }
    $result->close();
}

// Fetch available places if a station is selected
$available_places = [];
if (isset($_GET['station_id'])) {
    $station_id = $_GET['station_id'];
    $stmt = $conn->prepare("SELECT id, id_telesiege FROM places WHERE id_station = ? AND state = 0");
    $stmt->bind_param("i", $station_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $available_places[] = $row;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserve a Place</title>
    <link rel="icon" href="imgs/67675.png">
    <link rel="stylesheet" href="stylegetplace.css">
</head>
<body>
    <nav>
        <h1>Hiba @ TIPE 2024</h1>
        <button style="position: absolute; top: 25px; right: 5px;  background: red; border: none; cursor:pointer; padding: .4rem .7rem; border-radius: 7px;"><a href="logout.php" style="color: white">Logout</a></button>

    </nav>
    <div class="container">
        <?php
        if (isset($_GET['message'])) {
            if ($_GET['message'] == 'reserved') {
                echo "<p class='success-message'>Place réservée avec succès !</p>";
                // $reserved = 1;
            } elseif ($_GET['message'] == 'unavailable') {
                echo "<p class='error-message'>Place non disponible ou déjà réservée !</p>";
            }
        }
        ?>
        <?php if ($reserved): ?>
            <h2>Votre réservation :</h2>
            <p>Code unique : <span class="code"> <?php echo htmlspecialchars($unique_code); ?> </span></p>
            <p>Station : <span class="code"> <?php echo htmlspecialchars($station_name); ?> </span></p>
            <p>Télésiège n° :<span class="code"> <?php echo htmlspecialchars($telesiege_id); ?></span></p>
        <?php else: ?>
            <form method="GET" action="getplace.php">
                <label for="station">Sélectionnez une station :</label>
                <select name="station_id" id="station" onchange="this.form.submit()" required>
                    <option value="">--Sélectionnez une station--</option>
                    <?php foreach ($stations as $station): ?>
                        <option value="<?php echo $station['id_station']; ?>" <?php if (isset($_GET['station_id']) && $_GET['station_id'] == $station['id_station']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($station['station_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>

            <?php if (isset($_GET['station_id'])): ?>
                <h3>Places disponibles pour la station sélectionnée :</h3>
                <ul>
                    <?php foreach ($available_places as $place): ?>
                        <li>
                            Télésiège n°<?php echo $place['id_telesiege']; ?>
                            <form method="POST" action="getplace.php" style="display:inline;">
                                <input type="hidden" name="place_id" value="<?php echo $place['id']; ?>">
                                <button type="submit" name="reserve_place">Réserver</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                    <?php if (empty($available_places)): ?>
                        <li>Aucune place disponible.</li>
                    <?php endif; ?>
                </ul>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <script src="app.js"></script>
</body>
</html>

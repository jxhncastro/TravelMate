<?php
session_start();
@include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: itineraries.php");
    exit();
}

$userID = $_SESSION['user_id'];
$itineraryID = intval($_GET['id']);

// Fetch current itinerary details
$query = "SELECT * FROM itineraries WHERE itineraryID = ? AND userID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $itineraryID, $userID);
$stmt->execute();
$result = $stmt->get_result();
$itinerary = $result->fetch_assoc();
$stmt->close();

if (!$itinerary) {
    header("Location: itineraries.php");
    exit();
}

// Update itinerary
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newTitle = trim($_POST['title']);

    if (!empty($newTitle)) {
        $updateQuery = "UPDATE itineraries SET title = ? WHERE itineraryID = ? AND userID = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("sii", $newTitle, $itineraryID, $userID);
        if ($stmt->execute()) {
            header("Location: trip_details.php?id=" . $itineraryID);
            exit();
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Itinerary</title>
    <link rel="stylesheet" href="css/edit_itineraries.css">
</head>
<body>
    <header>
    <div class="logo">
            <img src="css/assets/logo.png" alt="Logo">
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="home.html">Home</a></li>
                <li><a href="itineraries.php" class="active">Trips</a></li>
                <li><a href="maps.html">Maps</a></li>
                <li><a href="aboutus.html">About Us</a></li>
            </ul>
        </nav>
        <div class="nav-buttons">
            <div class="profile">
                <a href="profile.php">
                    <img src="css/assets/profile.png" alt="Profile">
                </a>
            </div>
        </div>
    </header>
    <div class="container">
    </header>
    <div class="container">
        <h2>Edit Itinerary</h2>

        <form method="POST">
            <label for="title">Itinerary Title:</label>
            <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($itinerary['title']); ?>" required>
            <button type="submit">Save Changes</button>
        </form>

        <a href="itineraries.php?id=<?php echo $itineraryID; ?>" class="back-btn">Cancel</a>
    </div>
</body>
</html>

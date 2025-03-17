<?php
session_start();
@include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['user_id'];

$query = "SELECT itineraryID, title FROM itineraries WHERE userID = ? ORDER BY itineraryID ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

$itineraries = [];
while ($row = $result->fetch_assoc()) {
    $itineraries[] = $row;
}
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['itinerary_selection'])) {
    $days = intval($_POST['itinerary_selection']);
    $selectedDay = "Ilocos Trip ($days Days)";

    $insertQuery = "INSERT INTO itineraries (userID, title) VALUES (?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("is", $userID, $selectedDay);
    
    if ($stmt->execute()) {
        $itineraryID = $stmt->insert_id;  // Get the new itinerary ID

        // Sample itinerary activities
        $itineraryActivities = [
            1 => ["Check-in at a DOT-accredited accommodation establishment in Laoag City", 
            "Solsona-Apayao Road", 
            "Lunch at Solsona",
            "St. Augustine Parish Church",
            "Malacañang of the North",
            "Sinking Bell Tower",
            "Ilocos Norte Provincial Capitol",
            "Dinner at Laoag City"],
            2 => ["La Virgen Milagrosa",
            "Badoc Island",
            "Lunch at Badoc",
            "Batac Empanadaan",
            "Marcos Museum",
            "Immaculate Conception Parish - Batac Church",
            "Batac Mini Park",
            "Paoay Sand Dunes",
            "Dinner at food establishments along the Paoay Lake"],
            3 => ["Kapurpurawan Rock Formation",
            "Pagudpud Arch",
            "Check-in at Pagudpud and lunch",
            "Saud Beach",
            "Dinner at Pagudpud"],
            4 => ["Bantay Abot Cave",
            "Lunch at Barangay Pancian, Pagudpud",
            "Patapat Viaduct",
            "Blue Lagoon",
            "Dinner at Pagudpud"],
            5 => ["Free-time or swimming at the Blue Lagoon", 
            "Lunch at Barangay Balaoi, Pagudpud", 
            "Cape Bojeador Lighthouse",
            "Dinner at Laoag City or San Nicolas"]
        ];

        // Insert activities for the selected days
        $activityQuery = "INSERT INTO itinerary_activities (itineraryID, activity_day, activity_name) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($activityQuery);
        foreach ($itineraryActivities as $day => $activities) {
            if ($day <= $days) {
                foreach ($activities as $activity) {
                    $stmt->bind_param("iis", $itineraryID, $day, $activity);
                    $stmt->execute();
                }
            }
        }

        header("Location: itineraries.php");
        exit();
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TravelMate - My Trips</title>
    <link rel="stylesheet" href="css/itineraries.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>

<!-- Navbar -->
<header class="navbar">
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
    <div class="my-trips container">
    <h2 class="section-title">MY TRIPS</h2>

    <?php if (empty($itineraries)): ?>
        <div class="no-trips">
            <img src="css/assets/trips.png" alt="No Trips">
            <p>You don't have any trips yet.</p>
        </div>
    <?php else: ?>
        <ul class="trip-list">
            <?php foreach ($itineraries as $itinerary): ?>
                <li class="trip-item">
                    <h3><?php echo htmlspecialchars($itinerary['title']); ?></h3>
                    <div class="trip-actions">
                        <a href="trip_details.php?id=<?php echo $itinerary['itineraryID']; ?>" class="view-btn">View</a>
                        <a href="delete_itinerary.php?id=<?php echo $itinerary['itineraryID']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this itinerary?');">Delete</a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <button id="create-trip-btn">Create a Trip</button>
</div>
    <div id="trip-modal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h3>Select Trip Duration</h3>
            <button class="duration-btn" data-days="3">3 Days</button>
            <button class="duration-btn" data-days="4">4 Days</button>
            <button class="duration-btn" data-days="5">5 Days</button>
        </div>
    </div>

    <div id="suggested-itineraries" style="display:none;">
        <h3>Suggested Itinerary</h3>
        <div id="suggestions"></div>
        <form method="POST">
            <input type="hidden" name="itinerary_selection" id="selected_days">
            <button type="submit" id="create-trip-btn">Add Itinerary</button>
        </form>
    </div>
</div>
    <script src="js/view_itinerary.js"></script>
</body>
</html>

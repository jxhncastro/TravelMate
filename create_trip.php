<?php
session_start();
@include 'config.php';

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['user_id'];
$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $startDateTime = isset($_POST['startDateTime']) ? $_POST['startDateTime'] : '';
    $endDateTime = isset($_POST['endDateTime']) ? $_POST['endDateTime'] : '';
    $destination = isset($_POST['destination']) ? trim($_POST['destination']) : '';
    $notes = isset($_POST['notes']) ? trim($_POST['notes']) : NULL;
    $description = isset($_POST['description']) ? trim($_POST['description']) : NULL;

    if (!empty($title) && !empty($startDateTime) && !empty($endDateTime) && !empty($destination)) {
        // Ensure database connection is established
        if (!$conn) {
            die("Database connection error: " . mysqli_connect_error());
        }

        // Insert trip details into the database
        $query = "INSERT INTO itineraries (userID, title, startDateTime, endDateTime, destination, notes, description) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("issssss", $userID, $title, $startDateTime, $endDateTime, $destination, $notes, $description);

        if ($stmt->execute()) {
            header("Location: itineraries.php"); // Redirect after successful save
            exit();
        } else {
            $error = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $error = "Please fill in all required fields!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Trip</title>
    <link rel="stylesheet" href="css/createtrips.css">
</head>
<body>

<!-- Navbar -->
<header class="navbar">
        <div class="logo">
            <img src="css/assets/logo.png" alt="">
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="home.html">Home</a></li>
                <li><a href="itineraries.php">Trips</a></li>
                <li><a href="maps.html">Maps</a></li>
                <li><a href="aboutus.html" class="active">About Us</a></li>
            </ul>
        </nav>
        <div class="nav-buttons">
            <div class="profile">
                <a href="login.php">
             <img src="css/assets/profile.png" alt="" class="profile">
                </a>
            </div>
        </div>
    </header>

<div class="container">
    <h2>Create a New Trip</h2>

    <?php if (!empty($error)): ?>
        <p class="error-msg"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form action="" method="POST">
        <label for="title">Trip Title:</label>
        <input type="text" id="title" name="title" required>

        <label for="startDateTime">Start Date & Time:</label>
        <input type="datetime-local" id="startDateTime" name="startDateTime" required>

        <label for="endDateTime">End Date & Time:</label>
        <input type="datetime-local" id="endDateTime" name="endDateTime" required>

        <label for="destination">Destination:</label>
        <input type="text" id="destination" name="destination" required>

        <label for="notes">Notes (optional):</label>
        <textarea id="notes" name="notes"></textarea>

        <label for="description">Description:</label>
        <textarea id="description" name="description"></textarea>

        <button type="submit">Save Trip</button>
    </form>
</div>

</body>
</html>

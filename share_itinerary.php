<?php
session_start();
@include 'config.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['user_id'];
$itineraryID = $_GET['id'] ?? null;
$error = "";
$success = "";

// Check if itinerary belongs to the logged-in user
$query = "SELECT * FROM itineraries WHERE itineraryID = ? AND userID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $itineraryID, $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Invalid Itinerary Access!");
}

// Handle the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['share_with'])) {
    $shareWith = trim($_POST['share_with']);

    // Check if the user exists in the database
    $userQuery = "SELECT userID FROM users WHERE email = ?";
    $stmt = $conn->prepare($userQuery);
    $stmt->bind_param("s", $shareWith);
    $stmt->execute();
    $userResult = $stmt->get_result();
    
    if ($userResult->num_rows > 0) {
        $sharedUser = $userResult->fetch_assoc();
        $sharedUserID = $sharedUser['userID'];

        // Check if already shared
        $checkQuery = "SELECT * FROM shared_itineraries WHERE itineraryID = ? AND sharedWithUserID = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("ii", $itineraryID, $sharedUserID);
        $stmt->execute();
        $checkResult = $stmt->get_result();

        if ($checkResult->num_rows > 0) {
            $error = "This itinerary is already shared with this user.";
        } else {
            // Insert into shared_itineraries table
            $insertQuery = "INSERT INTO shared_itineraries (itineraryID, ownerID, sharedWithUserID) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("iii", $itineraryID, $userID, $sharedUserID);
            
            if ($stmt->execute()) {
                $success = "Itinerary successfully shared!";
            } else {
                $error = "Error sharing itinerary. Try again.";
            }
        }
    } else {
        $error = "User not found. Please enter a valid email or username.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Share Itinerary</title>
    <link rel="stylesheet" href="css/share.css">
</head>
<body>

    <div class="container">
        <h2>Share Itinerary</h2>
        
        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <p class="success"><?= $success ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="share_with">Enter Email or Username:</label>
            <input type="text" name="share_with" required placeholder="Enter email or username">
            <button type="submit">Share</button>
        </form>

        <a href="itineraries.php" class="back-btn">Back to Itineraries</a>
    </div>

</body>
</html>

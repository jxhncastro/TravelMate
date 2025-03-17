<?php
session_start();
@include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "Invalid request.";
    exit();
}

$itineraryID = intval($_GET['id']);
$userID = $_SESSION['user_id'];

// Check if the itinerary belongs to the user
$query = "SELECT * FROM itineraries WHERE itineraryID = ? AND userID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $itineraryID, $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Unauthorized action.";
    exit();
}

// Delete related activities first (to avoid foreign key issues)
$deleteActivitiesQuery = "DELETE FROM itinerary_activities WHERE itineraryID = ?";
$stmt = $conn->prepare($deleteActivitiesQuery);
$stmt->bind_param("i", $itineraryID);
$stmt->execute();

// Delete the itinerary
$deleteItineraryQuery = "DELETE FROM itineraries WHERE itineraryID = ?";
$stmt = $conn->prepare($deleteItineraryQuery);
$stmt->bind_param("i", $itineraryID);

if ($stmt->execute()) {
    header("Location: itineraries.php");
    exit();
} else {
    echo "Error deleting itinerary.";
}
$stmt->close();
?>

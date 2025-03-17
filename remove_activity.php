<?php
session_start();
@include 'config.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit();
}

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'])) {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit();
}

$activityID = intval($data['id']);

// Delete the activity
$query = "DELETE FROM itinerary_activities WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $activityID);
$success = $stmt->execute();
$stmt->close();

if ($success) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to delete activity"]);
}
?>

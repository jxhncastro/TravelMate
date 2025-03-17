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

// Fetch the itinerary details
$query = "SELECT title FROM itineraries WHERE itineraryID = ? AND userID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $itineraryID, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Itinerary not found.";
    exit();
}

$itinerary = $result->fetch_assoc();
$stmt->close();

// Fetch activities related to this itinerary
$activityQuery = "SELECT id, activity_name, activity_day FROM itinerary_activities WHERE itineraryID = ? ORDER BY activity_day ASC";
$stmt = $conn->prepare($activityQuery);
$stmt->bind_param("i", $itineraryID);
$stmt->execute();
$activitiesResult = $stmt->get_result();

$activities = [];
while ($row = $activitiesResult->fetch_assoc()) {
    $activities[$row['activity_day']][] = $row;
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($itinerary['title']); ?></title>
    <!-- <link rel="stylesheet" href="css/itineraries.css"> -->
     <link rel="stylesheet" href="css/tripdetails.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
</head>
<body>

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

<div class="container" id="printable-area">
    <h2><?php echo htmlspecialchars($itinerary['title']); ?></h2>
    
    <?php if (empty($activities)): ?>
        <p>No activities added yet.</p>
    <?php else: ?>
        <?php foreach ($activities as $day => $activityList): ?>
            <h3>Day <?php echo $day; ?></h3>
            <ul id="day-<?php echo $day; ?>" class="sortable">
                <?php foreach ($activityList as $activity): ?>
                    <li data-id="<?php echo $activity['id']; ?>">
                        <?php echo htmlspecialchars($activity['activity_name']); ?>
                        <button class="remove-btn" data-id="<?php echo $activity['id']; ?>">Remove</button>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endforeach; ?>
    <?php endif; ?>

    <a href="itineraries.php" class="view-btn">Back to My Trips</a>
    <button class="print-btn" onclick="window.print()">Print</button>
    <button class="share-btn" onclick="shareItinerary()">Share Itinerary</button>
</div>

<script>
document.querySelectorAll(".sortable").forEach(list => {
    new Sortable(list, {
        animation: 150,
        onEnd: function (evt) {
            let items = evt.from.children;
            let order = [];
            for (let i = 0; i < items.length; i++) {
                order.push(items[i].dataset.id);
            }
            fetch("update_activity_order.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ order: order })
            });
        }
    });
});

document.querySelectorAll(".remove-btn").forEach(button => {
    button.addEventListener("click", function() {
        let activityId = this.dataset.id;
        fetch("remove_activity.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id: activityId })
        }).then(response => response.json())
          .then(data => {
              if (data.success) {
                  this.parentElement.remove();
              } else {
                  alert("Failed to remove activity.");
              }
          });
    });
});

function printItinerary() {
    let printContents = document.getElementById("printable-area").innerHTML;
    let originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    window.location.reload(); // Reload page to restore event listeners
}

function shareItinerary() {
    const itineraryTitle = "<?php echo addslashes($itinerary['title']); ?>";
    const itineraryUrl = window.location.href;
    const recipientEmail = prompt("Enter recipient's email address:");

    if (recipientEmail) {
        const subject = encodeURIComponent(`Check out my itinerary: ${itineraryTitle}`);
        const body = encodeURIComponent(`Hey,\n\nI wanted to share my travel itinerary with you!\n\nYou can view it here: ${itineraryUrl}\n\nSafe travels!`);
        
        window.location.href = `mailto:${recipientEmail}?subject=${subject}&body=${body}`;
    }
}

</script>

</body>
</html>

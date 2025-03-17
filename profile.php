<?php
session_start();
@include 'config.php';

// Redirect to login if the user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user's name for display
$user_name = $_SESSION['user_name'];
$user_id = $_SESSION['user_id'];

// Fetch user's trips from the database
$itineraries = [];
$sql = "SELECT itineraryID, title FROM itineraries WHERE userID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $itineraries[] = $row;
    }
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/profile.css">
</head>
<body>

<div class="dashboard-container">
    
    <aside class="sidebar">
    <a href="itineraries.php" class="menu-item logout"><i class="fas fa-arrow-left"></i> Go Back</a>
    
        <div class="profile-card">
            <img src="css/assets/profile.png" alt="Profile" class="profile-pic">
            <h3><?= htmlspecialchars($user_name) ?></h3>
        </div>
        <ul class="menu-list">
            <li><a href="#" onclick="loadContent('profile')" class="menu-item"><i class="fas fa-user"></i> My Profile</a></li>
            <li><a href="#" onclick="loadContent('settings')" class="menu-item"><i class="fas fa-cog"></i> Settings</a></li>
            <li><a href="#" onclick="loadContent('mytrips')" class="menu-item"><i class="fas fa-suitcase"></i> My Trips</a></li>
            <li><a href="#" onclick="loadContent('help')" class="menu-item"><i class="fas fa-question-circle"></i> Help Center</a></li>
            
            
        </ul>
        <a href="logout.php" class="menu-item goback"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        
    </aside>
    <main class="content-box" id="mainContent">
        <h2>Welcome, <?= htmlspecialchars($user_name) ?>!</h2>
        <p>Select an option from the menu.</p>
    </main>
</div>

    <script>
        function loadContent(section) {
    let content = document.getElementById("mainContent");
    let sections = {
        profile: "<h2>My Profile</h2><p>Manage your profile information here.</p>",
        settings: "<h2>Settings</h2><p>Update your preferences here.</p>",
        help: "<h2>Help Center</h2><p>Find answers to your questions.</p>",
        mytrips: `
            <div class="my-trips">
                <h2>My Trips</h2>
                <?php if (empty($itineraries)): ?>
                    <div>
                        <img src="css/assets/trips.png" alt="No Trips">
                        <p>You don't have any trips yet.</p>
                        <button onclick="window.location.href='itineraries.php'" class="btn btn-primary mt-3">Create Trip</button>
                    </div>
                <?php else: ?>
                    <ul class="trip-list">
                        <?php foreach ($itineraries as $itinerary): ?>
                            <li class="trip-item">
                                <h3><?= htmlspecialchars($itinerary['title']); ?></h3>
                                <div class="trip-actions">
                                    <a href="trip_details.php?id=<?= $itinerary['itineraryID']; ?>" class="view-btn">View</a>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        `
    };
    content.innerHTML = sections[section];
}
    </script>
</body>
</html>

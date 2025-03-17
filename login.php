<?php
session_start();
include 'config.php'; // Ensure this file contains $conn = new mysqli(...);

$error = []; // Initialize the error array

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (!empty($email) && !empty($password)) {
        // FIXED: Use the correct column name from your database
        $query = "SELECT userID, name, password FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Verify password using password_verify()
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_name'] = $row['name'];
                $_SESSION['user_id'] = $row['userID']; // FIXED: Use correct column name

                header("Location: profile.php");
                exit();
            } else {
                $error[] = "Incorrect email or password!";
            }
        } else {
            $error[] = "User not found!";
        }

        $stmt->close();
    } else {
        $error[] = "All fields are required!";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN FORM</title>
    <link rel="icon" href="css/assets/logo.png">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    
    <div class="form-container">
        <form action="" method="post">
            <div>
                <span class="close-btn" onclick="window.location.href='home.html'">&times;</span>
            </div>
            <center>
                <h2>
                    <img src="css/assets/logo.png" width="150px" class="logo" alt="Logo">
                </h2>
            </center>

            <?php
            // FIXED: Ensure no errors appear if the array is empty
            if (!empty($error)) {
                echo '<span class="error-msg">' . implode('<br>', $error) . '</span>';
            }
            ?>

            <input type="email" name="email" required placeholder="Enter your Email">
            <input type="password" name="password" required placeholder="Enter your Password">
            <input type="submit" name="submit" value="Log in" class="form-btn">
            <p>Don't have an account? <a href="register.php">Register Now</a></p>
        </form>
    </div>
</body>
</html>

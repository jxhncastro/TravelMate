<?php
session_start();
@include 'config.php';

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    // Check if email already exists
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error[] = "User already exists!";
    } else {
        if ($password !== $cpassword) {
            $error[] = "Passwords do not match!";
        } else {
            // Hash the password securely
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user into the database
            $insert = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insert);
            $stmt->bind_param("sss", $name, $email, $hashed_password);
            $stmt->execute();

            header("Location: login.php");
            exit();
        }
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>REGISTER FORM</title>
   <link rel="icon" href="css/assets/logo.png">
   <link rel="stylesheet" href="css/registers.css">

</head>
<body>
   
<div class="form-container">
        <form action="" method="post">
        <div>
         <span class="close-btn" onclick="window.location.href='home.html'">&times;</span> <!-- Close Button -->
         </div>
        <center>
        <img src="css/assets/logo.png" width="150px" class="logo" alt="Logo">
    <center>

      <?php
      if (!empty($error) && is_array($error)){
        foreach ($error as $msg) {
            echo '<span class="error-msg">'.$msg.'</span>';
        }
      }
      ?>

      <input type="text" name="name" required placeholder="Enter your Name">
      <input type="email" name="email" required placeholder="Enter your Email">
      <input type="password" name="password" required placeholder="Enter your Password">
      <input type="password" name="cpassword" required placeholder="Confirm your Password">
      <input type="submit" name="submit" value="register now" class="form-btn">
      <p>Already Have an account? <a href="login.php">Login</a></p>
   </form>

</div>

</body>
</html>
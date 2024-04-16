<?php
// Include database connection file
include_once 'includes/db_connect.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $full_name = $_POST['full_name'];
  
  // Check if username already exists
  $check_query = "SELECT * FROM users WHERE username = '$username'";
  $check_result = mysqli_query($conn, $check_query);
  if (mysqli_num_rows($check_result) > 0) {
    // Username already exists, redirect back with error
    header("Location: register.php?error=username_taken");
    exit();
  }
  $check_query = "SELECT * FROM users WHERE email = '$email'";
  $check_result = mysqli_query($conn, $check_query);
  if (mysqli_num_rows($check_result) > 0) {
    // Username already exists, redirect back with error
    header("Location: register.php?error=email_taken");
    exit();
  }
  
  // Hash password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);
  
  // Insert user data into database
  $query = "INSERT INTO users (username, email, password, full_name) VALUES ('$username', '$email', '$hashed_password', '$full_name')";
  $result = mysqli_query($conn, $query);
  
  if ($result) {
    // Registration successful, redirect to login page
    header("Location: login.php");
    exit();
  } else {
    // Error occurred
    echo "Error: " . mysqli_error($conn);
  }
}
?>
<!-- register.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Social Network</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
    <div class="title"><a href="/blog/index.php" style="color: #fff; text-decoration: none;">Twitter Clone</a></div>
      <nav>
        <ul>
          <li><a href="/blog/index.php">Home</a></li>
          <?php
              if (isset($_SESSION['user_id'])) {
                echo '<li><a href="/blog/dashboard.php">Create/Delete Tweet</a></li>';
                echo '<li><a href="/blog/profile.php?username=' . $_SESSION['username'] . '">Profile</a></li>';
                echo '<li><a href="/blog/logout.php">Logout</a></li>';
              } else {
                echo '<li><a href="/blog/login.php">Login</a></li>';
                echo '<li><a href="/blog/register.php">Register</a></li>';
              }
          ?>
        </ul>
      </nav>
    </header>
    <div class="container">
        <h2>Register</h2>
        <?php
        // Check for error in URL
        if (isset($_GET['error'])) {
            if ($_GET['error'] == 'username_taken') {
                echo '<p class="error">Username is already taken.</p>';
            } else if ($_GET['error'] == 'email_taken') {
                echo '<p class="error">Email is already taken.</p>';
            }
        }
        ?>
        <form action="register.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="text" name="full_name" placeholder="Full Name">
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div> <!-- .container -->
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Twitter Clone </p>
    </footer>
</body>
</html>

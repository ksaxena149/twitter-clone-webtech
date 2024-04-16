<?php
session_start();
include_once 'includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_email = $_POST['username_email'];
    $password = $_POST['password'];

    // Retrieve hashed password from the database based on username/email
    $query = "SELECT user_id, username, password FROM users WHERE (username = '$username_email' OR email = '$username_email')";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $hashed_password = $user['password'];

        // Verify entered password against hashed password
        if (password_verify($password, $hashed_password)) {
            // Login successful, set session variables and redirect to dashboard
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
            exit();
        } else {
            // Incorrect password, redirect back to login page with error message
            header("Location: login.php?error=incorrect_password");
            exit();
        }
    } else {
        // User not found, redirect back to login page with error message
        header("Location: login.php?error=user_not_found");
        exit();
    }
}
?>



<!-- login.php -->
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
        <h2>Login</h2>
        <?php
        // Check for error in URL
        if (isset($_GET['error'])) {
            if ($_GET['error'] == 'incorrect_password') {
                echo '<p class="error">Incorrect Password</p>';
            } else if ($_GET['error'] == 'user_not_found') {
                echo '<p class="error">Username or Email does not exist. Register <a href="/blog/register.php">Here</a></p>';
            }
        }
        ?>
        <form action="login.php" method="POST">
            <input type="text" name="username_email" placeholder="Username or Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Sign up</a></p>
    </div> <!-- .container -->
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Twitter Clone </p>
    </footer>
</body>
</html>


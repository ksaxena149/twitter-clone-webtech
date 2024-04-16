<!-- dashboard.php -->
<?php
session_start();
include_once 'includes/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page
    header("Location: login.php");
    exit();
}

// Fetch user information
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Fetch user's tweets
$query = "SELECT * FROM posts WHERE user_id = '$user_id' ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
$tweets = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
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
        <h2>Dashboard</h2>
        <h3>Create another post</h3>
        <!-- Form to create a new post -->
        <div id="postForm">
            <form id="createPostForm" action="post.php" method="POST">
                <textarea name="post_content" placeholder="What's on your mind?" required></textarea>
                <button type="submit">Post</button>
            </form>
        </div>
        <h3>My Tweets</h3>
        <!-- Display tweets dynamically using PHP -->
        <?php 
          foreach ($tweets as $tweet) {
            // Fetch username associated with the tweet's user_id
            $query = "SELECT username FROM users WHERE user_id = '{$tweet['user_id']}'";
            $result = mysqli_query($conn, $query);
            $user_info = mysqli_fetch_assoc($result);
            $username = $user_info['username'];
          
            echo "<div class='tweet'>";
            echo "<p>{$tweet['post_content']}</p>";
            echo "<span class='author'>Posted by: {$username}</span>";
            // Add delete button
            echo "<form action='delete_tweet.php' method='POST'>";
            echo "<input type='hidden' name='tweet_id' value='{$tweet['post_id']}'>";
            echo "<button type='submit'>Delete</button>";
            echo "</form>";
            echo "</div>";
          }
        ?>
    </div> <!-- .container -->
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Twitter Clone </p>
    </footer>
</body>
</html>




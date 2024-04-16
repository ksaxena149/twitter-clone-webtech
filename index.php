<?php
session_start();
include_once 'includes/db_connect.php';


function hasLiked($conn, $postId, $userId) {
    $query = "SELECT * FROM likes WHERE post_id = '$postId' AND user_id = '$userId'";
    $result = mysqli_query($conn, $query);
    return mysqli_num_rows($result) > 0;
}
// Function to get the count of likes for a post
function getLikeCount($conn, $postId) {
    $query = "SELECT COUNT(*) AS like_count FROM likes WHERE post_id = '$postId'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['like_count'];
}

// Fetch all tweets from the database along with like counts
$query = "SELECT posts.*, users.username, 
                (SELECT COUNT(*) FROM likes WHERE post_id = posts.post_id) AS like_count
          FROM posts 
          INNER JOIN users ON posts.user_id = users.user_id 
          ORDER BY posts.created_at DESC";
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
        <h2>Home</h2>
        <!-- Display tweets dynamically using PHP -->
        <?php 
        foreach ($tweets as $tweet) {
            echo "<div class='tweet'>";
            echo "<p>{$tweet['post_content']}</p>";
            echo "<span class='like-count'>Likes: {$tweet['like_count']} </span>";
            echo "<span class='author'>Posted by: <a href='/blog/profile.php?username={$tweet['username']}'>{$tweet['username']} </a></span>";
            // Check if user is logged in to display like/unlike option
            if (isset($_SESSION['user_id'])) {
                $postId = $tweet['post_id'];
                $likeText = hasLiked($conn, $postId, $_SESSION['user_id']) ? 'Unlike' : 'Like';
                echo "<form action='like_unlike.php' method='POST'>";
                echo "<input type='hidden' name='post_id' value='$postId'>";
                echo "<button type='submit' name='action'>$likeText</button>";
                echo "</form>";
            }
            echo "</div>";
        }
        ?>
    </div> <!-- .container -->
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Twitter Clone </p>
    </footer>
</body>
</html>






<?php
// Function to check if a user is following another user
function isFollowing($conn, $follower_id, $followee_id) {
    $query = "SELECT COUNT(*) FROM followers WHERE follower_id = '$follower_id' AND followee_id = '$followee_id'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_row($result);
    return $row[0] > 0; // Returns true if the user is following, false otherwise
}

session_start();
include_once 'includes/db_connect.php';
$follower_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['username'])) {
    $username = $_GET['username'];
    
    // Fetch user information
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
    
    if ($user) {
        $user_id = $user['user_id'];
        
        // Fetch user's tweets
        $query = "SELECT * FROM posts WHERE user_id = '$user_id' ORDER BY created_at DESC";
        $result = mysqli_query($conn, $query);
        $tweets = mysqli_fetch_all($result, MYSQLI_ASSOC);
        
        // Fetch user's followers count
        $query = "SELECT COUNT(*) AS followers_count FROM followers WHERE followee_id = '$user_id'";
        $result = mysqli_query($conn, $query);
        $followers_count = mysqli_fetch_assoc($result)['followers_count'];
        
        // Fetch user's following count
        $query = "SELECT COUNT(*) AS following_count FROM followers WHERE follower_id = '$user_id'";
        $result = mysqli_query($conn, $query);
        $following_count = mysqli_fetch_assoc($result)['following_count'];
    } else {
        // User not found
        echo "User not found";
    }
}
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
        <h2>User Profile</h2>
        <!-- Display user profile dynamically using PHP -->
        <?php if ($user): ?>
            <div class="profile-info">
                <p><strong>Username:</strong> <?php echo $user['username']; ?></p>
                <p><strong>Name:</strong> <?php echo $user['full_name']; ?></p>
                <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
                <p><strong>Followers:</strong> <?php echo $followers_count; ?></p>
                <p><strong>Following:</strong> <?php echo $following_count; ?></p>
            </div>
            
            <!-- Follow/unfollow button (display only if user is logged in) -->
            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != $user_id): ?>
                <form action="follow_unfollow.php" method="POST">
                    <input type="hidden" name="followee_id" value="<?php echo $user_id; ?>">
                    <input type="hidden" name="user_id" value="<?php echo $follower_id; ?>">
                    <?php if (isFollowing($conn, $_SESSION['user_id'], $user_id)): ?>
                        <button type="submit" name="action" value="unfollow">Unfollow</button>
                    <?php else: ?>
                        <button type="submit" name="action" value="follow">Follow</button>
                    <?php endif; ?>
                </form>
            <?php endif; ?>
            
            <h3>User's Tweets</h3>
            <?php if (!empty($tweets)): ?>
                <ul class="tweets-list">
                    <?php foreach ($tweets as $tweet): ?>
                        <li><?php echo $tweet['post_content']; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No tweets found.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div> <!-- .container -->
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Twitter Clone </p>
    </footer>
</body>
</html>





<?php
session_start();
include_once 'includes/db_connect.php';

// Check if user is logged in and the request is a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
  // Get tweet id from POST data
  $tweet_id = $_POST['tweet_id'];
  $user_id = $_SESSION['user_id'];

  // Start transaction
  mysqli_begin_transaction($conn);

  try {
    // Delete likes associated with the tweet
    $query = "DELETE FROM likes WHERE post_id = '$tweet_id'";
    mysqli_query($conn, $query);

    // Delete tweet from database
    $query = "DELETE FROM posts WHERE post_id = '$tweet_id' AND user_id = '$user_id'";
    mysqli_query($conn, $query);

    // Commit transaction
    mysqli_commit($conn);

    // Redirect back to dashboard
    header("Location: dashboard.php");
    exit();
  } catch (mysqli_sql_exception $exception) {
    // Rollback transaction
    mysqli_rollback($conn);

    // Error occurred
    echo "Error: " . $exception->getMessage();
  }
} else {
  // Redirect to login page
  header("Location: login.php");
  exit();
}
?>
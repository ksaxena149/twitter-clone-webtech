<?php
session_start();
include_once 'includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $post_id = $_POST['post_id'];

    // Check if the user has already liked the post
    $query = "SELECT * FROM likes WHERE user_id = '$user_id' AND post_id = '$post_id'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 0) {
        // User has not liked the post, insert into database
        $query = "INSERT INTO likes (user_id, post_id) VALUES ('$user_id', '$post_id')";
        $result = mysqli_query($conn, $query);
    } else {
        // User has already liked the post, unlike
        $query = "DELETE FROM likes WHERE user_id = '$user_id' AND post_id = '$post_id'";
        $result = mysqli_query($conn, $query);
    }

    if ($result) {
        // Action completed successfully
        header("Location: index.php");
        exit();
    } else {
        // Error occurred
        echo "Error: " . mysqli_error($conn);
    }
}
?>

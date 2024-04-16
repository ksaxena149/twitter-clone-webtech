<?php
include_once 'includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $follower_id = $_POST['user_id'];
    $followee_id = $_POST['followee_id'];

    // Check if the user is already following the followee
    $query = "SELECT * FROM followers WHERE follower_id = '$follower_id' AND followee_id = '$followee_id'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 0) {
        // User is not following, insert into database
        $query = "INSERT INTO followers (follower_id, followee_id) VALUES ('$follower_id', '$followee_id')";
        $result = mysqli_query($conn, $query);
    } else {
        // User is already following, unfollow
        $query = "DELETE FROM followers WHERE follower_id = '$follower_id' AND followee_id = '$followee_id'";
        $result = mysqli_query($conn, $query);
    }

    $query2 = "SELECT username FROM users WHERE user_id = '$followee_id'";
    $result2 = mysqli_query($conn, $query2);
    if ($result) {
        // Action completed successfully
        $row = mysqli_fetch_assoc($result2);
        $username = $row['username'];
        header("Location: profile.php?username=$username");
        exit();
    } else {
        // Error occurred
        echo "Error: " . mysqli_error($conn);
    }
}
?>

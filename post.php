<?php
session_start();
include_once 'includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(!$_SESSION['user_id']){
      header("Location: login.php");
      exit();
    }
    $user_id = $_SESSION['user_id'];
    $post_content = $_POST['post_content'];
    
    $query = "INSERT INTO posts (user_id, post_content) VALUES ('$user_id', '$post_content')";
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        // Tweet posted successfully
        header("Location: dashboard.php");
        exit();
    } else {
        // Error occurred
        echo "Error: " . mysqli_error($conn);
    }
}
?>

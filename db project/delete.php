<?php
session_start();
include 'connection.php';

// Ensure the user is logged in before deleting their account
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the current user's ID
$userId = $_SESSION['user_id'];

// Prepare the SQL query to delete the user from the database
$sql = "DELETE FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    // If there is a problem with preparing the statement, show an error
    echo "Error preparing the SQL statement: " . $conn->error;
    exit();
}

// Bind the user ID to the query
$stmt->bind_param("i", $userId);

// Execute the delete query
if ($stmt->execute()) {
    // If successful, destroy the session and log the user out
    session_unset();
    session_destroy();

    // Redirect the user to the homepage or login page
    header("Location: homepage.php");  // You can change this to your preferred page
    exit();
} else {
    // If there was an error deleting the account, show an error message
    echo "Error deleting account. Please try again.";
}

$stmt->close();
$conn->close();
?>

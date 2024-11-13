<?php
session_start();
include 'connection.php';

if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];

    $query = "DELETE FROM bookings WHERE booking_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $booking_id);

    if ($stmt->execute()) {
        echo "<script>alert('Booking deleted successfully.');</script>";
        echo "<script>window.location.href = 'book_car.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

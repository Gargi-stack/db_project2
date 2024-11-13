<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <style>
        /* Set up the background image for the page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4; /* Light background for the body */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
        }

        /* Container for the confirmation box */
        .confirmation-box {
            background-color: white;
            padding: 30px;
            max-width: 500px;
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        /* Heading for the confirmation */
        .confirmation-box h2 {
            margin-bottom: 20px;
            color: #333;
        }

        /* Style for the details (Booking ID, Car Type, etc.) */
        .confirmation-box p {
            font-size: 1.1rem;
            margin: 10px 0;
            color: #333;
        }

        .confirmation-box p strong {
            color: #16c2d5; /* Color for the strong text */
        }

        /* Style for the action links */
        .confirmation-box .actions {
            margin-top: 20px;
        }

        .confirmation-box .actions a {
            font-size: 1rem;
            color: #2f4ae6;
            text-decoration: none;
            margin: 0 15px;
        }

        .confirmation-box .actions a:hover {
            color: #16c2d5; /* Hover effect */
        }

    </style>
</head>
<body>
<?php 
session_start();
include 'connection.php';

if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];

    // Retrieve booking details
    $query = "SELECT * FROM bookings WHERE booking_id = ?";
    
    // Prepare the SQL statement
    $stmt = $conn->prepare($query);

    // Check if the query preparation failed
    if ($stmt === false) {
        // Output the actual error message from MySQL
        die("ERROR: Failed to prepare the SQL query. " . $conn->error);
    }

    // Bind the parameter
    $stmt->bind_param("i", $booking_id);

    // Execute the query
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Fetch the booking details
    $booking = $result->fetch_assoc();

    if ($booking) {
        // Display the booking confirmation details inside a styled box
        echo "
        <div class='confirmation-box'>
            <h2>Booking Confirmation</h2>
            <p><strong>Booking ID:</strong> " . $booking['booking_id'] . "</p>
            <p><strong>Car Type:</strong> " . $booking['car_type'] . "</p>
            <p><strong>Car Model:</strong> " . $booking['car_model'] . "</p>
            <p><strong>Booking Date:</strong> " . $booking['booking_date'] . "</p>
            <p><strong>Booking Time:</strong> " . $booking['booking_time'] . "</p>
            <p><strong>Duration:</strong> " . $booking['duration'] . " hours</p>
            <div class='actions'>
                <a href='edit_booking.php?booking_id=" . $booking['booking_id'] . "'>Edit Booking</a> | 
                <a href='delete_booking.php?booking_id=" . $booking['booking_id'] . "'>Delete Booking</a> |
                <a href='homepage.php'>Home Page</a>
            </div>
        </div>
        ";
    } else {
        echo "<p>Booking not found.</p>";
    }

} else {
    echo "<p>Booking ID not provided.</p>";
}
?>

</body>
</html>



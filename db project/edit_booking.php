<?php
session_start();
include 'connection.php';

if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];

    // Update booking details
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $bookingDate = $_POST['bookingDate'];
        $bookingTime = $_POST['bookingTime'];
        $duration = $_POST['duration'];

        // Prepare the SQL query
        $query = "UPDATE bookings SET booking_date = ?, booking_time = ?, duration = ? WHERE booking_id = ?";
        $stmt = $conn->prepare($query);

        // Check for errors in query preparation
        if ($stmt === false) {
            die('Error preparing the statement: ' . $conn->error);
        }

        // Bind parameters (check data types and match column types)
        $stmt->bind_param("ssii", $bookingDate, $bookingTime, $duration, $booking_id);

        // Execute the query and handle success/failure
        if ($stmt->execute()) {
            header("Location: confirmation.php?booking_id=$booking_id");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    // Fetch current booking details
    $query = "SELECT * FROM bookings WHERE booking_id = ?";
    $stmt = $conn->prepare($query);

    // Check for errors in query preparation
    if ($stmt === false) {
        die('Error preparing the query: ' . $conn->error);
    }

    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Booking</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('transport.jpeg'); /* Add your background image */
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background-color: rgba(255, 255, 255, 0.8); /* Light background for the form */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 400px;
            text-align: center;
        }

        .form-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .form-container label {
            display: block;
            margin-bottom: 8px;
            font-size: 16px;
            color: #333;
        }

        .form-container input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-container button {
            padding: 10px 20px;
            background-color: #16c2d5;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        .form-container button:hover {
            background-color: #2f4ae6;
        }

        .form-container a {
            display: block;
            margin-top: 15px;
            text-decoration: none;
            color: #16c2d5;
        }

        .form-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Edit Booking</h2>
    <form method="post">
        <label for="bookingDate">Booking Date:</label>
        <input type="date" name="bookingDate" value="<?php echo $booking['booking_date']; ?>" required>
        
        <label for="bookingTime">Booking Time:</label>
        <input type="time" name="bookingTime" value="<?php echo $booking['booking_time']; ?>" required>
        
        <label for="duration">Duration (hours):</label>
        <input type="number" name="duration" value="<?php echo $booking['duration']; ?>" required>
        
        <button type="submit">Update Booking</button>
    </form>
    <a href="confirmation.php?booking_id=<?php echo $booking['booking_id']; ?>">Go to Confirmation</a>
</div>

</body>
</html>

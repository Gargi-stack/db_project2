<?php
session_start();
include 'connection.php';

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch the current user details
$userId = $_SESSION['user_id'];
$sql = "SELECT user_id, username, email, password FROM users WHERE user_id = ?";

// Check if the SQL statement is prepared correctly
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    // Display the error message if prepare() fails
    echo "Error preparing the SQL statement: " . $conn->error;
    exit();
}

$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

// Check if the user exists
if ($result->num_rows === 0) {
    echo "No user found with the provided ID.";
    exit();
}

$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'] ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $user['password'];

    // Update user details in the database
    $updateSql = "UPDATE users SET username = ?, email = ?, password = ? WHERE user_id = ?";
    $updateStmt = $conn->prepare($updateSql);

    if ($updateStmt === false) {
        echo "Error preparing the update SQL statement: " . $conn->error;
        exit();
    }

    $updateStmt->bind_param("sssi", $username, $email, $password, $userId);

    if ($updateStmt->execute()) {
        // Update the session username
        $_SESSION['username'] = $username;
        echo "<p>Profile updated successfully!</p>";
    } else {
        echo "<p>Error updating profile. Please try again.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="user_prof.css">
</head>
<body>
    <div class="container">
        <h2>User Profile</h2>
        <form method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" readonly required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly required>

            <label for="password">New Password (leave blank to keep current password):</label>
            <input type="password" id="password" name="password" readonly>

            <div class="buttons">
                <button type="submit" id="saveButton" style="display:none;">Save Changes</button>
                <button type="button" id="editButton">Edit Profile</button>
            </div>
            
            <div class="logout">
                <a href="logout.php" class="logout-button">Logout</a>
            </div>
            
            <div class="delete">
                <a href="delete.php" class="delete-button">Delete Account</a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('editButton').addEventListener('click', function () {
            // Enable the fields for editing
            document.querySelectorAll('input').forEach(input => input.removeAttribute('readonly'));
            
            // Show the save button and hide the edit button
            document.getElementById('saveButton').style.display = 'inline-block';
            document.getElementById('editButton').style.display = 'none';
        });
    </script>
</body>
</html>

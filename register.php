<?php

include "connection.php";

// Start the session
session_start();

// Check if the user is already logged in, redirect to the leaderboard page
if (isset($_SESSION['user_id'])) {
    header("Location: leaderboard.php");
    exit;
}


// Handle registration form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate form data (you can add additional validation if needed)

    // Check if the username is already taken
    $checkStmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
    $checkStmt->bind_param("s", $username);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        // Username already exists, display an error message
        $error = "Username already taken.";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new user into the database
        $insertStmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $insertStmt->bind_param("ss", $username, $hashedPassword);
        $insertStmt->execute();

        if ($insertStmt->affected_rows > 0) {
            // User registration successful, redirect to the login page
            header("Location: login.php");
            exit;
        } else {
            // Error occurred during user registration
            $error = "Error occurred during user registration.";
        }

        // Close the statement
        $insertStmt->close();
    }

    // Close the statement
    $checkStmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <link rel="stylesheet" href="user.css">
</head>
<body>
    <div class="container">
        <h2>User Registration</h2>
        <?php if (isset($error)) { ?>
            <div class="error"><?php echo $error; ?></div>
        <?php } ?>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Register" class="btn">
            </div>
        </form>
        <div class="login-link">
            You have alreay registered? Please <a href="login.php">Login</a>
        </div>
    </div>
</body>
</html>

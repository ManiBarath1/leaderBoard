<?php

include "connection.php";

// Start the session
session_start();

// Check if the user is already logged in, redirect to the appropriate page based on their role
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: leaderboard.php"); // Redirect admin to leaderboard page
    } else {
        header("Location: leaderboard.php"); // Redirect non-admin users to leaderboard page
    }
    exit;
}


// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate form data (you can add additional validation if needed)

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("SELECT user_id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Check if the user exists in the database
    if ($stmt->num_rows == 1) {
        $stmt->bind_result($user_id, $db_username, $db_password, $db_role);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $db_password)) {
            // Password is correct, set session variables and redirect based on user role
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $db_username;
            $_SESSION['role'] = $db_role;

            header("Location: leaderboard.php"); // Redirect all users to leaderboard page
            exit;
        } else {
            // Password is incorrect, display an error message
            $error = "Invalid username or password.";
        }
    } else {
        // User does not exist, display an error message
        $error = "Invalid username or password.";
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="user.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
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
                <input type="submit" value="Login" class="btn">
            </div>
        </form>
        <div class="register-link">
            Don't have an account? Please <a href="register.php">Register</a>
        </div>
    </div>
</body>
</html>

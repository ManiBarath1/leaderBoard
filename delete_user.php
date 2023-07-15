<?php
// Include the database connection and any necessary files
include "connection.php";

// Check if the userId parameter is received
if (isset($_POST['userId'])) {
    $userId = $_POST['userId'];

    // Sanitize and validate the userId if necessary

    // Prepare the DELETE query
    $query = "DELETE FROM users WHERE user_id = ?";

    // Create a prepared statement
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        // Bind the userId parameter to the prepared statement
        mysqli_stmt_bind_param($stmt, "i", $userId);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            // Deletion successful
            echo "User deleted successfully!";
        } else {
            // Failed to execute the statement
            echo "Error occurred while deleting the user.";
        }

        // Close the prepared statement
        mysqli_stmt_close($stmt);
    } else {
        // Failed to prepare the statement
        echo "Error occurred while preparing the delete statement.";
    }
} else {
    // userId parameter not received
    echo "Invalid request.";
}

// Close the database connection
mysqli_close($conn);
?>

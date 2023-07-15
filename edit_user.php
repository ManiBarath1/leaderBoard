<?php
include "connection.php";

// Check if the user ID and new role are provided via POST
if (isset($_POST['userId']) && isset($_POST['role'])) {
    $userId = $_POST['userId'];
    $newRole = $_POST['role'];

    // Update the user's role in the database (Replace with your actual update query)
    $query = "UPDATE users SET role = '$newRole' WHERE user_id = $userId";
    if (mysqli_query($conn, $query)) {
        echo "User role updated successfully!";
    } else {
        echo "Error occurred while updating the user role: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request. Please provide user ID and new role.";
}

// Close the database connection
mysqli_close($conn);
?>

<?php
include "connection.php";

// Retrieve the user data from the AJAX request
$username = $_POST['username'];
$password = $_POST['password'];
$role = $_POST['role'];

// Perform any necessary validation on the input data

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Prepare the SQL statement to insert the user into the database
$query = "INSERT INTO users (username, password, role) VALUES ('$username', '$hashedPassword', '$role')";
$result = mysqli_query($conn, $query);

// Check if the query executed successfully
if ($result) {
    echo "User added successfully!";
} else {
    echo "Error occurred while adding the user: " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>

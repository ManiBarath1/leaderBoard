<?php
// Include the necessary files and establish database connection
include "connection.php";

// Fetch user data from the database (Replace with your actual query)
$query = "SELECT user_id, username, role FROM users";
$result = mysqli_query($conn, $query);

// Check if the query executed successfully
if ($result === false) {
    die("Query execution failed: " . mysqli_error($conn));
}

// Check if any records are found
if (mysqli_num_rows($result) > 0) {
    // Build the user list HTML
    $userListHTML = "<table>";
    $userListHTML .= "<tr><th>ID</th><th>Username</th><th>Role</th><th>Action</th></tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        $userListHTML .= "<tr><td>" . $row['user_id'] . "</td><td>" . $row['username'] . "</td><td>" . $row['role'] . "</td>
        <td><button class='editUserBtn' data-userid='" . $row['user_id'] . "'>Edit</button>&nbsp;&nbsp;<button class='deleteUserBtn' data-userid='" . $row['user_id'] . "'>Delete</button></td></tr>";
    }

    $userListHTML .= "</table>";
} else {
    $userListHTML = "<p>No users found</p>";
}

// Send the user list HTML as the AJAX response
echo $userListHTML;

// Close the database connection
mysqli_close($conn);
?>

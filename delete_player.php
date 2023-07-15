<?php

include "connection.php";

// Get the player ID from the AJAX request
$playerId = $_POST['playerId'];

// Delete the player from the database
$query = "DELETE FROM leaderboard WHERE id = $playerId";
$result = mysqli_query($conn, $query);

if ($result === false) {
  die("Query execution failed: " . mysqli_error($conn));
}

// Send the success message as the AJAX response
echo "Player deleted successfully.";

// Close the database connection
mysqli_close($conn);
?>

<?php

include "connection.php";

// Retrieve player ID and new score from the AJAX POST request
if (isset($_POST['playerId']) && isset($_POST['score'])) {
  $playerId = $_POST['playerId'];
  $score = $_POST['score'];

  // Update the player's score in the database (Replace with your actual update query)
  $query = "UPDATE leaderboard SET score = $score WHERE id = $playerId";
  if (mysqli_query($conn, $query)) {
    echo "Score updated successfully!";
  } else {
    echo "Error occurred while updating the score: " . mysqli_error($conn);
  }
} else {
  echo "Invalid request. Please provide player ID and new score.";
}

// Close the database connection
mysqli_close($conn);
?>

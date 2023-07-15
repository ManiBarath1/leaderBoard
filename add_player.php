<?php

include "connection.php";

// Retrieve player name and score from the AJAX POST request
if (isset($_POST['playerName']) && isset($_POST['score'])) {
  $playerName = $_POST['playerName'];
  $score = $_POST['score'];

  // Insert the player data into the database (Replace with your actual insert query)
  $query = "INSERT INTO leaderboard (player_name, score) VALUES ('$playerName', $score)";
  if (mysqli_query($conn, $query)) {
    $playerId = mysqli_insert_id($conn); // Get the auto-generated player ID
    echo "Player added successfully! Player ID: " . $playerId;
  } else {
    echo "Error occurred while adding the player: " . mysqli_error($conn);
  }
} else {
  echo "Invalid request. Please provide player name and score.";
}

// Close the database connection
mysqli_close($conn);
?>

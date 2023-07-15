<?php
include "connection.php";

// Fetch leaderboard data from the database, considering ties in scores
$query = "SELECT id, player_name, score, FIND_IN_SET(score, (
            SELECT GROUP_CONCAT(DISTINCT score ORDER BY score DESC) 
            FROM leaderboard
          )) AS rank
          FROM leaderboard
          ORDER BY score DESC";
$result = mysqli_query($conn, $query);

// Check if the query executed successfully
if ($result === false) {
    die("Query execution failed: " . mysqli_error($conn));
}

// Create an array to store the leaderboard data
$leaderboardData = [];

while ($row = mysqli_fetch_assoc($result)) {
    $leaderboardData[] = [
        'player_name' => $row['player_name'],
        'score' => (int) $row['score']
    ];
}

// Close the database connection
mysqli_close($conn);

// Convert the leaderboard data to JSON format
$leaderboardJSON = json_encode($leaderboardData);

// Set the Content-Type header to application/json
header('Content-Type: application/json');

// Send the JSON response
echo $leaderboardJSON;
?>



function renderChart(data) {
  // Extract player names and scores from the data
  console.log(data)
  var playerNames = data.map(function(entry) {
    return entry.player_name;
  });
  var scores = data.map(function(entry) {
    return entry.score;
  });

  // Chart.js configuration
  var ctx = document.getElementById("barChart").getContext("2d");
  var chart = new Chart(ctx, {
    type: "bar",
    data: {
      labels: playerNames,
      datasets: [
        {
          label: "Score",
          data: scores,
          backgroundColor: "rgba(54, 162, 235, 0.8)",
          borderColor: "rgba(54, 162, 235, 1)",
          borderWidth: 1,
        },
      ],
    },
    options: {
      scales: {
        y: {
          beginAtZero: true,
        },
      },
    },
  });
}
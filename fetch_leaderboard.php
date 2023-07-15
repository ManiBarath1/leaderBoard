<?php
include "connection.php";

// Fetch leaderboard data from the database, considering ties in scores
$query = "SELECT id, player_name, score, FIND_IN_SET(score, (
            SELECT GROUP_CONCAT(DISTINCT score ORDER BY score DESC) 
            FROM leaderboard
          )) AS rank
          FROM leaderboard
          ORDER BY score DESC LIMIT 10";
$result = mysqli_query($conn, $query);

// Check if the query executed successfully
if ($result === false) {
    die("Query execution failed: " . mysqli_error($conn));
}

// Check if any records are found
if (mysqli_num_rows($result) > 0) {
    // Build the leaderboard HTML
    $leaderboardHTML = "<table>";
    $leaderboardHTML .= "<tr><th>Rank</th><th>Player</th><th>Score</th>";

    // Determine the actions based on the user's role (Replace with your actual authentication code)
    session_start();
    $isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

    if ($isAdmin) {
        $leaderboardHTML .= "<th>Action</th>";
    }

    $leaderboardHTML .= "</tr>";

    // Create an array to store the data for the bar chart
    $barChartData = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $leaderboardHTML .= "<tr><td>" . $row['rank'] . "</td><td>" . $row['player_name'] . "</td><td>" . $row['score'] . "</td>";

        // Add the actions based on the user's role
        if ($isAdmin) {
            $leaderboardHTML .= "<td><button class='editBtn' data-playerid='" . $row['id'] . "'>Edit</button> <button class='deleteBtn' data-playerid='" . $row['id'] . "'>Delete</button></td>";
        }

        $leaderboardHTML .= "</tr>";

        // Store the data for the bar chart
        $barChartData[] = [
            'player_name' => $row['player_name'],
            'score' => (int) $row['score']
        ];
    }
    $leaderboardHTML .= "</table>";
} else {
    $leaderboardHTML = "<p>No records found</p>";
}

// Send the leaderboard HTML as the AJAX response
echo $leaderboardHTML;

// Close the database connection
mysqli_close($conn);
?>

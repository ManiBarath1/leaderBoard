<?php
// Start the session
session_start();

// Check if the user is not logged in, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Check if the user is the admin
$isAdmin = false;
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $isAdmin = true;
}

// Logout functionality
if (isset($_GET['logout'])) {
    // Clear all session variables
    session_unset();

    // Destroy the session
    session_destroy();

    // Redirect to the login page after logout
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="leaderboard.css">
  <script src="leaderboard.js"></script>
</head>
<body>
<h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>

<!-- Logout button -->
<a href="leaderboard.php?logout=true" class="logout-button">Logout</a>

<?php if ($isAdmin): ?>
  <div id="userManagementSection" class="user-section">
    <h2>User Management</h2> 
    <form id="addUserForm" class="add-user-form">
      <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">&nbsp;&nbsp;&nbsp;&nbsp;Password:</label>
        <input type="password" id="password" name="password" required>
        <label for="role">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Role:</label>
        <select id="role" name="role">
          <option value="none">None</option>
          <option value="admin">Admin</option>
          <option value="user">User</option>
        </select>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="Add User" id="addUserBtn" class="btn" >
      </div>
    </form>
    <button id="userListBtn" class="userListBtn">Show User List</button>
    <br><br>
    <div id="userList" class="user-list"></div>
  </div>
<?php endif; ?>
<br>  
<?php if ($isAdmin): ?>
  <div class="input-section">
    <label for="playerName">Player Name:</label>
    <input type="text" id="playerName" placeholder="Enter player name">
    <label for="score">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Score:</label>
    <input type="number" id="score" placeholder="Enter score">
    &nbsp;&nbsp;
    <button id="addPlayerBtn" disabled>Add Player</button>
  </div>
<?php endif; ?>

<div class="container">
  <div class="leaderboard-container">
    <h1>Game Leaderboard</h1>
      <button id="leaderboardBtn">Show Leaderboard</button>
    <br>
    <div id="leaderboard" class="leaderboard"></div>
  </div>

  <div class="chart-container">
  <h1>Score Chart</h1>
    <div id="chartContainer" class="chartContainer">
      
      <canvas id="barChart"></canvas>
    </div>
  </div>
</div>

</body>
</html>

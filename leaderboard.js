$(document).ready(function() {
  // Enable the "Add Player" button only when both inputs have values
  $("#playerName, #score").keyup(function() {
    var playerName = $("#playerName").val();
    var score = $("#score").val();
    if (playerName && score) {
      $("#addPlayerBtn").prop("disabled", false);
    } else {
      $("#addPlayerBtn").prop("disabled", true);
    }
  });

  // Add Player functionality
  $("#addPlayerBtn").click(function() {
    var playerName = $("#playerName").val();
    var score = $("#score").val();

    if (playerName && score) {
      $.ajax({
        url: "add_player.php",
        type: "POST",
        data: { playerName: playerName, score: score },
        success: function(response) {
          // Display success message or perform any other actions
          alert("Player added successfully!");

          // Clear the input fields
          $("#playerName").val("");
          $("#score").val("");

          // Refresh the leaderboard after adding the player
          fetchLeaderboardAndRenderChart();
        },
        error: function() {
          // Handle error if the AJAX request fails
          alert("Error occurred while adding the player.");
        }
      });
    } else {
      alert("Please enter player name and score.");
    }
  });

  // Toggle Section functionality
  $("#leaderboardBtn").click(function() {
    toggleSection("leaderboard");
  });

  $("#userListBtn").click(function() {
    var userListContainer = $("#userList");
    if (userListContainer.is(":visible")) {
      userListContainer.hide();
      $(this).text("Show User List");
    } else {
      fetchUsers();
      userListContainer.show();
      $(this).text("Hide User List");
    }
  });

  // Handle the click event of the "Edit User" button
$(document).on('click', '.editUserBtn', function() {
  var userId = $(this).data('userid');
  var newRole = prompt('Enter the new role for the user:');
  if (newRole !== null) {
    $.ajax({
      url: 'edit_user.php',
      type: 'POST',
      data: { userId: userId, role: newRole },
      success: function(response) {
        alert(response);
        fetchUsers(); // Refresh the user list after editing the user
      },
      error: function() {
        alert('Error occurred while editing the user.');
      }
    });
  }
});

  // User Management functionality
  displayUserManagementSection();

  // Handle the submission of the Add User form
  $(document).on('submit', '#addUserForm', function(event) {
    event.preventDefault();

    var username = $("#username").val();
    var password = $("#password").val();
    var role = $("#role").val();

    if (username && password && role) {
      $.ajax({
        url: 'add_user.php',
        type: 'POST',
        data: { username: username, password: password, role: role },
        success: function(response) {
          alert(response);
          fetchUsers(); // Refresh the user list after adding a user
        },
        error: function(xhr, status, error) {
          alert('Error occurred while adding the user: ' + error);
        }
      });
    } else {
      alert('Please enter username, password, and role.');
    }
  });

  // Handle the click event of the "Delete User" button
  $(document).on('click', '.deleteUserBtn', function() {
    if (confirm('Are you sure you want to delete this user?')) {
      var userId = $(this).data('userid');
      $.ajax({
        url: 'delete_user.php',
        type: 'POST',
        data: { userId: userId },
        success: function(response) {
          alert(response);
          fetchUsers(); // Refresh the user list after deleting a user
        },
        error: function() {
          alert('Error occurred while deleting the user.');
        }
      });
    }
  });

 

  // Fetch leaderboard on page load
  fetchLeaderboardAndRenderChart();

  // Fetch users on page load
  fetchUsers();
});

$(document).ready(function() {
  // Edit Score functionality
  $(document).on('click', '.editBtn', function() {
    // Code for editing the score of a player
    var playerId = $(this).data('playerid');
    var newScore = prompt('Enter the new score for the player:');
    if (newScore !== null) {
      $.ajax({
        url: 'editscore.php',
        type: 'POST',
        data: { playerId: playerId, score: newScore },
        success: function(response) {
          alert(response);
          fetchLeaderboardAndRenderChart(); // Refresh the leaderboard after editing the score
        },
        error: function() {
          alert('Error occurred while editing the score.');
        }
      });
    }
  });

  // Delete Player functionality
  $(document).on('click', '.deleteBtn', function() {
    if (confirm('Are you sure you want to delete this player?')) {
      var playerId = $(this).data('playerid');
      $.ajax({
        url: 'delete_player.php',
        type: 'POST',
        data: { playerId: playerId },
        success: function(response) {
          alert(response);
          fetchLeaderboardAndRenderChart(); // Refresh the leaderboard after deleting the player
        },
        error: function() {
          alert('Error occurred while deleting the player.');
        }
      });
    }
  });
});

// Declare a global variable to store the chart instance
var barChart;

// Function to fetch the leaderboard data and update the chart
function fetchLeaderboardAndRenderChart() {
  $.ajax({
    url: 'fetch_leaderboard.php',
    type: 'GET',
    dataType: 'html',
    success: function(response) {
      $('#leaderboard').html(response);

      // Parse the JSON response and extract the necessary data for the chart
      var leaderboardData = [];
      var tableRows = $('#leaderboard table tr:not(:first-child)');
      tableRows.each(function(index) {
        var playerName = $(this).find('td:nth-child(2)').text();
        var score = parseInt($(this).find('td:nth-child(3)').text());
        var rank = index + 1; // Calculate the rank based on the row index
        leaderboardData.push({ player_name: playerName, score: score, rank: rank });
      });

      // Sort the data in descending order based on the score
      leaderboardData.sort(function(a, b) {
        return b.score - a.score;
      });

      // Prepare the data for the chart
      var chartLabels = leaderboardData.map(function(data) {
        return data.player_name;
      });
      var chartData = leaderboardData.map(function(data) {
        return data.score;
      });
      var chartRank = leaderboardData.map(function(data) {
        return data.rank;
      });

      // Update the chart data
      barChart.data.labels = chartLabels;
      barChart.data.datasets[0].data = chartData;

      // Check if the rank dataset already exists, if not, add it to the datasets array
      var rankDataset = barChart.data.datasets.find(function(dataset) {
        return dataset.label === 'Rank';
      });
      if (!rankDataset) {
        barChart.data.datasets.push({
          label: 'Rank',
          data: chartRank,
          backgroundColor: 'rgba(255, 99, 132, 0.6)',
          borderColor: 'rgba(255, 99, 132, 1)',
          borderWidth: 1,
        });
      } else {
        // If the rank dataset already exists, update its data
        rankDataset.data = chartRank;
      }

      barChart.update();
    },
    error: function(xhr, status, error) {
      console.log('Error:', error);
    }
  });
}

$(document).ready(function() {
  // Render the initial chart
  var ctx = document.getElementById('barChart').getContext('2d');
  barChart = new Chart(ctx, {
    type: 'radar',
    data: {
      labels: [],
      datasets: [
        {
          label: 'Score',
          data: [],
          backgroundColor: 'rgba(54, 162, 235, 0.6)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1,
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        r: {
          beginAtZero: true
        }
      }
    }
  });

  // Fetch and update the leaderboard data periodically (e.g., every 5 seconds)
  setInterval(fetchLeaderboardAndRenderChart, 5000);
});



// Other functions remain the same as in the previous code snippet


function toggleSection(sectionId) {
  var section = $("#" + sectionId);
  var button = $("#" + sectionId + "Btn");

  if (section.is(":visible")) {
    section.hide();
    button.text("Show " + sectionId);
  } else {
    section.show();
    button.text("Hide " + sectionId);
  }
}

function displayUserManagementSection() {
  $("#userManagementSection").show();
}

function fetchUsers() {
  $.ajax({
    url: "fetch_users.php",
    type: "GET",
    success: function(response) {
      // Update the user list section with the received data
      $("#userList").html(response);
    },
    error: function(xhr, status, error) {
      // Handle error if the AJAX request fails
      alert("Error occurred while fetching user data: " + error);
    }
  });
}

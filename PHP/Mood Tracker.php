<?php
    include("headerlogic.php");
    include("Header.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="shortcut icon" type="image/x-icon" href="favicon.png"/>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <title>Mood Tracker</title>
  <style>

/* Container */
.container {
    width: 90%;
    max-width: 600px;
    max-height: 800px;
    background-color: white;
    padding: 30px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    margin-top: 80px; /* Slightly pushed down to avoid header overlap */
    margin-bottom: 60px; /* Make space for the footer */
    flex: 1; /* Ensures content fills the remaining space */
}


    /* Header (Fixed at the top) */
  .header {
    background-color: #87CEFA;
    color: #000000;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 30px;
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 1000;
  }
  
  /* Logo Styling */
  .logo a {
    font-size: 1.8em;
    font-weight: bold;
    color: #000000;
    text-decoration: none;
  }
  
  /* Navbar Styling */
  .navbar {
    display: flex;
    gap: 25px;
  }
  
  .navbar a {
    color: #000000;
    font-size: 1.1em;
    text-decoration: none;
    padding: 8px 15px;
    border-radius: 25px;
    transition: background-color 0.3s;
  }
  
  .navbar a:hover {
    background-color: #E0FFFF;
  }
    .btn-lg {
      background-color: #4CAF50;
      color: white;
      font-size: 18px;
    }
    .btn-lg:hover {
      background-color: #45a049;
    }
    .footer {
    background-color: #87CEFA;
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: #000000;
    position: fixed;
    bottom: 0;
    width: 100%;
    z-index: 1000;
  }
  
  /* Footer Section */
  .footer-section {
    font-size: 14px;
  }
  
  .footer-section-right {
    text-align: right;
    font-size: 14px;
  }
  
  .footer-section p, .footer-section-right p {
    margin: 0;
  }
  </style>
</head>
<body>
  <div id="content" class="container">
    <div class="jumbotron bg-info text-white">
      <h1 class="display-4">Hello, <?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : "Guest"; ?>!</h1>
      <hr class="my-4">
      <p class="lead">Welcome to your daily mood tracker. How do you feel today?</p>
    </div>

    <div class="card">
      <div class="card-body">
        Today is: <h4 id="activeDate"><?php echo date('d F Y'); ?></h4>
      </div>
    </div>

    <div class="form-group">
      <label for="commentInput">Add Your Comment for Today:</label>
      <textarea class="form-control" id="commentInput" rows="3" placeholder="Share your thoughts..."></textarea>
    </div>

    <div class="form-group">
      <label for="emotionSelect">Select Emotion for Today:</label>
      <select class="form-control" id="emotionSelect">
        <option value="1">Very Tough</option>
        <option value="2">Difficult</option>
        <option value="3">Average</option>
        <option value="4">Great</option>
        <option value="5">Amazing</option>
      </select>
    </div>

    <button id="logMood" class="btn btn-primary btn-lg mt-3">Log Mood</button>
  </div>

  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

  <script>
document.addEventListener('DOMContentLoaded', function () {
    const moodSelect = document.querySelector("#emotionSelect");
    const commentInput = document.querySelector("#commentInput");
    const logButton = document.querySelector("#logMood");

    // Mood text corresponding to numeric values
    const moodDescriptions = {
        1: "Very Tough",
        2: "Difficult",
        3: "Average",
        4: "Great",
        5: "Amazing"
    };

    logButton.addEventListener("click", function() {
        const moodValue = moodSelect.value; // Get the selected mood number
        const comment = commentInput.value.trim(); // Get the comment
        
        // Translate the mood value into a description
        const moodDescription = moodDescriptions[moodValue];

        // If no description found, exit
        if (!moodDescription) {
            alert("Invalid mood selected.");
            return;
        }

        // Format the date as YYYY-MM-DD (MySQL format)
        const today = new Date();
        const date = today.getFullYear() + '-' + 
                     String(today.getMonth() + 1).padStart(2, '0') + '-' + 
                     String(today.getDate()).padStart(2, '0'); // Ensure date is in correct format

        if (!moodValue) {
            alert("Please select a mood before logging.");
            return;
        }

        // Create the mood entry object to send to the server
        const moodEntry = { 
            mood: moodDescription,  // Send the description to the server
            comment, 
            date 
        };

        // Send data to PHP via fetch
        fetch("log_mood.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(moodEntry),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Mood successfully recorded!");  // Success message
                commentInput.value = ""; // Clear the comment input field
                moodSelect.value = "1"; // Reset the emotion selection
            } else {
                alert("Error logging mood: " + data.message);  // Error message
            }
        })
        .catch(error => {
            alert("An error occurred while logging your mood.");  // General error message
        });
    });
});

  </script>
</body>
</html>
<?php
include("../HTML/Footer.html");
?>
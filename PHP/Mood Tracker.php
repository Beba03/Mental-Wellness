<?php
    include("headerlogic.php");
    include("Header.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mood Tracker - Mental Wellness</title>
    <link rel="stylesheet" href="../CSS/moodtracker.css">
</head>
<body>
    <h1>Personal Mood Tracker</h1>


    <div id="moodInput">
        <label for="mood" style="color: white;">How are you feeling today?</label> 
        <select id="mood">
            <option value="happy">Happy</option>
            <option value="sad">Sad</option>
            <option value="neutral">Neutral</option>
            <option value="angry">Angry</option>
            <option value="excited">Excited</option>
        </select>
        
        <label for="comment" style="color: white;">Why do you feel this way?</label>
        <input type="text" id="comment" placeholder="Write a comment...">
        
        <button id="logMood">Log Mood</button>
        <button id="deleteLogs">Delete All Logs</button>
    </div>
    
    <div id="moodLog" style="display: none;"></div>
    
    <script src="../JavaScript/moodtracker.js"></script>
</body>
</html>

<?php
include("../HTML/Footer.html");
?>

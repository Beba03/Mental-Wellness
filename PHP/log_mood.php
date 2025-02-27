<?php
session_start();
include("database.php"); // Include your database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "User not logged in."]);
    exit();
}

// Get the JSON data from the request body
$data = json_decode(file_get_contents("php://input"), true);

// Check if necessary data is provided
if (empty($data['mood']) || empty($data['date'])) {
    echo json_encode(["success" => false, "message" => "Mood or date missing."]);
    exit();
}

// Prepare values for insertion
$mood = htmlspecialchars($data['mood']);
$comment = htmlspecialchars($data['comment']);
$date = $data['date'];  // The date passed from the JavaScript (should be in 'YYYY-MM-DD' format)
$user_id = $_SESSION['user_id']; // User ID from session

// Directly use the date received, as it's in 'YYYY-MM-DD' format already

// Prepare the SQL query
$stmt = $conn->prepare("INSERT INTO moods (user_id, mood, comment, date) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isss", $user_id, $mood, $comment, $date);

// Execute the query and return response
if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Mood logged successfully."]);
} else {
    // If error occurs, log the error and return it in the response
    error_log("Error: " . $stmt->error); // This will log the error to the PHP error log
    echo json_encode(["success" => false, "message" => "Error logging mood: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>

<?php
header("Content-Type: application/json");
session_start();
include("database.php"); // Ensure this file has a working database connection

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    echo json_encode(["success" => false, "message" => "User not logged in."]);
    exit();
}

// Get JSON data from request
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || empty($data["mood"]) || empty($data["date"])) {
    echo json_encode(["success" => false, "message" => "Invalid input."]);
    exit();
}

// Sanitize input
$user_id = $_SESSION["user_id"];
$mood = htmlspecialchars($data["mood"]);
$comment = htmlspecialchars($data["comment"]);
$date = $data["date"];

// Insert into database
$stmt = $conn->prepare("INSERT INTO moods (user_id, mood, comment, date) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isss", $user_id, $mood, $comment, $date);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Database error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>

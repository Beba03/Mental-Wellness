<?php
header("Content-Type: application/json");
session_start();
include("database.php"); // Include database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit();
}

$user_id = $_SESSION['user_id']; // Get user_id from session

// Query to fetch the logged moods for the logged-in user
$query = "SELECT mood, comment, date FROM moods WHERE user_id = ? ORDER BY date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();

// Fetch the results
$result = $stmt->get_result();
$moods = [];

while ($row = $result->fetch_assoc()) {
    $moods[] = $row;
}

echo json_encode($moods);

$stmt->close();
$conn->close();
?>

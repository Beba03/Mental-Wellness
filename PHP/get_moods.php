<?php
header("Content-Type: application/json");
session_start();
include("database.php");

if (!isset($_SESSION["user_id"])) {
    echo json_encode([]);
    exit();
}

$user_id = $_SESSION["user_id"];

$sql = "SELECT mood, comment, date FROM moods WHERE user_id = ? ORDER BY date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$moods = [];
while ($row = $result->fetch_assoc()) {
    $moods[] = $row;
}

echo json_encode($moods);

$stmt->close();
$conn->close();
?>

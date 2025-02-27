<?php
include("headerlogic.php");
include("Header.php");
include("database.php"); // Ensure database connection is included

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<p>Please log in to see your mood history.</p>";
    exit();
}

$user_id = $_SESSION['user_id']; // Get the user ID from session

// Fetch mood entries from the database
$query = "SELECT mood, comment, date FROM moods WHERE user_id = ? ORDER BY date DESC LIMIT 7"; 
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mental Wellness</title>
    <link rel="stylesheet" href="../CSS/landing.css?v=<?php echo time(); ?>">



</head>
<body>
    <div class="main-content">
        <div class="content-left">
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></h2>
            <p>Mental wellness is essential. Track your mood and explore resources to improve your well-being.</p>
            <p>Mental wellness is an essential part of overall health. It involves finding balance in life, managing stress, and developing resilience. Our platform provides tools and resources to help you track your mood, access support, and learn strategies for maintaining mental well-being.</p>
            <p>Explore our self-help resources to find articles and guides on managing stress, understanding anxiety, and building emotional resilience. Whether you're looking to improve your sleep or seeking professional therapy, we are here to support you on your journey to mental wellness.</p>
        </div>

        <div class="content-right">
            <h3>Recent Mood Entries</h3>
            <div class="mood-history">
                <?php if ($result->num_rows > 0): ?>
                    <ul>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <li>
                                <strong><?php echo htmlspecialchars($row['date']); ?>:</strong> 
                                <?php echo htmlspecialchars($row['mood']); ?> - 
                                "<?php echo htmlspecialchars($row['comment']); ?>"
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p>No mood entries logged yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>

<?php
include("../HTML/Footer.html");
$conn->close(); // Close database connection
?>

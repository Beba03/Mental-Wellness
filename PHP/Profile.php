<?php
// Start the session if not already started
session_start();

// Step 1: Check if the user is logged in (i.e., session exists)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if no session found
    exit();
}

include("headerlogic.php");
include("Header.php");

// Include the database connection
include("database.php");

$user_id = $_SESSION['user_id']; // Retrieve the user ID from the session

// Fetch user details from the database
$stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch user data if found
if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    $_SESSION['name'] = $user['name'];
    $_SESSION['email'] = $user['email'];
} else {
    die("User not found.");
}

$stmt->close();
$conn->close();

// Logout logic: Destroy the session if the user clicks 'Log Out'
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout'])) {
    // Destroy the session to log the user out
    session_unset();  // Unset all session variables
    session_destroy();  // Destroy the session

    // Redirect the user back to the login page
    header("Location: login.php");
    exit(); // Stop further script execution
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Mental Wellness</title>
    <link rel="stylesheet" href="../CSS/base.css">
    <link rel="stylesheet" href="../CSS/profile.css">
</head>

<body>
    <div class="profile-container">
        <div class="profile-card">
            <div class="profile-image">😊</div>
            <div class="profile-info">
                <h2><?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : "User"; ?></h2>
                <p>Email: <?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : "No Email"; ?></p>
                <p>Password: ******** <a href="Forgetpassword.php">Change Password</a></p>
                
                <!-- Logout Form -->
                <form action="Profile.php" method="post">
                    <button type="submit" name="logout" class="auth-button">Log out</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

<?php
// Include Footer
include("../HTML/Footer.html");
?>  

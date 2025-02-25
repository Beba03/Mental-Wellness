<?php
// Start the session if not already started
session_start();

// Step 1: Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // If the user is logged in, redirect them to the profile page
    header("Location: Profile.php");
    exit(); // Ensure no further code is executed after the redirect
}

// Include the database connection
include("database.php");

$message = ''; // Variable to store error or success message

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if both email and password are provided
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = strtolower(trim($_POST["email"])); // Clean and normalize email
        $password = $_POST["password"]; // Get password

        // Make sure email and password are not empty
        if (!empty($email) && !empty($password)) {
            // Retrieve user data from the database
            $user = getUserByEmail($email, $conn);

            // Check if the user exists
            if ($user) {
                // Verify the password
                if (verifyPassword($password, $user["password"])) {
                    // Successful login, store user data in session and redirect to profile page
                    loginUser($user);
                } else {
                    $message = 'Incorrect Password!';
                }
            } else {
                $message = "Email not found.";
            }
        } else {
            $message = 'Email and password are required.';
        }
    } else {
        $message = 'Email and password fields are missing.';
    }
}

// Function to retrieve user by email
function getUserByEmail($email, $conn)
{
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($result);
}

// Function to verify the password with the hash in the database
function verifyPassword($inputPassword, $storedPassword)
{
    return password_verify($inputPassword, $storedPassword);
}

// Function to log in the user by storing user information in the session
function loginUser($user)
{
    $_SESSION['user_id'] = $user['id']; // Change 'id' to 'user_id' to match Profile.php
    $_SESSION['name'] = $user['name'];
    $_SESSION['email'] = $user['email'];

    // Redirect to Profile page
    header("Location: Profile.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mental Wellness</title>
    <link rel="stylesheet" href="../CSS/auth.css">
</head>

<body class="auth-body">

    <div class="auth-container">
        <h2>Login</h2>

        <!-- Display any error message -->
        <?php if (!empty($message)) { echo "<p style='color:red;'>$message</p>"; } ?>

        <!-- Login Form -->
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="auth-button">Login</button>
        </form>

        <p>Don't have an account? <a href="signup.php">Register here</a></p>
        <p><a href="Forgetpassword.php">Forgot Password?</a></p> <!-- Forgot password link -->
    </div>

</body>

</html>

<?php
// Close the database connection
$conn->close();
?>

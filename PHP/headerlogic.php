<?php
    // Check if a session is already started, and start it if not
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Check if the user is logged in by looking for the 'user_id' in the session
    $isLoggedIn = isset($_SESSION['user_id']); 
    $current_page = basename($_SERVER['PHP_SELF']);
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('profile-link').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default link behavior
            var isLoggedIn = <?php echo json_encode($isLoggedIn); ?>;

            if (isLoggedIn) {
                window.location.href = './Profile.php'; // Redirect to profile page
            } else {
                window.location.href = './login.php'; // Redirect to login page
            }
        });
    });
</script>

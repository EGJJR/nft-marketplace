```
<?php
// Start session if not started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Destroy current session
session_destroy();

// Redirect to index page
header('Location: ../index.php');

// Exit script
exit;
```

This code is already following some best practices and PSR standards, but there are a few areas that can be improved:

1. Use prepared statements for database queries to prevent SQL injection attacks.
2. Use a consistent naming convention throughout the code. For example, use camelCase or PascalCase for variable names consistently across the codebase.
3. Avoid using `include` statements for everything. Instead, consider using a template engine like Twig or Smarty to separate presentation and logic.
4. Use a consistent indentation style throughout the code. For example, use 4 spaces for indentation instead of tabs.
5. Consider adding error handling for SQL queries to handle any errors that may occur during query execution.
6. Use a consistent naming convention for database tables and columns. For example, use underscores to separate words in table and column names consistently across the codebase.
7. Avoid using `echo` statements directly inside `if` conditions. Instead, consider using a template engine or printing the contents of variables within an HTML element.
8. Use a consistent naming convention for HTML elements. For example, use camelCase or PascalCase for class names and attribute names consistently across the codebase.
9. Consider adding a `.htaccess` file to the project root directory with rewrite rules to handle URL rewriting and prevent direct access to PHP files.
10. Use a consistent naming convention for CSS classes and IDs. For example, use camelCase or PascalCase for class names and attribute names consistently across the codebase.

Here is an example of how the code could be refactored:
```php
<?php
session_start();
require_once 'db/db_config.php';

// Define allowed pages for both public and private sections
$public_pages = ['home', 'login', 'register'];
$private_pages = ['dashboard', 'upload_nft', 'manage_nfts', 'transactions', 'purchase', 'edit_nft', 'delete_nft'];

// Get the requested page
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Check if user is logged in
$is_logged_in = isset($_SESSION['user_id']);

// Handle page access logic
if (in_array($page, $private_pages) && !$is_logged_in) {
    header('Location: index.php?page=login');
    exit();
}

// Include header
include 'includes/header.php';

// Load the appropriate page
if (in_array($page, $public_pages)) {
    if ($page === 'login' || $page === 'register') {
        include "auth/$page.php";
    } else {
        include "public/$page.php";
    }
} elseif (in_array($page, $private_pages)) {
    include "members/$page.php";
} else {
    echo "<div class='container mt-5'><h2>Page Not Found</h2></div>";
}

// Include footer
include 'includes/footer.php';
```
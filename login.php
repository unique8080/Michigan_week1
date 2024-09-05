<?php
session_start();
require_once "pdo.php";

// Redirect to index.php if the cancel button is pressed
if (isset($_POST['cancel'])) {
    header("Location: index.php");
    return;
}

// Salt for password hashing
$salt = 'XyZzy12*_';

// Check if both email and password are set in POST request
if (isset($_POST['email']) && isset($_POST['pass'])) {
    // Hash the password using MD5 with a salt
    $check = hash('md5', $salt . $_POST['pass']);

    // Prepare the SQL query to check user credentials
    $stmt = $pdo->prepare('SELECT user_id, name FROM users WHERE email = :em AND password = :pw');
    $stmt->execute(array(':em' => $_POST['email'], ':pw' => $check));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // If a user is found, set session variables and redirect
    if ($row !== false) {
        $_SESSION['name'] = $row['name'];
        $_SESSION['user_id'] = $row['user_id'];
        header("Location: index.php");
        return;
    } else {
        // If login fails, set error message
        $_SESSION['error'] = "Incorrect password";
        header("Location: login.php");
        return;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <?php require_once "bootstrap.php"; ?>
    <title>Welcome to Autos Database | Jared Best</title>
</head>
<body>
<div class="container">
    <h1>Please Log In</h1>
    <?php
    // Display error message if set
    if (isset($_SESSION['error'])) {
        echo('<p style="color: red;">' . htmlentities($_SESSION['error']) . "</p>\n");
        unset($_SESSION['error']);
    }
    ?>
    <form method="POST" action="login.php">
        <label for="email">User Name</label>
        <input type="text" name="email" id="email"><br/>
        <label for="pass">Password</label>
        <input type="password" name="pass" id="pass"><br/>
        <input type="submit" value="Log In">
        <input type="submit" name="cancel" value="Cancel">
    </form>
    <p>
        For a password hint, view source and find a password hint
        in the HTML comments.
    </p>
</div>
</body>
</html>

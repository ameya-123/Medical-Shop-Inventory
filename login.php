<?php
session_start();
include('config.php');

if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Create the SQL query with placeholders to prevent SQL injection
    $query = "SELECT * FROM user_tbl WHERE username=? AND password=?";
    
    // Prepare the statement
    $stmt = mysqli_prepare($conn, $query);
    
    // Bind the parameters
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    
    // Execute the statement
    mysqli_stmt_execute($stmt);
    
    // Get the result set
    $result = mysqli_stmt_get_result($stmt);
    
    // Check if any rows were returned
    if(mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];
        
        // Redirect based on user role
        switch($_SESSION['role']) {
            case 'admin':
                header("Location: admin.php");
                break;
            case 'pharmacist':
                header("Location: pharmacist.php");
                break;
            case 'cashier':
                header("Location: cashier.php");
                break;
            default:
                // Handle invalid role
                break;
        }
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .login-container {
            width: 300px;
            margin: 100px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .login-form label {
            display: block;
            margin-bottom: 10px;
        }
        .login-form input[type="text"],
        .login-form input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        .login-form input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .login-form input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .error {
            color: #ff0000;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if(isset($error)) { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>
        <form class="login-form" method="post" action="">
            <label>Username:</label>
            <input type="text" name="username" required>
            <label>Password:</label>
            <input type="password" name="password" required>
            <input type="submit" name="login" value="Login">
        </form>
    </div>
</body>
</html>

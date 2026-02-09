<?php
include "db.php";
session_start();

$error_message = '';
$success_message = '';

if (isset($_POST['login'])) {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Validate input
    if (empty($username) || empty($password)) {
        $error_message = "Username and password are required.";
    } else {
        // Escape for SQL safety
        $username = mysqli_real_escape_string($connection, $username);
        
        $query = "SELECT * FROM users WHERE user_name='$username'";
        $select_user_query = mysqli_query($connection, $query);
        
        if (!$select_user_query) {
            $error_message = "Database error: " . mysqli_error($connection);
        } else {
            if (mysqli_num_rows($select_user_query) > 0) {
                $Row = mysqli_fetch_array($select_user_query);
                
                $user_id = $Row['user_id'];
                $user_role = $Row['user_role'];
                $user_name = $Row['user_name'];
                $user_firstname = $Row['user_firstname'];
                $user_lastname = $Row['user_lastname'];
                $user_password = $Row['user_password'];
                $user_email = isset($Row['user_email']) ? $Row['user_email'] : '';

                // Password verification - check if password matches
                // Try both: plain text comparison (if password not hashed) and password_verify for hashed passwords
                $password_match = false;
                
                // First check if it's a hashed password using password_verify
                if (function_exists('password_verify') && password_verify($password, $user_password)) {
                    $password_match = true;
                } 
                // If not hashed with password_hash, try crypt comparison
                elseif ($user_password === crypt($password, $user_password)) {
                    $password_match = true;
                }
                // Fallback: plain text comparison (for development only)
                elseif ($password === $user_password) {
                    $password_match = true;
                }

                // Check both username and password
                if ($username === $user_name && $password_match) {
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['username'] = $user_name;
                    $_SESSION['firstname'] = $user_firstname;
                    $_SESSION['lastname'] = $user_lastname;
                    $_SESSION['role'] = $user_role;
                    $_SESSION['user_email'] = $user_email;
                    
                    // Redirect based on user role
                    if ($user_role == 'admin') {
                        header("Location: ../admin/index.php");
                    } else {
                        // Regular users go to homepage
                        header("Location: ../index.php");
                    }
                    exit;
                } else {
                    $error_message = "Invalid username or password.";
                }
            } else {
                $error_message = "Invalid username or password.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - My Online Store</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Arial', sans-serif;
        }
        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 40px;
            width: 100%;
            max-width: 400px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header h1 {
            color: #333;
            font-size: 28px;
            margin: 0;
        }
        .login-header p {
            color: #666;
            margin-top: 10px;
        }
        .form-group label {
            color: #333;
            font-weight: 600;
        }
        .form-group input {
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 12px;
            font-size: 14px;
        }
        .form-group input:focus {
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.3);
            outline: none;
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 5px;
            width: 100%;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        .alert {
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .login-footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
        .login-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        .login-footer a:hover {
            text-decoration: underline;
        }
        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-header">
        <h1><span class="glyphicon glyphicon-shopping-cart"></span> My Online Store</h1>
        <p>Customer Login</p>
    </div>

    <?php if (!empty($error_message)) { ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <span class="glyphicon glyphicon-exclamation-sign"></span> <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php } ?>

    <?php if (!empty($success_message)) { ?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <span class="glyphicon glyphicon-ok"></span> <?php echo htmlspecialchars($success_message); ?>
        </div>
    <?php } ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required autofocus>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
        </div>

        <button type="submit" name="login" class="btn-login">
            <span class="glyphicon glyphicon-log-in"></span> Login
        </button>
    </form>

    <div class="login-footer">
        <p>Don't have an account? <a href="../registration.php">Create one here</a></p>
        <p><a href="../index.php">Back to Store</a></p>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="../js/jquery.js"></script>
<script src="../js/bootstrap.min.js"></script>

</body>
</html>

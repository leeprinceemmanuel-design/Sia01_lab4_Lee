<?php
include "includes/db.php";

$message = '';
$error = '';

if (isset($_POST['submit'])) {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $password_confirm = isset($_POST['password_confirm']) ? $_POST['password_confirm'] : '';

    // Validation
    if (empty($username) || empty($email) || empty($password) || empty($password_confirm)) {
        $error = "All fields are required.";
    } elseif (strlen($username) < 3) {
        $error = "Username must be at least 3 characters long.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } elseif ($password !== $password_confirm) {
        $error = "Passwords do not match.";
    } else {
        // Check if username already exists
        $username_escaped = mysqli_real_escape_string($connection, $username);
        $check_query = "SELECT user_id FROM users WHERE user_name='$username_escaped'";
        $check_result = mysqli_query($connection, $check_query);
        
        if (mysqli_num_rows($check_result) > 0) {
            $error = "Username already exists. Please choose another.";
        } else {
            // Check if email already exists
            $email_escaped = mysqli_real_escape_string($connection, $email);
            $check_email_query = "SELECT user_id FROM users WHERE user_email='$email_escaped'";
            $check_email_result = mysqli_query($connection, $check_email_query);
            
            if (mysqli_num_rows($check_email_result) > 0) {
                $error = "Email already registered. Please use another email or <a href='includes/login.php'>login</a>.";
            } else {
                // Hash password securely
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $hashed_password_escaped = mysqli_real_escape_string($connection, $hashed_password);
                
                // Insert new user
                $insert_query = "INSERT INTO users (user_name, user_email, user_password, user_role) ";
                $insert_query .= "VALUES('{$username_escaped}', '{$email_escaped}', '{$hashed_password_escaped}', 'subscriber')";
                
                $register_result = mysqli_query($connection, $insert_query);
                
                if (!$register_result) {
                    $error = "Registration failed: " . mysqli_error($connection);
                } else {
                    $message = "Registration successful! You can now <a href='includes/login.php'>login</a> with your credentials.";
                }
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
    <title>Register - My Online Store</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Arial', sans-serif;
            padding: 20px 0;
        }
        .register-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 40px;
            width: 100%;
            max-width: 450px;
        }
        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .register-header h1 {
            color: #333;
            font-size: 28px;
            margin: 0;
        }
        .register-header p {
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
        .btn-register {
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
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        .alert {
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .register-footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
        .register-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        .register-footer a:hover {
            text-decoration: underline;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .password-info {
            background: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
            font-size: 12px;
            color: #666;
            margin-top: 15px;
            margin-bottom: 20px;
        }
        .password-info ul {
            margin: 0;
            padding-left: 20px;
        }
    </style>
</head>
<body>

<div class="register-container">
    <div class="register-header">
        <h1><span class="glyphicon glyphicon-shopping-cart"></span> My Online Store</h1>
        <p>Create Your Account</p>
    </div>

    <?php if (!empty($message)) { ?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <span class="glyphicon glyphicon-ok"></span> <?php echo $message; ?>
        </div>
    <?php } ?>

    <?php if (!empty($error)) { ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <span class="glyphicon glyphicon-exclamation-sign"></span> <?php echo $error; ?>
        </div>
    <?php } ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Choose a username (min 3 characters)" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Create a password (min 6 characters)" required>
        </div>

        <div class="form-group">
            <label for="password_confirm">Confirm Password</label>
            <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="Confirm your password" required>
        </div>

        <div class="password-info">
            <strong>Password Requirements:</strong>
            <ul>
                <li>Minimum 6 characters</li>
                <li>Use a mix of letters and numbers</li>
                <li>Keep it secure and unique</li>
            </ul>
        </div>

        <button type="submit" name="submit" class="btn-register">
            <span class="glyphicon glyphicon-user"></span> Create Account
        </button>
    </form>

    <div class="register-footer">
        <p>Already have an account? <a href="includes/login.php">Login here</a></p>
        <p><a href="index.php">Back to Store</a></p>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>



    <?php include "includes/footer.php"; ?>
<?php
session_start();

$errors = [];
$old = [];
$success = "";

// Handle form submission
if($_SERVER['REQUEST_METHOD'] === 'POST') {

    $old = $_POST;
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // -------- Email Validation --------
    if(empty($email)){
        $errors['email'] = "Email is required";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors['email'] = "Invalid email format";
    }

    // -------- Password Validation --------
    if(empty($password)){
        $errors['password'] = "Password is required";
    }

    // -------- Success --------
    if(empty($errors)){
        // For demo purposes, just show success
        // Here you can check the credentials in the database
        $success = "Login successful!";
        $old = [];
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cafeteria Login</title>
<?php include "../layouts/jsCDN.php"?>
</head>
<body>
    <?php include "../layouts/navbar.php"?>

<div class="container mt-5" style="max-width: 500px;">

    <h1 class="text-center mb-4">Cafeteria</h1>

    <?php if($success){ ?>
    <div class="alert alert-success text-center"><?php echo $success; ?></div>
    <?php } ?>

    <form action="" method="post">

        <!-- Email -->
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input 
                type="text" 
                class="form-control" 
                name="email" 
                value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>">
            <?php if(isset($errors['email'])){ ?>
            <p style="color:#6b2c2c; font-size:14px;"><?php echo $errors['email']; ?></p>
            <?php } ?>
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" name="password">
            <?php if(isset($errors['password'])){ ?>
            <p style="color:#6b2c2c; font-size:14px;"><?php echo $errors['password']; ?></p>
            <?php } ?>
        </div>

        <!-- Login Button -->
        <div class="mb-3 d-flex justify-content-between align-items-center">
            <button type="submit" class="btn btn-primary">Login</button>
            <a href="forgot-password.php">Forgot Password?</a>
        </div>

    </form>
</div>

</body>
</html>
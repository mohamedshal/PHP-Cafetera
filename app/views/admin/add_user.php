<?php
include "../../controllers/AuthController.php"

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add User</title>
<?php include "./jsCDN.php"?>
</head>
<body>

<?php include '../layouts/navbar.php'; ?>

<div class="container mt-5">
<h1 class="text-center mb-4">Add User</h1>

<?php if(isset($success)){ ?>
<div class="alert alert-success text-center"><?php echo $success; ?></div>
<?php } ?>

<form action="" method="post" enctype="multipart/form-data">

<!-- Name -->
<div class="mb-3">
<label class="form-label">Name</label>
<input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($old['name'] ?? ''); ?>">
<?php if(isset($errors['name'])){ ?>
<p style="color:#6b2c2c; font-size:14px;"><?php echo $errors['name']; ?></p>
<?php } ?>
</div>

<!-- Email -->
<div class="mb-3">
<label class="form-label">Email</label>
<input type="text" class="form-control" name="email" value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>">
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

<!-- Confirm Password -->
<div class="mb-3">
<label class="form-label">Confirm Password</label>
<input type="password" class="form-control" name="confirm_password">
<?php if(isset($errors['confirm_password'])){ ?>
<p style="color:#6b2c2c; font-size:14px;"><?php echo $errors['confirm_password']; ?></p>
<?php } ?>
</div>

<!-- Room -->
<div class="mb-3">
<label class="form-label">Room No.</label>
<input type="text" class="form-control" name="room_no" value="<?php echo htmlspecialchars($old['room_no'] ?? ''); ?>">
<?php if(isset($errors['room_no'])){ ?>
<p style="color:#6b2c2c; font-size:14px;"><?php echo $errors['room_no']; ?></p>
<?php } ?>
</div>

<!-- Extension -->
<div class="mb-3">
<label class="form-label">Ext.</label>
<input type="text" class="form-control" name="ext" value="<?php echo htmlspecialchars($old['ext'] ?? ''); ?>">
<?php if(isset($errors['ext'])){ ?>
<p style="color:#6b2c2c; font-size:14px;"><?php echo $errors['ext']; ?></p>
<?php } ?>
</div>

<!-- Profile Picture -->
<div class="mb-3">
<label class="form-label">Profile Picture</label>
<input type="file" class="form-control" name="profile_picture">
<?php if(isset($errors['profile_picture'])){ ?>
<p style="color:#6b2c2c; font-size:14px;"><?php echo $errors['profile_picture']; ?></p>
<?php } ?>
</div>

<div class="mt-4">
<button type="submit" class="btn btn-success">Save</button>
<button type="reset" class="btn btn-secondary">Reset</button>
</div>

</form>
</div>
</body>
</html>
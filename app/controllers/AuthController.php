<?php
session_start();

$errors = [];
$old = [];

// Check if form was submitted
if($_SERVER['REQUEST_METHOD'] === 'POST') {

    $old = $_POST; // keep old input

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    $room = trim($_POST['room_no']);
    $ext = trim($_POST['ext']);

    // -------- Name Validation --------
    if(!preg_match("/^[A-Za-z ]{3,30}$/", $name)){
        $errors['name'] = "Name must be 3-30 letters and spaces only";
    }

    // -------- Email Validation --------
    if(!preg_match("/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/", $email)){
        $errors['email'] = "Invalid email format";
    }

    // -------- Password Validation --------
    if(!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/", $password)){
        $errors['password'] = "Password must be minimum 8 characters with letters and numbers";
    }

    // -------- Confirm Password --------
    if($password !== $confirm){
        $errors['confirm_password'] = "Passwords do not match";
    }

    // -------- Room --------
    if(!preg_match("/^[1-9][0-9]{0,3}$/", $room)){
        $errors['room_no'] = "Room number must be between 1 and 9999";
    }

    // -------- Extension --------
    if(!empty($ext) && !preg_match("/^\d{2,5}$/", $ext)){
        $errors['ext'] = "Extension must be 2-5 digits only";
    }

    // -------- Profile Picture --------
    if(!empty($_FILES['profile_picture']['name'])){
        $allowed = ['jpg','jpeg','png'];
        $fileName = $_FILES['profile_picture']['name'];
        $extFile = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if(!in_array($extFile,$allowed)){
            $errors['profile_picture'] = "Only JPG, JPEG, PNG are allowed";
        }
    }

    // -------- Success --------
    if(empty($errors)){
        // Here you can include server.php logic or process the form
        // For demo, just show success message
        $success = "User added successfully!";
        $old = []; // clear old input
    }
}
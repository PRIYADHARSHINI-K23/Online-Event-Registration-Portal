<?php
include 'db_connect.php'; // Make sure this file connects to your DB

if(isset($_POST['name'], $_POST['email'], $_POST['password'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0){
        echo "Email already registered!";
        exit;
    }

    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password_hashed);

    if($stmt->execute()){
        echo "success";
    } else {
        echo "Error: ".$stmt->error;
    }
} else {
    echo "Please fill in all fields!";
}
?>

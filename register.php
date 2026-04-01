<?php
header("Content-Type: application/json");

// Database connection
$conn = new mysqli("localhost", "root", "", "eventhub"); // change if needed
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed."]));
}

// Get POST data
$event_id = $_POST['event_id'] ?? '';
$event_title = $_POST['event_title'] ?? '';
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$payment_type = $_POST['price'] ?? 'free';
$file_path = "";

// Handle payment screenshot if uploaded
if ($payment_type === "paid" && isset($_FILES['payment_screenshot'])) {
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) mkdir($target_dir);
    $file_name = time() . "_" . basename($_FILES["payment_screenshot"]["name"]);
    $target_file = $target_dir . $file_name;
    if (move_uploaded_file($_FILES["payment_screenshot"]["tmp_name"], $target_file)) {
        $file_path = $target_file;
    }
}

// Insert into database
$stmt = $conn->prepare("INSERT INTO registrations (event_id, event_title, name, email, phone, payment_type, payment_screenshot) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("issssss", $event_id, $event_title, $name, $email, $phone, $payment_type, $file_path);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Registered successfully."]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to register."]);
}

$stmt->close();
$conn->close();
?>

<?php
function clean($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Get POST data
$fname    = clean($_POST["fname"] ?? '');
$email    = clean($_POST["email"] ?? '');
$password = clean($_POST["password"] ?? '');
$location = clean($_POST["location"] ?? '');
$zip      = clean($_POST["zip"] ?? '');
$city     = clean($_POST["city"] ?? '');
$terms    = isset($_POST["terms"]) ? "Agreed" : "Not Agreed";

// Set favorite color in a cookie (not stored in DB)
$color = $_POST["color"] ?? '';
if ($color) {
    setcookie('favorite_color', $color, time() + (86400 * 30), "/"); // 30 days
}

// Connect to the database
$servername = "localhost";
$username = "root";
$password_db = "";
$dbname = "aqi";

$conn = new mysqli($servername, $username, $password_db, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert into users table
$sql = "INSERT INTO users (`Full Name`, `E-mail`, `Password`, `Location`, `Zip Code`, `Preferred City`)
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $fname, $email, $password, $location, $zip, $city);



if ($stmt->execute()) {
    // Redirect to login page
    header("Location: index.html");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

<?php
function clean($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$fname    = clean($_POST["fname"] ?? '');
$email    = clean($_POST["email"] ?? '');
$password = clean($_POST["password"] ?? '');
$location = clean($_POST["location"] ?? '');
$zip      = clean($_POST["zip"] ?? '');
$city     = clean($_POST["city"] ?? '');
$terms    = isset($_POST["terms"]) ? "Agreed" : "Not Agreed";
$color    = $_POST["color"] ?? '';

if ($color) {
    setcookie("favorite_color", $color, time() + (86400 * 30), "/"); // 30 days
}

$con = mysqli_connect("localhost", "root", "", "aqi");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "INSERT INTO users (`Full Name`, `E-mail`, `Password`, `Location`, `Zip Code`, `Preferred City`) 
        VALUES ('$fname', '$email', '$password', '$location', '$zip', '$city')";

if (mysqli_query($con, $sql)) {
    header("Location: index.html");
    exit();
} else {
    echo "Error inserting: " . mysqli_error($con);
}

mysqli_close($con);
?>

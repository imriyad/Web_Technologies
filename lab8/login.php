<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conn = new mysqli('localhost', 'root', '', 'aqi');
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    $email = $_POST['loginEmail'] ?? '';
    $password = $_POST['loginPassword'] ?? '';

    if (!$email || !$password) {
        header("Location: index.html?error=" . urlencode("Please enter both email and password."));
        exit();
    }

    // ✅ "Full Name" needs backticks since it contains a space
    $sql = "SELECT `Full Name`, `Password` FROM users WHERE `E-mail` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($name, $db_password);
        $stmt->fetch();

        if ($password === $db_password) {
            $_SESSION['user_email'] = $email;
            $_SESSION['user_name'] = $name;
            header("Location: request.php");
            exit();
        } else {
            header("Location: index.html?error=" . urlencode("Invalid password."));
            exit();
        }
    } else {
        header("Location: index.html?error=" . urlencode("No user found with that email."));
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: index.html");
    exit();
}

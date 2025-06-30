<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['loginEmail'] ?? '';
    $password = $_POST['loginPassword'] ?? '';

    if (empty($email) || empty($password)) {
        header("Location: index.html?error=" . urlencode("Please enter both email and password."));
        exit();
    }

    $con = mysqli_connect("localhost", "root", "", "aqi");
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT `Full Name`, `Password` FROM users WHERE `E-mail` = '$email'";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        if ($password === $row['Password']) {
            $_SESSION['user_email'] = $email;
            $_SESSION['user_name'] = $row['Full Name'];
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

    mysqli_close($con);
} else {
    header("Location: index.html");
    exit();
}
?>

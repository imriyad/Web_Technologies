<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Submitted Info</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #eef2f7;
            margin: 0;
            padding: 40px;
            display: flex;
            justify-content: center;
        }
        .container {
            background-color: #ffffff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            max-width: 600px;
            width: 100%;
        }
        h2 {
            color: #007bff;
            text-align: center;
            margin-bottom: 25px;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            margin-bottom: 15px;
            font-size: 16px;
            line-height: 1.5;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        strong {
            color: #333;
        }
        .btn-group {
            display: flex;
            justify-content: space-around;
            margin-top: 30px;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn-confirm {
            background-color: #28a745;
            color: white;
        }
        .btn-confirm:hover {
            background-color: #218838;
        }
        .btn-cancel {
            background-color: #dc3545;
            color: white;
        }
        .btn-cancel:hover {
            background-color: #c82333;
        }
        p {
            text-align: center;
            font-size: 18px;
            color: #666;
        }
    </style>
</head>
<body>
<div class="container">
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    function clean($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    $fname = clean($_POST["fname"] ?? '');
    $email = clean($_POST["email"] ?? '');
    $password = clean($_POST["password"] ?? '');
    $confirm = clean($_POST["confirm_password"] ?? '');
    $location = clean($_POST["location"] ?? '');
    $zip = clean($_POST["zip"] ?? '');
    $city = clean($_POST["city"] ?? '');
    $color = isset($_POST["color"]) ? clean($_POST["color"]) : '';
    $terms = isset($_POST["terms"]) ? "Agreed" : "Not Agreed";

    // Set the favorite color cookie for 30 days
    if ($color) {
        setcookie('favorite_color', $color, time() + 60*60*24*30, "/");
    }

    echo "<h2>Submitted Information</h2>";
    echo "<ul>";
    echo "<li><strong>Full Name:</strong> {$fname}</li>";
    echo "<li><strong>Email:</strong> {$email}</li>";
    echo "<li><strong>Password:</strong> {$password}</li>";
    echo "<li><strong>Confirm Password:</strong> {$confirm}</li>";
    echo "<li><strong>Location:</strong> {$location}</li>";
    echo "<li><strong>Zip:</strong> {$zip}</li>";
    echo "<li><strong>City:</strong> {$city}</li>";
    echo "<li><strong>Favorite Color:</strong> <span style='color:{$color};'>{$color}</span></li>";
    echo "<li><strong>Terms Accepted:</strong> {$terms}</li>";
    echo "</ul>";
    ?>
    <div class="btn-group">
        <form action="index.html" method="get">
            <button class="btn btn-cancel" type="submit">Cancel</button>
        </form>
        <form action="save_user.php" method="post">
    <input type="hidden" name="fname" value="<?= $fname ?>">
    <input type="hidden" name="email" value="<?= $email ?>">
    <input type="hidden" name="password" value="<?= $password ?>">
    <input type="hidden" name="location" value="<?= $location ?>">
    <input type="hidden" name="zip" value="<?= $zip ?>">
    <input type="hidden" name="city" value="<?= $city ?>">
    <input type="hidden" name="terms" value="<?= $terms ?>">
    <button class="btn btn-confirm" type="submit">Confirm</button>
</form>

    </div>
<?php
} else {
    echo "<p>No form data received.</p>";
}
?>
</div>
</body>
</html>

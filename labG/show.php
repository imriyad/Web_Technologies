<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_name']) || !isset($_SESSION['user_email'])) {
    header("Location: index.html");
    exit();
}

$name = htmlspecialchars($_SESSION['user_name']);
$email = htmlspecialchars($_SESSION['user_email']);

// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'aqi';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$cityData = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['city_ids'])) {
    $city_ids = $_POST['city_ids'];

    // Sanitize and prepare the query
    $placeholders = implode(',', array_fill(0, count($city_ids), '?'));
    $stmt = $conn->prepare("SELECT City, Country, AQI FROM info WHERE id IN ($placeholders)");

    $types = str_repeat('i', count($city_ids)); //creates a string of 'i' characters indicating each parameter is an integer.
    $stmt->bind_param($types, ...$city_ids);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {  //Fetch all matching rows into the $cityData array:
        $cityData[] = $row;
    }

    $stmt->close();
} else {
    die("No city IDs received.");
}

$conn->close();

// Get favorite color from cookie or default
$favoriteColor = isset($_COOKIE['favorite_color']) ? htmlspecialchars($_COOKIE['favorite_color']) : '#ffffff';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>City Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef2f7;
            background: <?= $favoriteColor ?>;
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: #ffffff;
            background: <?= $favoriteColor ?>;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header .user-info {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            font-size: 14px;
            color: #333;
        }

        .header .user-info strong {
            font-size: 16px;
            color: #007bff;
        }

        .logout {
            margin-top: 5px;
            display: inline-block;
            text-decoration: none;
            color: #ff4d4d;
            font-weight: bold;
            font-size: 13px;
        }

        .logout:hover {
            text-decoration: underline;
        }

        .container {
            background: <?= $favoriteColor ?>;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            max-width: 800px;
            width: 100%;
            margin: 40px auto;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px 16px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>

<div class="header">
    <div></div>
    <div class="user-info">
        <strong><?= $name ?></strong>
        <span><?= $email ?></span>
        <a href="logout.php" class="logout">Logout</a>
    </div>
</div>

<div class="container">
    <h2>Selected City Information</h2>
    <table>
        <thead>
            <tr>
                <th>City</th>
                <th>Country</th>
                <th>AQI</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cityData as $city): ?>
                <tr>
                    <td><?= htmlspecialchars($city['City']) ?></td>
                    <td><?= htmlspecialchars($city['Country']) ?></td>
                    <td><?= htmlspecialchars($city['AQI']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>

<?php
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

    $types = str_repeat('i', count($city_ids));
    $stmt->bind_param($types, ...$city_ids);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $cityData[] = $row;
    }

    $stmt->close();
} else {
    die("No city IDs received.");
}

$conn->close();

// Get favorite color from cookie or use default white
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
            padding: 40px;
            display: flex;
            justify-content: center;
        }

        .container {
            background: <?= $favoriteColor ?>;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            max-width: 800px;
            width: 100%;
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

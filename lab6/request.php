<!-- request.php -->
<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'aqi';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, City FROM info LIMIT 20";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Cities</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f7fa;
            padding: 40px;
            display: flex;
            justify-content: center;
        }

        .container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
        }

        h2 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }

        .city-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 20px;
        }

        .city-list label {
            display: block;
            padding: 10px;
            background-color: #f0f4f8;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn {
            display: inline-block;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Select up to 10 Cities</h2>
    <form action="show.php" method="POST" onsubmit="return validateSelection();">
        <div class="city-list">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <label>
                        <input type="checkbox" name="city_ids[]" value="<?= $row['id'] ?>">
                        <?= htmlspecialchars($row['City']) ?>
                    </label>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No cities found.</p>
            <?php endif; ?>
        </div>
        <button type="submit" class="btn">Show Info</button>
        <p class="error" id="error-message"></p>
    </form>
</div>

<script>
    function validateSelection() {
        const checkboxes = document.querySelectorAll('input[name="city_ids[]"]:checked');
        const errorMessage = document.getElementById('error-message');
        if (checkboxes.length === 0) {
            errorMessage.textContent = "Please select at least one city.";
            return false;
        }
        if (checkboxes.length > 10) {
            errorMessage.textContent = "You can select up to 10 cities only.";
            return false;
        }
        return true;
    }
</script>
</body>
</html>

<?php $conn->close(); ?>

<?php
session_start();

if (!isset($_SESSION['user_name']) || !isset($_SESSION['user_email'])) {
    header('Location: index.html');
    exit();
}

$name = htmlspecialchars($_SESSION['user_name']);
$email = htmlspecialchars($_SESSION['user_email']);

$con = mysqli_connect("localhost", "root", "", "aqi");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT id, City FROM info LIMIT 20";
$result = mysqli_query($con, $sql);

function clean($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$fname     = isset($_POST['fname']) ? clean($_POST['fname']) : '';
$emailForm = isset($_POST['email']) ? clean($_POST['email']) : '';
$location  = isset($_POST['location']) ? clean($_POST['location']) : '';
$zip       = isset($_POST['zip']) ? clean($_POST['zip']) : '';
$city      = isset($_POST['city']) ? clean($_POST['city']) : '';
$color     = isset($_POST['color']) ? clean($_POST['color']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Cities</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #f8f9fc, #e9eff5);
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .user-info {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            font-size: 14px;
            color: #333;
        }
        .user-info strong {
            font-size: 16px;
            color: #007bff;
        }
        .logout {
            margin-top: 5px;
            text-decoration: none;
            color: #ff4d4d;
            font-weight: bold;
            font-size: 13px;
        }
        .logout:hover {
            text-decoration: underline;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #007bff;
            margin-bottom: 25px;
        }
        .city-list {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 25px;
        }
        .city-list label {
            background: #f1f5f9;
            border-radius: 8px;
            padding: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .city-list label:hover {
            background: #e2ecf4;
        }
        .btn {
            background-color: #007bff;
            color: #fff;
            padding: 14px;
            width: 100%;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            text-align: center;
            margin-top: 15px;
        }
        input[type="checkbox"] {
            transform: scale(1.2);
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
    <h2>Select up to 10 Cities</h2>
    <form action="show.php" method="POST" onsubmit="return validateSelection();">
        <div class="city-list">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <label>
                        <input type="checkbox" name="city_ids[]" value="<?= $row['id'] ?>">
                        <?= htmlspecialchars($row['City']) ?>
                    </label>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No cities found.</p>
            <?php endif; ?>
        </div>

        <input type="hidden" name="fname" value="<?= $fname ?>">
        <input type="hidden" name="email" value="<?= $emailForm ?>">
        <input type="hidden" name="location" value="<?= $location ?>">
        <input type="hidden" name="zip" value="<?= $zip ?>">
        <input type="hidden" name="city" value="<?= $city ?>">
        <input type="hidden" name="color" value="<?= $color ?>">

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

<?php mysqli_close($con); ?>

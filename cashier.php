<?php
session_start();
include('config.php');

// Function to fetch medicine details based on search query
function searchMedicine($conn, $searchTerm) {
    $sql = "SELECT * FROM medcines WHERE name LIKE ?";
    $stmt = mysqli_prepare($conn, $sql);
    $searchTerm = '%' . $searchTerm . '%';
    mysqli_stmt_bind_param($stmt, "s", $searchTerm);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return $result;
}

// Function to generate bill
function generateBill($medicines) {
    $totalPrice = 0;
    echo "<h2>Bill</h2>";
    echo "<table>";
    echo "<thead><tr><th>Name</th><th>Description</th><th>Price</th></tr></thead>";
    echo "<tbody>";
    while ($row = mysqli_fetch_assoc($medicines)) {
        $totalPrice += $row['price'];
        echo "<tr>";
        echo "<td>" . $row['name'] . "</td>";
        echo "<td>" . $row['description'] . "</td>";
        echo "<td>$" . $row['price'] . "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
    echo "<p>Total Price: $" . $totalPrice . "</p>";
}

$searchResult = null;
if(isset($_POST['search'])) {
    $searchTerm = $_POST['searchTerm'];
    $searchResult = searchMedicine($conn, $searchTerm);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier Dashboard</title>
    <style>
       body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

h1 {
    text-align: center;
    margin-bottom: 20px;
}

form {
    text-align: center;
    margin-bottom: 20px;
}

input[type="text"] {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    width: 300px;
    margin-right: 10px;
}

input[type="submit"] {
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transform: translate(0.6s);
}

input[type="submit"]:hover {
    background-color: #0056b3;
    translate: scale(0.6s);
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table, th, td {
    border: 1px solid #ccc;
    padding: 10px;
    text-align: left;
}

th {
    background-color: #f2f2f2;
}

.total-price {
    margin-top: 20px;
    text-align: right;
    font-weight: bold;
}

.no-result {
    text-align: center;
    color: #ff0000;
}

    </style>
</head>
<body>
    <h1>Cashier Dashboard</h1>
    <form method="post" action="">
        <label>Search Medicine:</label>
        <input type="text" name="searchTerm" required>
        <input type="submit" name="search" value="Search">
    </form>
    <div>
        <?php
        if($searchResult) {
            if(mysqli_num_rows($searchResult) > 0) {
                generateBill($searchResult);
            } else {
                echo "<p>No medicines found.</p>";
            }
        }
        ?>
    </div>
</body>
</html>

<?php
// Include config file
include('config.php');

// Define variables and initialize with empty values
$name = $description = $price = '';

// Processing form data when form is submitted
if(isset($_POST['submit'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Insert new medicine into database
    $sql = "INSERT INTO medcines (name, description, price) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssd", $name, $description, $price);
    mysqli_stmt_execute($stmt);

    // Redirect to same page to refresh after insertion
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

// Delete medicine
if(isset($_GET['delete'])) {
    $name = $_GET['delete'];
    
    // Prepare the SQL statement to delete a medicine by name
    $sql = "DELETE FROM medcines WHERE name=?";
    $stmt = mysqli_prepare($conn, $sql);
    
    // Bind the parameter
    mysqli_stmt_bind_param($stmt, "s", $name);
    
    // Execute the statement
    $result = mysqli_stmt_execute($stmt);
    
    if($result) {
        // Redirect to same page after successful deletion
        header("Location: admin.php");
        exit();
    } else {
        // Error handling
        die("Error in deleting medicine: " . mysqli_error($conn));
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .delete-btn {
            background-color: #dc3545;
            color: #fff;
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Dashboard</h2>
        <form method="post" action="">
            <label>Name:</label>
            <input type="text" name="name" required>
            <label>Description:</label>
            <input type="text" name="description" required>
            <label>Price:</label>
            <input type="number" name="price" step="0.01" required>
            <input type="submit" name="submit" value="Add Medicine">
        </form>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch medicines from database
                $sql = "SELECT * FROM medcines";
                $result = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['description'] . "</td>";
                    echo "<td>$" . $row['price'] . "</td>";
                    echo "<td><button class='delete-btn' onclick='deleteMedicine(\"" . $row['name'] . "\")'>Delete</button></td>";

                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script>
        function deleteMedicine(name) {
            if(confirm("Are you sure you want to delete this medicine?")) {
                window.location.href = "<?php echo $_SERVER['PHP_SELF']; ?>?delete=" + name;
            }
        }
    </script>
</body>
</html>

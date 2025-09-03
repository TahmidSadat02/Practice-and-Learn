<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "cruddb";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Operations</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 25px;
            text-align: center;
        }

        form {
            display: grid;
            gap: 15px;
            max-width: 500px;
            margin: 0 auto 30px auto;
        }

        input[type="text"],
        input[type="email"] {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="email"]:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 0 3px rgba(52,152,219,0.1);
        }

        button {
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            background-color: #3498db;
            color: white;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #2980b9;
        }

        .message {
            text-align: center;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 6px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f8f9fa;
            color: #2c3e50;
        }

        tr:hover {
            background-color: #f8f9fa;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .edit-btn {
            background-color: #ffc107;
        }

        .delete-btn {
            background-color: #dc3545;
        }

        .edit-btn:hover {
            background-color: #e0a800;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
<div class="container">
<?php


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$message = "";

if (isset($_POST['insert'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    $sql = "INSERT INTO CRUD (id, name, email) VALUES ('$id', '$name', '$email')";
    if ($conn->query($sql) === TRUE) {
        $message = "New record inserted successfully";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();

    } else {
        $message = "Error: " . $conn->error;
    }
}


if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    $sql = "UPDATE CRUD SET name='$name', email='$email' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        $message = "Record updated successfully";
    } else {
        $message = "Error: " . $conn->error;
    }
}

if (isset($_POST['delete'])) {
    $id = $_POST['id'];

    if (!empty($id)){
    $sql = "DELETE FROM CRUD WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        $message = "Record with id $id deleted successfully";
    }
    } else {
        $message = "Error: " . $conn->error;
    
    }
}

$searchResult = null;
if (isset($_POST['search'])) {
    $id = $_POST['id'];
    $email = $_POST['email'];
   
    $sql = "SELECT * FROM CRUD WHERE id='$id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $searchResult = $result->fetch_assoc();
    } else {
        $message = "No record found with EMAIL $email";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>CRUD Operations</title>
</head>
<body>
<h2>CRUD Operations</h2>
<h4>Delete or Search by just entering id.</h4>
<p style="color:green;"><?php echo $message; ?></p>

<form method="post">
    <input type="text" name="id" placeholder="ID">
    <input type="text" name="name" placeholder="Name">
    <input type="email" name="email" placeholder="Email">

    <br><br>
    <button type="submit" name="insert">Insert</button>
    <button type="submit" name="update">Update</button>
    <button type="submit" name="delete">Delete</button>
    <button type="submit" name="search">Search</button>
</form>

<?php if (!empty($searchResult)) { ?>
    <h3>Search Result</h3>
    ID: <?php echo $searchResult['id']; ?> <br>
    Name: <?php echo $searchResult['name']; ?> <br>
    Email: <?php echo $searchResult['email']; ?> <br>
<?php } ?>

<h3>All Records</h3>
<table border="1" cellpadding="5">
    <tr><th>ID</th><th>Name</th><th>Email</th></tr>
    <?php
    $sql = "SELECT * FROM CRUD";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>".$row['id']."</td>
                    <td>".$row['name']."</td>
                    <td>".$row['email']."</td>
                 </tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No records found</td></tr>";
    }
    ?>
</table>
</body>
</html>
<?php $conn->close(); ?>
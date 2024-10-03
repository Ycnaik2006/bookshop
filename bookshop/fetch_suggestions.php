<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bookshop";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

// Fetch data from the database
$sql = "SELECT * FROM book_suggestions";
$result = $conn->query($sql);

$suggestions = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $suggestions[] = $row;
    }
    echo json_encode($suggestions);
} else {
    echo json_encode([]);
}

$conn->close();
?>

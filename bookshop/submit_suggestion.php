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

// Handle file upload
$cover_image = $_FILES['cover']['name'];
$target_dir = "uploads/";
$target_file = $target_dir . basename($cover_image);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Check if image file is a real image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["cover"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo json_encode(["status" => "error", "message" => "File is not an image."]);
        $uploadOk = 0;
        exit;
    }
}

// Check file size (5MB max)
if ($_FILES["cover"]["size"] > 5000000) {
    echo json_encode(["status" => "error", "message" => "Sorry, your file is too large."]);
    $uploadOk = 0;
    exit;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
    echo json_encode(["status" => "error", "message" => "Sorry, only JPG, JPEG, PNG & GIF files are allowed."]);
    $uploadOk = 0;
    exit;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo json_encode(["status" => "error", "message" => "Sorry, your file was not uploaded."]);
    exit;
// If everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["cover"]["tmp_name"], $target_file)) {
        $username = $_POST['username'];
        $booktitle = $_POST['booktitle'];
        $author = $_POST['author'];
        $description = $_POST['description'];

        $sql = "INSERT INTO book_suggestions (username, booktitle, author, description, cover_image)
                VALUES ('$username', '$booktitle', '$author', '$description', '$cover_image')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "message" => "New record created successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error: " . $sql . "<br>" . $conn->error]);
        }

        $conn->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Sorry, there was an error uploading your file."]);
    }
}
?>

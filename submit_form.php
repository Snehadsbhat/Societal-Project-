<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "school";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $dob = isset($_POST['dob']) ? $_POST['dob'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $course = isset($_POST['course']) ? $_POST['course'] : '';
    $address = isset($_POST['address']) ? $_POST['address'] : '';

    
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $photo_name = $_FILES['photo']['name'];
        $photo_tmp = $_FILES['photo']['tmp_name'];
        $photo_size = $_FILES['photo']['size'];
        $photo_type = $_FILES['photo']['type'];
        $upload_dir = "uploads/";

      
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

     
        $photo_path = $upload_dir . basename($photo_name);
        if (move_uploaded_file($photo_tmp, $photo_path)) {
            $photo_data = file_get_contents($photo_path);
        } else {
            echo "<script>alert('Error uploading file'); window.history.back();</script>";
            exit();
        }
    } else {
        echo "<script>alert('No file uploaded or error in file upload'); window.history.back();</script>";
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO `admission_form` (name, dob, email, phone, course, address, photo) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssb", $name, $dob, $email, $phone, $course, $address, $photo_data);

    if ($stmt->execute()) {
        echo "<script>alert('Form submitted successfully!'); window.location.href='admission_form.html';</script>";
    } else {
        echo "<script>alert('Error submitting form. Please try again!');</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('Invalid request'); window.history.back();</script>";
}

$conn->close();
?>

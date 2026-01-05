<?php

session_start();
if (!isset($_SESSION['test_id'])) {
    echo json_encode(["message" => "User not authenticated"]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    // Database connection
    include_once '../connection/connection.php';
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $user_id = $_SESSION['user_id']; // Get user_id from session
    $file = $_FILES['file'];

    $file_name = $file['name'];
    $file_tmp_name = $file['tmp_name'];
    $file_size = $file['size'];
    $file_error = $file['error'];

    // Get file extension
    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

    // Check for file upload errors
    if ($file_error === 0) {
        // Create the directory structure for user-specific uploads
        $upload_directory = '../uploads/' . $_SESSION['test_id'] . '/'; // Directory structure with test_id
        if (!is_dir($upload_directory)) {
            mkdir($upload_directory, 0777, true); // Create the directory if it doesn't exist
        }

        // Construct the file path with the test_id and original file name
        $file_path = $upload_directory . basename($file_name);

        // Generate a unique file name by appending a timestamp or a unique ID
        $unique_file_name = uniqid() . "_" . $file_name;
        $file_path = $upload_directory . basename($unique_file_name);

        // Now move the uploaded file
        if (move_uploaded_file($file_tmp_name, $file_path)) {
            // Read the file content (if you want to store the content)
            $file_content = file_get_contents($file_path);

            // Insert data into the database
            $stmt = $conn->prepare("INSERT INTO submissions (user_id, file_name, file_extension, file_content) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $user_id, $unique_file_name, $file_ext, $file_content);

            if ($stmt->execute()) {
                echo json_encode(["message" => "File uploaded successfully", "nextQuestion" => true]);
            } else {
                echo json_encode(["message" => "Error inserting data into the database"]);
            }

            $stmt->close();
        } else {
            echo json_encode(["message" => "Failed to move the uploaded file"]);
        }
    } else {
        echo json_encode(["message" => "Error uploading file"]);
    }

    $conn->close();
} else {
    echo json_encode(["message" => "No file uploaded"]);
}
?>
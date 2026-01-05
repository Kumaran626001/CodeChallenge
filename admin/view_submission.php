<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin | View Submissions</title>
  <link rel="stylesheet" href="../boot/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/view_submission.css">
</head>

<body>

  <div class="container">
    <?php
    // Fetch all submissions for a specific user_id
    include_once '../connection/connection.php';

    if (isset($_GET['id'])) {
        $user_id = $_GET['id'];

        // Prepare the query to fetch all submissions for the user
        $query = "SELECT * FROM submissions WHERE user_id = ? ORDER BY upload_timestamp DESC";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch the corresponding roll_number for the user
        $user_query = "SELECT name,college_name,dept_name FROM Users WHERE user_id = ?";
        $user_stmt = $conn->prepare($user_query);
        $user_stmt->bind_param("i", $user_id);
        $user_stmt->execute();
        $user_result = $user_stmt->get_result();
        $user = $user_result->fetch_assoc();

        if ($user) {
            $name = $user['name'];
            $college_name = $user['college_name'];
            $dept_name = $user['dept_name'];
            echo "<h4><span style='font-weight:bold;color:#7630ff'>Submitted By</span> : " . htmlspecialchars($name);
            echo "<h4><span style='font-weight:bold;color:#7630ff'>College</span> : ".htmlspecialchars($college_name)."</h4>"; 
            echo "<h4><span style='font-weight:bold;color:#7630ff'>Department</span> : ".htmlspecialchars($dept_name) ."</h4>";
            
            if ($result->num_rows > 0) {
                while ($submission = $result->fetch_assoc()) {
                    // Split the timestamp into date and time
                    $timestamp = $submission['upload_timestamp'];
                    $date = date('Y-m-d', strtotime($timestamp)); // Extract the date
                    $time = date('H:i:s', strtotime($timestamp)); // Extract the time

                    echo "<div class='submission-card'>";
                    echo "<h5>File Name: " . htmlspecialchars($submission['file_name']) . "</h5>";
                    echo "<p><strong>Submission Date:</strong> " . htmlspecialchars($date) . "</p>";
                    echo "<p><strong>Submission Time:</strong> " . htmlspecialchars($time) . "</p>";
                    echo "<p><strong>File Content:</strong></p>";
                    echo "<pre class='file-content'>" . htmlspecialchars($submission['file_content']) . "</pre>";
                    echo "</div>";
                }
            } else {
                echo "<p class='text-danger'>No submissions found for this user.</p>";
            }
        } else {
            echo "<p class='text-danger'>User not found.</p>";
        }

        $stmt->close();
        $user_stmt->close();
    } else {
        echo "<p class='text-danger'>No user ID provided.</p>";
    }
    ?>

    <div class="text-center">
      <a href="admin_home.php" class="btn btn-back">Back to Dashboard</a>
    </div>
  </div>

  <script src="../boot/js/bootstrap.min.js"></script>
</body>

</html>
 
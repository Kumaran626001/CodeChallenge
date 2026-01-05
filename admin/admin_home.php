<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Home</title>
    <link rel="stylesheet" href="../boot/css/bootstrap.min.css">
</head>

<body>
<?php
// Include database connection
include_once '../connection/connection.php';

// Fetch all submissions along with user details
$query = "
    SELECT 
        s.user_id, 
        COUNT(s.user_id) AS file_count,
        u.name, 
        u.college_name, 
        u.dept_name 
    FROM submissions s
    JOIN Users u ON s.user_id = u.user_id
    GROUP BY s.user_id
    ORDER BY u.user_id, MAX(s.upload_timestamp) DESC
";
$result = $conn->query($query);

echo "<h2 class='text-center text-primary my-4'>All Submissions</h2>";
echo "<div class='container'>";
echo "<table class='table table-striped table-bordered table-hover'>";
echo "<thead class='thead-dark'>";
echo "<tr>
        <th>Serial No.</th>
        <th>Name</th>
        <th>College</th>
        <th>Department</th>
        <th>Total Files</th>
        <th>Action</th>
      </tr>";
echo "</thead><tbody>";

$serial_no = 1;

while ($row = $result->fetch_assoc()) {
    $user_id = $row['user_id'];
    $name = $row['name'];
    $college_name = $row['college_name'];
    $dept_name = $row['dept_name'];
    $file_count = $row['file_count'];

    echo "<tr>";
    echo "<td>" . $serial_no++ . "</td>";
    echo "<td>" . htmlspecialchars($name) . "</td>";
    echo "<td>" . htmlspecialchars($college_name) . "</td>";
    echo "<td>" . htmlspecialchars($dept_name) . "</td>";
    echo "<td>" . $file_count . "</td>";
    echo "<td><a href='view_submission.php?id=" . $user_id . "' class='btn btn-primary btn-sm'>View</a></td>";
    echo "</tr>";
}

echo "</tbody></table>";
echo "</div>";
?>

</body>

</html>

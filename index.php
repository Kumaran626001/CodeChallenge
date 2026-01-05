<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CodeChallenge</title>
  <link rel="stylesheet" href="./boot/css/bootstrap.min.css">
  <style>
    .gradient-custom-2 {
      /* fallback for old browsers */
      background: #fccb90;

      /* Chrome 10-25, Safari 5.1-6 */
      background: -webkit-linear-gradient(to right, #ee7724, #d8363a, #dd3675, #b44593);

      /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
      background: linear-gradient(to right, #ee7724, #d8363a, #dd3675, #b44593);
    }

    @media (min-width: 768px) {
      .gradient-form {
        height: 100vh !important;
      }
    }
    input{
      text-transform: uppercase;
    }
    @media (min-width: 769px) {
      .gradient-custom-2 {
        border-top-right-radius: .3rem;
        border-bottom-right-radius: .3rem;
      }
    }
  </style>
</head>

<body>
  <section class="h-100 gradient-form" style="background-color: #eee;">
    <div class="container py-2 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-xl-10">
          <div class="card rounded-2 text-black">
            <div class="row g-0">
              <div class="col-lg-6">
                <div class="card-body p-md-5 mx-md-5">

                  <div class="text-center">
                    <img src="logo.jpg" style="width: 100px; height: 100px" alt="logo">
                    <h4 class="mt-1 mb-3">Department of Information Technology</h4>
                  </div>
                  <form method="post" action="" id="login-form">
                    <div class="form-outline mb-2">
                      <label class="form-label" for="test_id" >Test ID</label>
                      <input type="text" id="test_id" name="test_id" class="form-control" required>
                    </div>

                    <div class="form-outline mb-2">
                      <label class="form-label" for="name">Name</label>
                      <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    <div class="form-outline mb-2">
                      <label class="form-label" for="college_name">College Name</label>
                      <input type="text" id="college_name" name="college_name" class="form-control" required>
                    </div>
                    <div class="form-outline mb-2">
                      <label class="form-label" for="dept_name">Department Name</label>
                      <input type="text" id="dept_name" name="dept_name" class="form-control" required>
                    </div>
                    <div class="text-center mt-2">
                      <button type="submit" name="submit" class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3">Login
                        </button>
                    </div>

                  </form>

                </div>
              </div>
              <div class="col-lg-6 d-flex align-items-center gradient-custom-2">
                <div class="text-white px-3 py-4 p-md-5 mx-md-4">
                  <h4 class="mb-4">Welcome Everyone</h4>
                  <p class="medium mb-3">To get started, please log in using the following credentials:
                    </p>
                    <li>Test Id : Enter your logged in id.</li>
                    <li>Name : Enter your name.</li>
                    <li>College Name : Enter the name of your college.</li>
                    <li>Department Name : Enter the name of your department.</li>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script src="./boot/js/bootstrap.min.js"></script>

  <?php
  session_start();
  include_once "./connection/connection.php";

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  if (isset($_POST['submit'])) {
    $test_id = strtoupper($_POST['test_id']);
    $name = strtoupper($_POST['name']);
    $college_name = strtoupper($_POST['college_name']);
    $dept_name = strtoupper($_POST['dept_name']);

    if (empty($test_id) || empty($name) || empty($college_name) || empty($dept_name)) {
      echo "<script>alert('Fill all the required fields.');</script>";
      exit;
    }

    // Check if the user already exists
    $stmt = $conn->prepare("SELECT user_id, role FROM users WHERE test_login = ? AND name = ?");
    $stmt->bind_param("ss", $test_id, $name);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      // User already exists, fetch the user_id and role
      $stmt->bind_result($user_id, $role);
      $stmt->fetch();
      $_SESSION['user_id'] = $user_id;
      $_SESSION['test_id'] = $test_id;
      $_SESSION['name'] = $name;
      $_SESSION['college_name'] = $college_name;
      $_SESSION['dept_name'] = $dept_name;
      if ($role === 'admin') {
        // If the user is an admin, redirect to the admin page
        header("Location: ./admin/admin_home.php");
      } else {
        header("Location: ./php/rule.php");
      }
    } else {
      // User does not exist, insert new user
      $stmt = $conn->prepare("INSERT INTO users (test_login, name, college_name, dept_name, role) VALUES (?, ?, ?, ?, 'user')");
      $stmt->bind_param("ssss", $test_id, $name, $college_name, $dept_name);
      

      if ($stmt->execute()) {
        // Fetch the last inserted user_id
        $last_id = $conn->insert_id;
        $_SESSION['user_id'] = $last_id;
        $_SESSION['test_id'] = $test_id;
        $_SESSION['name'] = $name;
        // $_SESSION['role'] = $role;
      $_SESSION['college_name'] = $college_name;
      $_SESSION['dept_name'] = $dept_name;
        // Default role is user
        header("Location: ./php/rule.php");
      } else {
        echo "<script>alert('Failed to add user: " . $stmt->error . "');</script>";
      }
    }

    $stmt->close();
  }
  ?>
  
</body>

</html>

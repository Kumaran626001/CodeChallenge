<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>
  <link rel="stylesheet" href="../boot/css/bootstrap.min.css" />
  <style>
  #question-text pre {
    font-family: monospace;
    background-color: #f8f9fa;
    padding: 10px;
    width: 100%;
    border: 1px solid #ccc;
    border-radius: 5px;
    display: inline-block;
  }
</style>
</head>

<body>
  <?php
  session_start();
  if (!isset($_SESSION['test_id'])) {
    header("Location: ../index.php");
    exit();
  }
  ?>
  <div id="main-content">
    <div class="container">
      <div class="header" style="display: flex;justify-content:center;">
        <img src="../head.png" style="max-width: 100%; height: auto;">
      </div>
      <div
        style="display:flex;justify-content :space-around;background-color:#16348C;height:121px;align-items:center;border-radius:3px">
        <div class="logo">
          <img src="../logo1.png" alt="" style="height:120px;">
        </div>
        <h2 class="text-center my-4" style="color:white;font-size:45px;">Silver Jubilee: Celebrating a Legacy of
          Excellence</h2>
        <div class="logo">
          <img src="../logo2.png" alt="" style="height: 110px;">
        </div>
      </div>
      <div id="timer" class="text-center mt-2 mb-4 p-3 bg-light border rounded shadow-sm">
        <h4 class="text-danger font-weight-bold">
          Time Left: <span id="time-left" class="badge bg-danger"></span>
        </h4>
      </div>
      <div id="question-container" class="mb-4">
        <h4 id="question-text"></h4>
      </div>
      <form id="file-upload-form" enctype="multipart/form-data" method="POST">
        <div class="container mb-4">
          <div>
            <label for="formFileLg" class="form-label">Upload your program file here.</label>
            <input class="form-control form-control-lg" id="formFileLg" type="file" disabled>
          </div>
        </div>
        <div class="container d-flex justify-content-between">
          <button id="upload-button" class="btn btn-success" disabled type="button">Upload</button>
        </div>
      </form>
    </div>
  </div>

  <div id="thank-you-message" class="text-center d-none">
    <h1 class="display-4 text-success">Thank You for Participating!</h1>
    <p class="lead">We appreciate your effort and contribution.</p>
  </div>

  <!-- Upload Confirmation Modal -->
  <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="uploadModalLabel">Upload Confirmation</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Your file will be uploaded. Do you want to proceed?<br>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" id="modal-ok-button">OK</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Tick Confirmation Modal -->
  <div class="modal fade" id="tickModal" tabindex="-1" aria-labelledby="tickModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body text-center">
          <div class="text-success">
            <img src="../assets/images/check-mark.png" alt="" style="height: 50px;width:50px;">
          </div>
          <h5 class="mt-3">File Uploaded Successfully!</h5>
        </div>
      </div>
    </div>
  </div>
  <script src="../scripts/block-right.js"></script>
  <script src="../boot/js/bootstrap.bundle.min.js"></script>
  <script src="../jQuery/jQuery3.7.1.js"></script>
  <script>
    const questions = [
  "Write a program to find the smallest of three integers without using any of the comparison operators?",
  "Write a program to print the first letter of each word.",
  `Write a program to find two adjacent numbers from a given matrix that when added gives the desired output. 
  The numbers can be adjacent either horizontally, vertically, or diagonally:

    1  3  4  6  2
    3  5  8  9  0
    1  7  3  2  4
    2  3  1  4  2
    6  4  3  2  1

  If the sum of two numbers is specified as 10, then the output should be:
    4 + 6 : 10
    7 + 3 : 10
    7 + 3 : 10
    6 + 4 : 10
    3 + 7 : 10
    8 + 2 : 10
    6 + 4 : 10`,
  `Given a two dimensional array of string like?

  <"luke","shaw">
  <"wayne","rooney">
  <"rooney","ronaldo">
  <"shaw","rooney">

  Where the first string is "child", second string is "Father". And given "Ronaldo" we have to find his 
  number of grand children. So our output should be 2.
  `,
  `Print the following format

  Input : 3
  Output :

  3 3 3 3 3
  3 2 2 2 3
  3 2 1 2 3
  3 2 2 2 3
  3 3 3 3 3`,

  `Given an array and a sum. Program to find the two numbers that forms sum in the given array

  Input Array : 6 2 1 3 4 0 5
  Sum         : 6
  
  Output : 
         6 and 0
         2 and 4
         1 and 5`
];

    let currentQuestion = 0;
    
    let timeLeft = 2400; // 40 minutes in seconds
    let timer = null; // Declare the timer variable

    const mainContent = document.getElementById("main-content");
    const thankYouMessage = document.getElementById("thank-you-message");
    const questionText = document.getElementById("question-text");
    const uploadButton = document.getElementById("upload-button");
    const fileInput = document.getElementById("formFileLg");
    const modalOkButton = document.getElementById("modal-ok-button");
    const uploadModal = new bootstrap.Modal(document.getElementById('uploadModal'));
    const tickModal = new bootstrap.Modal(document.getElementById('tickModal'));
    const modalCancelButton = document.querySelector("#uploadModal .btn-secondary");

    fileInput.addEventListener("change", () => {
      uploadButton.disabled = fileInput.files.length === 0;
    });

    modalCancelButton.addEventListener("click", () => {
      fileInput.value = ""; // Clear the selected file
      uploadButton.disabled = true;
    });


    function loadQuestion() {
  if (currentQuestion < questions.length) {
    questionText.innerHTML = `Question ${currentQuestion + 1}: <pre>${questions[currentQuestion]}</pre>`;
    fileInput.disabled = false;
    uploadButton.disabled = true;

    // Reset timer
    timeLeft = 2400;

    // Clear any existing timer
    if (timer) {
      clearInterval(timer);
    }

    // Start a new timer
    timer = setInterval(updateTimer, 1000);
  } else {
    // All questions answered
    mainContent.classList.add("d-none");
    thankYouMessage.classList.remove("d-none");

    // Clear the timer
    if (timer) {
      clearInterval(timer);
    }
  }
}




function updateTimer() {
  const minutes = Math.floor(timeLeft / 60);
  const seconds = timeLeft % 60;

  document.getElementById("time-left").textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

  if (timeLeft <= 0) {
    clearInterval(timer);
    fileInput.disabled = true;
    uploadButton.disabled = true;

    alert("Time Over! Moving to the next question...");
    currentQuestion++;
    loadQuestion();
  } else {
    timeLeft--; // Decrease time by 1 second
  }
}



    async function uploadFile(file) {
      console.log("Uploading file:", file.name);
      try {
        const formData = new FormData();
        formData.append("file", file);
        formData.append("user_id", "<?php echo $_SESSION['user_id']; ?>");

        const response = await fetch('upload_file.php', {
          method: 'POST',
          body: formData
        });

        const result = await response.json();
        if (response.ok && result.nextQuestion) {
          return { success: true, message: "File uploaded successfully" };
        } else {
          throw new Error(result.message || 'File upload failed');
        }
      } catch (error) {
        console.error("File upload failed:", error);
        return { success: false, message: error.message };
      }
    }

    uploadButton.addEventListener("click", () => {
      uploadModal.show();
    });

    modalOkButton.addEventListener("click", async () => {
      uploadModal.hide();
      const file = fileInput.files[0];

      if (file) {
        const result = await uploadFile(file);

        if (result.success) {
          tickModal.show();
          setTimeout(() => {
            tickModal.hide();
            currentQuestion++;
            fileInput.value = '';
            loadQuestion();
          }, 2000); // Using HackTimer
        } else {
          alert(result.message);
        }
      }
    });

    loadQuestion();
    
  </script>
</body>

</html>

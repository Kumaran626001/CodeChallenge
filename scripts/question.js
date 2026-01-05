const questions = [
    "Write a program to calculate the factorial of a given number?",
    "Write a program that checks if a given string is a palindrome.?",
    "Write a program to rotate a given 3x3 matrix by 90 degrees clockwise?",
    "Implement a program to find the nth Fibonacci number using recursion?",
    "Write a program to implement a stack using arrays, supporting push, pop, and display operations?"
  ];

  let currentQuestion = 0;
let timeLeft = 60;
let timer;

const uploadButton = document.getElementById("upload-button");
const fileInput = document.getElementById("formFileLg");
const modalOkButton = document.getElementById("modal-ok-button");
const uploadModal = new bootstrap.Modal(document.getElementById('uploadModal'));
const tickModal = new bootstrap.Modal(document.getElementById('tickModal'));

fileInput.addEventListener("change", () => {
  uploadButton.disabled = fileInput.files.length === 0;
});

function loadQuestion() {
  if (currentQuestion < questions.length) {
    document.getElementById("question-text").textContent = `Question ${currentQuestion + 1}: ${questions[currentQuestion]}`;
    fileInput.disabled = false;
    uploadButton.disabled = true;
  } else {
    alert("Quiz Completed!");
  }
}

function updateTimer() {
  document.getElementById("time-left").textContent = timeLeft;
  if (timeLeft <= 0) {
    clearInterval(timer);
    fileInput.disabled = true;
    uploadButton.disabled = true;
    // Show the alert dialog when the time runs out
    setTimeout(() => {
      alert("Time's up! Proceeding to the next question.");
      // Proceed to the next question after alert
      nextQuestion();
    }, 500); // Delay to allow for the timer to update before alert
  } else {
    timeLeft--;
  }
}

function freezeTimer() {
  clearInterval(timer); // Stops the timer
}

function uploadFile(file) {
  console.log("Uploading file:", file.name);
  // Simulate file upload with a timeout
  return new Promise((resolve) => {
    setTimeout(() => resolve("File uploaded successfully"), 1000);
  });
}

uploadButton.addEventListener("click", () => {
  uploadModal.show();
});

modalOkButton.addEventListener("click", async () => {
  uploadModal.hide();
  const file = fileInput.files[0];

  if (file) {
    try {
      // Create a FormData object
      const formData = new FormData();
      formData.append("file", file);
      formData.append("user_id", "<?php echo $_SESSION['user_id']; ?>"); // Assuming you have a user ID in the session

      // Send the file to the server using fetch
      const response = await fetch('upload_file.php', {
        method: 'POST',
        body: formData
      });

      const result = await response.json();
      console.log(result);

      if (response.ok && result.nextQuestion) {
        // Successfully uploaded, show the success modal
        freezeTimer(); // Stop the timer after successful upload
        tickModal.show();

        setTimeout(() => {
          tickModal.hide();
          fileInput.disabled = true;
          uploadButton.disabled = true;
          fileInput.value = "";
          // Proceed to the next question
          nextQuestion();
        }, 2000); // Tick modal stays visible for 2 seconds
      } else {
        throw new Error(result.message || 'File upload failed');
      }
    } catch (error) {
      console.error("File upload failed:", error);
    }
  }
});

function nextQuestion() {
  currentQuestion++;
  loadQuestion();
  timeLeft = 60;
  timer = setInterval(updateTimer, 1000); // Restart the timer for the next question
}

loadQuestion();
timer = setInterval(updateTimer, 1000);

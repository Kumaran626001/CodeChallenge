// Disable right-click
document.addEventListener("contextmenu", function (e) {
  e.preventDefault(); // Disable right-click menu
});

// Disable F12 (developer tools) and other key combinations
document.addEventListener("keydown", function (e) {
  // Prevent F12
  if (e.key === "F12") {
    e.preventDefault();
  }

  // Prevent Ctrl+Shift+I (Dev Tools)
  if (e.ctrlKey && e.shiftKey && e.key === "I") {
    e.preventDefault();
  }

  // Prevent Ctrl+Shift+J (Dev Tools)
  if (e.ctrlKey && e.shiftKey && e.key === "J") {
    e.preventDefault();
  }

  // Prevent F5 (Refresh)
  if (e.key === "F5") {
    e.preventDefault();
  }

  // Prevent Ctrl+R (Refresh)
  if (e.ctrlKey && e.key === "r") {
    e.preventDefault();
  }
});

// Disable browser refresh button
window.addEventListener("beforeunload", function (e) {
  // Show confirmation message when trying to refresh
  e.preventDefault();
  // Some browsers require a return value to show a confirmation dialog
  e.returnValue = ""; // Chrome shows a default confirmation dialog
});

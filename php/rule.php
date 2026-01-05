<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Competition Rules</title>
    <link rel="stylesheet" href="../boot/css/bootstrap.min.css" class="css">
    <link rel="stylesheet" href="../fontawesome-free-6.5.2-web/css/fontawesome.min.css">
    <link rel="stylesheet" href="../fontawesome-free-6.5.2-web/css/all.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3>Competition Rules</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Questions:</strong> Questions 1-6 must be completed within the allotted time.</p>
                        <p><strong>Time Limit:</strong> Each question has a maximum time limit of 30 minutes.</p>
                        <p><strong>Submission:</strong> Participants are required to upload their programming files as part of their answers.</p>
                        <p><strong>Malpractices:</strong></p>
                        <ul>
                            <li>Refreshing the page or any unauthorized activity is strictly prohibited and will result in disqualification.</li>
                            <li>Participants must not engage in any activity considered as malpractice.</li>
                        </ul>
                        <p><strong>Behavior:</strong> All participants must conduct themselves respectfully and avoid any inappropriate behavior.</p>
                        <p><strong>Technical Issues:</strong> In case of technical difficulties, notify the quiz administrator immediately. Exploiting technical glitches will be considered cheating.</p>
                        <p><strong>Honesty:</strong> All participants must follow the honor code and answer questions to the best of their ability without external help.</p>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <button class="btn btn-primary" onclick="nextPage()">Move to Quiz</button>
                </div>
            </div>
        </div>
    </div>
    <script src="../boot/js/bootstrap.min.js"></script>
    <script>
        function nextPage() {
            window.location.href = 'home.php';
        }
    </script>
</body>

</html>

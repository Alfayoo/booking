<?php
include 'dbcon.php';

// Get form values
$full_name = $_POST['full_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];

// Generate seat number based on current user count
$seat_number = null;
$user_query = "SELECT COUNT(*) AS total FROM users";
$user_result = mysqli_query($conn, $user_query);
if ($user_result) {
    $row = mysqli_fetch_assoc($user_result);
    $seat_number = 'S' . ($row['total'] + 1);
}

// Insert into database
$sql = "INSERT INTO users (full_name, email, phone, seat_number) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ssss", $full_name, $email, $phone, $seat_number);
    if (mysqli_stmt_execute($stmt)) {
        // Success message with Bootstrap
        echo '
            <html>
            <head>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                <title>Booking Success</title>
            </head>
            <body class="container mt-5">
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">Booking Successful!</h4>
                    <p>Thank you, <strong>' . htmlspecialchars($full_name) . '</strong>!</p>
                    <p>Your seat number is: <strong>' . $seat_number . '</strong></p>
                    <a href="home.html" class="btn btn-primary mt-3">Go to Home</a>
                </div>
            </body>
            </html>
        ';
    } else {
        // Insert failed
        echo '
            <div class="container mt-5">
                <div class="alert alert-danger" role="alert">
                    <h4 class="alert-heading">Booking Unsuccessful!</h4>
                    <p>Something went wrong. Please try again.</p>
                    <a href="home.html" class="btn btn-primary mt-3">Return to Home</a>
                </div>
            </div>
        ';
    }

    mysqli_stmt_close($stmt);
} else {
    // SQL preparation failed
    echo '
        <div class="container mt-5">
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading">Error preparing SQL!</h4>
                <p>' . mysqli_error($conn) . '</p>
                <a href="home.html" class="btn btn-primary mt-3">Return to Home</a>
            </div>
        </div>
    ';
}

mysqli_close($conn);
?>

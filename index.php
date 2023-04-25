<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
}

$username_ver = $_SESSION["user_id"];
$user_full_name = $_SESSION["user_full_name"];
$user_id = $_SESSION["user_id"];

// Database connection
require_once "database.php";

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query the days table with a specific ID
$sql = "SELECT * FROM days WHERE id_strange = " . $user_id; // Modify query to include WHERE clause
$result = mysqli_query($conn, $sql);
$user_id_ver = mysqli_fetch_array($result, MYSQLI_ASSOC);

// Check if query is successful
if ($user_id_ver) {
    // Fetch the data
    // Access the data using column names
    $data = $user_id_ver["data"];
    $rating = $user_id_ver["rating"];
    $info = $user_id_ver["info"];

    // Query the days table with the same user ID for additional data
    $query = "SELECT * FROM days WHERE id_strange = " . $user_id;
    $result2 = mysqli_query($conn, $query);

    // Fetch data from the result set
    $data = array();
    while ($row = mysqli_fetch_assoc($result2)) {
        $data[] = $row;
    }
} else {
    echo "0 results";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <title>User Dashboard</title>

    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="container2">
        <h1>welcome <span class="name"><?php echo strtok($user_full_name, " "); ?></span>, it's good to see you! <br> you are in the dashboard</h1>
       <!-- <p>Date: <?php echo $data; ?></p>
        <p>Rating: <?php echo $rating; ?></p>
        <p>Info: <?php echo $info; ?></p>
        <p>User ID: <?php echo $user_id; ?></p> -->

        <canvas id="myChart"></canvas> <!-- Canvas element for the chart -->
        <script>
            // Retrieve data from PHP and process it
            var data = <?php echo json_encode($data); ?>; // Data retrieved from PHP as an array

            // Extract relevant data for chart
            var dates = [];
            var ratings = [];
            data.forEach(function(entry) {
                dates.push(entry.data); // Assuming 'date' is the column name for dates in your database
                ratings.push(entry.rating); // Assuming 'rating' is the column name for ratings in your database
            });

            // Create a bar chart using Chart.js
            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: dates, // Use dates as labels
                    datasets: [{
                        label: 'ratings', // Label for the data
                        data: ratings, // Use ratings as data
                        backgroundColor: 'rgba(29, 104, 76, 1)', // Set background color for bars
                        borderColor: 'rgba(100, 100, 111, 1)', // Set border color for bars
                        borderWidth: 1 // Set border width for bars
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 20000 // Set maximum value for y-axis
                        }
                    }
                }
            });
        </script>
        
         <a href="logout.php" class="btn btn-warning">logout</a>
    </div>
</body>
<?php
// Include database connection and functions
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <h1>Dashboard</h1>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Product Categories
                    </div>
                    <div class="card-body">
                        <canvas id="productCategoriesChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Monthly Sales
                    </div>
                    <div class="card-body">
                        <canvas id="monthlySalesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Custom JS -->
    <script src="../assets/js/script.js"></script>

    <script>
        // Product Categories Chart
        var productCategoriesCtx = document.getElementById('productCategoriesChart').getContext('2d');
        var productCategoriesChart = new Chart(productCategoriesCtx, {
            type: 'pie',
            data: {
                labels: [
                    <?php
                    $categories = getProductCategories($conn);
                    foreach ($categories as $category) {
                        echo "'" . $category['Type_Name'] . "',";
                    }
                    ?>
                ],
                datasets: [{
                    data: [
                        <?php
                        foreach ($categories as $category) {
                            echo $category['Count'] . ",";
                        }
                        ?>
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Monthly Sales Chart
        var monthlySalesCtx = document.getElementById('monthlySalesChart').getContext('2d');
        var monthlySalesChart = new Chart(monthlySalesCtx, {
            type: 'line',
            data: {
                labels: [
                    <?php
                    $monthlySales = getMonthlySales($conn);
                    foreach ($monthlySales as $month) {
                        echo "'" . $month['Month'] . "',";
                    }
                    ?>
                ],
                datasets: [{
                    label: 'Sales',
                    data: [
                        <?php
                        foreach ($monthlySales as $month) {
                            echo $month['Total'] . ",";
                        }
                        ?>
                    ],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
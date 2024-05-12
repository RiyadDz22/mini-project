<?php
// Start session
session_start();

// Include configuration file
require_once 'includes/config.php';

// Include functions file
require_once 'includes/functions.php';

// Get the current page from the URL
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Management System</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php">Stock Management</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=dashboard">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=products">Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=clients">Clients</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=suppliers">Suppliers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=purchases">Purchases</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=sales">Sales</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=stock">Stock</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <?php
        // Load the requested page
        switch ($page) {
            case 'dashboard':
                require_once 'pages/dashboard.php';
                break;
            case 'products':
                require_once 'pages/products.php';
                break;
            case 'clients':
                require_once 'pages/clients.php';
                break;
            case 'suppliers':
                require_once 'pages/suppliers.php';
                break;
            case 'purchases':
                require_once 'pages/purchases.php';
                break;
            case 'sales':
                require_once 'pages/sales.php';
                break;
            case 'stock':
                require_once 'pages/stock.php';
                break;
            default:
                require_once 'pages/dashboard.php';
                break;
        }
        ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/script.js"></script>
</body>
</html>
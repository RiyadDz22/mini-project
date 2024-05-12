<?php
// Include database connection and functions
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Adjust stock
    if (isset($_POST['adjust_stock'])) {
        adjustStock($conn, $_POST);
    }
}

// Get stock data
$stock = getStock($conn);
$products = $conn->query("SELECT * FROM PRODUCT")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Stock</h1>

        <!-- Stock Adjustment Form -->
        <h2>Adjust Stock</h2>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
                <label for="product_id">Product</label>
                <select class="form-control" id="product_id" name="product_id" required>
                    <option value="">Select Product</option>
                    <?php foreach ($products as $product) : ?>
                        <option value="<?php echo $product['Product_Code']; ?>"><?php echo $product['Product_Name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" required>
            </div>
            <div class="form-group">
                <label for="adjustment_type">Adjustment Type</label>
                <select class="form-control" id="adjustment_type" name="adjustment_type" required>
                    <option value="in">In</option>
                    <option value="out">Out</option>
                </select>
            </div>
            <div class="form-group">
                <label for="reason">Reason</label>
                <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary" name="adjust_stock">Adjust Stock</button>
        </form>

        <hr>

        <!-- Stock List -->
        <h2>Current Stock</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Purchase Value</th>
                    <th>Sale Value</th>
                    <th>Profit</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stock as $item) : ?>
                    <tr>
                        <td><?php echo $item['Product_Name']; ?></td>
                        <td><?php echo $item['Quantity']; ?></td>
                        <td>
                            <?php
                            $purchase_value = $item['Purchase_Unit_Price'] * $item['Quantity'];
                            echo number_format($purchase_value, 2);
                            ?>
                        </td>
                        <td>
                            <?php
                            $sale_value = $item['Sale_Unit_Price'] * $item['Quantity'];
                            echo number_format($sale_value, 2);
                            ?>
                        </td>
                        <td>
                            <?php
                            $profit = $sale_value - $purchase_value;
                            echo number_format($profit, 2);
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Custom JS -->
    <script src="../assets/js/script.js"></script>
</body>
</html>
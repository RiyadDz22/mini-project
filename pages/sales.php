<?php
// Include database connection and functions
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add sale
    if (isset($_POST['add_sale'])) {
        addSale($conn, $_POST);
    }
}

// Delete sale
if (isset($_GET['delete'])) {
    deleteSale($conn, $_GET['delete']);
}

// Get all sales, clients, and products
$sales = getSales($conn);
$clients = $conn->query("SELECT * FROM CLIENT")->fetchAll();
$products = $conn->query("SELECT * FROM PRODUCT")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Sales</h1>

        <!-- Sale Form -->
        <h2>Create Sale</h2>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
                <label for="sale_date">Sale Date</label>
                <input type="date" class="form-control" id="sale_date" name="sale_date" required>
            </div>
            <div class="form-group">
                <label for="client_id">Client</label>
                <select class="form-control" id="client_id" name="client_id">
                    <option value="">Select Client</option>
                    <?php foreach ($clients as $client) : ?>
                        <option value="<?php echo $client['Client_Code']; ?>"><?php echo $client['First_Name'] . ' ' . $client['Last_Name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div id="product_list">
                <div class="form-row">
                    <div class="col">
                        <label for="product_1">Product</label>
                        <select class="form-control" id="product_1" name="product_ids[]" required>
                            <option value="">Select Product</option>
                            <?php foreach ($products as $product) : ?>
                                <option value="<?php echo $product['Product_Code']; ?>"><?php echo $product['Product_Name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col">
                        <label for="quantity_1">Quantity</label>
                        <input type="number" class="form-control" id="quantity_1" name="quantities[]" min="1" required>
                    </div>
                    <div class="col">
                        <label for="unit_price_1">Unit Price</label>
                        <input type="number" class="form-control" id="unit_price_1" name="unit_prices[]" min="0" step="0.01" required>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-secondary" id="add_product_row">Add Product</button>
            <button type="submit" class="btn btn-primary" name="add_sale">Create Sale</button>
        </form>

        <hr>

        <!-- Sale List -->
        <h2>Sales</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Sale Number</th>
                    <th>Sale Date</th>
                    <th>Client</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sales as $sale) : ?>
                    <tr>
                        <td><?php echo $sale['Sale_Number']; ?></td>
                        <td><?php echo $sale['Sale_Date']; ?></td>
                        <td><?php echo $sale['First_Name'] . ' ' . $sale['Last_Name']; ?></td>
                        <td>
                            <?php
                            // Calculate the total for this sale
                            $total = 0;
                            $sale_details = $conn->prepare("SELECT sd.Quantity, sd.Unit_Price FROM SALE_DETAIL sd WHERE sd.Sale_Number = ?");
                            $sale_details->execute([$sale['Sale_Number']]);
                            $sale_details_result = $sale_details->fetchAll();
                            foreach ($sale_details_result as $detail) {
                                $total += $detail['Quantity'] * $detail['Unit_Price'];
                            }
                            echo number_format($total, 2);
                            ?>
                        </td>
                        <td>
                            <a href="sale_details.php?id=<?php echo $sale['Sale_Number']; ?>" class="btn btn-primary btn-sm">Details</a>
                            <a href="?delete=<?php echo $sale['Sale_Number']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this sale?')">Delete</a>
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
<?php
// Include database connection and functions
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add purchase order
    if (isset($_POST['add_purchase_order'])) {
        addPurchaseOrder($conn, $_POST);
    }
    // Add invoice
    elseif (isset($_POST['add_invoice'])) {
        addInvoice($conn, $_POST);
    }
}

// Get all purchase orders, invoices, suppliers, and products
$purchase_orders = getPurchaseOrders($conn);
$invoices = getInvoices($conn);
$suppliers = $conn->query("SELECT * FROM SUPPLIER")->fetchAll();
$products = $conn->query("SELECT * FROM PRODUCT")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchases</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Purchases</h1>

        <!-- Purchase Order Form -->
        <h2>Create Purchase Order</h2>
        <form method="post" action="">
            <div class="form-group">
                <label for="order_date">Order Date</label>
                <input type="date" class="form-control" id="order_date" name="order_date" required>
            </div>
            <div class="form-group">
                <label for="supplier_id">Supplier</label>
                <select class="form-control" id="supplier_id" name="supplier_id" required>
                    <option value="">Select Supplier</option>
                    <?php foreach ($suppliers as $supplier) : ?>
                        <option value="<?php echo $supplier['Supplier_Code']; ?>"><?php echo $supplier['Name']; ?></option>
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
                </div>
            </div>
            <a href="index.php?page=products" class="btn btn-secondary">Add Product</a>
            <button type="submit" class="btn btn-primary" name="add_purchase_order">Create Purchase Order</button>
        </form>

        <hr>

        <!-- Purchase Order List -->
        <h2>Purchase Orders</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Order Number</th>
                    <th>Order Date</th>
                    <th>Supplier</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($purchase_orders as $purchase_order) : ?>
                    <tr>
                        <td><?php echo $purchase_order['Order_Number']; ?></td>
                        <td><?php echo $purchase_order['Order_Date']; ?></td>
                        <td><?php echo $purchase_order['Supplier_Name']; ?></td>
                        <td>
                            <a href="view_purchase_order.php?id=<?php echo $purchase_order['Order_Number']; ?>" class="btn btn-sm btn-primary">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <hr>

        <!-- Invoice Form -->
        <h2>Create Invoice</h2>
        <form method="post" action="">
            <div class="form-group">
                <label for="invoice_date">Invoice Date</label>
                <input type="date" class="form-control" id="invoice_date" name="invoice_date" required>
            </div>
            <div class="form-group">
                <label for="supplier_id">Supplier</label>
                <select class="form-control" id="supplier_id" name="supplier_id" required>
                    <option value="">Select Supplier</option>
                    <?php foreach ($suppliers as $supplier) : ?>
                        <option value="<?php echo $supplier['Supplier_Code']; ?>"><?php echo $supplier['Name']; ?></option>
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
                        <label for="purchase_price_1">Purchase Price</label>
                        <input type="number" class="form-control" id="purchase_price_1" name="purchase_prices[]" min="0" step="0.01" required>
                    </div>
                    <div class="col">
                        <label for="sale_price_1">Sale Price</label>
                        <input type="number" class="form-control" id="sale_price_1" name="sale_prices[]" min="0" step="0.01" required>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-secondary" id="add_product_row">Add Product</button>
            <button type="submit" class="btn btn-primary" name="add_invoice">Create Invoice</button>
        </form>

        <hr>

        <!-- Invoice List -->
        <h2>Invoices</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Invoice Number</th>
                    <th>Invoice Date</th>
                    <th>Supplier</th>
                    <th>Total HT</th>
                    <th>Total TTC</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invoices as $invoice) : ?>
                    <tr>
                        <td><?php echo $invoice['Invoice_Number']; ?></td>
                        <td><?php echo $invoice['Invoice_Date']; ?></td>
                        <td><?php echo $invoice['Supplier_Name']; ?></td>
                        <td>
                            <?php
                            // Calculate the total HT for this invoice
                            $total_ht = 0;
                            $invoice_details = $conn->prepare("SELECT id.Purchase_Quantity, id.Purchase_Unit_Price FROM INVOICE_DETAIL id WHERE id.Invoice_Number = ?");
                            $invoice_details->execute([$invoice['Invoice_Number']]);
                            $invoice_details_result = $invoice_details->fetchAll();
                            foreach ($invoice_details_result as $detail) {
                                $total_ht += $detail['Purchase_Quantity'] * $detail['Purchase_Unit_Price'];
                            }
                            echo number_format($total_ht, 2);
                            ?>
                        </td>
                        <td>
                            <?php
                            // Calculate the total TTC for this invoice
                            $total_ttc = $total_ht * 1.19; // Assuming a VAT rate of 19%
                            echo number_format($total_ttc, 2);
                            ?>
                        </td>
                        <td>
                            <a href="view_invoice.php?id=<?php echo $invoice['Invoice_Number']; ?>" class="btn btn-sm btn-primary">View</a>
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
<?php
// Include database connection and functions
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add or update product based on the form action
    if (isset($_POST['add_product'])) {
        addProduct($conn, $_POST);
    } elseif (isset($_POST['update_product'])) {
        updateProduct($conn, $_POST);
    }
}

// Delete product
if (isset($_GET['delete'])) {
    deleteProduct($conn, $_GET['delete']);
}

// Get all products and product types
$products = getProducts($conn);
$product_types = $conn->query("SELECT * FROM PRODUCT_TYPE")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Products</h1>

        <!-- Add Product Form -->
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
                <label for="product_name">Product Name</label>
                <input type="text" class="form-control" id="product_name" name="product_name" required>
            </div>
            <div class="form-group">
                <label for="product_type">Product Type</label>
                <select class="form-control" id="product_type" name="product_type" required>
                    <option value="">Select Product Type</option>
                    <?php foreach ($product_types as $type) : ?>
                        <option value="<?php echo $type['Type_Code']; ?>"><?php echo $type['Type_Name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" name="add_product">Add Product</button>
        </form>

        <hr>

        <!-- Product List -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Product Code</th>
                    <th>Product Name</th>
                    <th>Product Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product) : ?>
                    <tr>
                        <td><?php echo $product['Product_Code']; ?></td>
                        <td><?php echo $product['Product_Name']; ?></td>
                        <td><?php echo $product['Type_Name']; ?></td>
                        <td>
                            <a href="edit_product.php?id=<?php echo $product['Product_Code']; ?>" class="btn btn-sm btn-primary">Edit</a>
                            <a href="products.php?delete=<?php echo $product['Product_Code']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
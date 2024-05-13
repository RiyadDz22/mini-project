<?php
require_once __DIR__ . '/../includes/config.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_product'])) {
        addProduct($conn, $_POST);
    } elseif (isset($_POST['update_product'])) {
        updateProduct($conn, $_POST);
    } elseif (isset($_POST['delete_product_code'])) {
        $id = $_POST['delete_product_code'];
        $query = "DELETE FROM product WHERE Product_Code = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            // Redirect to prevent form resubmission
            header("Location: {$_SERVER['PHP_SELF']}");
            exit();
        } else {
            echo "Something went wrong. Please try again later.";
        }
    }
}

// Get all products and product types
$products = getProducts($conn);
$product_types = $conn->query("SELECT * FROM PRODUCT_TYPE")->fetchAll();

// Function to retrieve product details by code
function getProductByCode($products, $code) {
    foreach ($products as $product) {
        if ($product['Product_Code'] == $code) {
            return $product;
        }
    }
    return null;
}

// Function to update product details
function updateProduct2($conn, $data) {
    $product_code = $data['product_code'];
    $product_name = $data['product_name'];
    $product_type = $data['product_type'];

    $query = "UPDATE product SET Product_Name = :product_name, Type_Code = :product_type WHERE Product_Code = :product_code";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':product_name', $product_name);
    $stmt->bindParam(':product_type', $product_type);
    $stmt->bindParam(':product_code', $product_code);
    $stmt->execute();
}

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

<!-- Add or Update Product Form -->
<form method="post" action="">
    <?php if (isset($_POST['edit_product'])) : ?>
        <?php $edited_product = getProductByCode($products, $_POST['edit_product']); ?>
        <input type="hidden" name="product_code" value="<?php echo $edited_product['Product_Code']; ?>">
        <div class="form-group">
            <label for="product_name">Product Name</label>
            <input type="text" class="form-control" id="product_name" name="product_name" value="<?php echo $edited_product['Product_Name']; ?>" required>
        </div>
        <div class="form-group">
            <label for="product_type">Product Type</label>
            <select class="form-control" id="product_type" name="product_type" required>
                <option value="">Select Product Type</option>
                <?php foreach ($product_types as $type) : ?>
                    <option value="<?php echo $type['Type_Code']; ?>" <?php if ($type['Type_Code'] == $edited_product['Type_Code']) echo "selected"; ?>><?php echo $type['Type_Name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary" name="update_product">Update Product</button>
    <?php else : ?>
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
    <?php endif; ?>
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
                            <form method="post" action="">
                                <input type="hidden" name="edit_product" value="<?php echo $product['Product_Code']; ?>">
                                <button type="submit" class="btn btn-sm btn-primary">Edit</button>
                            </form>
                            <form method="post" action="">
                                <input type="hidden" name="delete_product_code" value="<?php echo $product['Product_Code']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                            </form>
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


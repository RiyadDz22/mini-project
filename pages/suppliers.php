<?php
// Include database connection and functions
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add or update supplier based on the form action
    if (isset($_POST['add_supplier'])) {
        addSupplier($conn, $_POST);
    } elseif (isset($_POST['update_supplier'])) {
        updateSupplier($conn, $_POST);
    }
}

// Delete supplier
if (isset($_GET['delete'])) {
    deleteSupplier($conn, $_GET['delete']);
}

// Get all suppliers
$suppliers = getSuppliers($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suppliers</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Suppliers</h1>

        <!-- Add Supplier Form -->
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
                <label for="supplier_name">Supplier Name</label>
                <input type="text" class="form-control" id="supplier_name" name="supplier_name" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" name="address">
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" class="form-control" id="phone" name="phone">
            </div>
            <button type="submit" class="btn btn-primary" name="add_supplier">Add Supplier</button>
        </form>

        <hr>

        <!-- Supplier List -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Supplier Code</th>
                    <th>Supplier Name</th>
                    <th>Address</th>
                    <th>Phone Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($suppliers as $supplier) : ?>
                    <tr>
                        <td><?php echo $supplier['Supplier_Code']; ?></td>
                        <td><?php echo $supplier['Name']; ?></td>
                        <td><?php echo $supplier['Address']; ?></td>
                        <td><?php echo $supplier['Phone_Number']; ?></td>
                        <td>
                            <a href="edit_supplier.php?id=<?php echo $supplier['Supplier_Code']; ?>" class="btn btn-sm btn-primary">Edit</a>
                            <a href="suppliers.php?delete=<?php echo $supplier['Supplier_Code']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this supplier?')">Delete</a>
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
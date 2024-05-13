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
    } elseif (isset($_POST['delete_supplier'])) {
        $id = $_POST['delete_supplier'];
        $query = "DELETE FROM supplier WHERE Supplier_Code = :id";
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

        <!-- Add or Update Supplier Form -->
        <form method="post" action="">
            <?php if (isset($_POST['edit_supplier'])) : ?>
                <?php $edited_supplier = getSupplierById($conn, $_POST['edit_supplier']); ?>
                <input type="hidden" name="supplier_code" value="<?php echo $edited_supplier['Supplier_Code']; ?>">
                <input type="hidden" name="supplier_id" value="<?php echo $edited_supplier['Supplier_Code']; ?>"> <!-- New line -->
                <div class="form-group">
                    <label for="supplier_name">Supplier Name</label>
                    <input type="text" class="form-control" id="supplier_name" name="supplier_name" value="<?php echo $edited_supplier['Name']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" id="address" name="address" value="<?php echo $edited_supplier['Address']; ?>">
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $edited_supplier['Phone_Number']; ?>">
                </div>
                <button type="submit" class="btn btn-primary" name="update_supplier">Update Supplier</button>
            <?php else : ?>
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
            <?php endif; ?>
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
                            <form method="post" action="">
                                <input type="hidden" name="delete_supplier" value="<?php echo $supplier['Supplier_Code']; ?>">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this supplier?')">Delete</button>
                            </form>
                            <form method="post" action="">
                                <input type="hidden" name="edit_supplier" value="<?php echo $supplier['Supplier_Code']; ?>">
                                <button type="submit" class="btn btn-sm btn-primary">Edit</button>
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

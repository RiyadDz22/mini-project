<?php
// Include database connection and functions
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add or update client based on the form action
    if (isset($_POST['add_client'])) {
        addClient($conn, $_POST);
    } elseif (isset($_POST['update_client'])) {
        updateClient($conn, $_POST);
    }
}

// Delete client
if (isset($_GET['delete'])) {
    deleteClient($conn, $_GET['delete']);
}

// Get all clients
$clients = getClients($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Clients</h1>

        <!-- Add Client Form -->
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" name="address">
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" class="form-control" id="phone" name="phone">
            </div>
            <div class="form-group">
                <label for="credit_amount">Credit Amount</label>
                <input type="number" class="form-control" id="credit_amount" name="credit_amount" step="0.01" value="0">
            </div>
            <button type="submit" class="btn btn-primary" name="add_client">Add Client</button>
        </form>

        <hr>

        <!-- Client List -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Client Code</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Address</th>
                    <th>Phone Number</th>
                    <th>Credit Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $client) : ?>
                    <tr>
                        <td><?php echo $client['Client_Code']; ?></td>
                        <td><?php echo $client['First_Name']; ?></td>
                        <td><?php echo $client['Last_Name']; ?></td>
                        <td><?php echo $client['Address']; ?></td>
                        <td><?php echo $client['Phone_Number']; ?></td>
                        <td><?php echo $client['Credit_Amount']; ?></td>
                        <td>
                            <a href="edit_client.php?id=<?php echo $client['Client_Code']; ?>" class="btn btn-sm btn-primary">Edit</a>
                            <a href="clients.php?delete=<?php echo $client['Client_Code']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this client?')">Delete</a>
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
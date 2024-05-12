<?php
/**
 * Get all clients from the database
 *
 * @param PDO $conn
 * @return array
 */
function getClients($conn)
{
    $stmt = $conn->prepare("SELECT * FROM CLIENT");
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Get a single client by ID
 *
 * @param PDO $conn
 * @param int $id
 * @return mixed
 */
function getClientById($conn, $id)
{
    $stmt = $conn->prepare("SELECT * FROM CLIENT WHERE Client_Code = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

/**
 * Add a new client to the database
 *
 * @param PDO $conn
 * @param array $data
 * @return bool
 */
function addClient($conn, $data)
{
    $stmt = $conn->prepare("INSERT INTO CLIENT (First_Name, Last_Name, Address, Phone_Number, Credit_Amount) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$data['first_name'], $data['last_name'], $data['address'], $data['phone'], $data['credit_amount']]);
}

/**
 * Update an existing client in the database
 *
 * @param PDO $conn
 * @param array $data
 * @return bool
 */
function updateClient($conn, $data)
{
    $stmt = $conn->prepare("UPDATE CLIENT SET First_Name = ?, Last_Name = ?, Address = ?, Phone_Number = ?, Credit_Amount = ? WHERE Client_Code = ?");
    return $stmt->execute([$data['first_name'], $data['last_name'], $data['address'], $data['phone'], $data['credit_amount'], $data['client_id']]);
}

/**
 * Delete a client from the database
 *
 * @param PDO $conn
 * @param int $id
 * @return bool
 */
function deleteClient($conn, $id)
{
    $stmt = $conn->prepare("DELETE FROM CLIENT WHERE Client_Code = ?");
    return $stmt->execute([$id]);
}


/**
 * Get all products from the database
 *
 * @param PDO $conn
 * @return array
 */
function getProducts($conn)
{
    $stmt = $conn->prepare("SELECT p.*, pt.Type_Name FROM PRODUCT p JOIN PRODUCT_TYPE pt ON p.Type_Code = pt.Type_Code");
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Get a single product by ID
 *
 * @param PDO $conn
 * @param int $id
 * @return mixed
 */
function getProductById($conn, $id)
{
    $stmt = $conn->prepare("SELECT p.*, pt.Type_Name FROM PRODUCT p JOIN PRODUCT_TYPE pt ON p.Type_Code = pt.Type_Code WHERE p.Product_Code = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

/**
 * Add a new product to the database
 *
 * @param PDO $conn
 * @param array $data
 * @return bool
 */
function addProduct($conn, $data)
{
    $stmt = $conn->prepare("INSERT INTO PRODUCT (Product_Name, Type_Code) VALUES (?, ?)");
    return $stmt->execute([$data['product_name'], $data['product_type']]);
}

/**
 * Update an existing product in the database
 *
 * @param PDO $conn
 * @param array $data
 * @return bool
 */
function updateProduct($conn, $data)
{
    $stmt = $conn->prepare("UPDATE PRODUCT SET Product_Name = ?, Type_Code = ? WHERE Product_Code = ?");
    return $stmt->execute([$data['product_name'], $data['product_type'], $data['product_id']]);
}

/**
 * Delete a product from the database
 *
 * @param PDO $conn
 * @param int $id
 * @return bool
 */
function deleteProduct($conn, $id)
{
    $stmt = $conn->prepare("DELETE FROM PRODUCT WHERE Product_Code = ?");
    return $stmt->execute([$id]);
}


/**
 * Get all suppliers from the database
 *
 * @param PDO $conn
 * @return array
 */
function getSuppliers($conn)
{
    $stmt = $conn->prepare("SELECT * FROM SUPPLIER");
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Get a single supplier by ID
 *
 * @param PDO $conn
 * @param int $id
 * @return mixed
 */
function getSupplierById($conn, $id)
{
    $stmt = $conn->prepare("SELECT * FROM SUPPLIER WHERE Supplier_Code = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

/**
 * Add a new supplier to the database
 *
 * @param PDO $conn
 * @param array $data
 * @return bool
 */
function addSupplier($conn, $data)
{
    $stmt = $conn->prepare("INSERT INTO SUPPLIER (Name, Address, Phone_Number) VALUES (?, ?, ?)");
    return $stmt->execute([$data['supplier_name'], $data['address'], $data['phone']]);
}

/**
 * Update an existing supplier in the database
 *
 * @param PDO $conn
 * @param array $data
 * @return bool
 */
function updateSupplier($conn, $data)
{
    $stmt = $conn->prepare("UPDATE SUPPLIER SET Name = ?, Address = ?, Phone_Number = ? WHERE Supplier_Code = ?");
    return $stmt->execute([$data['supplier_name'], $data['address'], $data['phone'], $data['supplier_id']]);
}

/**
 * Delete a supplier from the database
 *
 * @param PDO $conn
 * @param int $id
 * @return bool
 */
function deleteSupplier($conn, $id)
{
    $stmt = $conn->prepare("DELETE FROM SUPPLIER WHERE Supplier_Code = ?");
    return $stmt->execute([$id]);
}

/**
 * Get all purchase orders from the database
 *
 * @param PDO $conn
 * @return array
 */
function getPurchaseOrders($conn)
{
    $stmt = $conn->prepare("SELECT po.*, s.Name AS Supplier_Name FROM PURCHASE_ORDER po JOIN SUPPLIER s ON po.Supplier_Code = s.Supplier_Code");
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Get a single purchase order by ID
 *
 * @param PDO $conn
 * @param int $id
 * @return mixed
 */
function getPurchaseOrderById($conn, $id)
{
    $stmt = $conn->prepare("SELECT po.*, s.Name AS Supplier_Name, pod.* FROM PURCHASE_ORDER po JOIN SUPPLIER s ON po.Supplier_Code = s.Supplier_Code LEFT JOIN PURCHASE_ORDER_DETAIL pod ON po.Order_Number = pod.Order_Number WHERE po.Order_Number = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

/**
 * Add a new purchase order to the database
 *
 * @param PDO $conn
 * @param array $data
 * @return bool
 */
function addPurchaseOrder($conn, $data)
{
    $conn->beginTransaction();

    try {
        $stmt = $conn->prepare("INSERT INTO PURCHASE_ORDER (Order_Date, Supplier_Code) VALUES (?, ?)");
        $stmt->execute([$data['order_date'], $data['supplier_id']]);
        $order_number = $conn->lastInsertId();

        if (isset($data['product_ids'])) {
            $order_details_stmt = $conn->prepare("INSERT INTO PURCHASE_ORDER_DETAIL (Order_Number, Product_Code, Quantity) VALUES (?, ?, ?)");
            foreach ($data['product_ids'] as $index => $product_id) {
                $quantity = $data['quantities'][$index];
                $order_details_stmt->execute([$order_number, $product_id, $quantity]);
            }
        }

        $conn->commit();
        return true;
    } catch (PDOException $e) {
        $conn->rollBack();
        return false;
    }
}

/**
 * Get all invoices from the database
 *
 * @param PDO $conn
 * @return array
 */
function getInvoices($conn)
{
    $stmt = $conn->prepare("SELECT i.*, s.Name AS Supplier_Name FROM INVOICE i JOIN SUPPLIER s ON i.Supplier_Code = s.Supplier_Code");
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Get a single invoice by ID
 *
 * @param PDO $conn
 * @param int $id
 * @return mixed
 */
function getInvoiceById($conn, $id)
{
    $stmt = $conn->prepare("SELECT i.*, s.Name AS Supplier_Name, id.* FROM INVOICE i JOIN SUPPLIER s ON i.Supplier_Code = s.Supplier_Code LEFT JOIN INVOICE_DETAIL id ON i.Invoice_Number = id.Invoice_Number WHERE i.Invoice_Number = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

/**
 * Add a new invoice to the database
 *
 * @param PDO $conn
 * @param array $data
 * @return bool
 */
function addInvoice($conn, $data)
{
    $conn->beginTransaction();

    try {
        $stmt = $conn->prepare("INSERT INTO INVOICE (Invoice_Date, Supplier_Code) VALUES (?, ?)");
        $stmt->execute([$data['invoice_date'], $data['supplier_id']]);
        $invoice_number = $conn->lastInsertId();

        $total_ht = 0;
        $invoice_details_stmt = $conn->prepare("INSERT INTO INVOICE_DETAIL (Invoice_Number, Product_Code, Purchase_Quantity, Purchase_Unit_Price, Sale_Unit_Price) VALUES (?, ?, ?, ?, ?)");
        foreach ($data['product_ids'] as $index => $product_id) {
            $quantity = $data['quantities'][$index];
            $purchase_price = $data['purchase_prices'][$index];
            $sale_price = $data['sale_prices'][$index];
            $invoice_details_stmt->execute([$invoice_number, $product_id, $quantity, $purchase_price, $sale_price]);
            $total_ht += $purchase_price * $quantity;
        }

        $total_ttc = $total_ht * (1 + 0.19); // Assuming a VAT rate of 19%

        // Update stock quantities
        $update_stock_stmt = $conn->prepare("UPDATE STOCK SET Quantity = Quantity + ? WHERE Product_Code = ?");
        foreach ($data['product_ids'] as $index => $product_id) {
            $quantity = $data['quantities'][$index];
            $update_stock_stmt->execute([$quantity, $product_id]);
        }

        $conn->commit();
        return true;
    } catch (PDOException $e) {
        $conn->rollBack();
        return false;
    }
}

/**
 * Delete a purchase order from the database
 *
 * @param PDO $conn
 * @param int $id
 * @return bool
 */
function deletePurchaseOrder($conn, $id)
{
    $conn->beginTransaction();

    try {
        $stmt = $conn->prepare("DELETE FROM PURCHASE_ORDER_DETAIL WHERE Order_Number = ?");
        $stmt->execute([$id]);

        $stmt = $conn->prepare("DELETE FROM PURCHASE_ORDER WHERE Order_Number = ?");
        $stmt->execute([$id]);

        $conn->commit();
        return true;
    } catch (PDOException $e) {
        $conn->rollBack();
        return false;
    }
}

/**
 * Delete an invoice from the database
 *
 * @param PDO $conn
 * @param int $id
 * @return bool
 */
function deleteInvoice($conn, $id)
{
    $conn->beginTransaction();

    try {
        $stmt = $conn->prepare("DELETE FROM INVOICE_DETAIL WHERE Invoice_Number = ?");
        $stmt->execute([$id]);

        $stmt = $conn->prepare("DELETE FROM INVOICE WHERE Invoice_Number = ?");
        $stmt->execute([$id]);

        $conn->commit();
        return true;
    } catch (PDOException $e) {
        $conn->rollBack();
        return false;
    }
}

/**
 * Get all sales from the database
 *
 * @param PDO $conn
 * @return array
 */
function getSales($conn)
{
    $stmt = $conn->prepare("SELECT s.*, c.First_Name, c.Last_Name FROM SALE s LEFT JOIN CLIENT c ON s.Client_Code = c.Client_Code");
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Get a single sale by ID
 *
 * @param PDO $conn
 * @param int $id
 * @return mixed
 */
function getSaleById($conn, $id)
{
    $stmt = $conn->prepare("SELECT s.*, c.First_Name, c.Last_Name, sd.* FROM SALE s LEFT JOIN CLIENT c ON s.Client_Code = c.Client_Code LEFT JOIN SALE_DETAIL sd ON s.Sale_Number = sd.Sale_Number WHERE s.Sale_Number = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

/**
 * Add a new sale to the database
 *
 * @param PDO $conn
 * @param array $data
 * @return bool
 */
function addSale($conn, $data)
{
    $conn->beginTransaction();

    try {
        $stmt = $conn->prepare("INSERT INTO SALE (Sale_Date, Client_Code) VALUES (?, ?)");
        $stmt->execute([$data['sale_date'], $data['client_id']]);
        $sale_number = $conn->lastInsertId();

        $total_sale = 0;
        $sale_details_stmt = $conn->prepare("INSERT INTO SALE_DETAIL (Sale_Number, Product_Code, Quantity, Unit_Price) VALUES (?, ?, ?, ?)");
        foreach ($data['product_ids'] as $index => $product_id) {
            $quantity = $data['quantities'][$index];
            $unit_price = $data['unit_prices'][$index];
            $sale_details_stmt->execute([$sale_number, $product_id, $quantity, $unit_price]);
            $total_sale += $unit_price * $quantity;
        }

        // Update stock quantities
        $update_stock_stmt = $conn->prepare("UPDATE STOCK SET Quantity = Quantity - ? WHERE Product_Code = ?");
        foreach ($data['product_ids'] as $index => $product_id) {
            $quantity = $data['quantities'][$index];
            $update_stock_stmt->execute([$quantity, $product_id]);
        }

        $conn->commit();
        return true;
    } catch (PDOException $e) {
        $conn->rollBack();
        return false;
    }
}

/**
 * Delete a sale from the database
 *
 * @param PDO $conn
 * @param int $id
 * @return bool
 */
function deleteSale($conn, $id)
{
    $conn->beginTransaction();

    try {
        $stmt = $conn->prepare("DELETE FROM SALE_DETAIL WHERE Sale_Number = ?");
        $stmt->execute([$id]);

        $stmt = $conn->prepare("DELETE FROM SALE WHERE Sale_Number = ?");
        $stmt->execute([$id]);

        $conn->commit();
        return true;
    } catch (PDOException $e) {
        $conn->rollBack();
        return false;
    }
}


/**
 * Get current stock data
 *
 * @param PDO $conn
 * @return array
 */
function getStock($conn)
{
    $stmt = $conn->prepare("SELECT
                                p.Product_Name,
                                s.Quantity,
                                COALESCE(id.Purchase_Unit_Price, 0) AS Purchase_Unit_Price,
                                COALESCE(id.Sale_Unit_Price, 0) AS Sale_Unit_Price
                            FROM STOCK s
                            JOIN PRODUCT p ON s.Product_Code = p.Product_Code
                            LEFT JOIN INVOICE_DETAIL id ON s.Product_Code = id.Product_Code
                            GROUP BY p.Product_Name, s.Quantity");
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Adjust stock quantity
 *
 * @param PDO $conn
 * @param array $data
 * @return bool
 */
function adjustStock($conn, $data)
{
    $productCode = $data['product_id'];
    $quantity = $data['quantity'];
    $adjustmentType = $data['adjustment_type'];
    $reason = $data['reason'];

    $conn->beginTransaction();

    try {
        // Update stock quantity
        $stmt = $conn->prepare("UPDATE STOCK SET Quantity = Quantity + ? WHERE Product_Code = ?");
        $adjustment = ($adjustmentType === 'in') ? $quantity : -$quantity;
        $stmt->execute([$adjustment, $productCode]);

        // Insert stock movement record
        $movementDate = date('Y-m-d');
        $movementType = ($adjustmentType === 'in') ? 'IN' : 'OUT';
        $stmt = $conn->prepare("INSERT INTO STOCK_MOVEMENT (Movement_Date, Movement_Type, Reason, Product_Code, Quantity) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$movementDate, $movementType, $reason, $productCode, $quantity]);

        $conn->commit();
        return true;
    } catch (PDOException $e) {
        $conn->rollBack();
        return false;
    }
}


/**
 * Get product categories and their counts
 *
 * @param PDO $conn
 * @return array
 */
function getProductCategories($conn)
{
    $stmt = $conn->prepare("SELECT pt.Type_Name, COUNT(p.Product_Code) AS Count
                            FROM PRODUCT p
                            JOIN PRODUCT_TYPE pt ON p.Product_Type = pt.Type_Code
                            GROUP BY pt.Type_Name");
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Get monthly sales data
 *
 * @param PDO $conn
 * @return array
 */
function getMonthlySales($conn)
{
    $stmt = $conn->prepare("SELECT MONTH(Sale_Date) AS Month, SUM(sd.Quantity * sd.Unit_Price) AS Total
                            FROM SALE s
                            JOIN SALE_DETAIL sd ON s.Sale_Number = sd.Sale_Number
                            GROUP BY MONTH(Sale_Date)
                            ORDER BY MONTH(Sale_Date)");
    $stmt->execute();
    return $stmt->fetchAll();
}
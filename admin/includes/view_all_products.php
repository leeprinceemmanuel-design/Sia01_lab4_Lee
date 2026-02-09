<?php
include "./includes/ecommerce/Product.php";

// Delete Product.
if (isset($_GET["delete"])) {
    if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] == "Admin") {
            $product_id = mysqli_real_escape_string($connection, $_GET['delete']);
            $product = new Product($connection);
            $delete_query = $product->delete($product_id);
            header("Location: products.php");
            if (!$delete_query) {
                die("Query Failed: " . mysqli_error($connection));
            }
        }
    }
}

// Change product to Active.
if (isset($_GET["change_to_active"])) {
    $product_id = $_GET['change_to_active'];
    $product = new Product($connection);
    $productData = ['status' => 'active'];
    $update_query = $product->update($product_id, $productData);
    header("Location: products.php");
    if (!$update_query) {
        die("Query Failed: " . mysqli_error($connection));
    }
}

// Change product to Inactive.
if (isset($_GET["change_to_inactive"])) {
    $product_id = $_GET['change_to_inactive'];
    $product = new Product($connection);
    $productData = ['status' => 'inactive'];
    $update_query = $product->update($product_id, $productData);
    header("Location: products.php");
    if (!$update_query) {
        die("Query Failed: " . mysqli_error($connection));
    }
}

?>

<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Stock Quantity</th>
            <th>Status</th>
            <th>Created Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $product = new Product($connection);
        $fetch_products_data = $product->all();
        while ($row = mysqli_fetch_assoc($fetch_products_data)) {

            $product_id = $row['product_id'];
            $name = $row['name'];
            $description = $row['description'];
            $price = $row['price'];
            $stock_quantity = $row['stock_quantity'];
            $status = $row['status'];
            $created_at = $row['created_at'];

            echo "<tr>
                    <td>$product_id</td>
                    <td>$name</td>
                    <td>$description</td>
                    <td>$price</td>
                    <td>$stock_quantity</td>
                    <td>$status</td>
                    <td>$created_at</td>
                    <td>
                        <a href='products.php?change_to_active=$product_id'>Active</a> | 
                        <a href='products.php?change_to_inactive=$product_id'>Inactive</a> | 
                        <a href='products.php?source=edit_product&product_id=$product_id'>Edit</a> | 
                        <a onClick=\"javascript: return confirm('Are you sure you want to delete'); \" href='products.php?delete=$product_id'>Delete</a>
                    </td>
                </tr>";
        }
        ?>
    </tbody>
</table>

<?php
include "./includes/ecommerce/Product.php";

if (isset($_POST['add_product'])) {
    $product_name = $_POST['name'];
    $product_description = $_POST['description'];
    $product_price = $_POST['price'];
    $product_stock_quantity = $_POST['stock_quantity'];
    $product_status = $_POST['status'];

    // Create new product using Product class
    $product = new Product($connection);
    $create_product = $product->create($product_name, $product_description, $product_price, $product_stock_quantity, $product_status);

    if ($create_product) {
        echo "Product Created " . "<a href='products.php'>View Products</a>";
    } else {
        die("Query Failed: " . mysqli_error($connection));
    }
}

?>

<form action="" method="post">

    <div class="form-group">
        <label for="name">Product Name</label>
        <input type="text" class="form-control" name="name">
    </div>

    <div class="form-group">
        <label for="description">Description</label>
        <textarea class="form-control" name="description" rows="4"></textarea>
    </div>

    <div class="form-group">
        <label for="price">Price</label>
        <input type="number" class="form-control" name="price" step="0.01" min="0">
    </div>

    <div class="form-group">
        <label for="stock_quantity">Stock Quantity</label>
        <input type="number" class="form-control" name="stock_quantity" min="0">
    </div>

    <div class="form-group">
        <label for="status">Status</label>
        <select class="form-control" name="status" id="product_status">
            <option value='active'>Active</option>
            <option value='inactive'>Inactive</option>
        </select>
    </div>

    <div class="form-group">
        <input type="submit" class="btn btn-primary" name="add_product" value="Add Product">
    </div>
</form>

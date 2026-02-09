<?php
include "./includes/ecommerce/Product.php";

if (isset($_POST['update_product'], $_GET['product_id'])) {
    $the_product_id = $_GET['product_id'];

    $product_name = $_POST['name'];
    $product_description = $_POST['description'];
    $product_price = $_POST['price'];
    $product_stock_quantity = $_POST['stock_quantity'];
    $product_status = $_POST['status'];

    // Update a Product using Product class
    $product = new Product($connection);
    $productData = [
        'name' => $product_name,
        'description' => $product_description,
        'price' => $product_price,
        'stock_quantity' => $product_stock_quantity,
        'status' => $product_status
    ];
    
    $update_product = $product->update($the_product_id, $productData);
    if (!$update_product) {
        die("Query Failed: " . mysqli_error($connection));
    }
}
?>

<?php
if (isset($_GET['product_id'])) {
    $the_product_id = $_GET['product_id'];
    $product = new Product($connection);
    $fetch_data = $product->find($the_product_id);
    
    while ($row = mysqli_fetch_assoc($fetch_data)) {
        $product_id = $row['product_id'];
        $name = $row['name'];
        $description = $row['description'];
        $price = $row['price'];
        $stock_quantity = $row['stock_quantity'];
        $status = $row['status'];

        ?>

        <form action="" method="post">

            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" class="form-control" name="name" value='<?php echo $name; ?>'>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" name="description" rows="4"><?php echo $description; ?></textarea>
            </div>

            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" class="form-control" name="price" step="0.01" min="0" value='<?php echo $price; ?>'>
            </div>

            <div class="form-group">
                <label for="stock_quantity">Stock Quantity</label>
                <input type="number" class="form-control" name="stock_quantity" min="0" value='<?php echo $stock_quantity; ?>'>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" name="status" id="product_status">
                    <option value='<?php echo $status ?>'><?php echo $status ?></option>
                    <?php
                        if ($status == 'active') {
                            echo "<option value='inactive'>Inactive</option>";
                        } else {
                            echo "<option value='active'>Active</option>";
                        }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" name="update_product" value="Update Product">
            </div>
        </form>
<?php }
}
?>

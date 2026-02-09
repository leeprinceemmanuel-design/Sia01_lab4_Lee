<?php
include "includes/db.php";
require_once __DIR__ . '/admin/includes/ecommerce/Product.php';

/* Page Header and navigation */
include "includes/header.php";
include "includes/navigation.php";

// Initialize shopping cart session if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Get product ID from URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Add product to cart
if (isset($_POST['add_to_cart'])) {
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    // Check if product already in cart
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Get product details
        $product = new Product($connection);
        $result = $product->find($product_id);
        if ($result && $row = mysqli_fetch_assoc($result)) {
            $_SESSION['cart'][$product_id] = [
                'product_id' => $row['product_id'],
                'name' => $row['name'],
                'price' => $row['price'],
                'quantity' => $quantity
            ];
        }
    }
    $_SESSION['cart_message'] = "Product added to cart!";
    header("Location: product.php?id=" . $product_id);
}

// Fetch product details
$product = new Product($connection);
$product_result = $product->find($product_id);

if (!$product_result || !($product_row = mysqli_fetch_assoc($product_result))) {
    ?>
    <div class="container" style="margin-top: 50px;">
        <div class="alert alert-danger">
            <h4>Product Not Found</h4>
            <p>The product you're looking for doesn't exist.</p>
            <a href="shop.php" class="btn btn-primary">Back to Shop</a>
        </div>
    </div>
    <?php
    include "includes/footer.php";
    die();
}

$product_id = $product_row['product_id'];
$name = $product_row['name'];
$description = $product_row['description'];
$price = $product_row['price'];
$stock_quantity = $product_row['stock_quantity'];
$status = $product_row['status'];
$created_at = $product_row['created_at'];
?>

<!-- Page Content -->
<div class="container" style="margin-top: 50px;">

    <div class="row">
        <!-- Product Details Column -->
        <div class="col-md-8">
            <div class="well">
                <?php if (isset($_SESSION['cart_message'])) { ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <span class="glyphicon glyphicon-ok"></span> <?php echo $_SESSION['cart_message']; unset($_SESSION['cart_message']); ?>
                    </div>
                <?php } ?>

                <div class="row">
                    <!-- Product Image -->
                    <div class="col-md-5">
                        <div style="background: #f5f5f5; height: 400px; display: flex; align-items: center; justify-content: center; border-radius: 5px; margin-bottom: 15px;">
                            <span style="color: #ccc; font-size: 150px;" class="glyphicon glyphicon-picture"></span>
                        </div>
                        <div class="alert alert-info">
                            <p><strong>Product ID:</strong> #<?php echo $product_id; ?></p>
                            <p><strong>Added:</strong> <?php echo date('M d, Y', strtotime($created_at)); ?></p>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="col-md-7">
                        <h1 style="margin-top: 0;">
                            <?php echo htmlspecialchars($name); ?>
                        </h1>

                        <hr>

                        <!-- Price and Stock -->
                        <div style="background: #f9f9f9; padding: 20px; border-radius: 5px; margin-bottom: 20px;">
                            <h2 style="margin-top: 0; color: #e74c3c;">
                                $<?php echo number_format($price, 2); ?>
                            </h2>
                            
                            <?php if ($stock_quantity > 0) { ?>
                                <p>
                                    <span class="label label-success label-lg">
                                        <span class="glyphicon glyphicon-ok"></span> In Stock (<?php echo $stock_quantity; ?> available)
                                    </span>
                                </p>
                            <?php } else { ?>
                                <p>
                                    <span class="label label-danger label-lg">
                                        <span class="glyphicon glyphicon-ban-circle"></span> Out of Stock
                                    </span>
                                </p>
                            <?php } ?>
                        </div>

                        <!-- Description -->
                        <div style="margin-bottom: 25px;">
                            <h4>Product Description</h4>
                            <p style="line-height: 1.6; color: #555;">
                                <?php echo nl2br(htmlspecialchars($description)); ?>
                            </p>
                        </div>

                        <!-- Add to Cart Form -->
                        <?php if ($stock_quantity > 0) { ?>
                            <form method="post" action="" style="margin-bottom: 20px;">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="quantity" style="font-weight: bold;">Quantity:</label>
                                        <input type="number" id="quantity" name="quantity" class="form-control" value="1" min="1" max="<?php echo $stock_quantity; ?>">
                                    </div>
                                </div>
                                <br>
                                <button type="submit" name="add_to_cart" class="btn btn-success btn-lg">
                                    <span class="glyphicon glyphicon-shopping-cart"></span> Add to Cart
                                </button>
                            </form>
                        <?php } else { ?>
                            <button type="button" class="btn btn-danger btn-lg" disabled>
                                <span class="glyphicon glyphicon-ban-circle"></span> Out of Stock
                            </button>
                        <?php } ?>

                        <!-- Action Buttons -->
                        <div style="margin-top: 20px;">
                            <a href="shop.php" class="btn btn-info">
                                <span class="glyphicon glyphicon-arrow-left"></span> Continue Shopping
                            </a>
                            <a href="cart.php" class="btn btn-primary">
                                <span class="glyphicon glyphicon-shopping-cart"></span> View Cart (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?> items)
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <!-- Related Products Section -->
            <h3>Related Products</h3>
            <div class="row">
                <?php
                $related_query = "SELECT product_id, name, price FROM products WHERE status='active' AND product_id != $product_id LIMIT 3";
                $related_result = mysqli_query($connection, $related_query);
                
                if ($related_result && mysqli_num_rows($related_result) > 0) {
                    while ($related = mysqli_fetch_assoc($related_result)) {
                        ?>
                        <div class="col-md-4">
                            <div class="panel panel-default">
                                <div class="panel-body" style="background: #f9f9f9; height: 150px; display: flex; align-items: center; justify-content: center;">
                                    <span style="color: #ccc; font-size: 80px;" class="glyphicon glyphicon-picture"></span>
                                </div>
                                <div class="panel-footer">
                                    <p style="margin: 0;">
                                        <a href="product.php?id=<?php echo $related['product_id']; ?>">
                                            <?php echo htmlspecialchars(substr($related['name'], 0, 20)); ?>...
                                        </a>
                                    </p>
                                    <p style="margin: 5px 0 0 0; color: #e74c3c; font-weight: bold;">
                                        $<?php echo number_format($related['price'], 2); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p>No related products available.</p>";
                }
                ?>
            </div>
        </div>

        <?php
        include "includes/sidebar.php"
        ?>
    </div>
    <!-- /.row -->

    <hr>
    <?php
    /* Page Footer */
    include "includes/footer.php"
    ?>

</div> <!-- /.container -->

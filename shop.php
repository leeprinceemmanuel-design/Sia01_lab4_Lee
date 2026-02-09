<?php
include "includes/db.php";

/* Page Header and navigation */
include "includes/header.php";
include "includes/navigation.php";

require_once __DIR__ . '/admin/includes/ecommerce/Product.php';

// Initialize shopping cart session if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add product to cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
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
    header("Location: shop.php");
}
?>

<!-- Page Content -->
<div class="container" style="margin-top: 50px;">

    <div class="row">

        <!-- Products Column -->
        <div class="col-md-8">

            <h1 class="page-header">
                <span class="glyphicon glyphicon-shopping-cart"></span> All Products
            </h1>

            <!-- Shopping Cart Widget -->
            <div class="panel panel-success" style="margin-bottom: 30px;">
                <div class="panel-heading">
                    <span class="glyphicon glyphicon-shopping-cart"></span> Shopping Cart Summary
                </div>
                <div class="panel-body">
                    <p>
                        <strong>Items in Cart: <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?></strong>
                    </p>
                    <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) { 
                        $total = 0;
                        foreach ($_SESSION['cart'] as $item) {
                            $total += $item['price'] * $item['quantity'];
                        }
                    ?>
                        <p><strong>Total: $<?php echo number_format($total, 2); ?></strong></p>
                        <a href="cart.php" class="btn btn-primary btn-block">
                            <span class="glyphicon glyphicon-eye-open"></span> View Cart
                        </a>
                        <a href="checkout.php" class="btn btn-success btn-block">
                            <span class="glyphicon glyphicon-ok"></span> Checkout
                        </a>
                    <?php } else { ?>
                        <p class="text-muted">Your cart is empty.</p>
                        <a href="shop.php" class="btn btn-primary btn-block">Start Shopping</a>
                    <?php } ?>
                </div>
            </div>
            <hr>

            <!-- Products Grid -->
            <div class="row">
                <?php
                $product = new Product($connection);
                $fetch_products_data = $product->all();

                if ($fetch_products_data && mysqli_num_rows($fetch_products_data) > 0) {
                    while ($row = mysqli_fetch_assoc($fetch_products_data)) {
                        $product_id = $row['product_id'];
                        $name = $row['name'];
                        $description = $row['description'];
                        $price = $row['price'];
                        $stock_quantity = $row['stock_quantity'];
                        $status = $row['status'];
                        $created_at = $row['created_at'];

                        // Only show active products
                        if ($status != 'active') {
                            continue;
                        }

                        $description_excerpt = substr($description, 0, 100) . "...";
                        $in_stock = $stock_quantity > 0;

                        ?>
                        <!-- Product Card -->
                        <div class="col-md-6" style="margin-bottom: 30px;">
                            <div class="panel panel-default" style="height: 100%; display: flex; flex-direction: column; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                <div class="panel-body" style="flex: 1; padding: 15px; background: #f9f9f9; border-radius: 5px 5px 0 0;">
                                    <div style="background: #f0f0f0; height: 150px; display: flex; align-items: center; justify-content: center; border-radius: 5px; margin-bottom: 10px;">
                                        <span style="color: #ccc; font-size: 60px;" class="glyphicon glyphicon-picture"></span>
                                    </div>
                                    <h4 style="margin-top: 0;">
                                        <a href="product.php?id=<?php echo $product_id ?>" style="color: #333; text-decoration: none;">
                                            <?php echo htmlspecialchars($name); ?>
                                        </a>
                                    </h4>
                                    <p class="text-muted" style="font-size: 12px; height: 50px; overflow: hidden;">
                                        <?php echo htmlspecialchars($description_excerpt); ?>
                                    </p>
                                </div>
                                <div class="panel-footer" style="background: white; padding: 15px; border-top: 1px solid #ddd;">
                                    <p style="margin: 0 0 10px 0; font-size: 18px;">
                                        <strong style="color: #e74c3c;">$<?php echo number_format($price, 2); ?></strong>
                                    </p>
                                    <?php if ($in_stock) { ?>
                                        <p style="margin: 0 0 10px 0;"><span class="label label-success">In Stock</span></p>
                                    <?php } else { ?>
                                        <p style="margin: 0 0 10px 0;"><span class="label label-danger">Out of Stock</span></p>
                                    <?php } ?>
                                    
                                    <form method="post" action="">
                                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                        <div style="display: flex; gap: 5px; margin-bottom: 10px;">
                                            <input type="number" class="form-control" name="quantity" value="1" min="1" max="<?php echo $stock_quantity; ?>" style="flex: 1;">
                                            <?php if ($in_stock) { ?>
                                                <button type="submit" name="add_to_cart" class="btn btn-success" title="Add to Cart">
                                                    <span class="glyphicon glyphicon-shopping-cart"></span>
                                                </button>
                                            <?php } else { ?>
                                                <button type="button" class="btn btn-danger" disabled title="Out of Stock">
                                                    <span class="glyphicon glyphicon-ban-circle"></span>
                                                </button>
                                            <?php } ?>
                                        </div>
                                    </form>
                                    
                                    <a href="product.php?id=<?php echo $product_id ?>" class="btn btn-info btn-sm btn-block">
                                        View Details <span class="glyphicon glyphicon-chevron-right"></span>
                                    </a>
                                </div>
                            </div>
                        </div>

                    <?php }
                } else {
                    echo "<div class=\"col-md-12\"><p class=\"alert alert-info\">No products available at the moment.</p></div>";
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

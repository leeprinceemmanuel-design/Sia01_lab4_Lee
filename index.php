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
    header("Location: index.php");
}
?>

<!-- Hero Section -->
<div class="jumbotron" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; margin-top: 50px; margin-bottom: 30px;">
    <div class="container">
        <h1><span class="glyphicon glyphicon-shopping-cart"></span> Welcome to Our Store</h1>
        <p>Discover amazing products at great prices</p>
        <a href="shop.php" class="btn btn-light btn-lg"><span class="glyphicon glyphicon-search"></span> Shop Now</a>
    </div>
</div>

<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Featured Products Column -->
        <div class="col-md-8">

            <h1 class="page-header">
                <span class="glyphicon glyphicon-star"></span> Featured Products
            </h1>

            <!-- Shopping Cart Widget -->
            <div class="panel panel-info" style="margin-bottom: 30px;">
                <div class="panel-heading">
                    <span class="glyphicon glyphicon-shopping-cart"></span> My Cart
                </div>
                <div class="panel-body">
                    <p>
                        <strong>Items: <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?></strong>
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
                        <p class="text-muted">Your cart is empty. Start shopping!</p>
                    <?php } ?>
                </div>
            </div>
            <hr>

            <?php
            $product = new Product($connection);
            $fetch_products_data = $product->all();

            if ($fetch_products_data && mysqli_num_rows($fetch_products_data) > 0) {
                $count = 0;
                while ($row = mysqli_fetch_assoc($fetch_products_data)) {
                    if ($count >= 3) break; // Show only 3 featured products
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
                    $count++;

                    $description_excerpt = substr($description, 0, 150) . "...";

                    ?>
                    <!-- Product Item -->
                    <div class="well">
                        <div class="row">
                            <div class="col-md-4">
                                <div style="background: #f0f0f0; height: 200px; display: flex; align-items: center; justify-content: center; border-radius: 5px;">
                                    <span style="color: #ccc; font-size: 80px;" class="glyphicon glyphicon-picture"></span>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <h3>
                                    <a href="product.php?id=<?php echo $product_id ?>"><?php echo htmlspecialchars($name); ?></a>
                                </h3>
                                <p class="text-muted"><?php echo htmlspecialchars($description_excerpt); ?></p>
                                <p style="font-size: 18px;">
                                    <strong style="color: #e74c3c;">$<?php echo number_format($price, 2); ?></strong>
                                </p>
                                <?php if ($stock_quantity > 0) { ?>
                                    <p><span class="label label-success">In Stock (<?php echo $stock_quantity; ?>)</span></p>
                                <?php } else { ?>
                                    <p><span class="label label-danger">Out of Stock</span></p>
                                <?php } ?>
                                <form method="post" action="" style="margin-top: 10px;">
                                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                    <div class="form-group" style="width: 120px; margin-bottom: 0;">
                                        <input type="number" class="form-control" name="quantity" value="1" min="1" max="<?php echo $stock_quantity; ?>">
                                    </div>
                                    <?php if ($stock_quantity > 0) { ?>
                                        <button type="submit" name="add_to_cart" class="btn btn-success">
                                            <span class="glyphicon glyphicon-shopping-cart"></span> Add to Cart
                                        </button>
                                    <?php } else { ?>
                                        <button type="button" class="btn btn-danger" disabled>Out of Stock</button>
                                    <?php } ?>
                                </form>
                                <br>
                                <a href="product.php?id=<?php echo $product_id ?>" class="btn btn-info btn-sm">
                                    View Details <span class="glyphicon glyphicon-chevron-right"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <a class="btn btn-primary" href="post.php?p_id=<?php echo $post_id ?>">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>
                    <hr>

            <?php
                }
            } else {
                echo "<p>No products available.</p>";
            }
            ?>

            <div class="text-center" style="margin-top: 30px;">
                <a href="shop.php" class="btn btn-primary btn-lg">View All Products</a>
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
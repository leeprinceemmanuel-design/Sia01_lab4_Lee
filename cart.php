<?php

include "includes/db.php";
/* Page Header and navigation */
include "includes/header.php";
include "includes/navigation.php";

// Initialize shopping cart session if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Remove product from cart
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    unset($_SESSION['cart'][$product_id]);
    header("Location: cart.php");
}

// Update product quantity in cart
if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $product_id => $quantity) {
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$product_id]);
        } else {
            $_SESSION['cart'][$product_id]['quantity'] = (int)$quantity;
        }
    }
    header("Location: cart.php");
}

// Clear cart
if (isset($_GET['clear'])) {
    $_SESSION['cart'] = [];
    if (isset($_SESSION['cart'])) {
        unset($_SESSION['cart']);
        error_log('Cleared the cart');
    }
    header("Location: cart.php");
}
?>

<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Cart Column -->
        <div class="col-md-8">

            <h1 class="page-header">
                <span class="glyphicon glyphicon-shopping-cart"></span> Shopping Cart
            </h1>

            <?php if (empty($_SESSION['cart'])) { ?>
                <div class="alert alert-info alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <h4><span class="glyphicon glyphicon-info-sign"></span> Cart Empty</h4>
                    <p>Your cart is empty. Let's add some products!</p>
                    <a href="shop.php" class="btn btn-primary btn-lg">
                        <span class="glyphicon glyphicon-shopping-cart"></span> Continue Shopping
                    </a>
                </div>
            <?php } else { ?>
                <!-- Cart Items Table -->
                <div class="well" style="margin-bottom: 20px;">
                    <form method="post" action="">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead style="background-color: #f5f5f5;">
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cart_total = 0;
                                    $items_count = 0;
                                    foreach ($_SESSION['cart'] as $product_id => $item) {
                                        $subtotal = $item['price'] * $item['quantity'];
                                        $cart_total += $subtotal;
                                        $items_count += $item['quantity'];
                                        ?>
                                        <tr>
                                            <td>
                                                <a href="product.php?id=<?php echo $product_id; ?>" style="color: #333; font-weight: 500;">
                                                    <?php echo htmlspecialchars($item['name']); ?>
                                                </a>
                                            </td>
                                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                                            <td>
                                                <input type="number" name="quantity[<?php echo $product_id; ?>]" class="form-control" style="width: 80px;" value="<?php echo $item['quantity']; ?>" min="1">
                                            </td>
                                            <td style="font-weight: 600; color: #e74c3c;">$<?php echo number_format($subtotal, 2); ?></td>
                                            <td>
                                                <a href="cart.php?remove=<?php echo $product_id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Remove this item?');">
                                                    <span class="glyphicon glyphicon-trash"></span>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" name="update_cart" class="btn btn-primary">
                                    <span class="glyphicon glyphicon-refresh"></span> Update Cart
                                </button>
                                <a href="cart.php?clear=1" class="btn btn-warning" onclick="return confirm('Clear entire cart?');">
                                    <span class="glyphicon glyphicon-trash"></span> Clear Cart
                                </a>
                                <a href="shop.php" class="btn btn-info">
                                    <span class="glyphicon glyphicon-plus"></span> Continue Shopping
                                </a>
                            </div>
                            <div class="col-md-6 text-right">
                                <h4>Cart Summary:</h4>
                                <p>Items: <strong><?php echo $items_count; ?></strong></p>
                                <h3 style="color: #e74c3c; margin-top: 10px;">
                                    Total: <strong>$<?php echo number_format($cart_total, 2); ?></strong>
                                </h3>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Checkout Section -->
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h4 class="panel-title"><span class="glyphicon glyphicon-credit-card"></span> Ready to Checkout?</h4>
                    </div>
                    <div class="panel-body">
                        <p>Review your cart items above and proceed to checkout when ready.</p>
                        <form action="checkout.php" method="POST">
                            <button type="submit" class="btn btn-lg btn-success btn-block">
                                <span class="glyphicon glyphicon-ok"></span> Proceed to Checkout
                            </button>
                        </form>
                    </div>
                </div>

            <?php } ?>

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

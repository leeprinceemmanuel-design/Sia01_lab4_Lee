<?php

include "includes/db.php";
include "includes/EmailService.php";
/* Page Header and navigation */
include "includes/header.php";
include "includes/navigation.php";
require_once __DIR__ . '/admin/includes/ecommerce/Order.php';
require_once __DIR__ . '/admin/includes/ecommerce/OrderItem.php';

$error_message = '';
$success_message = '';


// Check if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    ?>
    <!-- Page Content -->
    <div class="container" style="margin-top: 50px;">
        <div class="row">
            <div class="col-md-8">
                <div class="alert alert-warning">
                    <h4><span class="glyphicon glyphicon-warning-sign"></span> Cart is Empty</h4>
                    <p>You need to add products to your cart before checking out.</p>
                    <a href="shop.php" class="btn btn-primary">Back to Shop</a>
                </div>
            </div>
            <div class="col-md-4">
                <?php include "includes/sidebar.php"; ?>
            </div>
        </div>
    </div>
    <?php
    include "includes/footer.php";
    die();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = 'checkout.php';
    ?>
    <!-- Page Content -->
    <div class="container" style="margin-top: 50px;">
        <div class="row">
            <div class="col-md-8">
                <div class="alert alert-info">
                    <h4><span class="glyphicon glyphicon-log-in"></span> Login Required</h4>
                    <p>You must be logged in to complete your purchase.</p>
                    <a href="includes/login.php" class="btn btn-primary btn-lg">
                        <span class="glyphicon glyphicon-log-in"></span> Login
                    </a>
                    <a href="registration.php" class="btn btn-success btn-lg">
                        <span class="glyphicon glyphicon-user"></span> Create Account
                    </a>
                </div>
            </div>
            <div class="col-md-4">
                <?php include "includes/sidebar.php"; ?>
            </div>
        </div>
    </div>
    <?php
    include "includes/footer.php";
    die();
}

// Process the order
try {
    $cart = $_SESSION['cart'];
    
    $total_amount = 0;
    foreach ($cart as $item) {
        $total_amount += ($item['price'] * $item['quantity']);
    }

    $order = new Order($connection);
    $order->create(
        $_SESSION['user_id'],
        $total_amount
    );

    // Build items HTML for email
    $items_html = '';
    $items_array = [];
    foreach ($cart as $item) {
        $orderItem = new OrderItem($connection);
        $orderItem->create(
            $order->getOrderId(),
            $item['product_id'],
            $item['quantity'],
            $item['price']
        );
        
        // Add to items HTML for email
        $subtotal = $item['price'] * $item['quantity'];
        $items_html .= "<tr>";
        $items_html .= "<td style='padding: 10px; border-bottom: 1px solid #ddd;'>" . htmlspecialchars($item['name']) . "</td>";
        $items_html .= "<td style='text-align: center; padding: 10px; border-bottom: 1px solid #ddd;'>" . intval($item['quantity']) . "</td>";
        $items_html .= "<td style='text-align: right; padding: 10px; border-bottom: 1px solid #ddd;'>$" . number_format($item['price'], 2) . "</td>";
        $items_html .= "<td style='text-align: right; padding: 10px; border-bottom: 1px solid #ddd;'>$" . number_format($subtotal, 2) . "</td>";
        $items_html .= "</tr>";

        $items_array[] = [
            'name' => $item['name'],
            'quantity' => intval($item['quantity']),
            'price' => floatval($item['price']),
            'subtotal' => $subtotal,
            'product_id' => $item['product_id']
        ];
    }
    
    // Prepare order data for email (basic)
    $order_data = [
        'items_html' => $items_html,
        'items' => $items_array,
        'total' => $total_amount
    ];

    // Get customer info for email (use firstname/lastname/email)
    $customer_id = intval($_SESSION['user_id']);
    $customer_query = "SELECT user_firstname, user_lastname, user_name, user_email FROM users WHERE user_id = " . $customer_id;
    $customer_result = mysqli_query($connection, $customer_query);
    $customer_info = $customer_result ? mysqli_fetch_assoc($customer_result) : null;

    // Fetch order details (date, status) from DB so emails show accurate info
    $order_id = $order->getOrderId();
    $order_row = null;
    if ($order_id) {
        $order_row_q = "SELECT order_date, status, total_amount FROM orders WHERE order_id = " . intval($order_id);
        $order_row_r = mysqli_query($connection, $order_row_q);
        $order_row = $order_row_r ? mysqli_fetch_assoc($order_row_r) : null;
        if ($order_row) {
            $order_data['order_date'] = $order_row['order_date'];
            $order_data['status'] = $order_row['status'];
            $order_data['total'] = $order_row['total_amount'];
        }
    }
    
    // Send confirmation emails via Mailtrap (robust: catch failures per-send)
    try {
        $emailService = new EmailService();
    } catch (Exception $e) {
        error_log("Email service init failed but order was saved: " . $e->getMessage());
        $emailService = null;
    }

    if ($emailService) {
        // Send customer confirmation email in its own try/catch to avoid breaking order flow
        try {
            if ($customer_info && !empty($customer_info['user_email'])) {
                $customer_fullname = trim(($customer_info['user_firstname'] ?? '') . ' ' . ($customer_info['user_lastname'] ?? ''));
                if (empty($customer_fullname)) {
                    $customer_fullname = $customer_info['user_name'] ?? 'Customer';
                }

                // Attach customer info into order_data for template
                $order_data['customer_name'] = $customer_fullname;
                $order_data['customer_email'] = $customer_info['user_email'];

                $emailService->sendOrderConfirmation(
                    $customer_info['user_email'],
                    $customer_fullname,
                    $order->getOrderId(),
                    $order_data
                );
            }
        } catch (Exception $e) {
            // This prevents the white screen if the email fails
            error_log("Email failed but order was saved: " . $e->getMessage());
        }

        // Send admin notification; separate try/catch so admin email failures also don't break flow
        try {
            $emailService->sendAdminOrderNotification(
                $order->getOrderId(),
                $order_data,
                $customer_info['user_email'] ?? 'unknown@customer.com'
            );
        } catch (Exception $e) {
            error_log("Admin email failed: " . $e->getMessage());
        }
    }
    
    // Clear the cart
    unset($_SESSION['cart']);
    $_SESSION['cart'] = [];
    
    $success_message = "Order placed successfully! Order ID: " . $order->getOrderId();
    $order_id = $order->getOrderId();
    
} catch (Exception $e) {
    $error_message = "Error processing order: " . $e->getMessage();
}

?>

<!-- Page Content -->
<div class="container" style="margin-top: 50px;">

    <div class="row">
        <!-- Checkout Column -->
        <div class="col-md-8">

            <h1 class="page-header">
                <span class="glyphicon glyphicon-credit-card"></span> Checkout
            </h1>

            <?php if (!empty($error_message)) { ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong><span class="glyphicon glyphicon-exclamation-sign"></span> Error:</strong> <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php } ?>

            <?php if (!empty($success_message)) { ?>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong><span class="glyphicon glyphicon-ok"></span> Success!</strong> <?php echo htmlspecialchars($success_message); ?>
                </div>

                <!-- Order Confirmation -->
                <div class="well">
                    <h3>Order Confirmation</h3>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <p>
                                <strong>Order ID:</strong><br>
                                <span style="font-size: 18px; color: #e74c3c;">#<?php echo htmlspecialchars($order_id); ?></span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p>
                                <strong>Order Date:</strong><br>
                                <?php echo date('M d, Y H:i A'); ?>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <p>
                                <strong>Order Status:</strong><br>
                                <span class="label label-info">Pending</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p>
                                <strong>Total Amount:</strong><br>
                                <span style="font-size: 18px; font-weight: bold; color: #27ae60;">$<?php echo number_format($total_amount, 2); ?></span>
                            </p>
                        </div>
                    </div>

                    <hr>

                    <h4>Next Steps:</h4>
                    <ol>
                        <li>You will receive a confirmation email shortly</li>
                        <li>Track your order status in your account</li>
                        <li>Your order will be shipped within 2-3 business days</li>
                    </ol>

                    <hr>

                    <div>
                        <a href="admin/orders.php" class="btn btn-primary">
                            <span class="glyphicon glyphicon-eye-open"></span> View My Orders
                        </a>
                        <a href="shop.php" class="btn btn-info">
                            <span class="glyphicon glyphicon-shopping-cart"></span> Continue Shopping
                        </a>
                        <a href="index.php" class="btn btn-default">
                            <span class="glyphicon glyphicon-home"></span> Back to Home
                        </a>
                    </div>
                </div>

            <?php } else { ?>
                <!-- Order Review Before Processing -->
                <h3>Review Your Order</h3>

                <div class="well">
                    <h4>Order Items:</h4>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead style="background-color: #f5f5f5;">
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total_amount = 0;
                                foreach ($_SESSION['cart'] as $product_id => $item) {
                                    $subtotal = $item['price'] * $item['quantity'];
                                    $total_amount += $subtotal;
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td><strong>$<?php echo number_format($subtotal, 2); ?></strong></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <hr>

                    <h3 style="text-align: right; color: #e74c3c;">
                        Total: $<?php echo number_format($total_amount, 2); ?>
                    </h3>
                </div>

                <!-- Shipping Address -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title"><span class="glyphicon glyphicon-map-marker"></span> Shipping Address</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label>Full Name:</label>
                            <p><?php echo htmlspecialchars($_SESSION['username']); ?></p>
                        </div>
                        <p class="text-muted"><small>We will use your registered address for shipping. Contact support to update your address.</small></p>
                    </div>
                </div>

                <!-- Place Order -->
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h4 class="panel-title"><span class="glyphicon glyphicon-ok"></span> Place Your Order</h4>
                    </div>
                    <div class="panel-body">
                        <p>By clicking the button below, you confirm that you want to place this order.</p>
                        <form method="post" action="">
                            <button type="submit" name="place_order" class="btn btn-success btn-lg btn-block">
                                <span class="glyphicon glyphicon-ok"></span> Place Order
                            </button>
                        </form>
                        <br>
                        <a href="cart.php" class="btn btn-info btn-block">
                            <span class="glyphicon glyphicon-arrow-left"></span> Back to Cart
                        </a>
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

</div> <!-- /.container -->
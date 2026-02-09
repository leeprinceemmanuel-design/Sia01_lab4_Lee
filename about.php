<?php
include "includes/db.php";
/* Page Header and navigation */
include "includes/header.php";
include "includes/navigation.php";
?>

<!-- Page Content -->
<div class="container" style="margin-top: 50px;">

    <div class="row">

        <!-- Content Column -->
        <div class="col-md-8">

            <h1 class="page-header">
                <span class="glyphicon glyphicon-info-sign"></span> About Our Store
            </h1>

            <div class="well">
                <h2>Welcome to Our Online Store</h2>
                <p style="line-height: 1.8; font-size: 16px;">
                    We are a leading online retailer dedicated to providing high-quality products at competitive prices. 
                    With over a decade of experience in e-commerce, we have built a reputation for excellence, reliability, and customer satisfaction.
                </p>

                <hr>

                <h3>Why Choose Us?</h3>

                <div class="row" style="margin-bottom: 30px;">
                    <div class="col-md-6">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <span class="glyphicon glyphicon-thumbs-up"></span> Quality Products
                                </h4>
                            </div>
                            <div class="panel-body">
                                We carefully select all our products to ensure they meet our high standards of quality and durability.
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <span class="glyphicon glyphicon-credit-card"></span> Competitive Prices
                                </h4>
                            </div>
                            <div class="panel-body">
                                We offer the best prices in the market without compromising on quality. Regular discounts and promotions available.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-bottom: 30px;">
                    <div class="col-md-6">
                        <div class="panel panel-warning">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <span class="glyphicon glyphicon-ok"></span> Fast Shipping
                                </h4>
                            </div>
                            <div class="panel-body">
                                We offer fast and reliable shipping to most locations. Track your order in real-time.
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="panel panel-danger">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <span class="glyphicon glyphicon-earphone"></span> Great Support
                                </h4>
                            </div>
                            <div class="panel-body">
                                Our dedicated customer support team is available 24/7 to help you with any questions or concerns.
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <h3>Our Mission</h3>
                <p>
                    Our mission is to make online shopping convenient, affordable, and enjoyable for everyone. 
                    We strive to provide excellent customer service and a wide selection of quality products.
                </p>

                <h3>Our Values</h3>
                <ul style="font-size: 16px; line-height: 2;">
                    <li><strong>Integrity:</strong> We believe in honest and transparent business practices</li>
                    <li><strong>Quality:</strong> We are committed to offering only the best products</li>
                    <li><strong>Innovation:</strong> We continuously improve our services and user experience</li>
                    <li><strong>Customer Focus:</strong> Your satisfaction is our top priority</li>
                </ul>

                <hr>

                <h3>Get in Touch</h3>
                <p>
                    Have any questions or need more information? Feel free to <a href="contact.php">contact us</a> anytime!
                </p>

                <a href="shop.php" class="btn btn-primary btn-lg">
                    <span class="glyphicon glyphicon-shopping-cart"></span> Start Shopping
                </a>
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

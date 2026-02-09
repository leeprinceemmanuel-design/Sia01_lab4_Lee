<?php
session_start();
?>
<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php"><span class="glyphicon glyphicon-shopping-cart"></span> My Online Store</a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href='index.php'><span class="glyphicon glyphicon-home"></span> Home</a></li>
                <li><a href='shop.php'><span class="glyphicon glyphicon-shopping-cart"></span> Shop</a></li>
                <li><a href='about.php'><span class="glyphicon glyphicon-info-sign"></span> About</a></li>
                <li><a href='contact.php'><span class="glyphicon glyphicon-envelope"></span> Contact</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="cart.php"><span class="glyphicon glyphicon-shopping-cart"></span> Cart <span class="badge" style="background-color: #e74c3c;"><?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?></span></a></li>
                <?php if (isset($_SESSION['username'])) { ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <?php echo htmlspecialchars($_SESSION['username']); ?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="admin/profile.php">My Profile</a></li>
                            <li><a href="admin/orders.php">My Orders</a></li>
                            <li class="divider"></li>
                            <li><a href="includes/logout.php">Logout</a></li>
                        </ul>
                    </li>
                <?php } else { ?>
                    <li><a href="includes/login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
                    <li><a href="registration.php"><span class="glyphicon glyphicon-user"></span> Register</a></li>
                <?php } ?>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') { ?>
                    <li><a href="admin"><span class="glyphicon glyphicon-cog"></span> Admin Panel</a></li>
                <?php } ?>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>
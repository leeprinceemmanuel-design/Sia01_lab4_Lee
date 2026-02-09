<!-- Store Sidebar Widgets Column -->
<div class="col-md-4">

    <!-- Product Search Well -->
    <div class="well">
        <h4><span class="glyphicon glyphicon-search"></span> Search Products</h4>
        <form action="search.php" method="POST">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search products...">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="submit" name="submit">
                        <span class="glyphicon glyphicon-search"></span>
                    </button>
                </span>
            </div>
        </form>
    </div>

    <!-- Login Form -->
    <div class="well">
        <h4><span class="glyphicon glyphicon-log-in"></span> Account</h4>
        <?php if (isset($_SESSION['username'])) { ?>
            <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></p>
            <a href="admin/profile.php" class="btn btn-primary btn-block">My Account</a>
            <a href="admin/orders.php" class="btn btn-info btn-block">My Orders</a>
            <a href="includes/logout.php" class="btn btn-danger btn-block">Logout</a>
        <?php } else { ?>
            <form action="includes/login.php" method="POST">
                <div class="form-group">
                    <input type="text" name="username" class="form-control" placeholder="Username">
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Password">
                </div>
                <div class="form-group">
                    <button class="btn btn-primary btn-block" type="submit" name="login">
                        Login
                    </button>
                </div>
            </form>
            <p class="text-center"><a href="registration.php">Create an account</a></p>
        <?php } ?>
    </div>

    <!-- Product Categories Well -->
    <div class="well">
        <h4><span class="glyphicon glyphicon-th"></span> Shop</h4>
        <div class="row">
            <div class="col-lg-12">
                <ul class="list-unstyled">
                    <li><a href="shop.php"><strong>All Products</strong></a></li>
                    <li><a href="shop.php?sort=price_low">Price: Low to High</a></li>
                    <li><a href="shop.php?sort=price_high">Price: High to Low</a></li>
                    <li><a href="shop.php?sort=newest">Newest First</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Price Filter Well -->
    <div class="well">
        <h4><span class="glyphicon glyphicon-tag"></span> Price Range</h4>
        <form method="GET" action="shop.php">
            <div class="form-group">
                <label>Min Price: $<span id="minPrice">0</span></label>
                <input type="range" name="min_price" class="form-control" min="0" max="1000" value="0">
            </div>
            <div class="form-group">
                <label>Max Price: $<span id="maxPrice">1000</span></label>
                <input type="range" name="max_price" class="form-control" min="0" max="1000" value="1000">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Filter</button>
        </form>
    </div>

    <!-- Featured Products Well -->
    <div class="well">
        <h4><span class="glyphicon glyphicon-star"></span> Featured</h4>
        <ul class="list-unstyled">
            <?php
            $query = "SELECT product_id, name, price FROM products WHERE status='active' ORDER BY RAND() LIMIT 5";
            $fetch_data = mysqli_query($connection, $query);
            if ($fetch_data && mysqli_num_rows($fetch_data) > 0) {
                while ($Row = mysqli_fetch_assoc($fetch_data)) {
                    $prod_id = $Row['product_id'];
                    $prod_name = $Row['name'];
                    $prod_price = $Row['price'];
                    echo "<li><a href='product.php?id=$prod_id'>" . htmlspecialchars($prod_name) . " - <strong>$" . number_format($prod_price, 2) . "</strong></a></li>";
                }
            }
            ?>
        </ul>
    </div>

</div>
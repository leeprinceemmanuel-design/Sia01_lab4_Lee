<?php
/**
 * Quick Sample Products Insertion Script
 * This script adds 16 sample products to your database
 * Access this file at: http://localhost/cms-monolithic-legacy-php-main/add_sample_products.php
 */

include "includes/db.php";

// Check if user is admin (optional - comment out for public access)
// if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
//     die("Access denied. Admin access required.");
// }

$sample_products = [
    [
        'name' => 'Wireless Headphones',
        'description' => 'Premium noise-cancelling wireless headphones with 30-hour battery life. Bluetooth 5.0 connectivity, comfortable fit for long wear.',
        'price' => 89.99,
        'stock_quantity' => 50
    ],
    [
        'name' => 'USB-C Fast Charger',
        'description' => 'Ultra-fast 65W USB-C charger compatible with all modern devices. Compact design, multiple ports for simultaneous charging.',
        'price' => 34.99,
        'stock_quantity' => 100
    ],
    [
        'name' => 'Portable Bluetooth Speaker',
        'description' => 'Waterproof portable speaker with 360-degree sound. Perfect for outdoor activities with 12-hour battery life.',
        'price' => 49.99,
        'stock_quantity' => 45
    ],
    [
        'name' => 'Wireless Mouse',
        'description' => 'Ergonomic wireless mouse with precision scrolling. Silent click technology, 18-month battery life.',
        'price' => 19.99,
        'stock_quantity' => 120
    ],
    [
        'name' => 'Phone Screen Protector',
        'description' => 'Tempered glass screen protector with high clarity. Anti-fingerprint coating, easy installation kit included.',
        'price' => 9.99,
        'stock_quantity' => 200
    ],
    [
        'name' => 'USB Hub Adapter',
        'description' => '7-port USB 3.0 hub with individual switches. Fast data transfer up to 5Gbps, compact design.',
        'price' => 29.99,
        'stock_quantity' => 80
    ],
    [
        'name' => 'Laptop Stand',
        'description' => 'Adjustable aluminum laptop stand for ergonomic comfortable viewing. Supports all laptops up to 17 inches.',
        'price' => 39.99,
        'stock_quantity' => 60
    ],
    [
        'name' => 'Mechanical Keyboard',
        'description' => 'RGB backlit mechanical keyboard with Cherry MX switches. Programmable keys, aluminum frame.',
        'price' => 129.99,
        'stock_quantity' => 35
    ],
    [
        'name' => '4K Webcam',
        'description' => 'Professional 4K Ultra HD webcam with auto-focus. Built-in stereo microphone, perfect for streaming and conferencing.',
        'price' => 79.99,
        'stock_quantity' => 40
    ],
    [
        'name' => 'Laptop Cooling Pad',
        'description' => 'Dual fan laptop cooler with USB power supply. Reduces temperature by up to 15 degrees, ultra-quiet operation.',
        'price' => 24.99,
        'stock_quantity' => 90
    ],
    [
        'name' => 'Desk Lamp',
        'description' => 'LED desk lamp with adjustable brightness and color temperature. Touch control, USB charging port on base.',
        'price' => 44.99,
        'stock_quantity' => 55
    ],
    [
        'name' => 'Cable Organizer Kit',
        'description' => 'Complete cable management kit with clips, ties, and sleeves. Organize your desk and keep cables tidy.',
        'price' => 14.99,
        'stock_quantity' => 150
    ],
    [
        'name' => 'HD Monitor Light Bar',
        'description' => 'Asymmetrical lighting monitor lamp that reduces eye strain. Automatic brightness adjustment, USB powered.',
        'price' => 59.99,
        'stock_quantity' => 30
    ],
    [
        'name' => 'Wireless Charging Pad',
        'description' => 'Fast-charge wireless charging pad compatible with all Qi-enabled devices. Sleek design, non-slip surface.',
        'price' => 19.99,
        'stock_quantity' => 110
    ],
    [
        'name' => 'Phone Case - Premium',
        'description' => 'Durable premium phone case with shock absorption. Military-grade protection, slim design available in multiple colors.',
        'price' => 24.99,
        'stock_quantity' => 200
    ],
    [
        'name' => 'Desktop Monitor Stand',
        'description' => 'Adjustable monitor stand with storage drawer. Supports monitors up to 32 inches, cable management included.',
        'price' => 54.99,
        'stock_quantity' => 70
    ]
];

$success_count = 0;
$error_count = 0;
$errors = [];

echo "<h2>Adding Sample Products...</h2>";
echo "<hr>";

foreach ($sample_products as $product) {
    $name = mysqli_real_escape_string($connection, $product['name']);
    $description = mysqli_real_escape_string($connection, $product['description']);
    $price = $product['price'];
    $stock_quantity = $product['stock_quantity'];

    $query = "INSERT INTO products (name, description, price, stock_quantity, status, created_at) 
              VALUES ('$name', '$description', $price, $stock_quantity, 'active', NOW())";

    if (mysqli_query($connection, $query)) {
        echo "<p style='color: green;'><strong>✓ Added:</strong> " . htmlspecialchars($product['name']) . " (\$" . $product['price'] . ")</p>";
        $success_count++;
    } else {
        echo "<p style='color: red;'><strong>✗ Error:</strong> " . htmlspecialchars($product['name']) . " - " . mysqli_error($connection) . "</p>";
        $error_count++;
        $errors[] = $product['name'];
    }
}

echo "<hr>";
echo "<h3>Summary:</h3>";
echo "<p><strong>Successfully added:</strong> <span style='color: green; font-size: 18px;'>" . $success_count . "</span> products</p>";
if ($error_count > 0) {
    echo "<p><strong>Errors:</strong> <span style='color: red; font-size: 18px;'>" . $error_count . "</span> products</p>";
}
echo "<hr>";
echo "<p><a href='shop.php' class='btn btn-primary'>View Products in Store</a> 
       <a href='admin/products.php' class='btn btn-info'>View in Admin Panel</a></p>";

mysqli_close($connection);
?>

<style>
    body {
        font-family: Arial, sans-serif;
        max-width: 800px;
        margin: 40px auto;
        background: #f9f9f9;
        padding: 20px;
        border-radius: 5px;
    }
    h2, h3 {
        color: #333;
    }
    p {
        line-height: 1.8;
    }
    a {
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        display: inline-block;
        margin: 5px;
    }
    .btn-primary {
        background: #667eea;
        color: white;
    }
    .btn-info {
        background: #17a2b8;
        color: white;
    }
</style>

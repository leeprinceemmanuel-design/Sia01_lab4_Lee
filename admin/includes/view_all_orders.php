<?php
include "./includes/ecommerce/Order.php";
include "./includes/ecommerce/OrderItem.php";
include "./includes/ecommerce/Product.php";

?>

<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Total Amount</th>
            <th>Order Date</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $order = new Order($connection);
        $all_orders = $order->all();
        while ($row = mysqli_fetch_assoc($all_orders)) {

            $order_id = $row['order_id'];
            $user_id = $row['user_id'];
            $customer = $row['customer'];
            $total_amount = $row['total_amount'];
            $order_date = $row['order_date'];
            $status = $row['status'];

            echo "<tr>
                    <td>$order_id</td>
                    <td>$user_id : $customer</td>
                    <td>$total_amount</td>
                    <td>$order_date</td>
                    <td>$status</td>
                </tr>";
        }
        ?>
    </tbody>
</table>

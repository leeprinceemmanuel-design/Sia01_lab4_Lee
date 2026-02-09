<?php

class OrderItem {
    private $connection;
    private $order_item_id;
    private $order_id;
    private $product_id;
    private $quantity;
    private $price;

    public function __construct($dbConnection, $data = []) {
        $this->connection = $dbConnection;
        if (!empty($data)) {
            $this->setData($data);
        }
    }

    /**
     * Set object properties from array
     */
    private function setData($data) {
        $this->order_item_id = $data['order_item_id'] ?? null;
        $this->order_id = $data['order_id'] ?? null;
        $this->product_id = $data['product_id'] ?? null;
        $this->quantity = $data['quantity'] ?? 1;
        $this->price = $data['price'] ?? 0.00;
    }

    /**
     * Get all order items
     */
    public function all() {
        $query = "SELECT * FROM order_items";
        $result = mysqli_query($this->connection, $query);
        return $result ? $result : false;
    }

    /**
     * Find order item by ID
     */
    public function find($order_item_id) {
        if (!$this->isValidId($order_item_id)) {
            return false;
        }

        $stmt = $this->connection->prepare("SELECT * FROM order_items WHERE order_item_id = ?");
        $stmt->bind_param("i", $order_item_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    /**
     * Create new order item
     */
    public function create($order_id, $product_id, $quantity, $price) {
        if (!$this->validateData($order_id, $product_id, $quantity, $price)) {
            return false;
        }

        $stmt = $this->connection->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $order_id, $product_id, $quantity, $price);
        return $stmt->execute();
    }

    /**
     * Update order item
     */
    public function update($order_item_id, $itemData) {
        if (!$this->isValidId($order_item_id)) {
            return false;
        }

        $quantity = $itemData['quantity'] ?? null;
        $price = $itemData['price'] ?? null;

        if (!is_numeric($quantity) || $quantity <= 0 || !is_numeric($price) || $price < 0) {
            return false;
        }

        $stmt = $this->connection->prepare("UPDATE order_items SET quantity=?, price=? WHERE order_item_id=?");
        $stmt->bind_param("idi", $quantity, $price, $order_item_id);
        return $stmt->execute();
    }

    /**
     * Delete order item
     */
    public function delete($order_item_id) {
        if (!$this->isValidId($order_item_id)) {
            return false;
        }

        $stmt = $this->connection->prepare("DELETE FROM order_items WHERE order_item_id = ?");
        $stmt->bind_param("i", $order_item_id);
        return $stmt->execute();
    }

    /**
     * Get all items for an order
     */
    public function getByOrderId($order_id) {
        if (!$this->isValidId($order_id)) {
            return false;
        }

        $stmt = $this->connection->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    /**
     * Validate order item data
     */
    private function validateData($order_id, $product_id, $quantity, $price) {
        if (!is_numeric($order_id) || $order_id <= 0) {
            return false;
        }
        if (!is_numeric($product_id) || $product_id <= 0) {
            return false;
        }
        if (!is_numeric($quantity) || $quantity <= 0) {
            return false;
        }
        if (!is_numeric($price) || $price < 0) {
            return false;
        }
        return true;
    }

    /**
     * Validate ID
     */
    private function isValidId($id) {
        return is_numeric($id) && $id > 0;
    }

    // Getters
    public function getId() {
        return $this->order_item_id;
    }

    public function getOrderId() {
        return $this->order_id;
    }

    public function getProductId() {
        return $this->product_id;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function getPrice() {
        return $this->price;
    }

    // Setters
    public function setOrderId($order_id) {
        $this->order_id = $order_id;
        return $this;
    }

    public function setProductId($product_id) {
        $this->product_id = $product_id;
        return $this;
    }

    public function setQuantity($quantity) {
        $this->quantity = $quantity;
        return $this;
    }

    public function setPrice($price) {
        $this->price = $price;
        return $this;
    }
}
<?php

class Order {
    private $connection;
    private $order_id;
    private $user_id;
    private $total_amount;
    private $order_date;
    private $status;

    public function __construct($dbConnection, $data = []) {
        $this->connection = $dbConnection;
        if (!empty($data)) {
            $this->setData($data);
        }
    }

    public function getOrderId() {
        return $this->order_id;
    }

    /**
     * Set object properties from array
     */
    private function setData($data) {
        $this->order_id = $data['order_id'] ?? null;
        $this->user_id = $data['user_id'] ?? null;
        $this->total_amount = $data['total_amount'] ?? 0.00;
        $this->order_date = $data['order_date'] ?? date('Y-m-d H:i:s');
        $this->status = $data['status'] ?? 'pending';
    }

    /**
     * Get all orders
     */
    public function all() {
        $query = "SELECT o.*, CONCAT(u.user_firstname, ' ', u.user_lastname) AS customer FROM orders AS o LEFT JOIN users AS u ON (o.user_id=u.user_id)";
        $result = mysqli_query($this->connection, $query);
        return $result ? $result : false;
    }

    /**
     * Find order by ID
     */
    public function find($order_id) {
        if (!$this->isValidId($order_id)) {
            return false;
        }
        
        $stmt = $this->connection->prepare("SELECT * FROM orders WHERE order_id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    /**
     * Create new order
     */
    public function create($user_id, $total_amount, $status = 'pending') {
        if (!$this->validateData($user_id, $total_amount)) {
            return false;
        }

        $stmt = $this->connection->prepare("INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, ?)");
        $stmt->bind_param("ids", $user_id, $total_amount, $status);
        if ($stmt->execute()) {
            $this->order_id = mysqli_insert_id($this->connection);
        }
    }

    /**
     * Update order
     */
    public function update($order_id, $orderData) {
        if (!$this->isValidId($order_id)) {
            return false;
        }

        $user_id = $orderData['user_id'] ?? null;
        $total_amount = $orderData['total_amount'] ?? null;
        $status = $orderData['status'] ?? null;

        if (!$this->validateData($user_id, $total_amount)) {
            return false;
        }

        $stmt = $this->connection->prepare("UPDATE orders SET user_id=?, total_amount=?, status=? WHERE order_id=?");
        $stmt->bind_param("idsi", $user_id, $total_amount, $status, $order_id);
        return $stmt->execute();
    }

    /**
     * Delete order
     */
    public function delete($order_id) {
        if (!$this->isValidId($order_id)) {
            return false;
        }

        $stmt = $this->connection->prepare("DELETE FROM orders WHERE order_id = ?");
        $stmt->bind_param("i", $order_id);
        return $stmt->execute();
    }

    /**
     * Get orders by user ID
     */
    public function getByUserId($user_id) {
        if (!$this->isValidId($user_id)) {
            return false;
        }

        $stmt = $this->connection->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    /**
     * Validate order data
     */
    private function validateData($user_id, $total_amount) {
        if (!is_numeric($user_id) || $user_id <= 0) {
            return false;
        }
        if (!is_numeric($total_amount) || $total_amount < 0) {
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
        return $this->order_id;
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function getTotalAmount() {
        return $this->total_amount;
    }

    public function getOrderDate() {
        return $this->order_date;
    }

    public function getStatus() {
        return $this->status;
    }

    // Setters
    public function setUserId($user_id) {
        $this->user_id = $user_id;
        return $this;
    }

    public function setTotalAmount($total_amount) {
        $this->total_amount = $total_amount;
        return $this;
    }

    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }
}
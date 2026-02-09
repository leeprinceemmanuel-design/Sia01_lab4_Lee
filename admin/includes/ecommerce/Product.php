<?php

class Product {
    private $connection;
    private $product_id;
    private $name;
    private $description;
    private $price;
    private $stock_quantity;
    private $status;
    private $created_at;

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
        $this->product_id = $data['product_id'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->description = $data['description'] ?? null;
        $this->price = $data['price'] ?? 0.00;
        $this->stock_quantity = $data['stock_quantity'] ?? 0;
        $this->status = $data['status'] ?? 'active';
        $this->created_at = $data['created_at'] ?? date('Y-m-d H:i:s');
    }

    /**
     * Get all products
     */
    public function all() {
        $query = "SELECT * FROM products";
        $result = mysqli_query($this->connection, $query);
        return $result ? $result : false;
    }

    /**
     * Find product by ID
     */
    public function find($product_id) {
        if (!$this->isValidId($product_id)) {
            return false;
        }
        
        $stmt = $this->connection->prepare("SELECT * FROM products WHERE product_id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    /**
     * Create new product
     */
    public function create($name, $description, $price, $stock_quantity, $status = 'active') {
        if (!$this->validateData($name, $price, $stock_quantity)) {
            return false;
        }

        $stmt = $this->connection->prepare("INSERT INTO products (name, description, price, stock_quantity, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdis", $name, $description, $price, $stock_quantity, $status);
        return $stmt->execute();
    }

    /**
     * Update product
     */
    public function update($product_id, $productData) {
        if (!$this->isValidId($product_id)) {
            return false;
        }

        $name = $productData['name'] ?? null;
        $description = $productData['description'] ?? null;
        $price = $productData['price'] ?? null;
        $stock_quantity = $productData['stock_quantity'] ?? null;
        $status = $productData['status'] ?? null;

        if (!$this->validateData($name, $price, $stock_quantity)) {
            return false;
        }

        $stmt = $this->connection->prepare("UPDATE products SET name=?, description=?, price=?, stock_quantity=?, status=? WHERE product_id=?");
        $stmt->bind_param("ssdisi", $name, $description, $price, $stock_quantity, $status, $product_id);
        return $stmt->execute();
    }

    /**
     * Delete product
     */
    public function delete($product_id) {
        if (!$this->isValidId($product_id)) {
            return false;
        }

        $stmt = $this->connection->prepare("DELETE FROM products WHERE product_id = ?");
        $stmt->bind_param("i", $product_id);
        return $stmt->execute();
    }

    /**
     * Validate product data
     */
    private function validateData($name, $price, $stock_quantity) {
        if (empty($name) || strlen($name) > 255) {
            return false;
        }
        if (!is_numeric($price) || $price < 0) {
            return false;
        }
        if (!is_numeric($stock_quantity) || $stock_quantity < 0) {
            return false;
        }
        return true;
    }

    /**
     * Validate product ID
     */
    private function isValidId($id) {
        return is_numeric($id) && $id > 0;
    }

    // Getters
    public function getId() {
        return $this->product_id;
    }

    public function getName() {
        return $this->name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getStockQuantity() {
        return $this->stock_quantity;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }


    // Setters
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    public function setPrice($price) {
        $this->price = $price;
        return $this;
    }

    public function setStockQuantity($stock_quantity) {
        $this->stock_quantity = $stock_quantity;
        return $this;
    }

    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }

}
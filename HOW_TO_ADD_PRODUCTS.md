# How to Add Sample Products to Your E-commerce Store

You have **3 easy methods** to add sample products. Choose the one that works best for you!

## **METHOD 1: Fastest - Click One Link ⭐ RECOMMENDED**

The easiest way! Just visit this URL in your browser:

```
http://localhost/cms-monolithic-legacy-php-main/add_sample_products.php
```

**That's it!** It will automatically add 16 sample products to your store.

- ✅ 16 different products with realistic data
- ✅ Stock quantities pre-filled
- ✅ All products set to "Active"
- ✅ Prices ranging from $9.99 to $129.99
- ✅ Complete descriptions

---

## **METHOD 2: Using phpMyAdmin (SQL Import)**

### **Step 1: Open phpMyAdmin**
1. Go to: `http://localhost/phpmyadmin`
2. Log in (default username: `root`, password: empty)

### **Step 2: Select Your Database**
1. Click on `local_cms` database (or whatever your database is named)

### **Step 3: Import SQL File**
1. Click the **"Import"** tab at the top
2. Click **"Choose File"**
3. Navigate to: `C:\xampp\htdocs\cms-monolithic-legacy-php-main\db\sample_products.sql`
4. Click it and select **Open**
5. Scroll down and click **"Import"**

**Done!** All 16 products are now in your database.

---

## **METHOD 3: Manually Add via Admin Panel**

### **Step 1: Login to Admin**
1. Go to: `http://localhost/cms-monolithic-legacy-php-main/admin`
2. Login with your admin account

### **Step 2: Add Product**
1. Click **"Products"** in the left menu
2. Click **"Add Product"** button
3. Fill in the form:
   - **Product Name**: (e.g., "Wireless Headphones")
   - **Description**: Product details
   - **Price**: (e.g., 89.99)
   - **Stock Quantity**: (e.g., 50)
   - **Status**: Select "Active"
4. Click **"Add Product"** button

**Repeat for each product you want to add.**

---

## **Sample Products Included**

When you use METHOD 1 or 2, you get these 16 products automatically:

| Product | Price | Stock |
|---------|-------|-------|
| Wireless Headphones | $89.99 | 50 |
| USB-C Fast Charger | $34.99 | 100 |
| Portable Bluetooth Speaker | $49.99 | 45 |
| Wireless Mouse | $19.99 | 120 |
| Phone Screen Protector | $9.99 | 200 |
| USB Hub Adapter | $29.99 | 80 |
| Laptop Stand | $39.99 | 60 |
| Mechanical Keyboard | $129.99 | 35 |
| 4K Webcam | $79.99 | 40 |
| Laptop Cooling Pad | $24.99 | 90 |
| Desk Lamp | $44.99 | 55 |
| Cable Organizer Kit | $14.99 | 150 |
| HD Monitor Light Bar | $59.99 | 30 |
| Wireless Charging Pad | $19.99 | 110 |
| Phone Case - Premium | $24.99 | 200 |
| Desktop Monitor Stand | $54.99 | 70 |

---

## **Verify Products Were Added**

### **In the Store:**
1. Visit: `http://localhost/cms-monolithic-legacy-php-main/shop.php`
2. You should see all products displayed in a grid layout
3. Click on any product to see details
4. Add to cart and test checkout flow

### **In Admin:**
1. Go to: `http://localhost/cms-monolithic-legacy-php-main/admin/products.php`
2. View all products in the admin list
3. Edit or delete as needed

---

## **Add Your Own Products**

After adding samples, you can easily add your own:

### **Via Admin Panel:**
1. Login to admin
2. Click "Products" → "Add Product"
3. Fill in your product details
4. Click "Add Product"

### **Via SQL (Advanced):**
Use this template:

```sql
INSERT INTO `products` (`name`, `description`, `price`, `stock_quantity`, `status`, `created_at`) 
VALUES ('Your Product Name', 'Product description here', 99.99, 50, 'active', NOW());
```

---

## **Common Issues & Solutions**

### **Products not showing in shop?**
- Clear browser cache (Ctrl+F5)
- Make sure products have status = "active"
- Check database connection is working

### **Can't see edit/delete buttons?**
- Make sure you're logged in as admin
- Check your user role in the database

### **Need to delete products?**
- Go to Admin → Products
- Click the delete icon next to each product
- Or use SQL: `DELETE FROM products WHERE product_id = 1;`

---

## **Quick Start**

1. **Right now:** Visit `http://localhost/cms-monolithic-legacy-php-main/add_sample_products.php`
2. **Wait:** See success messages for each product added
3. **Try it:** Go to `http://localhost/cms-monolithic-legacy-php-main/shop.php`
4. **Test:** Add products to cart, login, checkout

---

**That's it! Your store now has products and is ready to use! 🎉**

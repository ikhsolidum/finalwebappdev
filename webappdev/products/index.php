<?php
// Establish a database connection using PDO
$pdo = new PDO("mysql:host=localhost;dbname=productlists", "root", "");
// Select all products and order them by price ascending
$sql = "SELECT * FROM products ORDER BY price ASC";
$result = $pdo->query($sql);

//  order creation
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (isset($_POST["productId"]) && isset($_POST["quantity"])) {
    $productId = $_POST["productId"];
    $quantity = $_POST["quantity"];

    // Update the quantity in the products table
    $stmt = $pdo->prepare("UPDATE products SET quantity = quantity - :quantity WHERE id = :productId");
    $stmt->bindParam(":quantity", $quantity);
    $stmt->bindParam(":productId", $productId);
    $stmt->execute();

    // Insert the order details into the orders table
    $stmt = $pdo->prepare("INSERT INTO orders (productid, quantity) VALUES (:productId, :quantity)");
    $stmt->bindParam(":productId", $productId);
    $stmt->bindParam(":quantity", $quantity);
    $stmt->execute();

    // Insert the new product into the products table
    $stmt = $pdo->prepare("INSERT INTO products (brand, name, type, gender, description, price, quantity) VALUES (:brand, :name, :type, :gender, :description, :price, :quantity)");
$stmt->bindParam(":brand", $brand);
$stmt->bindParam(":name", $name);
$stmt->bindParam(":type", $type);
$stmt->bindParam(":gender", $gender);
$stmt->bindParam(":description", $description);
$stmt->bindParam(":price", $price);
$stmt->bindParam(":quantity", $quantity); // Add this line
$stmt->execute();
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Product Lists</title>
  <link rel="stylesheet" href="css/producttable.css">
</head>
<body>
  <h1>Product Lists</h1>
  
  <div class="search-container">
    <input type="text" id="search-input" name="search" placeholder="Search..." oninput="updateTable()">
    
    <?php if ($user_status !== 'Staff'): ?>
  <button class="add-button" id="add-product-button">Add Product</button>
<?php endif; ?>
  </div>
  
  <table id="product-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Brand</th>
        <th>Name</th>
        <th>Type</th>
        <th>Gender</th>
        <th>Description</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
  <?php if ($result->rowCount() > 0): ?>
    <?php foreach ($result as $row): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['brand'] ?></td>
        <td><?= $row['name'] ?></td>
        <td><?= $row['type'] ?></td>
        <td><?= $row['gender'] ?></td>
        <td class="description"><?= $row['description'] ?></td>
        <td><?= $row['price'] ?></td>
        <td><?= $row['quantity'] ?></td>
        
        <td class="action-buttons">
          <div class="action-buttons">
            <button onclick="showOrderPopup(
              '<?= $row['id'] ?>',
              '<?= $row['brand'] ?>',
              '<?= $row['name'] ?>',
              '<?= $row['type'] ?>',
              '<?= $row['gender'] ?>',
              '<?= $row['description'] ?>',
              '<?= $row['price'] ?>', <?= $row['quantity'] ?>)">Order</button>

            <?php if ($user_status !== 'Staff'): ?>
              <button onclick="showEditProductPopup(
                '<?= $row['id'] ?>',
                '<?= $row['brand'] ?>',
                '<?= $row['name'] ?>',
                '<?= $row['type'] ?>',
                '<?= $row['gender'] ?>',
                '<?= $row['description'] ?>',
                '<?= $row['price'] ?>', <?= $row['quantity'] ?>)">Edit</button>

              <button onclick="deleteProduct(<?= $row['id'] ?>)">Delete</button>
            <?php endif; ?>
          </div>
        </td>
      </tr>
    <?php endforeach; ?>
  <?php else: ?>
    <tr>
      <td colspan="9">No products found</td>
    </tr>
  <?php endif; ?>
</tbody>
  </table>

  <!-- order popup -->
<div class="popup" id="order-popup">
  <h2>Order Product</h2>
  <p>ID: <span id="order-product-id"></span></p>
  <p>Brand: <span id="order-product-brand"></span></p>
  <p>Name: <span id="order-product-name"></span></p>
  <p>Type: <span id="order-product-type"></span></p>
  <p>Gender: <span id="order-product-gender"></span></p>
  <p>Description: <span id="order-product-description"></span></p>
  <p>Price: <span id="order-product-price"></span></p>

  <label for="order-product-quantity">Quantity:</label>
  <input type="number" id="order-product-quantity" name="order-product-quantity" value="1" min="1">
  <br>

  <label for="order-first-name">First Name:</label>
  <input type="text" id="order-first-name" name="order-first-name">
  <br>

  <label for="order-last-name">Last Name:</label>
  <input type="text" id="order-last-name" name="order-last-name">
  <br>

  <label for="order-contact-number">Contact Number:</label>
  <input type="text" id="order-contact-number" name="order-contact-number">
  <br>

  <label for="order-email">Email:</label>
  <input type="email" id="order-email" name="order-email">
  <br>

  <button onclick="createOrder()">Create Order</button>
  <button onclick="cancelOrder()">Cancel</button>
</div>



<div class="popup" id="edit-product-popup">
  <h2>Edit Product</h2>
  <div>
    <input type="hidden" id="edit-product-id" name="productId">
    <label for="edit-product-brand">Brand:</label>
    <input type="text" id="edit-product-brand" name="brand" required>
    <label for="edit-product-name">Name:</label>
    <input type="text" id="edit-product-name" name="name" required>
    <label for="edit-product-type">Type:</label>
    <input type="text" id="edit-product-type" name="type" required>

    <label for="edit-product-gender">Gender:</label>
    <select id="edit-product-gender" name="gender" required class="select-styling">
      <option value="Men">Men</option>
      <option value="Women">Women</option>
      <option value="Unisex">Unisex</option>
    </select>

    <label for="edit-product-description">Description:</label>
    <textarea id="edit-product-description" name="description" required></textarea>
    <label for="edit-product-price">Price:</label>
    <input type="number" id="edit-product-price" name="price" step="0.01" required>
    <label for="edit-product-quantity">Quantity:</label>
    <input type="number" id="edit-product-quantity" name="quantity" min="0" required>
    <br>
    <button onclick="saveProduct()">Save</button>
    <button onclick="cancelEditProduct()">Cancel</button>
  </div>
</div>


<div class="popup" id="add-product-popup">
  <h2>Add Product</h2>
  <div class="form-container">
    <!-- Add form fields for the product details -->
    <label for="add-product-brand">Brand:</label>
    <input type="text" id="add-product-brand" name="brand" required>
    <label for="add-product-name">Name:</label>
    <input type="text" id="add-product-name" name="name" required>
    <label for="add-product-type">Type:</label>
    <input type="text" id="add-product-type" name="type" required>

    <label for="add-product-gender">Gender:</label>
    <select id="add-product-gender" name="gender" required class="select-styling">
      <option value="Men">Men</option>
      <option value="Women">Women</option>
      <option value="Unisex">Unisex</option>
    </select>

    <label for="add-product-description">Description:</label>
    <textarea id="add-product-description" name="description" required></textarea>
    <label for="add-product-price">Price:</label>
    <input type="number" id="add-product-price" name="price" step="0.01" required>
    <label for="add-product-quantity">Quantity:</label>
    <input type="number" id="add-product-quantity" name="quantity" min="0" required>
    <br>
    <button onclick="addProduct()">Add</button>
    <button onclick="cancelAddProduct()">Cancel</button>
  </div>
</div>

<script src="js/producttable.js"></script>
</body>
</html>

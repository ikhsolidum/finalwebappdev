<?php
include 'config.php';
// Establish a database connection using PDO
$pdo = new PDO("mysql:host=localhost;dbname=productlists", "root", "");
// Select all orders from the orders table with the associated product details
$sql = "SELECT orders.id, orders.productid, products.name, products.price, orders.quantity, orders.firstName, orders.lastName, orders.contactNumber, orders.email, DATE_FORMAT(orders.created_at, '%W') AS day, SUM(orders.quantity) AS total_quantity
        FROM orders
        INNER JOIN products ON orders.productid = products.id
        GROUP BY day, products.name, orders.id, orders.productid, products.price, orders.quantity, orders.firstName, orders.lastName, orders.contactNumber, orders.email
        ORDER BY total_quantity DESC";

$result = $pdo->query($sql);
$data = $result->fetchAll(PDO::FETCH_ASSOC);
$totalAmount = 0; // Initialize totalAmount to 0


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Lists</title>
    <link rel="stylesheet" href="css/order_details.css">
   
    <script>
        function showOrderDetails(orderId) {
            const form = document.getElementById(`order-details-form-${orderId}`);
            form.style.display = 'block';
        }
    </script>
<body>
</head>
<body>
    <h1>Order Lists</h1>
<!-- Search -->
<div class="search-container">
  <form method="post">
    <input type="text" id="search-input" name="search" placeholder="Search..." oninput="updateTable()">
    <script src="js/order_details.js"></script>
  </form>
</div>

<!-- Order Table -->
<table id="order-table">
  <thead>
    <tr>
      <th>Order ID</th>
      <th>Product ID</th>
      <th>Product Name</th>
      <th>Product Price</th>
      <th>Quantity</th>
      <th>Total Price</th>
      <th>First Name</th>
      <th>Last Name</th>
      <th>Contact Number</th>
      <th>Email</th>
      <th class="action-column">Action</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($data)): ?>
      <?php foreach ($data as $row): ?>
        <?php
          $totalPrice = $row['price'] * $row['quantity'];
          $totalAmount += $totalPrice;
        ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= $row['productid'] ?></td>
          <td><?= $row['name'] ?></td>
          <td><?= $row['price'] ?></td>
          <td><?= $row['quantity'] ?></td>
          <td><?= $totalPrice ?></td>
          <td><?= $row['firstName'] ?></td>
          <td><?= $row['lastName'] ?></td>
          <td><?= $row['contactNumber'] ?></td>
          <td><?= $row['email'] ?></td>
          <td>
            <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
            <div class="action-cell">
    <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
    <button class="delete-button" onclick="if(confirm('Are you sure you want to delete this order?')) { window.location.href='products/delete_order.php?id=<?= $row['id'] ?>'; }">Delete</button>
    <button class="delete-all-button" onclick="deleteAllOrders()">Delete All</button>
    <button class="confirm-order-button" onclick="confirmOrder(<?= $row['id'] ?>)">Confirm Order</button>
    <button class="confirm-all-orders-button" onclick="confirmAllOrders()">Confirm All Orders</button>

  </div>

          </td>
        </tr>
      <?php endforeach; ?>
      <tr>
        <td colspan="6" style="text-align: right; font-weight: bold;">Total Amount:</td>
        <td colspan="5"><?= $totalAmount ?></td>
      </tr>
    <?php else: ?>
      <tr>
        <td colspan="11">No orders found.</td>
      </tr>
    <?php endif; ?>
    
  </tbody>
  
</table>



    <br>
    
    

    
</body>
</html>

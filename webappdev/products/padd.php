<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $brand = $_POST['brand'];
  $name = $_POST['name'];
  $type = $_POST['type'];
  $gender = $_POST['gender'];
  $description = $_POST['description'];
  $price = $_POST['price'];
  $quantity = $_POST['quantity'];

  // Insert product into database
  $sql = "INSERT INTO products (brand, name, type, gender, description, price, quantity)
          VALUES (:brand, :name, :type, :gender, :description, :price, :quantity)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(['brand' => $brand, 'name' => $name, 'type' => $type, 'gender' => $gender,
                  'description' => $description, 'price' => $price, 'quantity' => $quantity]);
  header('Location: ../index.php?page=products');
  exit();
}
?>


<!DOCTYPE html>
<html>
<head>
	<title>Add Product</title>
	<link rel="stylesheet" href="css/productadd.css">
	
</head>
<body>
	<h1>Add Product</h1>
	<form method="POST" enctype="multipart/form-data">
		<?php if (!empty($errors)): ?>
		  <div style="color: red;">
		    <?php foreach ($errors as $error): ?>
		      <p><?php echo $error; ?></p>
		    <?php endforeach; ?>
		  </div>
		<?php endif; ?>
		<label>Name:</label>
		<input type="text" name="name" required autofocus>

		<label>Brand:</label>
		<input type="text" name="brand" required>

		<label>Description:</label>
		<textarea name="description" required></textarea>

		<label>Type:</label>
		<input type="text" name="type" required>

		<label for="gender">Gender:</label>
		<select name="gender" id="gender" required>
		  <option value="Men">Men</option>
		  <option value="Women">Women</option>
		  <option value="Unisex">Unisex</option>
		</select>
		<br>

		<label>Price:</label>
		<input type="number" name="price" required>

		<label>Quantity:</label>
		<input type="number" name="quantity" required>
		
		<input type="submit" value="Add Product">
		<button type="button" onclick="goBack()" class="cancel-button">Cancel</button>
	</form>
	<script>
	  function goBack() {
	    window.history.back();
	  }
	</script>
</body>
</html>

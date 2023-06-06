<?php
// Retrieve the order details from the request
$productId = $_POST['productId'];
$quantity = $_POST['quantity'];
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$contactNumber = $_POST['contactNumber'];
$email = $_POST['email'];

// Save the order details in the database
$pdo = new PDO("mysql:host=localhost;dbname=productlists", "root", "");
$sql = "INSERT INTO orders (productid, quantity, firstName, lastName, contactNumber, email) VALUES (:productId, :quantity, :firstName, :lastName, :contactNumber, :email)";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':productId', $productId);
$stmt->bindParam(':quantity', $quantity);
$stmt->bindParam(':firstName', $firstName);
$stmt->bindParam(':lastName', $lastName);
$stmt->bindParam(':contactNumber', $contactNumber);
$stmt->bindParam(':email', $email);
$stmt->execute();

// Return a response to the client (optional)
$response = array('success' => true);
echo json_encode($response);
?>

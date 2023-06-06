<?php
include 'config.php';

try {
  // Establish a database connection using PDO
  $pdo = new PDO("mysql:host=localhost;dbname=productlists", "root", "");

  // Update the 'confirmed' field in the orders table for all orders
  $sql = "UPDATE orders SET confirmed = 'yes'";
  $stmt = $pdo->prepare($sql);

  if ($stmt->execute()) {
    echo "All orders confirmed successfully.";
    header("Location: ../index.php?page=orderlists");
    exit(); // Terminate the current script to ensure the redirect happens
  } else {
    echo "Error updating orders: " . $stmt->errorInfo()[2];
  }

  $stmt->closeCursor();
} catch (PDOException $e) {
  echo "Database Error: " . $e->getMessage();
}
?>

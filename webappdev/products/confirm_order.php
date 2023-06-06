<?php
include 'config.php';

if (isset($_GET['id'])) {
  $orderId = $_GET['id'];

  try {
    // Establish a database connection using PDO
    $pdo = new PDO("mysql:host=localhost;dbname=productlists", "root", "");

    // Update the 'confirmed' field in the orders table
    $sql = "UPDATE orders SET confirmed = 'yes' WHERE id = :orderId";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':orderId', $orderId);

    if ($stmt->execute()) {
        echo "Order confirmed successfully.";
        header("Location: ../index.php?page=orderlists");
        exit(); // Terminate the current script to ensure the redirect happens
      } else {
        echo "Error updating order: " . $stmt->errorInfo()[2];
      }
      
    $stmt->closeCursor();
  } catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
  }
} else {
  echo "Invalid order ID.";
}
?>

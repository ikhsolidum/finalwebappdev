<?php
// Establish a database connection using PDO
$pdo = new PDO("mysql:host=localhost;dbname=productlists", "root", "");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (isset($_POST["productId"]) && isset($_POST["brand"]) && isset($_POST["name"]) && isset($_POST["type"]) && isset($_POST["gender"]) && isset($_POST["description"]) && isset($_POST["price"]) && isset($_POST["quantity"])) {
    $productId = $_POST["productId"];
    $brand = $_POST["brand"];
    $name = $_POST["name"];
    $type = $_POST["type"];
    $gender = $_POST["gender"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];

    // Update the product details in the database
    $stmt = $pdo->prepare("UPDATE products SET brand = :brand, name = :name, type = :type, gender = :gender, description = :description, price = :price, quantity = :quantity WHERE id = :productId");
    $stmt->bindParam(":brand", $brand);
    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":type", $type);
    $stmt->bindParam(":gender", $gender);
    $stmt->bindParam(":description", $description);
    $stmt->bindParam(":price", $price);
    $stmt->bindParam(":quantity", $quantity);
    $stmt->bindParam(":productId", $productId);
    $stmt->execute();
  }
}
?>

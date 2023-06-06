<?php
include 'config.php';

$sql = "DELETE FROM orders";
$stmt = $pdo->prepare($sql);

if ($stmt->execute()) {
    header('Location: ../index.php?page=orderlists');
    exit;
} else {
    echo "Error: " . $sql . "<br>" . $stmt->errorInfo()[2];
}

$stmt->closeCursor();
?>

<?php
include 'config.php';

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM orders WHERE id=:id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    
    if ($stmt->execute()) {
        header('Location: ../index.php?page=orderlists');
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $stmt->errorInfo()[2];
    }
    
    $stmt->closeCursor();
} else {
    // handle the case where "id" is not set in the URL
    $id = 0;
}
?>
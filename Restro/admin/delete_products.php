<?php
session_start();
include('config/config.php');
include('config/checklogin.php');

check_login();

if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];

    // Delete the product from the database
    $deleteQuery = "DELETE FROM rpos_productos WHERE prod_id = ?";
    $deleteStmt = $mysqli->prepare($deleteQuery);
    $deleteStmt->bind_param('s', $delete);
    $deleteStmt->execute();

    if ($deleteStmt) {
        // Product deleted successfully
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit();
    } else {
        // Failed to delete the product
        echo "Error al eliminar el producto. Inténtalo de nuevo.";
    }
}

$mysqli->close();
?>

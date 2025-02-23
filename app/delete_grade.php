<?php
require 'database.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'];

// Delete grade
$stmt = $db->prepare("DELETE FROM grades WHERE id = :id");
$stmt->bindValue(':id', $id, SQLITE3_INTEGER);
$stmt->execute();

header('Location: index.php');
exit;
?>

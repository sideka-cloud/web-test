<?php
require 'database.php';

if (!isset($_GET['id'])) {
    header('Location: studies.php');
    exit;
}

$id = $_GET['id'];

// Delete study
$stmt = $db->prepare("DELETE FROM studies WHERE id = :id");
$stmt->bindValue(':id', $id, SQLITE3_INTEGER);
$stmt->execute();

header('Location: studies.php');
exit;
?>

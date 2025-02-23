<?php
require 'database.php';

if (!isset($_GET['id'])) {
    header('Location: students.php');
    exit;
}

$id = $_GET['id'];

// Delete student
$stmt = $db->prepare("DELETE FROM students WHERE id = :id");
$stmt->bindValue(':id', $id, SQLITE3_INTEGER);
$stmt->execute();

header('Location: students.php');
exit;
?>

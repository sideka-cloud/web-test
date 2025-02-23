<?php
require 'database.php';

if (!isset($_GET['id'])) {
    header('Location: students.php');
    exit;
}

$id = $_GET['id'];

// Fetch student by ID
$stmt = $db->prepare("SELECT * FROM students WHERE id = :id");
$stmt->bindValue(':id', $id, SQLITE3_INTEGER);
$result = $stmt->execute();
$student = $result->fetchArray(SQLITE3_ASSOC);

if (!$student) {
    header('Location: students.php');
    exit;
}

// Update student
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $born = $_POST['born'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    $stmt = $db->prepare("UPDATE students SET name = :name, born = :born, address = :address, phone = :phone WHERE id = :id");
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':born', $born, SQLITE3_TEXT);
    $stmt->bindValue(':address', $address, SQLITE3_TEXT);
    $stmt->bindValue(':phone', $phone, SQLITE3_TEXT);
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $stmt->execute();

    header('Location: students.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include 'includes/header.php'; ?>
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h1 class="text-center">Edit Student</h1>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= $student['name'] ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="born" class="form-label">Born</label>
                            <input type="date" class="form-control" id="born" name="born" value="<?= $student['born'] ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address" value="<?= $student['address'] ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="<?= $student['phone'] ?>" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success mt-3 w-100">Update Student</button>
                </form>
            </div>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>

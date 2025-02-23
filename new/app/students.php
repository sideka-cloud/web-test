<?php
require 'database.php';

// Add new student
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $born = $_POST['born'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    $stmt = $db->prepare("INSERT INTO students (name, born, address, phone) VALUES (:name, :born, :address, :phone)");
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':born', $born, SQLITE3_TEXT);
    $stmt->bindValue(':address', $address, SQLITE3_TEXT);
    $stmt->bindValue(':phone', $phone, SQLITE3_TEXT);
    $stmt->execute();
}

// Fetch all students
$students = $db->query("SELECT * FROM students");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include 'includes/header.php'; ?>
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h1 class="text-center">Manage Students</h1>
            </div>
            <div class="card-body">
                <form method="POST" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="born" class="form-label">Born</label>
                            <input type="date" class="form-control" id="born" name="born" required>
                        </div>
                        <div class="col-md-6">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address" required>
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success mt-3 w-100">Add Student</button>
                </form>

                <table class="table table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Born</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($student = $students->fetchArray(SQLITE3_ASSOC)): ?>
                            <tr>
                                <td><?= $student['id'] ?></td>
                                <td><?= $student['name'] ?></td>
                                <td><?= $student['born'] ?></td>
                                <td><?= $student['address'] ?></td>
                                <td><?= $student['phone'] ?></td>
                                <td>
                                    <a href="edit_student.php?id=<?= $student['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="delete_student.php?id=<?= $student['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>

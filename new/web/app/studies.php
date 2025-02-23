<?php
require 'database.php';

// Add new study
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $standard_grade = $_POST['standard_grade'];

    $stmt = $db->prepare("INSERT INTO studies (name, standard_grade) VALUES (:name, :standard_grade)");
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':standard_grade', $standard_grade, SQLITE3_INTEGER);
    $stmt->execute();
}

// Fetch all studies
$studies = $db->query("SELECT * FROM studies");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Studies</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include 'includes/header.php'; ?>
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h1 class="text-center">Manage Studies</h1>
            </div>
            <div class="card-body">
                <form method="POST" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Study Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="standard_grade" class="form-label">Standard Grade</label>
                            <input type="number" class="form-control" id="standard_grade" name="standard_grade" min="1" max="100" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success mt-3 w-100">Add Study</button>
                </form>

                <table class="table table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Standard Grade</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($study = $studies->fetchArray(SQLITE3_ASSOC)): ?>
                            <tr>
                                <td><?= $study['id'] ?></td>
                                <td><?= $study['name'] ?></td>
                                <td><?= $study['standard_grade'] ?></td>
                                <td>
                                    <a href="edit_study.php?id=<?= $study['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="delete_study.php?id=<?= $study['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
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

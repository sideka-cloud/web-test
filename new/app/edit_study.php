<?php
require 'database.php';

if (!isset($_GET['id'])) {
    header('Location: studies.php');
    exit;
}

$id = $_GET['id'];

// Fetch study by ID
$stmt = $db->prepare("SELECT * FROM studies WHERE id = :id");
$stmt->bindValue(':id', $id, SQLITE3_INTEGER);
$result = $stmt->execute();
$study = $result->fetchArray(SQLITE3_ASSOC);

if (!$study) {
    header('Location: studies.php');
    exit;
}

// Update study
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $standard_grade = $_POST['standard_grade'];

    $stmt = $db->prepare("UPDATE studies SET name = :name, standard_grade = :standard_grade WHERE id = :id");
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':standard_grade', $standard_grade, SQLITE3_INTEGER);
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $stmt->execute();

    header('Location: studies.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Study</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include 'includes/header.php'; ?>
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h1 class="text-center">Edit Study</h1>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Study Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= $study['name'] ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="standard_grade" class="form-label">Standard Grade</label>
                            <input type="number" class="form-control" id="standard_grade" name="standard_grade" value="<?= $study['standard_grade'] ?>" min="1" max="100" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success mt-3 w-100">Update Study</button>
                </form>
            </div>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>

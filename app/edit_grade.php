<?php
require 'database.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'];

// Fetch grade by ID
$stmt = $db->prepare("
    SELECT grades.id, grades.student_id, grades.study_id, grades.grade, students.name AS student_name, studies.name AS study_name
    FROM grades
    JOIN students ON grades.student_id = students.id
    JOIN studies ON grades.study_id = studies.id
    WHERE grades.id = :id
");
$stmt->bindValue(':id', $id, SQLITE3_INTEGER);
$result = $stmt->execute();
$grade = $result->fetchArray(SQLITE3_ASSOC);

if (!$grade) {
    header('Location: index.php');
    exit;
}

// Update grade
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $grade_value = $_POST['grade'];

    // Fetch standard grade for the selected study
    $stmt = $db->prepare("SELECT standard_grade FROM studies WHERE id = :study_id");
    $stmt->bindValue(':study_id', $grade['study_id'], SQLITE3_INTEGER);
    $result = $stmt->execute();
    $study = $result->fetchArray(SQLITE3_ASSOC);

    if ($study) {
        $standard_grade = $study['standard_grade'];
        $marks = ($grade_value >= $standard_grade) ? 'Pass' : 'Fail';

        // Update grade
        $stmt = $db->prepare("UPDATE grades SET grade = :grade, marks = :marks WHERE id = :id");
        $stmt->bindValue(':grade', $grade_value, SQLITE3_INTEGER);
        $stmt->bindValue(':marks', $marks, SQLITE3_TEXT);
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $stmt->execute();

        header('Location: index.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Grade</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include 'includes/header.php'; ?>
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h1 class="text-center">Edit Grade</h1>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="student" class="form-label">Student</label>
                        <input type="text" class="form-control" value="<?= $grade['student_name'] ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="study" class="form-label">Study</label>
                        <input type="text" class="form-control" value="<?= $grade['study_name'] ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="grade" class="form-label">Grade</label>
                        <input type="number" class="form-control" id="grade" name="grade" value="<?= $grade['grade'] ?>" min="1" max="100" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Update Grade</button>
                </form>
            </div>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>

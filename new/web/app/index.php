<?php
require 'database.php';

// Add new grade
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $study_id = $_POST['study_id'];
    $grade = $_POST['grade'];

    // Fetch standard grade for the selected study
    $stmt = $db->prepare("SELECT standard_grade FROM studies WHERE id = :study_id");
    $stmt->bindValue(':study_id', $study_id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $study = $result->fetchArray(SQLITE3_ASSOC);

    if ($study) {
        $standard_grade = $study['standard_grade'];
        $marks = ($grade >= $standard_grade) ? 'Pass' : 'Fail';

        // Insert grade
        $stmt = $db->prepare("INSERT INTO grades (student_id, study_id, grade, marks) VALUES (:student_id, :study_id, :grade, :marks)");
        $stmt->bindValue(':student_id', $student_id, SQLITE3_INTEGER);
        $stmt->bindValue(':study_id', $study_id, SQLITE3_INTEGER);
        $stmt->bindValue(':grade', $grade, SQLITE3_INTEGER);
        $stmt->bindValue(':marks', $marks, SQLITE3_TEXT);
        $stmt->execute();
    }
}

// Fetch all grades with student and study details
$grades = $db->query("
    SELECT grades.id, students.name AS student_name, studies.name AS study_name, grades.grade, grades.marks
    FROM grades
    JOIN students ON grades.student_id = students.id
    JOIN studies ON grades.study_id = studies.id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include 'includes/header.php'; ?>
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h1 class="text-center">Student Grades</h1>
            </div>
            <div class="card-body">
                <form method="POST" class="mb-4">
                    <div class="mb-3">
                        <label for="student_id" class="form-label">Student Name</label>
                        <select class="form-control" id="student_id" name="student_id" required>
                            <option value="">Select Student</option>
                            <?php
                            $students = $db->query("SELECT * FROM students");
                            while ($student = $students->fetchArray(SQLITE3_ASSOC)): ?>
                                <option value="<?= $student['id'] ?>"><?= $student['name'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="study_id" class="form-label">Study</label>
                        <select class="form-control" id="study_id" name="study_id" required>
                            <option value="">Select Study</option>
                            <?php
                            $studies = $db->query("SELECT * FROM studies");
                            while ($study = $studies->fetchArray(SQLITE3_ASSOC)): ?>
                                <option value="<?= $study['id'] ?>"><?= $study['name'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="grade" class="form-label">Grade</label>
                        <input type="number" class="form-control" id="grade" name="grade" min="1" max="100" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Add Grade</button>
                </form>

                <table class="table table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Student</th>
                            <th>Study</th>
                            <th>Grade</th>
                            <th>Marks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($grade = $grades->fetchArray(SQLITE3_ASSOC)): ?>
                            <tr>
                                <td><?= $grade['id'] ?></td>
                                <td><?= $grade['student_name'] ?></td>
                                <td><?= $grade['study_name'] ?></td>
                                <td><?= $grade['grade'] ?></td>
                                <td><span class="badge <?= $grade['marks'] === 'Pass' ? 'bg-success' : 'bg-danger' ?>"><?= $grade['marks'] ?></span></td>
                                <td>
                                    <a href="edit_grade.php?id=<?= $grade['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="delete_grade.php?id=<?= $grade['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
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

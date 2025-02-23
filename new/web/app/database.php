<?php
// database.php
$db = new SQLite3('db/students.db');

// Create students table
$query = "CREATE TABLE IF NOT EXISTS students (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    born DATE NOT NULL,
    address TEXT NOT NULL,
    phone TEXT NOT NULL
)";
$db->exec($query);

// Create studies table
$query = "CREATE TABLE IF NOT EXISTS studies (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    standard_grade INTEGER NOT NULL
)";
$db->exec($query);

// Create grades table
$query = "CREATE TABLE IF NOT EXISTS grades (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    student_id INTEGER NOT NULL,
    study_id INTEGER NOT NULL,
    grade INTEGER NOT NULL,
    marks TEXT NOT NULL,
    FOREIGN KEY (student_id) REFERENCES students (id),
    FOREIGN KEY (study_id) REFERENCES studies (id)
)";
$db->exec($query);
?>

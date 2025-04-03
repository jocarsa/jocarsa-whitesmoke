<?php
// api/db.php
// Opens (or creates) the SQLite database via PDO.

$dsn = 'sqlite:../../databases/whitesmoke.db';
try {
    $pdo = new PDO($dsn);
    // Enable foreign keys
    $pdo->exec("PRAGMA foreign_keys = ON;");
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
    exit;
}
?>

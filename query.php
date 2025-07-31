<?php
// Set your database connection
// require_once '../includes/footer.php';
require_once 'config/config.php';

function is_safe_query($sql) {
    $sql = strtolower($sql);
    $unsafe = ['insert', 'update', 'delete', 'drop', 'alter', 'create', 'truncate'];
    foreach ($unsafe as $word) {
        if (preg_match('/\b' . preg_quote($word, '/') . '\b/', $sql)) {
            return false;
        }
    }

    return true;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = trim($_POST['query']);

    if (!is_safe_query($sql)) {
        echo "<div class='error'>⚠️ Only simple SELECT queries are allowed.</div>";
        exit;
    }

    try {
        $pdo = getDbConnection();
        $stmt = $pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($rows) === 0) {
            echo "<div>No results found.</div>";
            exit;
        }

        echo "<table><tr>";
        foreach (array_keys($rows[0]) as $col) {
            echo "<th>" . htmlspecialchars($col) . "</th>";
        }
        echo "</tr>";

        foreach ($rows as $row) {
            echo "<tr>";
            foreach ($row as $cell) {
                echo "<td>" . htmlspecialchars($cell) . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";

    } catch (PDOException $e) {
        echo "<div class='error'>❌ Database error: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
}
?>

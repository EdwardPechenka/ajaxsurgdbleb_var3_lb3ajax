<?php
header('Content-Type: text/xml; charset=utf-8'); 
include "db.php";
$department = $_GET['department'] ?? '';

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<root>';

$stmt = $pdo->prepare("SELECT name, date, shift FROM nurse WHERE department = ?");
$stmt->execute([$department]);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo '<nurse>';
    echo '<name>' . htmlspecialchars($row['name']) . '</name>';
    echo '<date>' . htmlspecialchars($row['date']) . '</date>';
    echo '<shift>' . htmlspecialchars($row['shift']) . '</shift>';
    echo '</nurse>';
}
echo '</root>';
?>
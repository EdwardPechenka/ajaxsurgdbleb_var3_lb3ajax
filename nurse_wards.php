<?php
include "db.php";
$nurse_id = $_GET['nurse'] ?? '';

$sql = "SELECT w.name AS ward_name
        FROM ward w
        JOIN nurse_ward nw ON w.id_ward = nw.fid_ward
        WHERE nw.fid_nurse = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$nurse_id]);

$nurse_stmt = $pdo->prepare("SELECT name FROM nurse WHERE id_nurse = ?");
$nurse_stmt->execute([$nurse_id]);
$nurse_name = $nurse_stmt->fetch()['name'] ?? '';

echo "<h3>Перелік палат медсестри " . htmlspecialchars($nurse_name) . " (формат Text)</h3><ul>";
$has_wards = false;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $has_wards = true;
    echo "<li>Палата: <b>{$row['ward_name']}</b></li>";
}
if (!$has_wards) {
    echo "<li>Ця медсестра не закріплена за жодною палатою.</li>";
}
echo "</ul>";
?>
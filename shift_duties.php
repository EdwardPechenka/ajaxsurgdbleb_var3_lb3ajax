<?php
header('Content-Type: application/json; charset=utf-8');
include "db.php";
$shift = $_GET['shift'] ?? '';

$sql = "SELECT n.name AS nurse_name, n.department, n.date, w.name AS ward_name
        FROM nurse n
        JOIN nurse_ward nw ON n.id_nurse = nw.fid_nurse
        JOIN ward w ON nw.fid_ward = w.id_ward
        WHERE n.shift = ?
        ORDER BY n.date ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$shift]);

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows, JSON_UNESCAPED_UNICODE); 
?>
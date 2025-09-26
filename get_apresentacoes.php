<?php
include 'config.php';

$playlist_id = $_GET['playlist_id'] ?? 1;

$stmt = $pdo->prepare("SELECT * FROM conteudos WHERE playlist_id = ? ORDER BY id");
$stmt->execute([$playlist_id]);
$conteudos = $stmt->fetchAll();

echo json_encode($conteudos);
?>
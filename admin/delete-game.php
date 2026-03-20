<?php // delete-game.php
session_start();
if (!isset($_SESSION["admin_logged"])) { header("Location: /brainhub/brainhub/admin/login.php"); exit; }
require_once __DIR__ . "/../includes/db.php";
$id = (int)($_GET["id"] ?? 0);
if (!$id) die("Missing ID");
$pdo->prepare("DELETE FROM games WHERE id = ?")->execute([$id]);
header("Location: /brainhub/brainhub/admin/?deleted=1");
exit;

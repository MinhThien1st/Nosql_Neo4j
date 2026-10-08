<?php
/**
 * ==============================================================================
 * GEOQUADRILATERAL - NHÓM 9
 * File: includes/header.php - Thanh điều hướng (Bao gồm phần của Member 2)
 * ==============================================================================
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Neo4jService.php';

$neo4jInstance = Neo4jService::getInstance();
$isNeo4jLive = $neo4jInstance->isConnected();
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - Hệ Thống Học Toán Hình Học Tứ Giác</title>
    
    <!-- Google Fonts: Plus Jakarta Sans & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Vis-network CDN cho trực quan hóa đồ thị Neo4j -->
    <script type="text/javascript" src="https://unpkg.com/vis-network/standalone/umd/vis-network.min.js"></script>
    
    <!-- CSS Tùy Chỉnh -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header class="navbar">
    <div class="nav-container">
        <a href="index.php" class="logo-brand">
            <div class="logo-symbol">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 3h18v18H3zM9 9h6v6H9z"/>
                </svg>
            </div>
            <div class="logo-text">
                <h1>GeoQuadrilateral</h1>
                <span>NoSQL Neo4j • Nhóm 9</span>
            </div>
        </a>

        <ul class="nav-links">
            <li class="nav-item <?= ($currentPage == 'index.php') ? 'active' : '' ?>">
                <a href="index.php">Sơ Đồ Đồ Thị</a>
            </li>
            <li class="nav-item <?= ($currentPage == 'shapes.php') ? 'active' : '' ?>">
                <a href="shapes.php">Danh Mục Hình</a>
            </li>
            <li class="nav-item <?= ($currentPage == 'calculator.php') ? 'active' : '' ?>">
                <a href="calculator.php">Công Cụ Tính Toán</a>
            </li>
            <li class="nav-item <?= ($currentPage == 'compare.php') ? 'active' : '' ?>">
                <a href="compare.php">So Sánh Hình</a>
            </li>
        </ul>
    </div>
</header>

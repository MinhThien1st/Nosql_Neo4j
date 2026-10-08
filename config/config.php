<?php
/**
 * ==============================================================================
 * ĐỒ ÁN MÔN NOSQL (NEO4J) - NHÓM 9
 * File: config/config.php - Cấu hình hệ thống & Kết nối Neo4j
 * ==============================================================================
 */

// Cấu hình môi trường & Báo lỗi
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', 0);

// Thông tin ứng dụng
define('APP_NAME', 'GeoQuadrilateral');
define('APP_SUBTITLE', 'Hệ Thống Trực Quan Hóa & Học Tập Hình Học Tứ Giác (Neo4j Graph DB)');
define('APP_VERSION', '2.0.0');
define('GROUP_NAME', 'Nhóm 9 - Học phần NoSQL (Neo4j)');

// Cấu hình kết nối cơ sở dữ liệu đồ thị Neo4j
define('NEO4J_HOST', getenv('NEO4J_HOST') ?: 'localhost');
define('NEO4J_HTTP_PORT', getenv('NEO4J_HTTP_PORT') ?: 7474);
define('NEO4J_BOLT_PORT', getenv('NEO4J_BOLT_PORT') ?: 7687);
define('NEO4J_USER', getenv('NEO4J_USER') ?: 'neo4j');
define('NEO4J_PASS', getenv('NEO4J_PASS') ?: '12345678');
define('NEO4J_DATABASE', getenv('NEO4J_DATABASE') ?: 'neo4j'); // Neo4j mặc định tên db là neo4j

// Đường dẫn thư mục gốc
define('ROOT_PATH', dirname(__DIR__));
define('DATABASE_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'database');
define('CYPHER_SETUP_FILE', DATABASE_PATH . DIRECTORY_SEPARATOR . 'setup_quadrilaterals.cypher');

// Khởi động session nếu chưa có
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

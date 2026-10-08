<?php
/**
 * ==============================================================================
 * GEOQUADRILATERAL - NHÓM 9
 * File: index.php - Trang chủ: Trực quan hóa Sơ đồ đồ thị Neo4j cây phả hệ tứ giác
 * ==============================================================================
 */

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/classes/GeometryModel.php';

$model = new GeometryModel();
?>

<main class="main-wrapper">
    <!-- Hero Banner Hiện Đại -->
    <section class="hero-section">
        <div class="hero-content">
            <div class="hero-tag">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                Graph Database NoSQL • Neo4j 5.x
            </div>
            <h1 class="hero-title">Trực Quan Hóa Mối Quan Hệ Giữa Các Hình Tứ Giác</h1>
            <p class="hero-desc">
                Hệ thống mô hình hóa quan hệ kế thừa và chuyển hóa hình học phẳng theo chuẩn cấu trúc đồ thị Neo4j. Khám phá các thuộc tính, điều kiện chuyển đổi và công thức tính toán thông qua giao diện tương tác.
            </p>
            <div class="hero-actions">
                <a href="#graph-view-anchor" class="btn btn-primary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                    Xem Sơ Đồ Đồ Thị
                </a>
                <a href="transformer.php" class="btn btn-outline-white">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 3 21 3 21 8"/><line x1="4" y1="20" x2="21" y2="3"/><polyline points="21 16 21 21 16 21"/><line x1="15" y1="15" x2="21" y2="21"/><line x1="4" y1="4" x2="9" y2="9"/></svg>
                    Chuyển Hóa Hình Học
                </a>
                <a href="quiz.php" class="btn btn-accent">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    Làm Bài Quiz Test
                </a>
            </div>
        </div>
    </section>

    <!-- Khu Vực Sơ Đồ Đồ Thị Neo4J Tương Tác -->
    <section id="graph-view-anchor" class="graph-section">
        <div class="section-header">
            <div class="section-title">
                <div style="width:36px;height:36px;background:var(--primary-subtle);border-radius:8px;display:flex;align-items:center;justify-content:center;color:var(--primary);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                </div>
                <div>
                    <h2>Sơ Đồ Tiến Hóa Các Hình Tứ Giác (Neo4j Graph Schema)</h2>
                    <p>Mô hình hóa chuẩn theo sơ đồ của giảng viên. Nhấp chuột vào từng node để xem chi tiết thông số.</p>
                </div>
            </div>
            <div class="graph-controls">
                <button id="btn-fit-graph" class="btn btn-sm btn-secondary">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="m12 8 4 4-4 4M8 12h8"/></svg>
                    Căn giữa
                </button>
                <button id="btn-reset-graph" class="btn btn-sm btn-secondary">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>
                    Tải lại đồ thị
                </button>
            </div>
        </div>

        <div class="graph-workspace">
            <div id="cy-network">
                <!-- Vis-network sẽ vẽ đồ thị vào đây -->
            </div>

            <div id="graph-sidebar" class="graph-sidebar">
                <div class="sidebar-empty-state">
                    <div class="sidebar-empty-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 15l6 6m-11-4a7 7 0 110-14 7 7 0 010 14z"/></svg>
                    </div>
                    <h3 style="font-weight:800;margin-bottom:8px;font-size:1.15rem;color:var(--text-strong);">Chọn Một Node Trên Đồ Thị</h3>
                    <p style="font-size:0.88rem;line-height:1.6;">
                        Nhấp chuột vào bất kỳ khối hình học nào (Hình thang, Hình bình hành, Hình chữ nhật, Hình thoi, Hình vuông...) để hiển thị chi tiết đặc tính và công thức.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Khám Phá Nhanh Các Tính Năng -->
    <section style="margin-bottom: 20px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
            <a href="shapes.php" style="background:white;border-radius:14px;padding:22px;border:1px solid var(--border-light);text-decoration:none;color:inherit;transition:var(--transition);display:block;" onmouseover="this.style.borderColor='var(--primary)';this.style.boxShadow='var(--shadow-md)'" onmouseout="this.style.borderColor='var(--border-light)';this.style.boxShadow='none'">
                <div style="width:40px;height:40px;background:#EFF6FF;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#2563EB;margin-bottom:14px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M6 6h10M6 10h10"/></svg>
                </div>
                <h3 style="font-weight:800;margin-bottom:6px;font-size:1.1rem;color:var(--text-strong);">Danh Mục Hình Học</h3>
                <p style="font-size:0.88rem;color:var(--text-muted);line-height:1.5;">Khám phá toàn bộ 9 hình tứ giác với hình vẽ vector sắc nét, định nghĩa và công thức chuẩn.</p>
            </a>

            <a href="transformer.php" style="background:white;border-radius:14px;padding:22px;border:1px solid var(--border-light);text-decoration:none;color:inherit;transition:var(--transition);display:block;" onmouseover="this.style.borderColor='var(--primary)';this.style.boxShadow='var(--shadow-md)'" onmouseout="this.style.borderColor='var(--border-light)';this.style.boxShadow='none'">
                <div style="width:40px;height:40px;background:#F5F3FF;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#4F46E5;margin-bottom:14px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 3 21 3 21 8"/><line x1="4" y1="20" x2="21" y2="3"/><polyline points="21 16 21 21 16 21"/><line x1="15" y1="15" x2="21" y2="21"/><line x1="4" y1="4" x2="9" y2="9"/></svg>
                </div>
                <h3 style="font-weight:800;margin-bottom:6px;font-size:1.1rem;color:var(--text-strong);">Chuyển Hóa Hình Học</h3>
                <p style="font-size:0.88rem;color:var(--text-muted);line-height:1.5;">Tra cứu con đường chuyển đổi ngắn nhất giữa 2 hình học bất kỳ bằng thuật toán Graph Traversal.</p>
            </a>

            <a href="calculator.php" style="background:white;border-radius:14px;padding:22px;border:1px solid var(--border-light);text-decoration:none;color:inherit;transition:var(--transition);display:block;" onmouseover="this.style.borderColor='var(--primary)';this.style.boxShadow='var(--shadow-md)'" onmouseout="this.style.borderColor='var(--border-light)';this.style.boxShadow='none'">
                <div style="width:40px;height:40px;background:#ECFDF5;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#059669;margin-bottom:14px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="2" width="16" height="20" rx="2"/><line x1="8" y1="6" x2="16" y2="6"/><line x1="16" y1="14" x2="16" y2="18"/><path d="M16 10h.01M12 10h.01M8 10h.01M12 14h.01M8 14h.01M12 18h.01M8 18h.01"/></svg>
                </div>
                <h3 style="font-weight:800;margin-bottom:6px;font-size:1.1rem;color:var(--text-strong);">Công Cụ Tính Toán</h3>
                <p style="font-size:0.88rem;color:var(--text-muted);line-height:1.5;">Nhập số đo kích thước, hình vẽ SVG biến đổi thời gian thực và tự động giải từng bước cụ thể.</p>
            </a>

            <a href="quiz.php" style="background:white;border-radius:14px;padding:22px;border:1px solid var(--border-light);text-decoration:none;color:inherit;transition:var(--transition);display:block;" onmouseover="this.style.borderColor='var(--primary)';this.style.boxShadow='var(--shadow-md)'" onmouseout="this.style.borderColor='var(--border-light)';this.style.boxShadow='none'">
                <div style="width:40px;height:40px;background:#FDF2F8;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#DB2777;margin-bottom:14px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                </div>
                <h3 style="font-weight:800;margin-bottom:6px;font-size:1.1rem;color:var(--text-strong);">Quiz Test</h3>
                <p style="font-size:0.88rem;color:var(--text-muted);line-height:1.5;">Kiểm tra mức độ am hiểu hình học qua các câu hỏi nhận diện và công thức trắc nghiệm.</p>
            </a>
        </div>
    </section>
</main>

<script src="assets/js/graph-view.js"></script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

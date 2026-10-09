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
                <a href="shapes.php" class="btn btn-outline-white">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M6 6h10M6 10h10"/></svg>
                    Khám Phá Danh Mục Hình
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

        <!-- Bộ Lọc Đồ Thị Theo Khối Lớp Học -->
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 16px; padding: 0 4px;">
            <div style="display: inline-flex; background: #F1F5F9; padding: 4px; border-radius: 999px; gap: 4px; border: 1px solid var(--border-light);">
                <button type="button" class="btn btn-sm btn-secondary active" id="filter-node-all" onclick="filterGraphByGrade('all', this)">
                    📚 Tất Cả Lớp
                </button>
                <button type="button" class="btn btn-sm btn-secondary" id="filter-node-6" onclick="filterGraphByGrade(6, this)">
                    🎒 Toán Lớp 6
                </button>
                <button type="button" class="btn btn-sm btn-secondary" id="filter-node-7" onclick="filterGraphByGrade(7, this)">
                    📐 Toán Lớp 7
                </button>
                <button type="button" class="btn btn-sm btn-secondary" id="filter-node-8" onclick="filterGraphByGrade(8, this)">
                    🎓 Toán Lớp 8
                </button>
            </div>
            <div style="font-size: 0.85rem; color: var(--text-muted);">
                💡 Chọn khối lớp để làm nổi bật các hình học thuộc chương trình trên đồ thị Neo4j
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

    <!-- Khám Phá Nhanh Danh Mục Hình Học -->
    <section style="margin-bottom: 20px;">
        <div style="background:white;border-radius:14px;padding:26px 32px;border:1px solid var(--border-light);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:20px;">
            <div style="display:flex;align-items:center;gap:18px;">
                <div style="width:48px;height:48px;background:#EFF6FF;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#2563EB;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M6 6h10M6 10h10"/></svg>
                </div>
                <div>
                    <h3 style="font-weight:800;font-size:1.15rem;color:var(--text-strong);margin-bottom:2px;">Kho Tàng Các Hình Tứ Giác</h3>
                    <p style="font-size:0.9rem;color:var(--text-muted);margin:0;">Xem danh mục 9 loại hình học phẳng với hình vẽ vector trực quan và định nghĩa chuẩn xác.</p>
                </div>
            </div>
            <a href="shapes.php" class="btn btn-primary" style="padding:10px 22px;">
                Xem Danh Mục Hình ➔
            </a>
        </div>
    </section>
</main>

<script src="assets/js/graph-view.js"></script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

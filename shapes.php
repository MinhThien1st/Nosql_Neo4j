<?php
/**
 * ==============================================================================
 * GEOQUADRILATERAL - NHÓM 9
 * File: shapes.php - Danh mục toàn bộ các hình tứ giác & Chi tiết đặc tính
 * ==============================================================================
 */

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/classes/GeometryModel.php';
require_once __DIR__ . '/includes/svg_shapes.php';

$model = new GeometryModel();
$shapes = $model->getAllShapes();
?>

<main class="main-wrapper">
    <div style="text-align: center; margin-bottom: 36px;">
        <span style="background: var(--primary-subtle); color: var(--primary); font-weight: 700; padding: 4px 14px; border-radius: 999px; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid #BFDBFE;">
            Hệ Thống Phân Loại Hình Học
        </span>
        <h1 style="font-size: 2rem; font-weight: 800; margin: 12px 0 8px; color: var(--text-strong); letter-spacing: -0.5px;">
            Danh Mục Các Dạng Hình Tứ Giác
        </h1>
        <p style="color: var(--text-muted); font-size: 0.98rem; max-width: 650px; margin: 0 auto;">
            Khảo sát các định nghĩa toán học, đặc tính nhận diện và bộ công thức chu vi & diện tích chuẩn mực.
        </p>
    </div>

    <div class="shapes-grid">
        <?php foreach ($shapes as $shape): 
            $formula = $shape['formula'] ?? [];
            $props = $shape['properties'] ?? [];
        ?>
            <div class="shape-card" id="card-<?= htmlspecialchars($shape['id']) ?>">
                <div>
                    <div class="shape-card-header">
                        <span class="shape-badge" style="background: <?= $shape['color'] ?? '#2563EB' ?>; color: white;">
                            <?= htmlspecialchars($shape['badge'] ?? 'Tứ giác') ?>
                        </span>
                        <button onclick="speakText('<?= addslashes($shape['name'] . '. ' . $shape['definition']) ?>')" class="btn btn-sm btn-secondary" title="Nghe đọc định nghĩa">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07"/></svg>
                            Nghe đọc
                        </button>
                    </div>

                    <div class="shape-preview-svg">
                        <?= renderShapeSvg($shape['id']) ?>
                    </div>

                    <h2 class="shape-card-title"><?= htmlspecialchars($shape['name']) ?></h2>
                    <div class="shape-card-subtitle">
                        <?= htmlspecialchars($shape['name_en'] ?? '') ?> • <?= htmlspecialchars($shape['alias'] ?? '') ?>
                    </div>

                    <p class="shape-card-desc">
                        <?= htmlspecialchars($shape['definition']) ?>
                    </p>

                    <div style="background: #F8FAFC; border-radius: 8px; padding: 10px 12px; margin-bottom: 14px; border: 1px solid var(--border-light); font-size: 0.85rem;">
                        <span style="font-weight: 700; color: var(--text-strong);">Ứng dụng thực tế: </span>
                        <span style="color: var(--text-muted);"><?= htmlspecialchars($shape['real_life'] ?? 'Trong các công trình kiến trúc và đồ vật đời sống') ?></span>
                    </div>

                    <div class="formula-tag-group">
                        <div style="margin-bottom: 4px; display: flex; justify-content: space-between;">
                            <span style="color: var(--text-muted); font-size: 0.82rem; font-weight: 600;">Chu vi:</span>
                            <span class="formula-tag"><?= htmlspecialchars($formula['perimeter_formula'] ?? 'P = a + b + c + d') ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--text-muted); font-size: 0.82rem; font-weight: 600;">Diện tích:</span>
                            <span class="formula-tag"><?= htmlspecialchars($formula['area_formula'] ?? 'S = ...') ?></span>
                        </div>
                    </div>
                </div>

                <div class="shape-card-actions">
                    <a href="calculator.php?shape=<?= urlencode($shape['id']) ?>" class="btn-card btn-card-calc">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="2" width="16" height="20" rx="2"/><line x1="8" y1="6" x2="16" y2="6"/></svg>
                        Tính toán
                    </a>
                    <a href="compare.php?s1=<?= urlencode($shape['id']) ?>" class="btn-card btn-card-primary">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 3 18 18M3 21h18"/></svg>
                        So sánh
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

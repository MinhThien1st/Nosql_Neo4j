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

    <!-- Bộ Lọc Phân Cấp Theo Khối Lớp Học (SGK Toán THCS) -->
    <div style="max-width: 850px; margin: 0 auto 30px; text-align: center;">
        <div class="grade-filter-container" style="display: inline-flex; background: #F1F5F9; padding: 6px; border-radius: 999px; gap: 6px; flex-wrap: wrap; justify-content: center; border: 1px solid var(--border-light);">
            <button type="button" class="grade-filter-btn active" data-grade="all" onclick="filterByGrade('all')">
                📚 Tất Cả Khối Lớp (9 hình)
            </button>
            <button type="button" class="grade-filter-btn" data-grade="6" onclick="filterByGrade(6)">
                🎒 Toán Lớp 6 (4 hình)
            </button>
            <button type="button" class="grade-filter-btn" data-grade="7" onclick="filterByGrade(7)">
                📐 Toán Lớp 7 (3 hình)
            </button>
            <button type="button" class="grade-filter-btn" data-grade="8" onclick="filterByGrade(8)">
                🎓 Toán Lớp 8 (9 hình)
            </button>
        </div>
        <div id="grade-curriculum-info" style="margin-top: 14px; font-size: 0.9rem; color: var(--text-muted); background: white; padding: 12px 20px; border-radius: var(--radius-md); border: 1px solid var(--border-light); display: inline-block;">
            💡 Đang hiển thị trọn vẹn toàn bộ 9 dạng hình tứ giác trong cơ sở dữ liệu đồ thị Neo4j.
        </div>
    </div>

    <div class="shapes-grid" id="shapes-cards-container">
        <?php foreach ($shapes as $shape): 
            $formula = $shape['formula'] ?? [];
            $props = $shape['properties'] ?? [];
            $gradesJson = json_encode($shape['grades'] ?? [8]);
        ?>
            <div class="shape-card" id="card-<?= htmlspecialchars($shape['id']) ?>" data-grades='<?= htmlspecialchars($gradesJson, ENT_QUOTES) ?>'>
                <div>
                    <div class="shape-card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                        <span class="shape-badge" style="background: <?= $shape['color'] ?? '#2563EB' ?>; color: white;">
                            <?= htmlspecialchars($shape['badge'] ?? 'Tứ giác') ?>
                        </span>
                        <span style="background: #F1F5F9; color: #475569; font-size: 0.75rem; font-weight: 700; padding: 3px 10px; border-radius: 999px; border: 1px solid #E2E8F0;">
                            <?= htmlspecialchars($shape['grade_text'] ?? 'Toán THCS') ?>
                        </span>
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
            </div>
        <?php endforeach; ?>
    </div>
</main>

<script>
const gradeDescriptions = {
    'all': '💡 Đang hiển thị trọn vẹn toàn bộ 9 dạng hình tứ giác trong cơ sở dữ liệu đồ thị Neo4j.',
    '6': '🎒 <strong>Toán Lớp 6 (Hình học trực quan):</strong> Học sinh làm quen nhận biết các hình cơ bản trong thực tế gồm: <em>Hình chữ nhật, Hình thoi, Hình bình hành, Hình thang cân</em> cùng công thức chu vi và diện tích thực nghiệm.',
    '7': '📐 <strong>Toán Lớp 7 (Định hình & Góc):</strong> Làm quen cấu trúc đa giác, khái niệm 2 đường thẳng song song tạo thành <em>Hình thang</em> và tam giác vuông tạo thành <em>Hình thang vuông</em>.',
    '8': '🎓 <strong>Toán Lớp 8 (Định lý & Dấu hiệu nhận biết):</strong> Đỉnh cao của hình học tứ giác phẳng! Học sinh học đầy đủ 9 hình với hệ thống định lý, tính chất đối xứng và các dấu hiệu nhận biết chứng minh hình học.'
};

function filterByGrade(grade) {
    document.querySelectorAll('.grade-filter-btn').forEach(btn => {
        btn.classList.toggle('active', btn.getAttribute('data-grade') == grade.toString());
    });

    const infoBox = document.getElementById('grade-curriculum-info');
    if (infoBox && gradeDescriptions[grade.toString()]) {
        infoBox.innerHTML = gradeDescriptions[grade.toString()];
    }

    const cards = document.querySelectorAll('.shape-card');
    cards.forEach(card => {
        const grades = JSON.parse(card.getAttribute('data-grades') || '[]');
        if (grade === 'all' || grades.includes(Number(grade))) {
            card.style.display = 'flex';
            card.style.opacity = '1';
            card.style.transform = 'scale(1)';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

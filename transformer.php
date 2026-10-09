<?php
/**
 * ==============================================================================
 * GEOQUADRILATERAL - NHÓM 9
 * File: transformer.php - Phân tích đường đi Chuyển Hóa Hình Học (Neo4j Shortest Path)
 * ==============================================================================
 */

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/classes/GeometryModel.php';

$model = new GeometryModel();
$shapes = $model->getAllShapes();
?>

<main class="main-wrapper">
    <div style="text-align: center; margin-bottom: 32px;">
        <span style="background: var(--primary-subtle); color: var(--primary); font-weight: 700; padding: 4px 14px; border-radius: 999px; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid #BFDBFE;">
            Neo4j Graph Traversal & Shortest Path
        </span>
        <h1 style="font-size: 2rem; font-weight: 800; margin: 12px 0 8px; color: var(--text-strong); letter-spacing: -0.5px;">
            Mối Quan Hệ Chuyển Hóa Giữa Các Hình Tứ Giác
        </h1>
        <p style="color: var(--text-muted); font-size: 0.98rem; max-width: 680px; margin: 0 auto;">
            Tra cứu và phân tích con đường chuyển đổi ngắn nhất giữa hai hình học bất kỳ trong đồ thị tri thức Neo4j, xác định chính xác các điều kiện hình học cần bổ sung.
        </p>
    </div>

    <div style="background: white; border-radius: var(--radius-xl); border: 1px solid var(--border-light); padding: 32px; box-shadow: var(--shadow-sm); margin-bottom: 40px;">
        <div style="display: flex; align-items: center; justify-content: center; gap: 24px; margin-bottom: 32px; flex-wrap: wrap;">
            <div style="min-width: 260px; flex: 1; max-width: 320px;">
                <label class="form-label" style="color: var(--primary);">Hình Ban Đầu (Start):</label>
                <select id="select-start-shape" class="form-control" style="font-weight: 700;">
                    <?php foreach ($shapes as $s): ?>
                        <option value="<?= htmlspecialchars($s['id']) ?>" <?= ($s['id'] === 'hinh_binh_hanh') ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="margin-top: 24px; color: var(--text-muted); display: flex; align-items: center; justify-content: center;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </div>

            <div style="min-width: 260px; flex: 1; max-width: 320px;">
                <label class="form-label" style="color: var(--accent-rose);">Hình Mục Tiêu (Target):</label>
                <select id="select-target-shape" class="form-control" style="font-weight: 700;">
                    <?php foreach ($shapes as $s): ?>
                        <option value="<?= htmlspecialchars($s['id']) ?>" <?= ($s['id'] === 'hinh_vuong') ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="align-self: flex-end; margin-top: 10px;">
                <button id="btn-do-transform" class="btn btn-primary" style="padding: 11px 24px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 3 21 3 21 8"/><line x1="4" y1="20" x2="21" y2="3"/><polyline points="21 16 21 21 16 21"/><line x1="15" y1="15" x2="21" y2="21"/><line x1="4" y1="4" x2="9" y2="9"/></svg>
                    Phân Tích Chuyển Hóa
                </button>
            </div>
        </div>

        <div id="transformer-result">
            <div style="text-align: center; padding: 48px 20px; color: var(--text-muted);">
                <div style="width: 50px; height: 50px; background: var(--bg-muted); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px; color: var(--primary);">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                </div>
                <h3 style="font-weight: 700; margin-bottom: 6px; color: var(--text-strong);">Chọn 2 hình học và bấm nút Phân Tích Chuyển Hóa</h3>
                <p style="font-size: 0.9rem;">Hệ thống sẽ thực thi thuật toán tìm đường đi ngắn nhất (shortestPath) trên đồ thị Neo4j.</p>
            </div>
        </div>
    </div>
</main>

<script src="assets/js/transformer.js"></script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

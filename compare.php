<?php
/**
 * ==============================================================================
 * GEOQUADRILATERAL - NHÓM 9
 * File: compare.php - Công cụ So sánh chi tiết giữa 2 hình tứ giác
 * ==============================================================================
 */

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/classes/GeometryModel.php';
require_once __DIR__ . '/includes/svg_shapes.php';

$model = new GeometryModel();
$shapes = $model->getAllShapes();

$s1 = $_GET['s1'] ?? 'hinh_chu_nhat';
$s2 = $_GET['s2'] ?? 'hinh_thoi';

$comparison = $model->compareShapes($s1, $s2);
?>

<main class="main-wrapper">
    <div style="text-align: center; margin-bottom: 32px;">
        <span style="background: var(--primary-subtle); color: var(--primary); font-weight: 700; padding: 4px 14px; border-radius: 999px; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid #BFDBFE;">
            Comparative Analysis
        </span>
        <h1 style="font-size: 2rem; font-weight: 800; margin: 12px 0 8px; color: var(--text-strong); letter-spacing: -0.5px;">
            So Sánh Đặc Tính Giữa Hai Hình Tứ Giác
        </h1>
        <p style="color: var(--text-muted); font-size: 0.98rem; max-width: 650px; margin: 0 auto;">
            Đối chiếu tính chất hình học chung và các điểm phân biệt giữa hai hình trong hệ thống phân loại.
        </p>
    </div>

    <!-- Form Chọn 2 Hình -->
    <form method="GET" action="compare.php" style="background: white; border-radius: var(--radius-lg); padding: 22px 28px; border: 1px solid var(--border-light); margin-bottom: 32px; box-shadow: var(--shadow-xs);">
        <div style="display: flex; gap: 24px; align-items: center; justify-content: center; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 220px; max-width: 340px;">
                <label class="form-label" style="color: var(--primary);">Hình Thứ Nhất:</label>
                <select name="s1" class="form-control" onchange="this.form.submit()" style="font-weight: 700;">
                    <?php foreach ($shapes as $s): ?>
                        <option value="<?= htmlspecialchars($s['id']) ?>" <?= ($s['id'] === $s1) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="font-size: 14px; font-weight: 800; color: var(--text-muted); margin-top: 24px; text-transform: uppercase; background: var(--bg-muted); padding: 6px 14px; border-radius: 999px;">
                ĐỐI CHIẾU
            </div>

            <div style="flex: 1; min-width: 220px; max-width: 340px;">
                <label class="form-label" style="color: var(--accent-indigo);">Hình Thứ Hai:</label>
                <select name="s2" class="form-control" onchange="this.form.submit()" style="font-weight: 700;">
                    <?php foreach ($shapes as $s): ?>
                        <option value="<?= htmlspecialchars($s['id']) ?>" <?= ($s['id'] === $s2) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </form>

    <?php if ($comparison): 
        $shape1 = $comparison['shape1'];
        $shape2 = $comparison['shape2'];
    ?>
        <!-- Bảng Đặt Cạnh Nhau 2 Hình -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 28px;">
            <div style="background: white; border-radius: var(--radius-lg); padding: 24px; border: 1px solid var(--border-light); text-align: center; box-shadow: var(--shadow-xs);">
                <h3 style="font-weight: 800; color: var(--primary); margin-bottom: 12px; font-size: 1.25rem;"><?= htmlspecialchars($shape1['name']) ?></h3>
                <div class="shape-preview-svg" style="height: 140px; margin-bottom: 14px;">
                    <?= renderShapeSvg($shape1['id'], 220, 130) ?>
                </div>
                <div style="font-weight: 700; color: var(--text-strong); font-size: 0.9rem; font-family: 'JetBrains Mono', monospace;">
                    P = <?= htmlspecialchars($shape1['formula']['perimeter_formula'] ?? '') ?>
                </div>
                <div style="font-weight: 700; color: var(--accent-teal); font-size: 0.9rem; margin-top: 4px; font-family: 'JetBrains Mono', monospace;">
                    S = <?= htmlspecialchars($shape1['formula']['area_formula'] ?? '') ?>
                </div>
            </div>

            <div style="background: white; border-radius: var(--radius-lg); padding: 24px; border: 1px solid var(--border-light); text-align: center; box-shadow: var(--shadow-xs);">
                <h3 style="font-weight: 800; color: var(--accent-indigo); margin-bottom: 12px; font-size: 1.25rem;"><?= htmlspecialchars($shape2['name']) ?></h3>
                <div class="shape-preview-svg" style="height: 140px; margin-bottom: 14px;">
                    <?= renderShapeSvg($shape2['id'], 220, 130) ?>
                </div>
                <div style="font-weight: 700; color: var(--text-strong); font-size: 0.9rem; font-family: 'JetBrains Mono', monospace;">
                    P = <?= htmlspecialchars($shape2['formula']['perimeter_formula'] ?? '') ?>
                </div>
                <div style="font-weight: 700; color: var(--accent-teal); font-size: 0.9rem; margin-top: 4px; font-family: 'JetBrains Mono', monospace;">
                    S = <?= htmlspecialchars($shape2['formula']['area_formula'] ?? '') ?>
                </div>
            </div>
        </div>

        <!-- Kết Quả So Sánh Chi Tiết -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
            <!-- Điểm Giống Nhau -->
            <div style="background: #F0FDF4; border: 1px solid #BBF7D0; border-radius: var(--radius-lg); padding: 24px;">
                <h4 style="color: #166534; font-weight: 800; margin-bottom: 14px; font-size: 1.05rem;">
                    Đặc Tính Chung (Quan Hệ Kế Thừa)
                </h4>
                <?php if (!empty($comparison['common_properties'])): ?>
                    <ul style="padding-left: 0; list-style: none;">
                        <?php foreach ($comparison['common_properties'] as $cp): ?>
                            <li style="margin-bottom: 8px; color: #15803D; font-weight: 600; font-size: 0.92rem; display: flex; align-items: flex-start; gap: 8px;">
                                <span style="color: #16A34A;">✔</span>
                                <span><?= htmlspecialchars($cp) ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p style="color: #166534; font-size: 0.92rem;">Cả hai hình đều là tứ giác thuộc hình học phẳng (có 4 cạnh và tổng 4 góc trong bằng 360°).</p>
                <?php endif; ?>
            </div>

            <!-- Điểm Khác Biệt -->
            <div style="background: #F8FAFC; border: 1px solid var(--border-light); border-radius: var(--radius-lg); padding: 24px;">
                <h4 style="color: var(--text-strong); font-weight: 800; margin-bottom: 14px; font-size: 1.05rem;">
                    Đặc Điểm Phân Biệt Riêng
                </h4>
                <div style="margin-bottom: 16px;">
                    <strong style="color: var(--primary); font-size: 0.9rem; display: block; margin-bottom: 6px;">Riêng ở <?= htmlspecialchars($shape1['name']) ?>:</strong>
                    <ul style="padding-left: 0; list-style: none;">
                        <?php if (!empty($comparison['only_shape1'])): ?>
                            <?php foreach ($comparison['only_shape1'] as $op): ?>
                                <li style="color: var(--text-regular); font-size: 0.88rem; margin-bottom: 4px; display: flex; align-items: flex-start; gap: 6px;">
                                    <span style="color: var(--primary);">•</span>
                                    <span><?= htmlspecialchars($op) ?></span>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li style="color: var(--text-muted); font-size: 0.88rem;">Không có đặc tính riêng trội hơn.</li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div>
                    <strong style="color: var(--accent-indigo); font-size: 0.9rem; display: block; margin-bottom: 6px;">Riêng ở <?= htmlspecialchars($shape2['name']) ?>:</strong>
                    <ul style="padding-left: 0; list-style: none;">
                        <?php if (!empty($comparison['only_shape2'])): ?>
                            <?php foreach ($comparison['only_shape2'] as $op): ?>
                                <li style="color: var(--text-regular); font-size: 0.88rem; margin-bottom: 4px; display: flex; align-items: flex-start; gap: 6px;">
                                    <span style="color: var(--accent-indigo);">•</span>
                                    <span><?= htmlspecialchars($op) ?></span>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li style="color: var(--text-muted); font-size: 0.88rem;">Không có đặc tính riêng trội hơn.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php
/**
 * ==============================================================================
 * GEOQUADRILATERAL - NHÓM 9
 * File: calculator.php - Công cụ Tính toán Chu vi & Diện tích trực quan với SVG động
 * ==============================================================================
 */

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/classes/GeometryModel.php';

$model = new GeometryModel();
$shapes = $model->getAllShapes();
$selectedShape = $_GET['shape'] ?? 'hinh_chu_nhat';
?>

<main class="main-wrapper">
    <div style="text-align: center; margin-bottom: 32px;">
        <span style="background: var(--primary-subtle); color: var(--primary); font-weight: 700; padding: 4px 14px; border-radius: 999px; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid #BFDBFE;">
            Interactive Geometry Engine
        </span>
        <h1 style="font-size: 2rem; font-weight: 800; margin: 12px 0 8px; color: var(--text-strong); letter-spacing: -0.5px;">
            Công Cụ Tính Chu Vi & Diện Tích Trực Quan
        </h1>
        <p style="color: var(--text-muted); font-size: 0.98rem; max-width: 600px; margin: 0 auto;">
            Nhập các thông số hình học để hệ thống tự động vẽ mô phỏng SVG và xuất các bước giải phương trình chi tiết.
        </p>
    </div>

    <div class="calc-container">
        <div class="calc-grid">
            <!-- Cột Trái: Form Nhập Liệu & Kết Quả -->
            <div>
                <div class="form-group">
                    <label class="form-label">Loại Hình Tứ Giác:</label>
                    <select id="calc-shape-select" class="form-control" style="font-weight: 700; color: var(--primary);">
                        <?php foreach ($shapes as $s): ?>
                            <option value="<?= htmlspecialchars($s['id']) ?>" <?= ($s['id'] === $selectedShape) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($s['name']) ?> (<?= htmlspecialchars($s['name_en'] ?? '') ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div id="calc-inputs-container" style="background: #F8FAFC; padding: 20px; border-radius: var(--radius-md); border: 1px solid var(--border-light); margin-bottom: 20px;">
                    <!-- Sẽ render tự động qua calculator.js -->
                </div>

                <!-- Hộp Kết Quả -->
                <div class="calc-result-box">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
                        <span style="font-weight:800;color:var(--text-strong);font-size:1.05rem;">Kết Quả Tính Toán</span>
                    </div>

                    <div class="result-metric">
                        <span style="font-weight:600;color:var(--text-muted);font-size:0.95rem;">Chu vi (P):</span>
                        <span id="res-perimeter" class="metric-value">-- cm</span>
                    </div>

                    <div class="result-metric">
                        <span style="font-weight:600;color:var(--text-muted);font-size:0.95rem;">Diện tích (S):</span>
                        <span id="res-area" class="metric-value" style="color:var(--accent-teal);">-- cm²</span>
                    </div>

                    <div style="margin-top:16px;background:white;padding:16px;border-radius:8px;border:1px solid var(--border-light);">
                        <div style="font-weight:700;color:var(--text-strong);margin-bottom:6px;font-size:0.88rem;text-transform:uppercase;">Các bước giải chi tiết:</div>
                        <div id="res-steps" style="color:var(--text-regular);font-size:0.92rem;">Đang tính toán...</div>
                    </div>
                </div>
            </div>

            <!-- Cột Phải: Hình Vẽ SVG Động Thay Đổi Theo Giá Trị -->
            <div style="background: #FAFCFE; border: 1px solid var(--border-light); border-radius: var(--radius-xl); padding: 24px; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 440px;">
                <div style="font-weight: 700; color: var(--primary); margin-bottom: 16px; font-size: 0.88rem; text-transform: uppercase; letter-spacing: 0.5px;">
                    Mô Phỏng Hình Học Thời Gian Thực (SVG)
                </div>
                
                <svg id="calc-dynamic-svg" viewBox="0 0 320 240" width="320" height="240" xmlns="http://www.w3.org/2000/svg">
                    <!-- Được vẽ tự động bằng JavaScript -->
                </svg>

                <p style="color: var(--text-muted); font-size: 0.85rem; margin-top: 18px;">
                    Hình vẽ tự động cập nhật tọa độ hình học tương ứng khi thay đổi thông số.
                </p>
            </div>
        </div>
    </div>
</main>

<script src="assets/js/calculator.js"></script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php
/**
 * ==============================================================================
 * GEOQUADRILATERAL - NHÓM 9
 * File: draw.php - Phòng Thí Nghiệm Vẽ Hình Tứ Giác & Nhận Diện Thời Gian Thực
 * ==============================================================================
 */

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/classes/GeometryModel.php';

$model = new GeometryModel();
$shapes = $model->getAllShapes();
?>

<main class="main-wrapper">
    <div style="text-align: center; margin-bottom: 28px;">
        <span style="background: var(--primary-subtle); color: var(--primary); font-weight: 700; padding: 4px 14px; border-radius: 999px; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid #BFDBFE;">
            Phòng Thí Nghiệm Hình Học Tương Tác
        </span>
        <h1 style="font-size: 2rem; font-weight: 800; margin: 12px 0 8px; color: var(--text-strong); letter-spacing: -0.5px;">
            Vẽ Tứ Giác & Nhận Diện Thông Minh
        </h1>
        <p style="color: var(--text-muted); font-size: 0.98rem; max-width: 720px; margin: 0 auto;">
            Dùng chuột kéo thả các đỉnh hoặc cạnh để thay đổi kích thước và góc. Hệ thống sẽ tự động đo đạc và phân tích xem hình hiện tại khớp với dạng hình tứ giác nào trong cơ sở dữ liệu Neo4j!
        </p>
    </div>

    <!-- Bộ Lọc Khối Lớp Học (Toán THCS) -->
    <div style="display: flex; justify-content: center; margin-bottom: 22px;">
        <div class="grade-filter-container" style="display: inline-flex; background: #F1F5F9; padding: 6px; border-radius: 999px; gap: 6px; flex-wrap: wrap; justify-content: center; border: 1px solid var(--border-light);">
            <button type="button" class="grade-filter-btn active" onclick="filterPresetsByGrade('all')">
                📚 Tất Cả Khối Lớp (9 mẫu)
            </button>
            <button type="button" class="grade-filter-btn" onclick="filterPresetsByGrade(6)">
                🎒 Toán Lớp 6 (HCN, Thoi, HBH, Thang Cân)
            </button>
            <button type="button" class="grade-filter-btn" onclick="filterPresetsByGrade(7)">
                📐 Toán Lớp 7 (Tứ giác, Thang, Thang Vuông)
            </button>
            <button type="button" class="grade-filter-btn" onclick="filterPresetsByGrade(8)">
                🎓 Toán Lớp 8 (Đầy đủ 9 hình)
            </button>
        </div>
    </div>

    <!-- Khu Vực Thao Tác Vẽ & Nhận Diện 2 Cột -->
    <div class="draw-workspace">
        <!-- Cột Trái: Bàn Vẽ Canvas & Thanh Công Cụ -->
        <div class="draw-canvas-card">
            <!-- Thanh Chọn Mẫu Hình Học Nhanh (Presets) -->
            <div class="draw-presets-bar">
                <span style="font-size: 0.82rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-right: 4px;">Mẫu chuẩn:</span>
                
                <button type="button" class="preset-chip active" data-preset="hinh_chu_nhat" data-grades="[6,8]" onclick="quadLabInstance.applyPreset('hinh_chu_nhat')">
                    🟩 Hình Chữ Nhật
                </button>
                <button type="button" class="preset-chip" data-preset="hinh_vuong" data-grades="[6,8]" onclick="quadLabInstance.applyPreset('hinh_vuong')">
                    🟦 Hình Vuông
                </button>
                <button type="button" class="preset-chip" data-preset="hinh_thoi" data-grades="[6,8]" onclick="quadLabInstance.applyPreset('hinh_thoi')">
                    💎 Hình Thoi
                </button>
                <button type="button" class="preset-chip" data-preset="hinh_binh_hanh" data-grades="[6,8]" onclick="quadLabInstance.applyPreset('hinh_binh_hanh')">
                    🔷 Hình Bình Hành
                </button>
                <button type="button" class="preset-chip" data-preset="hinh_thang_can" data-grades="[6,8]" onclick="quadLabInstance.applyPreset('hinh_thang_can')">
                    📐 Hình Thang Cân
                </button>
                <button type="button" class="preset-chip" data-preset="hinh_thang_vuong" data-grades="[7,8]" onclick="quadLabInstance.applyPreset('hinh_thang_vuong')">
                    📐 Hình Thang Vuông
                </button>
                <button type="button" class="preset-chip" data-preset="hinh_dieu" data-grades="[8]" onclick="quadLabInstance.applyPreset('hinh_dieu')">
                    🪁 Hình Diều
                </button>
                <button type="button" class="preset-chip" data-preset="tu_giac" data-grades="[7,8]" onclick="quadLabInstance.applyPreset('tu_giac')">
                    ✏️ Tứ Giác Bất Kỳ
                </button>
            </div>

            <!-- Vùng Canvas Vẽ Trực Quan -->
            <div class="draw-canvas-wrapper">
                <canvas id="quad-canvas"></canvas>
            </div>

            <!-- Thanh Tùy Chọn Bật / Tắt Hiển Thị Đo Đạc -->
            <div class="draw-toolbar-bottom">
                <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
                    <button type="button" id="btn-toggle-grid" class="draw-toggle-btn active" onclick="toggleOptionUI('showGrid', this)">
                        📐 Lưới Ô Ly
                    </button>
                    <button type="button" id="btn-toggle-snap" class="draw-toggle-btn" onclick="toggleOptionUI('gridSnap', this)">
                        🧲 Hút Dính Lưới
                    </button>
                    <button type="button" id="btn-toggle-lengths" class="draw-toggle-btn active" onclick="toggleOptionUI('showLengths', this)">
                        📏 Cạnh (cm)
                    </button>
                    <button type="button" id="btn-toggle-angles" class="draw-toggle-btn active" onclick="toggleOptionUI('showAngles', this)">
                        📐 Góc (°)
                    </button>
                    <button type="button" id="btn-toggle-diagonals" class="draw-toggle-btn active" onclick="toggleOptionUI('showDiagonals', this)">
                        ❌ Đường Chéo
                    </button>
                </div>
                <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                    <span style="background: #EFF6FF; color: #1E40AF; font-size: 0.8rem; font-weight: 700; padding: 5px 12px; border-radius: 6px; border: 1px solid #BFDBFE; display: inline-flex; align-items: center; gap: 6px;">
                        ⌨️ Giữ phím <kbd style="background:white;padding:1px 6px;border-radius:4px;border:1px solid #93C5FD;box-shadow:0 1px 2px rgba(0,0,0,0.06);">Shift</kbd> để khóa Song Song, Vuông Góc (90°) & Trùng đường ô ly chính
                    </span>
                    <button type="button" class="btn btn-sm btn-secondary" onclick="quadLabInstance.applyPreset('tu_giac')">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>
                        Đặt lại vị trí
                    </button>
                </div>
            </div>
        </div>

        <!-- Cột Phải: Bảng Nhận Diện Thời Gian Thực & Cơ Sở Tri Thức Neo4j -->
        <div class="draw-sidebar">
            <!-- Hộp Kết Quả Nhận Diện Tức Thì -->
            <div id="detected-box" class="detected-header-box" style="background: #ECFDF5; border: 1px solid #6EE7B7;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                    <span id="detected-badge" style="background: #10B981; color: white; font-weight: 700; font-size: 0.75rem; padding: 2px 10px; border-radius: 999px; text-transform: uppercase;">
                        Cấp độ 4 - Đặc biệt
                    </span>
                    <span id="detected-grade" style="font-size: 0.76rem; font-weight: 700; color: #475569;">
                        Toán Lớp 6 & Lớp 8
                    </span>
                </div>
                <div id="detected-title" class="detected-title" style="color: #10B981;">
                    <span>Hình Chữ Nhật</span>
                </div>
                <p id="detected-desc" style="font-size: 0.85rem; color: #334155; margin: 8px 0 0; line-height: 1.5;">
                    Đang tính toán các thông số hình học...
                </p>
            </div>

            <!-- Bảng Đo Đạc Số Liệu Thời Gian Thực -->
            <div style="margin-bottom: 20px;">
                <span style="font-weight: 800; color: var(--text-strong); font-size: 0.88rem; text-transform: uppercase; display: block; margin-bottom: 10px; letter-spacing: 0.3px;">
                    📊 Số Liệu Đo Đạc Thời Gian Thực
                </span>
                
                <div class="metric-grid-2x2">
                    <div class="metric-cell">
                        <div class="metric-cell-label">Chu vi (P)</div>
                        <div id="metric-perimeter" class="metric-cell-value">-- cm</div>
                    </div>
                    <div class="metric-cell">
                        <div class="metric-cell-label">Diện tích (S)</div>
                        <div id="metric-area" class="metric-cell-value" style="color: var(--primary);">-- cm²</div>
                    </div>
                </div>

                <div style="background: #F8FAFC; border: 1px solid var(--border-light); border-radius: 8px; padding: 10px 12px; margin-bottom: 8px; font-size: 0.84rem;">
                    <div style="font-weight: 700; color: var(--text-muted); font-size: 0.74rem; text-transform: uppercase; margin-bottom: 3px;">Độ dài 4 cạnh (cm)</div>
                    <div id="metric-edges" style="font-family: 'JetBrains Mono', monospace; font-weight: 700; color: var(--text-strong);">--</div>
                </div>

                <div style="background: #F8FAFC; border: 1px solid var(--border-light); border-radius: 8px; padding: 10px 12px; font-size: 0.84rem;">
                    <div style="font-weight: 700; color: var(--text-muted); font-size: 0.74rem; text-transform: uppercase; margin-bottom: 3px;">Số đo 4 góc nội tiếp (°)</div>
                    <div id="metric-angles" style="font-family: 'JetBrains Mono', monospace; font-weight: 700; color: var(--text-strong);">--</div>
                </div>
            </div>

            <!-- Danh Sách Điều Kiện Toán Học (Checklist) -->
            <div>
                <span style="font-weight: 800; color: var(--text-strong); font-size: 0.88rem; text-transform: uppercase; display: block; margin-bottom: 10px; letter-spacing: 0.3px;">
                    🎯 Dấu Hiệu Nhận Biết Tương Ứng
                </span>
                <ul id="condition-checklist" class="condition-checklist">
                    <!-- Sẽ render động qua draw-engine.js -->
                </ul>
            </div>

            <!-- Nút Hành Động Liên Kết Tri Thức Neo4j -->
            <div style="display: flex; gap: 10px; margin-top: 18px;">
                <a href="index.php" class="btn btn-sm btn-secondary" style="flex: 1; justify-content: center;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                    Xem Đồ Thị Neo4j
                </a>
                <a href="calculator.php" class="btn btn-sm btn-primary" style="flex: 1; justify-content: center;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="2" width="16" height="20" rx="2"/><line x1="8" y1="6" x2="16" y2="6"/><line x1="16" y1="14" x2="16" y2="14"/><line x1="8" y1="14" x2="8" y2="14"/></svg>
                    Công Cụ Tính
                </a>
            </div>
        </div>
    </div>
</main>

<script src="assets/js/draw-engine.js"></script>

<script>
function toggleOptionUI(optName, btn) {
    if (quadLabInstance) {
        const state = quadLabInstance.toggleOption(optName);
        btn.classList.toggle('active', state);
    }
}

function filterPresetsByGrade(grade) {
    document.querySelectorAll('.grade-filter-container .grade-filter-btn').forEach(btn => {
        btn.classList.toggle('active', btn.innerText.includes(grade.toString()) || (grade === 'all' && btn.innerText.includes('Tất Cả')));
    });

    const chips = document.querySelectorAll('.preset-chip');
    chips.forEach(chip => {
        const grades = JSON.parse(chip.getAttribute('data-grades') || '[]');
        if (grade === 'all' || grades.includes(Number(grade))) {
            chip.style.display = 'inline-flex';
        } else {
            chip.style.display = 'none';
        }
    });
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

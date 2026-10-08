<?php
/**
 * ==============================================================================
 * GEOQUADRILATERAL - NHÓM 9
 * File: neo4j_console.php - Bảng điều khiển Quản trị & Thực thi Cypher Neo4j
 * ==============================================================================
 */

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/classes/Neo4jService.php';

$neo4j = Neo4jService::getInstance();
$pingRes = $neo4j->ping();
?>

<main class="main-wrapper">
    <div style="text-align: center; margin-bottom: 32px;">
        <span style="background: var(--primary-subtle); color: var(--primary); font-weight: 700; padding: 4px 14px; border-radius: 999px; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid #BFDBFE;">
            Neo4j Database Administration
        </span>
        <h1 style="font-size: 2rem; font-weight: 800; margin: 12px 0 8px; color: var(--text-strong); letter-spacing: -0.5px;">
            Trung Tâm Quản Trị & Thực Thi Cypher Neo4j
        </h1>
        <p style="color: var(--text-muted); font-size: 0.98rem; max-width: 650px; margin: 0 auto;">
            Kiểm tra trạng thái máy chủ cơ sở dữ liệu đồ thị, nạp tự động kịch bản Cypher hoặc thực thi các câu truy vấn đồ thị tùy biến.
        </p>
    </div>

    <!-- Khối Trạng Thái Kết Nối -->
    <div style="background: white; border-radius: var(--radius-lg); padding: 28px; border: 1px solid var(--border-light); margin-bottom: 32px; box-shadow: var(--shadow-xs);">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 20px;">
            <div>
                <h3 style="font-weight: 800; color: var(--text-strong); font-size: 1.15rem; margin-bottom: 4px;">Trạng Thái Kết Nối Máy Chủ Neo4j</h3>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin: 0; font-family: 'JetBrains Mono', monospace;">
                    Host: <?= NEO4J_HOST ?>:<?= NEO4J_HTTP_PORT ?> (Bolt: <?= NEO4J_BOLT_PORT ?>) | Database: <?= NEO4J_DATABASE ?> | User: <?= NEO4J_USER ?>
                </p>
            </div>
            <div>
                <?php if ($pingRes['success']): ?>
                    <span style="background: #DCFCE7; color: #15803D; font-weight: 700; padding: 6px 16px; border-radius: 999px; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 8px; border: 1px solid #86EFAC;">
                        <span class="status-dot status-live"></span> Đang Kết Nối Hoạt Động (Live)
                    </span>
                <?php else: ?>
                    <span style="background: #FEF3C7; color: #B45309; font-weight: 700; padding: 6px 16px; border-radius: 999px; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 8px; border: 1px solid #FDE68A;">
                        <span class="status-dot status-mock"></span> Máy Chủ Offline (Chế độ Dự Phòng)
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <div style="background: #F8FAFC; border-radius: 8px; padding: 14px 18px; border: 1px solid var(--border-light); margin-bottom: 20px; font-size: 0.9rem; color: var(--text-regular);">
            <strong>Phản hồi từ hệ thống:</strong> <?= htmlspecialchars($pingRes['message']) ?>
            <?php if (!$pingRes['success']): ?>
                <div style="margin-top: 6px; font-size: 0.85rem; color: var(--text-muted);">
                    Khởi động DBMS trong Neo4j Desktop với mật khẩu được thiết lập trong <code>config/config.php</code>. Khi máy chủ bật, hệ thống sẽ tự động chuyển sang chế độ Live.
                </div>
            <?php endif; ?>
        </div>

        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
            <button id="btn-ping" class="btn btn-secondary">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>
                Kiểm Tra Lại Kết Nối
            </button>
            <button id="btn-import-seed" class="btn btn-primary">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Nạp Dữ Liệu Tứ Giác Vào Neo4j (1-Click Seed)
            </button>
        </div>
        <div id="seed-status" style="margin-top: 14px; font-weight: 700; font-size: 0.92rem; display: none;"></div>
    </div>

    <!-- Khối Thực Thi Cypher Console Trực Tiếp -->
    <div style="background: white; border-radius: var(--radius-lg); padding: 28px; border: 1px solid var(--border-light); margin-bottom: 32px; box-shadow: var(--shadow-xs);">
        <h3 style="font-weight: 800; color: var(--text-strong); font-size: 1.15rem; margin-bottom: 6px;">Cypher Query Console</h3>
        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 16px;">
            Nhập câu truy vấn Cypher để thực thi và xem kết quả JSON trả về:
        </p>

        <div style="margin-bottom: 14px;">
            <textarea id="cypher-input" class="form-control" rows="4" style="font-family: 'JetBrains Mono', monospace; font-size: 0.92rem; background: #0F172A; color: #38BDF8; border-color: #334155;">MATCH (s:Shape) RETURN s.name, s.alias, s.badge LIMIT 10;</textarea>
        </div>

        <div style="display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap;">
            <button id="btn-exec-cypher" class="btn btn-primary">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                Thực Thi Cypher
            </button>
            <button onclick="setSampleCypher(1)" class="btn btn-sm btn-secondary">
                Mẫu 1: Xem cây phả hệ
            </button>
            <button onclick="setSampleCypher(2)" class="btn btn-sm btn-secondary">
                Mẫu 2: Shortest Path chuyển hóa
            </button>
            <button onclick="setSampleCypher(3)" class="btn btn-sm btn-secondary">
                Mẫu 3: Lấy thuộc tính Hình chữ nhật
            </button>
        </div>

        <div id="cypher-result-container" style="background: #F8FAFC; border-radius: 8px; padding: 18px; border: 1px solid var(--border-light); display: none;">
            <span style="font-weight: 700; margin-bottom: 8px; color: var(--text-strong); display: block; font-size: 0.85rem; text-transform: uppercase;">Dữ liệu phản hồi (JSON Result):</span>
            <pre id="cypher-result-raw" style="font-family: 'JetBrains Mono', monospace; font-size: 0.82rem; max-height: 350px; overflow-y: auto; background: white; padding: 14px; border-radius: 6px; border: 1px solid var(--border-light); color: var(--text-strong);"></pre>
        </div>
    </div>
</main>

<script>
document.getElementById('btn-ping').addEventListener('click', async () => {
    location.reload();
});

document.getElementById('btn-import-seed').addEventListener('click', async () => {
    const statusBox = document.getElementById('seed-status');
    statusBox.style.display = 'block';
    statusBox.style.color = 'var(--primary)';
    statusBox.innerText = 'Đang tiến hành nạp các node và relationship vào Neo4j...';

    try {
        const res = await fetch('api.php?action=import_cypher');
        const json = await res.json();
        if (json.success) {
            statusBox.style.color = '#059669';
            statusBox.innerHTML = '✔ ' + json.message;
            SoundEffects.celebrate();
            triggerConfetti();
        } else {
            statusBox.style.color = '#DC2626';
            statusBox.innerHTML = '✖ ' + (json.message || 'Lỗi khi nạp dữ liệu vào Neo4j!');
            SoundEffects.wrong();
        }
    } catch (e) {
        statusBox.style.color = '#DC2626';
        statusBox.innerText = '✖ Lỗi kết nối tới máy chủ!';
    }
});

document.getElementById('btn-exec-cypher').addEventListener('click', async () => {
    const query = document.getElementById('cypher-input').value;
    const resBox = document.getElementById('cypher-result-container');
    const rawBox = document.getElementById('cypher-result-raw');

    resBox.style.display = 'block';
    rawBox.innerText = 'Đang thực thi trên Neo4j...';

    try {
        const formData = new FormData();
        formData.append('action', 'run_cypher');
        formData.append('cypher', query);

        const res = await fetch('api.php', { method: 'POST', body: formData });
        const json = await res.json();

        rawBox.innerText = JSON.stringify(json, null, 2);
    } catch (e) {
        rawBox.innerText = 'Lỗi thực thi: ' + e;
    }
});

function setSampleCypher(type) {
    const inp = document.getElementById('cypher-input');
    if (type === 1) {
        inp.value = "MATCH (n:Shape)-[r:EVOLVES_TO]->(m:Shape) RETURN n.name AS HinhGoc, r.condition AS DieuKien, m.name AS HinhSau;";
    } else if (type === 2) {
        inp.value = "MATCH (start:Shape {id: 'hinh_binh_hanh'}), (target:Shape {id: 'hinh_vuong'})\nMATCH p = shortestPath((start)-[:EVOLVES_TO*]->(target))\nRETURN p;";
    } else if (type === 3) {
        inp.value = "MATCH (s:Shape {id: 'hinh_chu_nhat'})-[:HAS_PROPERTY]->(p:Property)\nRETURN s.name, collect(p.name) AS DanhSachDacTinh;";
    }
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

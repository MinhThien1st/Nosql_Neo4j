/**
 * ==============================================================================
 * GEOQUADRILATERAL - NHÓM 9
 * File: assets/js/transformer.js - Phân tích đường đi chuyển hóa hình học Neo4j
 * ==============================================================================
 */

document.addEventListener('DOMContentLoaded', () => {
    const btnTransform = document.getElementById('btn-do-transform');
    if (!btnTransform) return;

    btnTransform.addEventListener('click', async () => {
        const start = document.getElementById('select-start-shape').value;
        const target = document.getElementById('select-target-shape').value;
        const resultContainer = document.getElementById('transformer-result');

        if (start === target) {
            resultContainer.innerHTML = `
                <div style="background:#FEF2F2;border:1px solid #FECACA;color:#991B1B;padding:16px;border-radius:10px;text-align:center;font-weight:600;font-size:0.92rem;">
                    Vui lòng chọn 2 hình khác nhau để phân tích quá trình chuyển hóa.
                </div>
            `;
            SoundEffects.wrong();
            return;
        }

        resultContainer.innerHTML = `
            <div style="text-align:center;padding:36px;color:#2563EB;font-weight:700;">
                Đang thực thi thuật toán Graph Traversal của Neo4j...
            </div>
        `;

        try {
            const res = await fetch(`api.php?action=transform&start=${start}&target=${target}`);
            const json = await res.json();

            if (!json.success) {
                resultContainer.innerHTML = `
                    <div style="background:#FFFBEB;border:1px solid #FDE68A;color:#92400E;padding:22px;border-radius:12px;text-align:center;">
                        <h4 style="font-weight:700;margin-bottom:6px;font-size:1.05rem;">Không tìm thấy đường chuyển hóa trực tiếp</h4>
                        <p style="font-size:0.9rem;color:#78350F;">${json.message || 'Không tồn tại đường đi trực tiếp một chiều giữa hai hình này theo sơ đồ phân cấp.'}</p>
                    </div>
                `;
                SoundEffects.wrong();
                return;
            }

            SoundEffects.correct();

            const nodes = json.nodes;
            const rels = json.relationships;
            let stepsHtml = '';

            for (let i = 0; i < rels.length; i++) {
                const fromNode = nodes[i];
                const toNode = nodes[i + 1];
                const cond = rels[i];

                stepsHtml += `
                    <div class="timeline-step">
                        <div class="step-number">${i + 1}</div>
                        <div style="flex:1;">
                            <div style="display:flex;align-items:center;gap:12px;margin-bottom:6px;">
                                <span style="font-weight:800;font-size:1.05rem;color:${fromNode.color || '#2563EB'};">${fromNode.name}</span>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                                <span style="font-weight:800;font-size:1.05rem;color:${toNode.color || '#DB2777'};">${toNode.name}</span>
                            </div>
                            <div style="background:#F8FAFC;border-radius:8px;padding:10px 14px;border:1px solid #E2E8F0;font-size:0.92rem;color:#334155;">
                                <strong style="color:#0F172A;">Điều kiện bổ sung:</strong> <span style="color:#2563EB;font-weight:700;">${cond.condition}</span>
                            </div>
                        </div>
                    </div>
                `;
            }

            resultContainer.innerHTML = `
                <div style="background:#F0FDF4;border:1px solid #BBF7D0;border-radius:12px;padding:16px 20px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
                    <div>
                        <h4 style="color:#166534;font-weight:800;font-size:1.05rem;margin-bottom:2px;">Đã tìm thấy lộ trình chuyển hóa tối ưu</h4>
                        <p style="color:#15803D;font-size:0.88rem;margin:0;">Lộ trình gồm <strong>${json.steps_count} bước chuyển hóa</strong> được trích xuất từ đồ thị Neo4j.</p>
                    </div>
                    <span style="background:#DCFCE7;color:#15803D;font-weight:700;padding:4px 12px;border-radius:999px;font-size:0.8rem;border:1px solid #86EFAC;">
                        Neo4j ShortestPath
                    </span>
                </div>
                <div class="timeline-path">
                    ${stepsHtml}
                </div>
            `;
        } catch (e) {
            console.error(e);
            resultContainer.innerHTML = '<div style="color:#DC2626;text-align:center;">Lỗi kết nối máy chủ!</div>';
        }
    });
});

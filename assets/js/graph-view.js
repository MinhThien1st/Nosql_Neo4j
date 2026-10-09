/**
 * ==============================================================================
 * GEOQUADRILATERAL - NHÓM 9
 * File: assets/js/graph-view.js - Vẽ và tương tác Đồ thị cây phả hệ Neo4j
 * ==============================================================================
 */

let networkInstance = null;
let nodesDataSet = null;
let rawNodesCache = [];

document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('cy-network');
    if (!container) return;

    loadNeo4jGraph(container);

    const fitBtn = document.getElementById('btn-fit-graph');
    if (fitBtn) {
        fitBtn.addEventListener('click', () => {
            if (networkInstance) {
                networkInstance.fit({ animation: { duration: 500, easingFunction: 'easeInOutQuad' } });
            }
        });
    }

    const resetBtn = document.getElementById('btn-reset-graph');
    if (resetBtn) {
        resetBtn.addEventListener('click', () => {
            loadNeo4jGraph(container);
        });
    }
});

async function loadNeo4jGraph(container) {
    container.innerHTML = '<div style="display:flex;height:100%;align-items:center;justify-content:center;color:#64748B;font-weight:600;">Đang tải cấu trúc đồ thị Neo4j...</div>';

    try {
        const response = await fetch('api.php?action=get_graph');
        const res = await response.json();

        if (!res.success || !res.data) {
            container.innerHTML = '<div style="color:#DC2626;padding:30px;font-weight:600;">Không thể tải dữ liệu đồ thị!</div>';
            return;
        }

        renderVisGraph(container, res.data);
    } catch (e) {
        console.error(e);
        container.innerHTML = '<div style="color:#DC2626;padding:30px;font-weight:600;">Lỗi kết nối API!</div>';
    }
}

function wrapText(text, maxLen = 28) {
    if (!text || text.length <= maxLen) return text;
    const words = text.split(' ');
    let lines = [];
    let currentLine = '';

    words.forEach(w => {
        if ((currentLine + ' ' + w).trim().length <= maxLen) {
            currentLine = (currentLine + ' ' + w).trim();
        } else {
            if (currentLine) lines.push(currentLine);
            currentLine = w;
        }
    });
    if (currentLine) lines.push(currentLine);
    return lines.join('\n');
}

function renderVisGraph(container, graphData) {
    container.innerHTML = '';

    // Cấu hình phân cấp cây phả hệ chuẩn theo mô hình của giảng viên
    const levelMap = {
        'hinh_hoc_phang': 0,
        'tu_giac': 1,
        'hinh_thang': 2,
        'hinh_dieu': 2,
        'hinh_binh_hanh': 2,
        'hinh_thang_vuong': 3,
        'hinh_thang_can': 3,
        'hinh_chu_nhat': 3,
        'hinh_thoi': 3,
        'hinh_vuong': 4
    };

    // Bảng màu hiện đại, trang nhã cho từng node
    const colorPalette = {
        'hinh_hoc_phang': { bg: '#1E293B', border: '#0F172A', text: '#FFFFFF' },
        'tu_giac': { bg: '#0284C7', border: '#0369A1', text: '#FFFFFF' },
        'hinh_thang': { bg: '#0EA5E9', border: '#0284C7', text: '#FFFFFF' },
        'hinh_dieu': { bg: '#F97316', border: '#EA580C', text: '#FFFFFF' },
        'hinh_binh_hanh': { bg: '#2563EB', border: '#1D4ED8', text: '#FFFFFF' },
        'hinh_chu_nhat': { bg: '#059669', border: '#047857', text: '#FFFFFF' },
        'hinh_thoi': { bg: '#0D9488', border: '#0F766E', text: '#FFFFFF' },
        'hinh_vuong': { bg: '#DB2777', border: '#BE185D', text: '#FFFFFF' }
    };

    const formattedNodes = graphData.nodes.map(n => {
        const lvl = levelMap[n.id] !== undefined ? levelMap[n.id] : 2;
        const clr = colorPalette[n.id] || { bg: '#475569', border: '#334155', text: '#FFFFFF' };

        return {
            id: n.id,
            label: n.label.replace('📐', '').replace('★', '').trim(),
            level: lvl,
            shape: 'box',
            margin: { top: 12, bottom: 12, left: 18, right: 18 },
            shapeProperties: {
                borderRadius: 8
            },
            color: {
                background: clr.bg,
                border: clr.border,
                highlight: {
                    background: '#4F46E5',
                    border: '#3730A3'
                }
            },
            font: {
                color: clr.text,
                face: 'Plus Jakarta Sans',
                size: 13,
                bold: true
            },
            shadow: {
                enabled: true,
                color: 'rgba(15, 23, 42, 0.12)',
                size: 6,
                x: 0,
                y: 3
            }
        };
    });

    const formattedEdges = graphData.edges.map(e => ({
        from: e.from,
        to: e.to,
        label: wrapText(e.label || '', 24),
        arrows: {
            to: { enabled: true, scaleFactor: 0.85 }
        },
        color: {
            color: '#94A3B8',
            highlight: '#4F46E5'
        },
        font: {
            color: '#1E293B',
            face: 'Plus Jakarta Sans',
            size: 11,
            bold: true,
            background: '#FFFFFF', // Tạo nền trắng che đường kẻ để chữ rõ ràng, không bị chồng đè
            strokeWidth: 0,
            align: 'horizontal'     // Giữ chữ luôn nằm ngang chuẩn mực, không quay chéo
        },
        smooth: {
            type: 'cubicBezier',
            forceDirection: 'vertical',
            roundness: 0.25
        }
    }));

    rawNodesCache = JSON.parse(JSON.stringify(formattedNodes));
    nodesDataSet = new vis.DataSet(formattedNodes);

    const data = {
        nodes: nodesDataSet,
        edges: new vis.DataSet(formattedEdges)
    };

    const options = {
        layout: {
            hierarchical: {
                direction: 'UD', // Up to Down
                sortMethod: 'directed',
                nodeSpacing: 250,       // Tăng khoảng cách ngang để các nhãn không va chạm
                levelSeparation: 155,   // Tăng khoảng cách tầng dọc để đường mũi tên thoáng đãng
                treeSpacing: 260
            }
        },
        interaction: {
            hover: true,
            tooltipDelay: 100,
            zoomView: true,
            dragView: true
        },
        physics: false
    };

    networkInstance = new vis.Network(container, data, options);

    // Bắt sự kiện click vào node
    networkInstance.on('click', function (params) {
        if (params.nodes.length > 0) {
            const nodeId = params.nodes[0];
            SoundEffects.click();
            loadShapeDetailSidebar(nodeId);
        }
    });
}

async function loadShapeDetailSidebar(shapeId) {
    const sidebar = document.getElementById('graph-sidebar');
    if (!sidebar) return;

    if (shapeId === 'hinh_hoc_phang') {
        sidebar.innerHTML = `
            <div style="text-align:center;padding:24px 0;">
                <div style="width:48px;height:48px;background:#F1F5F9;border-radius:10px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;color:#1E293B;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 3 18 18M3 21h18"/></svg>
                </div>
                <h3 style="font-weight:800;margin-bottom:8px;color:#0F172A;font-size:1.25rem;">Hình Học Phẳng</h3>
                <p style="color:#64748B;font-size:0.9rem;line-height:1.6;">
                    Không gian 2 chiều (2D) là nền tảng khởi nguồn cho mọi đa giác và tứ giác trong hình học Euclide.
                </p>
            </div>
        `;
        return;
    }

    sidebar.innerHTML = '<div style="text-align:center;padding:40px 0;color:#64748B;font-weight:600;">Đang nạp dữ liệu chi tiết...</div>';

    try {
        const response = await fetch(`api.php?action=get_shape&id=${shapeId}`);
        const res = await response.json();

        if (!res.success || !res.data) {
            sidebar.innerHTML = '<div style="color:#DC2626;">Không tìm thấy dữ liệu hình!</div>';
            return;
        }

        const shape = res.data;
        const formula = shape.formula || {};
        const properties = shape.properties || [];

        let propsHtml = '';
        properties.forEach(p => {
            propsHtml += `
                <li style="margin-bottom:8px;font-size:0.88rem;color:#334155;display:flex;align-items:flex-start;gap:8px;">
                    <span style="color:#2563EB;margin-top:2px;">•</span>
                    <span>${p.name}</span>
                </li>
            `;
        });

        sidebar.innerHTML = `
            <div class="sidebar-detail-content">
                <div style="display:flex;align-items:center;margin-bottom:14px;">
                    <span style="background:${shape.color || '#2563EB'};color:white;font-weight:700;padding:3px 10px;border-radius:999px;font-size:0.75rem;text-transform:uppercase;">
                        ${shape.badge || 'Tứ giác'}
                    </span>
                </div>

                <h3 style="font-size:1.35rem;font-weight:800;margin-bottom:2px;color:#0F172A;">${shape.name}</h3>
                <div style="font-size:0.82rem;color:#64748B;font-weight:600;margin-bottom:14px;">${shape.name_en || ''}</div>

                <div style="background:#F8FAFC;border-radius:10px;padding:14px;border:1px solid #E2E8F0;margin-bottom:16px;">
                    <span style="color:#2563EB;display:block;font-size:0.8rem;font-weight:700;margin-bottom:4px;text-transform:uppercase;">Định nghĩa toán học</span>
                    <p style="font-size:0.9rem;color:#1E293B;line-height:1.5;">${shape.definition}</p>
                </div>

                <div style="margin-bottom:18px;">
                    <span style="font-size:0.85rem;font-weight:700;color:#0F172A;display:block;margin-bottom:8px;text-transform:uppercase;letter-spacing:0.3px;">Đặc điểm nhận biết</span>
                    <ul style="padding-left:0;list-style:none;margin:0;">
                        ${propsHtml || '<li style="font-size:0.85rem;color:#64748B;">Đang cập nhật...</li>'}
                    </ul>
                </div>

                <div style="background:#F0FDF4;border-radius:10px;padding:14px;margin-bottom:20px;border:1px solid #BBF7D0;">
                    <span style="color:#166534;display:block;font-size:0.8rem;font-weight:700;margin-bottom:6px;text-transform:uppercase;">Công thức</span>
                    <div style="font-family:'JetBrains Mono', monospace;font-weight:700;color:#14532D;font-size:0.85rem;line-height:1.6;">
                        Chu vi: ${formula.perimeter_formula || 'P = a + b + c + d'}<br>
                        Diện tích: ${formula.area_formula || 'S = ...'}
                    </div>
                </div>

                <div>
                    <a href="calculator.php?shape=${shape.id}" class="btn btn-primary" style="width:100%;text-align:center;padding:10px;text-decoration:none;font-weight:700;font-size:0.88rem;">
                        Tính chu vi & diện tích
                    </a>
                </div>
            </div>
        `;
    } catch (e) {
        console.error(e);
        sidebar.innerHTML = '<div style="color:#DC2626;">Lỗi tải chi tiết!</div>';
    }
}

// Lọc và làm nổi bật các node theo khối lớp học (Toán 6, 7, 8)
window.filterGraphByGrade = function(grade, btn) {
    if (!nodesDataSet || !rawNodesCache.length) return;

    // Cập nhật trạng thái nút
    document.querySelectorAll('#filter-node-all, #filter-node-6, #filter-node-7, #filter-node-8').forEach(b => {
        b.classList.remove('active');
    });
    if (btn) btn.classList.add('active');

    const gradeMap = {
        6: ['hinh_chu_nhat', 'hinh_thoi', 'hinh_binh_hanh', 'hinh_thang_can'],
        7: ['tu_giac', 'hinh_thang', 'hinh_thang_vuong'],
        8: ['tu_giac', 'hinh_thang', 'hinh_thang_vuong', 'hinh_thang_can', 'hinh_binh_hanh', 'hinh_chu_nhat', 'hinh_thoi', 'hinh_vuong', 'hinh_dieu']
    };

    const targetIds = (grade === 'all') ? null : (gradeMap[grade] || []);

    const updates = rawNodesCache.map(origNode => {
        if (!targetIds) {
            // Khôi phục tất cả
            return {
                id: origNode.id,
                opacity: 1,
                borderWidth: origNode.borderWidth || 2,
                color: origNode.color
            };
        }

        const isMatch = targetIds.includes(origNode.id);
        if (isMatch) {
            return {
                id: origNode.id,
                opacity: 1,
                borderWidth: 4,
                color: {
                    border: '#2563EB',
                    background: origNode.color.background,
                    highlight: origNode.color.highlight
                }
            };
        } else {
            return {
                id: origNode.id,
                opacity: 0.25,
                borderWidth: 1
            };
        }
    });

    nodesDataSet.update(updates);

    if (networkInstance && targetIds && targetIds.length) {
        networkInstance.fit({
            nodes: targetIds,
            animation: { duration: 600, easingFunction: 'easeInOutQuad' }
        });
    } else if (networkInstance) {
        networkInstance.fit({ animation: { duration: 500, easingFunction: 'easeInOutQuad' } });
    }
};

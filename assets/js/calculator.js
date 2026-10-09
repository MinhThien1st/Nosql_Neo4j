/**
 * ==============================================================================
 * ĐỒ ÁN MÔN NOSQL (NEO4J) - NHÓM 9: BÉ HỌC TỨ GIÁC
 * File: assets/js/calculator.js - Máy tính Chu vi & Diện tích kèm SVG động
 * ==============================================================================
 */

document.addEventListener('DOMContentLoaded', () => {
    const shapeSelect = document.getElementById('calc-shape-select');
    if (!shapeSelect) return;

    shapeSelect.addEventListener('change', () => {
        updateCalcFields();
        calculateAndRender();
    });

    // Bắt sự kiện thay đổi giá trị trong các input
    const inputsContainer = document.getElementById('calc-inputs-container');
    if (inputsContainer) {
        inputsContainer.addEventListener('input', () => {
            calculateAndRender();
        });
    }

    updateCalcFields();
    calculateAndRender();
});

function updateCalcFields() {
    const shape = document.getElementById('calc-shape-select').value;
    const container = document.getElementById('calc-inputs-container');
    if (!container) return;

    let html = '';

    switch (shape) {
        case 'hinh_vuong':
            html = `
                <div class="form-group">
                    <label class="form-label">Độ dài cạnh (a):</label>
                    <input type="number" id="inp-a" class="form-control" value="6" min="1" step="0.5">
                </div>
            `;
            break;

        case 'hinh_chu_nhat':
            html = `
                <div class="form-group">
                    <label class="form-label">Chiều dài (a):</label>
                    <input type="number" id="inp-a" class="form-control" value="8" min="1" step="0.5">
                </div>
                <div class="form-group">
                    <label class="form-label">Chiều rộng (b):</label>
                    <input type="number" id="inp-b" class="form-control" value="5" min="1" step="0.5">
                </div>
            `;
            break;

        case 'hinh_binh_hanh':
            html = `
                <div class="form-group">
                    <label class="form-label">Cạnh đáy (a):</label>
                    <input type="number" id="inp-a" class="form-control" value="7" min="1" step="0.5">
                </div>
                <div class="form-group">
                    <label class="form-label">Cạnh bên (b):</label>
                    <input type="number" id="inp-b" class="form-control" value="5" min="1" step="0.5">
                </div>
                <div class="form-group">
                    <label class="form-label">Chiều cao (h):</label>
                    <input type="number" id="inp-h" class="form-control" value="4" min="1" step="0.5">
                </div>
            `;
            break;

        case 'hinh_thoi':
            html = `
                <div class="form-group">
                    <label class="form-label">Độ dài cạnh (a):</label>
                    <input type="number" id="inp-a" class="form-control" value="5" min="1" step="0.5">
                </div>
                <div class="form-group">
                    <label class="form-label">Đường chéo 1 (d₁):</label>
                    <input type="number" id="inp-d1" class="form-control" value="8" min="1" step="0.5">
                </div>
                <div class="form-group">
                    <label class="form-label">Đường chéo 2 (d₂):</label>
                    <input type="number" id="inp-d2" class="form-control" value="6" min="1" step="0.5">
                </div>
            `;
            break;

        case 'hinh_thang':
        case 'hinh_thang_vuong':
        case 'hinh_thang_can':
            html = `
                <div class="form-group">
                    <label class="form-label">Đáy lớn (a):</label>
                    <input type="number" id="inp-a" class="form-control" value="9" min="1" step="0.5">
                </div>
                <div class="form-group">
                    <label class="form-label">Đáy bé (b):</label>
                    <input type="number" id="inp-b" class="form-control" value="5" min="1" step="0.5">
                </div>
                <div class="form-group">
                    <label class="form-label">Chiều cao (h):</label>
                    <input type="number" id="inp-h" class="form-control" value="4" min="1" step="0.5">
                </div>
                <div class="form-group">
                    <label class="form-label">Cạnh bên trái (c):</label>
                    <input type="number" id="inp-c" class="form-control" value="4.5" min="1" step="0.5">
                </div>
                <div class="form-group">
                    <label class="form-label">Cạnh bên phải (d):</label>
                    <input type="number" id="inp-d" class="form-control" value="4.5" min="1" step="0.5">
                </div>
            `;
            break;

        case 'hinh_dieu':
            html = `
                <div class="form-group">
                    <label class="form-label">Cạnh ngắn (a):</label>
                    <input type="number" id="inp-a" class="form-control" value="4" min="1" step="0.5">
                </div>
                <div class="form-group">
                    <label class="form-label">Cạnh dài (b):</label>
                    <input type="number" id="inp-b" class="form-control" value="7" min="1" step="0.5">
                </div>
                <div class="form-group">
                    <label class="form-label">Đường chéo ngang (d₁):</label>
                    <input type="number" id="inp-d1" class="form-control" value="6" min="1" step="0.5">
                </div>
                <div class="form-group">
                    <label class="form-label">Đường chéo dọc (d₂):</label>
                    <input type="number" id="inp-d2" class="form-control" value="8" min="1" step="0.5">
                </div>
            `;
            break;

        default:
            html = `
                <div class="form-group">
                    <label class="form-label">Cạnh a:</label>
                    <input type="number" id="inp-a" class="form-control" value="5">
                </div>
                <div class="form-group">
                    <label class="form-label">Cạnh b:</label>
                    <input type="number" id="inp-b" class="form-control" value="6">
                </div>
                <div class="form-group">
                    <label class="form-label">Cạnh c:</label>
                    <input type="number" id="inp-c" class="form-control" value="7">
                </div>
                <div class="form-group">
                    <label class="form-label">Cạnh d:</label>
                    <input type="number" id="inp-d" class="form-control" value="8">
                </div>
            `;
            break;
    }

    container.innerHTML = html;
}

async function calculateAndRender() {
    const shape = document.getElementById('calc-shape-select').value;
    const inpA = document.getElementById('inp-a');
    const inpB = document.getElementById('inp-b');
    const inpH = document.getElementById('inp-h');
    const inpC = document.getElementById('inp-c');
    const inpD = document.getElementById('inp-d');
    const inpD1 = document.getElementById('inp-d1');
    const inpD2 = document.getElementById('inp-d2');

    const params = new URLSearchParams();
    params.append('action', 'calculate');
    params.append('shape', shape);
    if (inpA) params.append('a', inpA.value);
    if (inpB) params.append('b', inpB.value);
    if (inpH) params.append('h', inpH.value);
    if (inpC) params.append('c', inpC.value);
    if (inpD) params.append('d', inpD.value);
    if (inpD1) params.append('d1', inpD1.value);
    if (inpD2) params.append('d2', inpD2.value);

    try {
        const res = await fetch(`api.php?${params.toString()}`);
        const json = await res.json();
        if (json.success) {
            const data = json.data;
            document.getElementById('res-perimeter').innerText = data.perimeter + ' cm';
            document.getElementById('res-area').innerText = data.area + ' cm²';

            const stepsContainer = document.getElementById('res-steps');
            if (stepsContainer) {
                stepsContainer.innerHTML = data.steps.map(s => `<p style="margin-bottom:8px;font-size:0.95rem;">${s}</p>`).join('');
            }

            drawInteractiveSvg(shape, {
                a: inpA ? parseFloat(inpA.value) : 6,
                b: inpB ? parseFloat(inpB.value) : 4,
                h: inpH ? parseFloat(inpH.value) : 4,
                d1: inpD1 ? parseFloat(inpD1.value) : 8,
                d2: inpD2 ? parseFloat(inpD2.value) : 6
            });
        }
    } catch (e) {
        console.error(e);
    }
}

function drawInteractiveSvg(shape, vals) {
    const svg = document.getElementById('calc-dynamic-svg');
    if (!svg) return;

    let points = '';
    let extra = '';
    const w = 320;
    const h = 240;
    const cx = w / 2;
    const cy = h / 2;

    switch (shape) {
        case 'hinh_vuong':
            const sz = Math.min(140, Math.max(50, vals.a * 15));
            const x0 = cx - sz / 2;
            const y0 = cy - sz / 2;
            points = `${x0},${y0} ${x0 + sz},${y0} ${x0 + sz},${y0 + sz} ${x0},${y0 + sz}`;
            extra = `
                <text x="${cx}" y="${y0 - 8}" text-anchor="middle" font-weight="bold" fill="#4F46E5" font-size="13">a = ${vals.a}</text>
                <text x="${x0 + sz + 12}" y="${cy}" text-anchor="start" font-weight="bold" fill="#4F46E5" font-size="13">a = ${vals.a}</text>
            `;
            break;

        case 'hinh_chu_nhat':
            const rw = Math.min(220, Math.max(70, vals.a * 15));
            const rh = Math.min(150, Math.max(40, vals.b * 15));
            const rx0 = cx - rw / 2;
            const ry0 = cy - rh / 2;
            points = `${rx0},${ry0} ${rx0 + rw},${ry0} ${rx0 + rw},${ry0 + rh} ${rx0},${ry0 + rh}`;
            extra = `
                <text x="${cx}" y="${ry0 - 8}" text-anchor="middle" font-weight="bold" fill="#10B981" font-size="13">dài = ${vals.a}</text>
                <text x="${rx0 + rw + 14}" y="${cy}" text-anchor="start" font-weight="bold" fill="#10B981" font-size="13">rộng = ${vals.b}</text>
            `;
            break;

        case 'hinh_thoi':
            const diagX = Math.min(220, Math.max(70, (vals.d1 || 8) * 15));
            const diagY = Math.min(160, Math.max(50, (vals.d2 || 6) * 15));
            points = `${cx},${cy - diagY / 2} ${cx + diagX / 2},${cy} ${cx},${cy + diagY / 2} ${cx - diagX / 2},${cy}`;
            extra = `
                <line x1="${cx - diagX / 2}" y1="${cy}" x2="${cx + diagX / 2}" y2="${cy}" stroke="#D97706" stroke-dasharray="4" stroke-width="1.5" />
                <line x1="${cx}" y1="${cy - diagY / 2}" x2="${cx}" y2="${cy + diagY / 2}" stroke="#D97706" stroke-dasharray="4" stroke-width="1.5" />
                <text x="${cx + 8}" y="${cy - 8}" fill="#B45309" font-weight="bold" font-size="11">90°</text>
            `;
            break;

        case 'hinh_binh_hanh':
            const bw = 140;
            const bh = 90;
            const skew = 35;
            points = `${cx - bw / 2 + skew},${cy - bh / 2} ${cx + bw / 2},${cy - bh / 2} ${cx + bw / 2 - skew},${cy + bh / 2} ${cx - bw / 2},${cy + bh / 2}`;
            extra = `
                <line x1="${cx - bw / 2 + skew}" y1="${cy - bh / 2}" x2="${cx - bw / 2 + skew}" y2="${cy + bh / 2}" stroke="#EF4444" stroke-dasharray="3" stroke-width="1.5" />
                <text x="${cx - bw / 2 + skew - 14}" y="${cy}" fill="#DC2626" font-weight="bold" font-size="12">h = ${vals.h}</text>
            `;
            break;

        case 'hinh_thang':
        case 'hinh_thang_can':
        case 'hinh_thang_vuong':
            const twTop = 100;
            const twBot = 180;
            const th = 85;
            points = `${cx - twTop / 2},${cy - th / 2} ${cx + twTop / 2},${cy - th / 2} ${cx + twBot / 2},${cy + th / 2} ${cx - twBot / 2},${cy + th / 2}`;
            extra = `
                <line x1="${cx - twTop / 2}" y1="${cy - th / 2}" x2="${cx - twTop / 2}" y2="${cy + th / 2}" stroke="#EF4444" stroke-dasharray="3" stroke-width="1.5" />
                <text x="${cx - twTop / 2 - 14}" y="${cy}" fill="#DC2626" font-weight="bold" font-size="12">h</text>
                <text x="${cx}" y="${cy - th / 2 - 8}" text-anchor="middle" font-weight="bold" fill="#0EA5E9" font-size="12">đáy bé (b)</text>
                <text x="${cx}" y="${cy + th / 2 + 18}" text-anchor="middle" font-weight="bold" fill="#0EA5E9" font-size="12">đáy lớn (a)</text>
            `;
            break;

        case 'hinh_dieu':
        default:
            const kw = 120;
            const khTop = 45;
            const khBot = 95;
            points = `${cx},${cy - khTop} ${cx + kw / 2},${cy} ${cx},${cy + khBot} ${cx - kw / 2},${cy}`;
            extra = `
                <line x1="${cx - kw / 2}" y1="${cy}" x2="${cx + kw / 2}" y2="${cy}" stroke="#F97316" stroke-dasharray="3" stroke-width="1.5" />
                <line x1="${cx}" y1="${cy - khTop}" x2="${cx}" y2="${cy + khBot}" stroke="#F97316" stroke-dasharray="3" stroke-width="1.5" />
            `;
            break;
    }

    svg.innerHTML = `
        <polygon points="${points}" fill="rgba(79, 70, 229, 0.12)" stroke="#4F46E5" stroke-width="3" stroke-linejoin="round" />
        ${extra}
    `;
}

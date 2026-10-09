/**
 * ==============================================================================
 * GEOQUADRILATERAL - NHÓM 9
 * File: assets/js/draw-engine.js
 * Cỗ máy vẽ hình tứ giác tương tác và Nhận diện hình học thời gian thực
 * ==============================================================================
 */

// Bộ dữ liệu thông tin chuẩn đồng bộ với CSDL Neo4j
const NEO4J_SHAPES_DATA = {
    'hinh_vuong': {
        name: 'Hình Vuông',
        name_en: 'Square',
        badge: 'Cấp độ 5 - Tinh hoa',
        color: '#EC4899',
        bgColor: '#FDF2F8',
        borderColor: '#F472B6',
        grades: [6, 8],
        gradeText: 'Toán Lớp 6 & Lớp 8',
        definition: 'Hình vuông là tứ giác hoàn hảo nhất: có 4 góc vuông (90°) và 4 cạnh có độ dài bằng nhau. Vừa là hình chữ nhật đặc biệt, vừa là hình thoi đặc biệt.',
        conditions: [
            { text: 'Hai cặp cạnh đối song song (AB // CD, BC // DA)', check: (d) => d.para1 && d.para2 },
            { text: 'Có 4 góc vuông 90° (∠A = ∠B = ∠C = ∠D = 90°)', check: (d) => d.allRightAngles },
            { text: 'Cả 4 cạnh có độ dài bằng nhau (AB = BC = CD = DA)', check: (d) => d.allEdgesEqual },
            { text: 'Hai đường chéo vuông góc và bằng nhau (d₁ = d₂, d₁ ⊥ d₂)', check: (d) => d.diagEqual && d.diagPerp }
        ]
    },
    'hinh_chu_nhat': {
        name: 'Hình Chữ Nhật',
        name_en: 'Rectangle',
        badge: 'Cấp độ 4 - Đặc biệt',
        color: '#10B981',
        bgColor: '#ECFDF5',
        borderColor: '#6EE7B7',
        grades: [6, 8],
        gradeText: 'Toán Lớp 6 & Lớp 8',
        definition: 'Hình chữ nhật là tứ giác có 4 góc vuông (90°). Hai cặp cạnh đối diện song song và bằng nhau từng đôi một, 2 đường chéo bằng nhau.',
        conditions: [
            { text: 'Hai cặp cạnh đối song song (AB // CD, BC // DA)', check: (d) => d.para1 && d.para2 },
            { text: 'Có 4 góc vuông 90° (∠A = ∠B = ∠C = ∠D = 90°)', check: (d) => d.allRightAngles },
            { text: 'Hai cặp cạnh đối bằng nhau (AB = CD, BC = DA)', check: (d) => d.oppEdgesEqual },
            { text: 'Hai đường chéo có độ dài bằng nhau (AC = BD)', check: (d) => d.diagEqual }
        ]
    },
    'hinh_thoi': {
        name: 'Hình Thoi',
        name_en: 'Rhombus',
        badge: 'Cấp độ 4 - Đặc biệt',
        color: '#059669',
        bgColor: '#ECFDF5',
        borderColor: '#34D399',
        grades: [6, 8],
        gradeText: 'Toán Lớp 6 & Lớp 8',
        definition: 'Hình thoi là tứ giác có 4 cạnh bằng nhau. Hai cặp cạnh đối song song, hai đường chéo vuông góc với nhau tại trung điểm của mỗi đường.',
        conditions: [
            { text: 'Cả 4 cạnh có độ dài bằng nhau (AB = BC = CD = DA)', check: (d) => d.allEdgesEqual },
            { text: 'Hai cặp cạnh đối song song (AB // CD, BC // DA)', check: (d) => d.para1 && d.para2 },
            { text: 'Hai đường chéo vuông góc với nhau (AC ⊥ BD)', check: (d) => d.diagPerp },
            { text: 'Các góc đối diện có số đo bằng nhau', check: (d) => d.oppAnglesEqual }
        ]
    },
    'hinh_binh_hanh': {
        name: 'Hình Bình Hành',
        name_en: 'Parallelogram',
        badge: 'Cấp độ 3 - Mở rộng',
        color: '#38BDF8',
        bgColor: '#F0F9FF',
        borderColor: '#7DD3FC',
        grades: [6, 8],
        gradeText: 'Toán Lớp 6 & Lớp 8',
        definition: 'Hình bình hành là tứ giác có các cặp cạnh đối diện song song và bằng nhau. Các góc đối bằng nhau, hai đường chéo cắt nhau tại trung điểm mỗi đường.',
        conditions: [
            { text: 'Hai cặp cạnh đối song song (AB // CD, BC // DA)', check: (d) => d.para1 && d.para2 },
            { text: 'Hai cặp cạnh đối có độ dài bằng nhau', check: (d) => d.oppEdgesEqual },
            { text: 'Các góc đối diện bằng nhau', check: (d) => d.oppAnglesEqual },
            { text: 'Hai đường chéo cắt nhau tại trung điểm mỗi đường', check: (d) => d.diagBisect }
        ]
    },
    'hinh_dieu': {
        name: 'Hình Diều',
        name_en: 'Kite',
        badge: 'Cấp độ 2 - Mở rộng',
        color: '#F97316',
        bgColor: '#FFF7ED',
        borderColor: '#FDBA74',
        grades: [8],
        gradeText: 'Toán Lớp 8',
        definition: 'Hình diều là tứ giác có hai cặp cạnh kề nhau bằng nhau. Hai đường chéo vuông góc với nhau và đường chéo chính là trung trực của đường chéo phụ.',
        conditions: [
            { text: 'Có 2 cặp cạnh kề nhau bằng nhau (AB = AD và CB = CD)', check: (d) => d.kiteEdges },
            { text: 'Hai đường chéo vuông góc với nhau (AC ⊥ BD)', check: (d) => d.diagPerp }
        ]
    },
    'hinh_thang_vuong': {
        name: 'Hình Thang Vuông',
        name_en: 'Right Trapezoid',
        badge: 'Cấp độ 2.1 - Hình thang',
        color: '#06B6D4',
        bgColor: '#ECFEFF',
        borderColor: '#67E8F9',
        grades: [7, 8],
        gradeText: 'Toán Lớp 7 & Lớp 8',
        definition: 'Hình thang vuông là hình thang có ít nhất một góc vuông (90°). Cạnh bên vuông góc với 2 đáy chính là chiều cao của hình.',
        conditions: [
            { text: 'Có đúng 1 cặp cạnh đối song song (hai đáy)', check: (d) => (d.para1 && !d.para2) || (!d.para1 && d.para2) },
            { text: 'Có ít nhất 1 góc vuông 90° (cạnh bên vuông góc đáy)', check: (d) => d.hasAnyRightAngle }
        ]
    },
    'hinh_thang_can': {
        name: 'Hình Thang Cân',
        name_en: 'Isosceles Trapezoid',
        badge: 'Cấp độ 2.2 - Hình thang',
        color: '#14B8A6',
        bgColor: '#F0FDFA',
        borderColor: '#5EEAD4',
        grades: [6, 8],
        gradeText: 'Toán Lớp 6 & Lớp 8',
        definition: 'Hình thang cân là hình thang có hai góc kề một đáy bằng nhau, hoặc hai cạnh bên bằng nhau và hai đường chéo có độ dài bằng nhau.',
        conditions: [
            { text: 'Có đúng 1 cặp cạnh đối song song (hai đáy)', check: (d) => (d.para1 && !d.para2) || (!d.para1 && d.para2) },
            { text: 'Hai góc kề một đáy bằng nhau (hoặc hai đường chéo bằng nhau)', check: (d) => d.isoAngles || d.diagEqual }
        ]
    },
    'hinh_thang': {
        name: 'Hình Thang',
        name_en: 'Trapezoid',
        badge: 'Cấp độ 2 - Cơ bản',
        color: '#0EA5E9',
        bgColor: '#F0F9FF',
        borderColor: '#7DD3FC',
        grades: [7, 8],
        gradeText: 'Toán Lớp 7 & Lớp 8',
        definition: 'Hình thang là tứ giác có một cặp cạnh đối diện song song với nhau (gọi là hai đáy: đáy lớn và đáy nhỏ).',
        conditions: [
            { text: 'Có một cặp cạnh đối song song (AB // CD hoặc BC // DA)', check: (d) => d.para1 || d.para2 }
        ]
    },
    'tu_giac': {
        name: 'Tứ Giác Thường',
        name_en: 'Quadrilateral',
        badge: 'Cấp độ 1 - Gốc',
        color: '#0284C7',
        bgColor: '#F8FAFC',
        borderColor: '#CBD5E1',
        grades: [7, 8],
        gradeText: 'Toán Lớp 7 & Lớp 8',
        definition: 'Tứ giác lồi là đa giác có 4 cạnh, 4 đỉnh và 4 góc. Tổng số đo 4 góc trong của mọi tứ giác lồi luôn bằng 360 độ.',
        conditions: [
            { text: 'Có đúng 4 cạnh, 4 đỉnh khép kín trong mặt phẳng', check: () => true },
            { text: 'Tổng số đo 4 góc trong luôn bằng 360°', check: () => true }
        ]
    }
};

class QuadrilateralDrawingLab {
    constructor(canvasId) {
        this.canvas = document.getElementById(canvasId);
        if (!this.canvas) return;
        this.ctx = this.canvas.getContext('2d');

        // Tùy chọn hiển thị
        this.options = {
            showLengths: true,
            showAngles: true,
            showDiagonals: true,
            showGrid: true, // Mặc định luôn bật lưới ô ly toán học
            gridSnap: false,
            gridSize: 25 // 25 pixel = đúng 1.0 cm
        };

        // Điểm A, B, C, D (Thứ tự kim đồng hồ - chuẩn khớp mắt lưới ô ly 25px)
        this.vertices = [
            { id: 'A', x: 250, y: 150, label: 'A', color: '#EF4444' },
            { id: 'B', x: 500, y: 150, label: 'B', color: '#3B82F6' },
            { id: 'C', x: 500, y: 350, label: 'C', color: '#10B981' },
            { id: 'D', x: 250, y: 350, label: 'D', color: '#F59E0B' }
        ];

        // Tương tác chuột / chạm
        this.dragMode = null; // 'vertex', 'edge', 'body'
        this.dragTargetIndex = -1;
        this.dragOffset = { x: 0, y: 0 };
        this.isHoveringVertex = -1;
        this.isHoveringEdge = -1;
        this.hoveringCenter = false;

        // Tỷ lệ quy đổi: 25 pixel = 1 cm
        this.PIXELS_PER_CM = 25;

        // Trạng thái nhận diện hiện tại
        this.currentDetection = null;

        // Trạng thái phím Shift & Đường gióng thẳng hàng
        this.isShiftDown = false;
        this.activeGuide = null;
        this.lastPointerPos = null;

        this.initCanvasSize();
        this.bindEvents();
        this.applyPreset('hinh_chu_nhat');
    }

    initCanvasSize() {
        const rect = this.canvas.parentElement.getBoundingClientRect();
        const dpr = window.devicePixelRatio || 1;
        this.canvas.width = rect.width * dpr;
        this.canvas.height = rect.height * dpr;
        this.ctx.scale(dpr, dpr);
        this.width = rect.width;
        this.height = rect.height;
    }

    bindEvents() {
        window.addEventListener('resize', () => {
            this.initCanvasSize();
            this.render();
        });

        // Mouse Events
        this.canvas.addEventListener('mousedown', (e) => this.onPointerDown(e));
        window.addEventListener('mousemove', (e) => this.onPointerMove(e));
        window.addEventListener('mouseup', () => this.onPointerUp());

        // Touch Events
        this.canvas.addEventListener('touchstart', (e) => {
            e.preventDefault();
            const touch = e.touches[0];
            this.onPointerDown(touch);
        }, { passive: false });

        window.addEventListener('touchmove', (e) => {
            if (this.dragMode) {
                const touch = e.touches[0];
                this.onPointerMove(touch);
            }
        });

        window.addEventListener('touchend', () => this.onPointerUp());

        // Bắt sự kiện phím Shift (Khóa thẳng hàng)
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Shift') {
                this.isShiftDown = true;
                if (this.dragMode && this.lastPointerPos) {
                    this.onPointerMove({ clientX: this.lastPointerPos.rawX, clientY: this.lastPointerPos.rawY, shiftKey: true });
                }
            }
        });

        window.addEventListener('keyup', (e) => {
            if (e.key === 'Shift') {
                this.isShiftDown = false;
                this.activeGuide = null;
                this.render();
            }
        });
    }

    getCanvasPos(e) {
        const rect = this.canvas.getBoundingClientRect();
        return {
            x: e.clientX - rect.left,
            y: e.clientY - rect.top
        };
    }

    onPointerDown(e) {
        const pos = this.getCanvasPos(e);

        // 1. Kiểm tra click vào đỉnh
        for (let i = 0; i < this.vertices.length; i++) {
            const v = this.vertices[i];
            const dist = Math.hypot(pos.x - v.x, pos.y - v.y);
            if (dist <= 18) {
                this.dragMode = 'vertex';
                this.dragTargetIndex = i;
                this.dragOffset = { x: pos.x - v.x, y: pos.y - v.y };
                this.canvas.style.cursor = 'grabbing';
                return;
            }
        }

        // 2. Kiểm tra click vào cạnh
        for (let i = 0; i < 4; i++) {
            const v1 = this.vertices[i];
            const v2 = this.vertices[(i + 1) % 4];
            const dist = this.distToSegment(pos, v1, v2);
            if (dist <= 12) {
                this.dragMode = 'edge';
                this.dragTargetIndex = i;
                this.lastPos = pos;
                this.canvas.style.cursor = 'grabbing';
                return;
            }
        }

        // 3. Kiểm tra click vào trong lòng tứ giác
        if (this.isPointInsideQuad(pos)) {
            this.dragMode = 'body';
            this.lastPos = pos;
            this.canvas.style.cursor = 'grabbing';
        }
    }

    onPointerMove(e) {
        const pos = this.getCanvasPos(e);
        this.lastPointerPos = { x: pos.x, y: pos.y, rawX: e.clientX, rawY: e.clientY };
        const isShift = Boolean(e.shiftKey || this.isShiftDown);

        if (!this.dragMode) {
            // Hover states
            this.isHoveringVertex = -1;
            this.isHoveringEdge = -1;
            this.hoveringCenter = false;

            for (let i = 0; i < this.vertices.length; i++) {
                if (Math.hypot(pos.x - this.vertices[i].x, pos.y - this.vertices[i].y) <= 18) {
                    this.isHoveringVertex = i;
                    this.canvas.style.cursor = 'grab';
                    this.render();
                    return;
                }
            }

            for (let i = 0; i < 4; i++) {
                if (this.distToSegment(pos, this.vertices[i], this.vertices[(i + 1) % 4]) <= 12) {
                    this.isHoveringEdge = i;
                    this.canvas.style.cursor = 'move';
                    this.render();
                    return;
                }
            }

            if (this.isPointInsideQuad(pos)) {
                this.hoveringCenter = true;
                this.canvas.style.cursor = 'move';
            } else {
                this.canvas.style.cursor = 'default';
            }

            this.render();
            return;
        }

        // Đang kéo thả
        if (this.dragMode === 'vertex') {
            const rawX = pos.x - this.dragOffset.x;
            const rawY = pos.y - this.dragOffset.y;
            const sz = this.options.gridSize; // 25px = 1.0cm (Đường grid ô ly chính)

            let targetX, targetY;

            if (isShift) {
                // Tọa độ làm tròn sơ bộ theo mắt lưới ô ly chính
                const gridX = Math.round(rawX / sz) * sz;
                const gridY = Math.round(rawY / sz) * sz;
                const i = this.dragTargetIndex;
                const curr = this.vertices[i];
                const prev = this.vertices[(i + 3) % 4];
                const next = this.vertices[(i + 1) % 4];
                const opp = this.vertices[(i + 2) % 4];

                const candidates = [];

                // -----------------------------------------------------------------
                // 1. KHÓA VUÔNG GÓC (Perpendicular - 90°)
                // -----------------------------------------------------------------
                // 1.1. Góc vuông 90° tại đỉnh đang kéo: (prev - Vi) ⊥ (Vi - next)
                // Kiểu 1: prev ngang tới Vi (y = prev.y), Vi dọc tới next (x = next.x)
                {
                    const cx = next.x, cy = prev.y;
                    const dist = Math.hypot(rawX - cx, rawY - cy);
                    if (dist <= 55) {
                        candidates.push({
                            x: cx, y: cy, dist,
                            guide: {
                                type: 'perpendicular',
                                subtype: 'corner',
                                x: cx, y: cy,
                                label: `Góc vuông 90° tại đỉnh ${curr.label}`
                            }
                        });
                    }
                }
                // Kiểu 2: prev dọc tới Vi (x = prev.x), Vi ngang tới next (y = next.y)
                {
                    const cx = prev.x, cy = next.y;
                    const dist = Math.hypot(rawX - cx, rawY - cy);
                    if (dist <= 55) {
                        candidates.push({
                            x: cx, y: cy, dist,
                            guide: {
                                type: 'perpendicular',
                                subtype: 'corner',
                                x: cx, y: cy,
                                label: `Góc vuông 90° tại đỉnh ${curr.label}`
                            }
                        });
                    }
                }

                // 1.2. Khóa đường ngang / dọc theo đường ô ly (Vuông góc với trục đối diện)
                // - Cạnh nối với prev nằm ngang trên đường ô ly (y = prev.y)
                {
                    const dist = Math.abs(rawY - prev.y);
                    if (dist <= 42) {
                        candidates.push({
                            x: gridX, y: prev.y, dist,
                            guide: {
                                type: 'perpendicular',
                                subtype: 'horizontal',
                                y: prev.y,
                                label: `Vuông góc 90° (Cạnh ${prev.label}${curr.label} nằm ngang)`
                            }
                        });
                    }
                }
                // - Cạnh nối với next nằm ngang trên đường ô ly (y = next.y)
                {
                    const dist = Math.abs(rawY - next.y);
                    if (dist <= 42) {
                        candidates.push({
                            x: gridX, y: next.y, dist,
                            guide: {
                                type: 'perpendicular',
                                subtype: 'horizontal',
                                y: next.y,
                                label: `Vuông góc 90° (Cạnh ${curr.label}${next.label} nằm ngang)`
                            }
                        });
                    }
                }
                // - Cạnh nối với prev thẳng đứng trên đường ô ly (x = prev.x)
                {
                    const dist = Math.abs(rawX - prev.x);
                    if (dist <= 42) {
                        candidates.push({
                            x: prev.x, y: gridY, dist,
                            guide: {
                                type: 'perpendicular',
                                subtype: 'vertical',
                                x: prev.x,
                                label: `Vuông góc 90° (Cạnh ${prev.label}${curr.label} thẳng đứng)`
                            }
                        });
                    }
                }
                // - Cạnh nối với next thẳng đứng trên đường ô ly (x = next.x)
                {
                    const dist = Math.abs(rawX - next.x);
                    if (dist <= 42) {
                        candidates.push({
                            x: next.x, y: gridY, dist,
                            guide: {
                                type: 'perpendicular',
                                subtype: 'vertical',
                                x: next.x,
                                label: `Vuông góc 90° (Cạnh ${curr.label}${next.label} thẳng đứng)`
                            }
                        });
                    }
                }

                // -----------------------------------------------------------------
                // 2. KHÓA SONG SONG (Parallel - 2 đường thẳng song song)
                // -----------------------------------------------------------------
                // 2.1. Cạnh (prev, Vi) song song với cạnh đối diện (opp, next)
                const dxOpp1 = opp.x - next.x;
                const dyOpp1 = opp.y - next.y;

                if (dyOpp1 === 0) {
                    // Cạnh đối diện nằm ngang -> Khóa cạnh prev-Vi nằm ngang song song
                    const dist = Math.abs(rawY - prev.y);
                    if (dist <= 50) {
                        candidates.push({
                            x: gridX, y: prev.y, dist: dist * 0.9,
                            guide: {
                                type: 'parallel',
                                subtype: 'horizontal',
                                y1: prev.y, y2: next.y,
                                label: `Song song: ${prev.label}${curr.label} // ${opp.label}${next.label}`
                            }
                        });
                    }
                } else if (dxOpp1 === 0) {
                    // Cạnh đối diện thẳng đứng -> Khóa cạnh prev-Vi thẳng đứng song song
                    const dist = Math.abs(rawX - prev.x);
                    if (dist <= 50) {
                        candidates.push({
                            x: prev.x, y: gridY, dist: dist * 0.9,
                            guide: {
                                type: 'parallel',
                                subtype: 'vertical',
                                x1: prev.x, x2: next.x,
                                label: `Song song: ${prev.label}${curr.label} // ${opp.label}${next.label}`
                            }
                        });
                    }
                } else {
                    // Cạnh đối diện xiên -> Khóa điểm tạo thành hình bình hành song song
                    const pbhX = prev.x + (next.x - opp.x);
                    const pbhY = prev.y + (next.y - opp.y);
                    const distPBH = Math.hypot(rawX - pbhX, rawY - pbhY);
                    if (distPBH <= 50) {
                        candidates.push({
                            x: pbhX, y: pbhY, dist: distPBH * 0.85,
                            guide: {
                                type: 'parallel',
                                subtype: 'slanted',
                                x1: prev.x, y1: prev.y, x2: pbhX, y2: pbhY,
                                refX1: next.x, refY1: next.y, refX2: opp.x, refY2: opp.y,
                                label: `Song song: ${prev.label}${curr.label} // ${opp.label}${next.label}`
                            }
                        });
                    }
                }

                // 2.2. Cạnh (Vi, next) song song với cạnh đối diện (opp, prev)
                const dxOpp2 = opp.x - prev.x;
                const dyOpp2 = opp.y - prev.y;

                if (dyOpp2 === 0) {
                    const dist = Math.abs(rawY - next.y);
                    if (dist <= 50) {
                        candidates.push({
                            x: gridX, y: next.y, dist: dist * 0.9,
                            guide: {
                                type: 'parallel',
                                subtype: 'horizontal',
                                y1: next.y, y2: prev.y,
                                label: `Song song: ${curr.label}${next.label} // ${opp.label}${prev.label}`
                            }
                        });
                    }
                } else if (dxOpp2 === 0) {
                    const dist = Math.abs(rawX - next.x);
                    if (dist <= 50) {
                        candidates.push({
                            x: next.x, y: gridY, dist: dist * 0.9,
                            guide: {
                                type: 'parallel',
                                subtype: 'vertical',
                                x1: next.x, x2: prev.x,
                                label: `Song song: ${curr.label}${next.label} // ${opp.label}${prev.label}`
                            }
                        });
                    }
                } else {
                    const pbhX2 = next.x - (opp.x - prev.x);
                    const pbhY2 = next.y - (opp.y - prev.y);
                    const distPBH2 = Math.hypot(rawX - pbhX2, rawY - pbhY2);
                    if (distPBH2 <= 50) {
                        candidates.push({
                            x: pbhX2, y: pbhY2, dist: distPBH2 * 0.85,
                            guide: {
                                type: 'parallel',
                                subtype: 'slanted',
                                x1: pbhX2, y1: pbhY2, x2: next.x, y2: next.y,
                                refX1: prev.x, refY1: prev.y, refX2: opp.x, refY2: opp.y,
                                label: `Song song: ${curr.label}${next.label} // ${opp.label}${prev.label}`
                            }
                        });
                    }
                }

                if (candidates.length > 0) {
                    candidates.sort((a, b) => a.dist - b.dist);
                    const best = candidates[0];
                    targetX = Math.round(best.x / sz) * sz;
                    targetY = Math.round(best.y / sz) * sz;
                    this.activeGuide = best.guide;
                } else {
                    // Nếu chưa rơi vào vùng song song hoặc vuông góc:
                    // Tự động hút CHẶT vào giao điểm ô ly chính gần nhất (trùng đường grid chính 100%)
                    targetX = gridX;
                    targetY = gridY;
                    this.activeGuide = {
                        type: 'grid',
                        x: gridX,
                        y: gridY,
                        label: 'Trùng đường ô ly chính (1.0 cm)'
                    };
                }
            } else {
                this.activeGuide = null;
                if (this.options.gridSnap) {
                    targetX = Math.round(rawX / sz) * sz;
                    targetY = Math.round(rawY / sz) * sz;
                } else {
                    targetX = rawX;
                    targetY = rawY;
                }
            }

            // Giới hạn biên canvas và đảm bảo tọa độ luôn là bội số sz
            targetX = Math.max(sz, Math.min(Math.floor((this.width - sz) / sz) * sz, targetX));
            targetY = Math.max(sz, Math.min(Math.floor((this.height - sz) / sz) * sz, targetY));

            this.vertices[this.dragTargetIndex].x = targetX;
            this.vertices[this.dragTargetIndex].y = targetY;
        } else if (this.dragMode === 'edge') {
            let dx = pos.x - this.lastPos.x;
            let dy = pos.y - this.lastPos.y;
            const sz = this.options.gridSize;

            // Xử lý Shift khi kéo cạnh: Khóa di chuyển thuần ngang hoặc thuần dọc trên đường ô ly
            if (isShift) {
                if (Math.abs(dx) >= Math.abs(dy)) {
                    dy = 0;
                    this.activeGuide = {
                        type: 'perpendicular',
                        subtype: 'horizontal',
                        y: this.vertices[this.dragTargetIndex].y,
                        label: 'Khóa trượt ngang theo hàng ô ly'
                    };
                } else {
                    dx = 0;
                    this.activeGuide = {
                        type: 'perpendicular',
                        subtype: 'vertical',
                        x: this.vertices[this.dragTargetIndex].x,
                        label: 'Khóa trượt dọc theo cột ô ly'
                    };
                }
            } else {
                this.activeGuide = null;
            }

            this.lastPos = pos;

            const i1 = this.dragTargetIndex;
            const i2 = (this.dragTargetIndex + 1) % 4;

            let nx1 = this.vertices[i1].x + dx;
            let ny1 = this.vertices[i1].y + dy;
            let nx2 = this.vertices[i2].x + dx;
            let ny2 = this.vertices[i2].y + dy;

            if (isShift) {
                nx1 = Math.round(nx1 / sz) * sz;
                ny1 = Math.round(ny1 / sz) * sz;
                nx2 = Math.round(nx2 / sz) * sz;
                ny2 = Math.round(ny2 / sz) * sz;
            }

            this.vertices[i1].x = Math.max(sz, Math.min(this.width - sz, nx1));
            this.vertices[i1].y = Math.max(sz, Math.min(this.height - sz, ny1));
            this.vertices[i2].x = Math.max(sz, Math.min(this.width - sz, nx2));
            this.vertices[i2].y = Math.max(sz, Math.min(this.height - sz, ny2));
        } else if (this.dragMode === 'body') {
            const dx = pos.x - this.lastPos.x;
            const dy = pos.y - this.lastPos.y;
            this.lastPos = pos;
            this.activeGuide = null;

            for (let v of this.vertices) {
                v.x = Math.max(20, Math.min(this.width - 20, v.x + dx));
                v.y = Math.max(20, Math.min(this.height - 20, v.y + dy));
            }
        }

        this.render();
    }

    onPointerUp() {
        this.dragMode = null;
        this.dragTargetIndex = -1;
        this.activeGuide = null;
        this.canvas.style.cursor = 'default';
        this.render();
    }

    distToSegment(p, v, w) {
        const l2 = (v.x - w.x) ** 2 + (v.y - w.y) ** 2;
        if (l2 === 0) return Math.hypot(p.x - v.x, p.y - v.y);
        let t = ((p.x - v.x) * (w.x - v.x) + (p.y - v.y) * (w.y - v.y)) / l2;
        t = Math.max(0, Math.min(1, t));
        return Math.hypot(p.x - (v.x + t * (w.x - v.x)), p.y - (v.y + t * (w.y - v.y)));
    }

    isPointInsideQuad(p) {
        let inside = false;
        for (let i = 0, j = 3; i < 4; j = i++) {
            const xi = this.vertices[i].x, yi = this.vertices[i].y;
            const xj = this.vertices[j].x, yj = this.vertices[j].y;
            const intersect = ((yi > p.y) !== (yj > p.y)) &&
                (p.x < (xj - xi) * (p.y - yi) / (yj - yi) + xi);
            if (intersect) inside = !inside;
        }
        return inside;
    }

    // =========================================================================
    // THUẬT TOÁN HÌNH HỌC & NHẬN DIỆN THÔNG MINH
    // =========================================================================
    analyzeGeometry() {
        const [A, B, C, D] = this.vertices;

        // Độ dài cạnh (pixel)
        const lenAB = Math.hypot(B.x - A.x, B.y - A.y);
        const lenBC = Math.hypot(C.x - B.x, C.y - B.y);
        const lenCD = Math.hypot(D.x - C.x, D.y - C.y);
        const lenDA = Math.hypot(A.x - D.x, A.y - D.y);

        // Độ dài đường chéo
        const lenAC = Math.hypot(C.x - A.x, C.y - A.y);
        const lenBD = Math.hypot(D.x - B.x, D.y - B.y);

        // Vector các cạnh
        const vAB = { x: B.x - A.x, y: B.y - A.y };
        const vBC = { x: C.x - B.x, y: C.y - B.y };
        const vCD = { x: D.x - C.x, y: D.y - C.y };
        const vDA = { x: A.x - D.x, y: A.y - D.y };

        // Vector đường chéo
        const vAC = { x: C.x - A.x, y: C.y - A.y };
        const vBD = { x: D.x - B.x, y: D.y - B.y };

        // Hàm tính góc giữa 2 vector (độ)
        const getAngle = (u, v) => {
            const dot = u.x * v.x + u.y * v.y;
            const mag = Math.hypot(u.x, u.y) * Math.hypot(v.x, v.y);
            if (mag === 0) return 0;
            const cos = Math.max(-1, Math.min(1, dot / mag));
            return Math.acos(cos) * (180 / Math.PI);
        };

        // Góc tại 4 đỉnh
        // Tại A: giữa vAB và vector ngược của vDA (-vDA)
        const angleA = getAngle(vAB, { x: D.x - A.x, y: D.y - A.y });
        // Tại B: giữa vector ngược vAB và vBC
        const angleB = getAngle({ x: A.x - B.x, y: A.y - B.y }, vBC);
        // Tại C: giữa vector ngược vBC và vCD
        const angleC = getAngle({ x: B.x - C.x, y: B.y - C.y }, vCD);
        // Tại D: giữa vector ngược vCD và -vDA
        const angleD = getAngle({ x: C.x - D.x, y: C.y - D.y }, { x: A.x - D.x, y: A.y - D.y });

        // Kiểm tra song song
        // Hai đoạn thẳng AB và CD song song nếu vector vAB cùng phương với vector ngược của vCD (vDC = -vCD)
        const isParallel = (u, v) => {
            const cross = Math.abs(u.x * v.y - u.y * v.x);
            const mag = Math.hypot(u.x, u.y) * Math.hypot(v.x, v.y);
            return (cross / mag) < 0.085; // Sai số góc < ~4.8 độ
        };

        const para1 = isParallel(vAB, { x: C.x - D.x, y: C.y - D.y }); // AB // CD
        const para2 = isParallel(vBC, { x: A.x - D.x, y: A.y - D.y }); // BC // DA

        // Kiểm tra góc vuông (~90 độ)
        const isRight = (ang) => Math.abs(ang - 90) <= 4.5;
        const rightA = isRight(angleA);
        const rightB = isRight(angleB);
        const rightC = isRight(angleC);
        const rightD = isRight(angleD);
        const allRightAngles = rightA && rightB && rightC && rightD;
        const hasAnyRightAngle = rightA || rightB || rightC || rightD;

        // Kiểm tra độ dài bằng nhau
        const isClose = (a, b) => Math.abs(a - b) <= Math.max(3, 0.065 * Math.max(a, b));
        const allEdgesEqual = isClose(lenAB, lenBC) && isClose(lenBC, lenCD) && isClose(lenCD, lenDA);
        const oppEdgesEqual = isClose(lenAB, lenCD) && isClose(lenBC, lenDA);

        // Kiểm tra đường chéo
        const diagEqual = isClose(lenAC, lenBD);
        const diagDot = Math.abs(vAC.x * vBD.x + vAC.y * vBD.y);
        const diagMag = lenAC * lenBD;
        const diagPerp = diagMag > 0 && (diagDot / diagMag) < 0.09; // Tích vô hướng gần bằng 0 -> vuông góc

        // Kiểm tra góc đối bằng nhau
        const oppAnglesEqual = isClose(angleA, angleC) && isClose(angleB, angleD);

        // Kiểm tra hai đường chéo cắt nhau tại trung điểm
        const midAC = { x: (A.x + C.x) / 2, y: (A.y + C.y) / 2 };
        const midBD = { x: (B.x + D.x) / 2, y: (B.y + D.y) / 2 };
        const diagBisect = Math.hypot(midAC.x - midBD.x, midAC.y - midBD.y) <= 10;

        // Kiểm tra hình diều (2 cặp cạnh kề bằng nhau)
        const kiteEdges = (isClose(lenAB, lenDA) && isClose(lenBC, lenCD)) ||
                          (isClose(lenAB, lenBC) && isClose(lenCD, lenDA));

        // Kiểm tra góc kề đáy bằng nhau (hình thang cân)
        const isoAngles = (para1 && (isClose(angleA, angleB) || isClose(angleD, angleC))) ||
                          (para2 && (isClose(angleA, angleD) || isClose(angleB, angleC)));

        // Chu vi và diện tích (theo cm)
        const cmAB = lenAB / this.PIXELS_PER_CM;
        const cmBC = lenBC / this.PIXELS_PER_CM;
        const cmCD = lenCD / this.PIXELS_PER_CM;
        const cmDA = lenDA / this.PIXELS_PER_CM;
        const perimeter = cmAB + cmBC + cmCD + cmDA;

        // Diện tích theo công thức Shoelace (Gauss)
        const areaPx = 0.5 * Math.abs(
            (A.x * B.y - B.x * A.y) +
            (B.x * C.y - C.x * B.y) +
            (C.x * D.y - D.x * C.y) +
            (D.x * A.y - A.x * D.y)
        );
        const area = areaPx / (this.PIXELS_PER_CM ** 2);

        const data = {
            lenAB, lenBC, lenCD, lenDA,
            lenAC, lenBD,
            cmAB, cmBC, cmCD, cmDA,
            angleA, angleB, angleC, angleD,
            para1, para2,
            rightA, rightB, rightC, rightD,
            allRightAngles, hasAnyRightAngle,
            allEdgesEqual, oppEdgesEqual,
            diagEqual, diagPerp, diagBisect,
            oppAnglesEqual, kiteEdges, isoAngles,
            perimeter, area
        };

        // Phân loại hình theo thứ tự ưu tiên từ đặc biệt nhất đến cơ bản
        let detectedKey = 'tu_giac';

        if ((para1 && para2 && allRightAngles && allEdgesEqual) || (allRightAngles && allEdgesEqual)) {
            detectedKey = 'hinh_vuong';
        } else if ((para1 && para2 && allRightAngles) || (oppEdgesEqual && allRightAngles)) {
            detectedKey = 'hinh_chu_nhat';
        } else if ((para1 && para2 && allEdgesEqual) || (allEdgesEqual && diagPerp)) {
            detectedKey = 'hinh_thoi';
        } else if ((para1 && para2) || (oppEdgesEqual && (para1 || para2))) {
            detectedKey = 'hinh_binh_hanh';
        } else if (kiteEdges && diagPerp) {
            detectedKey = 'hinh_dieu';
        } else if ((para1 || para2) && hasAnyRightAngle) {
            detectedKey = 'hinh_thang_vuong';
        } else if ((para1 || para2) && (isoAngles || diagEqual || isClose(lenDA, lenBC))) {
            detectedKey = 'hinh_thang_can';
        } else if (para1 || para2) {
            detectedKey = 'hinh_thang';
        }

        return {
            shapeKey: detectedKey,
            shape: NEO4J_SHAPES_DATA[detectedKey],
            metrics: data
        };
    }

    render() {
        this.ctx.clearRect(0, 0, this.width, this.height);

        const analysis = this.analyzeGeometry();
        this.currentDetection = analysis;
        const { shape, metrics } = analysis;

        // 1. Vẽ nền và lưới ô ly toán học
        if (this.options.showGrid) {
            this.drawGrid();
        }

        // 2. Vẽ đường chéo (nếu bật)
        if (this.options.showDiagonals) {
            this.drawDiagonals(metrics);
        }

        // 3. Tô màu lòng tứ giác
        this.ctx.beginPath();
        this.ctx.moveTo(this.vertices[0].x, this.vertices[0].y);
        for (let i = 1; i < 4; i++) {
            this.ctx.lineTo(this.vertices[i].x, this.vertices[i].y);
        }
        this.ctx.closePath();
        this.ctx.fillStyle = shape.color + '1A'; // 10% opacity
        this.ctx.fill();

        // 4. Vẽ các cạnh tứ giác
        this.ctx.lineWidth = 3.5;
        this.ctx.strokeStyle = shape.color;
        this.ctx.stroke();

        // 5. Vẽ ký hiệu góc vuông và cung tròn góc
        if (this.options.showAngles) {
            this.drawCornerAngles(metrics);
        }

        // 6. Vẽ ký hiệu cạnh song song và cạnh bằng nhau
        this.drawEdgeDecorations(metrics);

        // 7. Vẽ nhãn độ dài cạnh
        if (this.options.showLengths) {
            this.drawEdgeLabels(metrics);
        }

        // 8. Vẽ 4 đỉnh A, B, C, D
        this.drawVertices();

        // 8.5. Vẽ đường gióng thẳng hàng khi giữ Shift
        if (this.activeGuide) {
            this.drawAlignmentGuide();
        }

        // 9. Cập nhật Sidebar bên phải
        this.updateSidebarUI(analysis);
    }

    drawAlignmentGuide() {
        if (!this.activeGuide) return;
        const g = this.activeGuide;
        this.ctx.save();

        if (g.type === 'perpendicular') {
            // Phong cách Vuông góc: Nét đứt màu đỏ tươi #DC2626
            this.ctx.setLineDash([6, 4]);
            this.ctx.lineWidth = 1.8;
            this.ctx.strokeStyle = '#DC2626';

            if (g.subtype === 'corner') {
                // Góc vuông 90° tại đỉnh: Vẽ 2 đường gióng ngang và dọc cắt nhau
                this.ctx.beginPath();
                this.ctx.moveTo(0, g.y);
                this.ctx.lineTo(this.width, g.y);
                this.ctx.moveTo(g.x, 0);
                this.ctx.lineTo(g.x, this.height);
                this.ctx.stroke();

                // Vẽ ô vuông góc vuông 90° nhỏ tại (g.x, g.y)
                this.drawSquareCornerMarker(g.x, g.y, 14);
                this.drawGuideTag(`🔒 SHIFT: ${g.label} (Khớp ô ly)`, g.x + 14, g.y - 14, '#DC2626');
            } else if (g.subtype === 'horizontal') {
                this.ctx.beginPath();
                this.ctx.moveTo(0, g.y);
                this.ctx.lineTo(this.width, g.y);
                this.ctx.stroke();
                this.drawGuideTag(`🔒 SHIFT: ${g.label} (Khớp ô ly)`, Math.max(20, Math.min(this.width - 280, 60)), g.y - 12, '#DC2626');
            } else if (g.subtype === 'vertical') {
                this.ctx.beginPath();
                this.ctx.moveTo(g.x, 0);
                this.ctx.lineTo(g.x, this.height);
                this.ctx.stroke();
                this.drawGuideTag(`🔒 SHIFT: ${g.label} (Khớp ô ly)`, g.x + 10, Math.max(24, Math.min(this.height - 30, 45)), '#DC2626');
            }
        } else if (g.type === 'parallel') {
            // Phong cách Song song: 2 đường nét đứt màu xanh dương #2563EB
            this.ctx.setLineDash([7, 4]);
            this.ctx.lineWidth = 2.0;
            this.ctx.strokeStyle = '#2563EB';

            if (g.subtype === 'horizontal') {
                // 2 đường thẳng song song ngang
                this.ctx.beginPath();
                this.ctx.moveTo(0, g.y1);
                this.ctx.lineTo(this.width, g.y1);
                this.ctx.moveTo(0, g.y2);
                this.ctx.lineTo(this.width, g.y2);
                this.ctx.stroke();

                const tagY = Math.min(g.y1, g.y2) - 12;
                this.drawGuideTag(`🔒 SHIFT: ${g.label} (Khớp ô ly)`, Math.max(20, Math.min(this.width - 320, 60)), tagY, '#2563EB');
            } else if (g.subtype === 'vertical') {
                // 2 đường thẳng song song dọc
                this.ctx.beginPath();
                this.ctx.moveTo(g.x1, 0);
                this.ctx.lineTo(g.x1, this.height);
                this.ctx.moveTo(g.x2, 0);
                this.ctx.lineTo(g.x2, this.height);
                this.ctx.stroke();

                const tagX = Math.min(g.x1, g.x2) + 10;
                this.drawGuideTag(`🔒 SHIFT: ${g.label} (Khớp ô ly)`, tagX, Math.max(24, Math.min(this.height - 30, 45)), '#2563EB');
            } else if (g.subtype === 'slanted') {
                this.drawExtendedLine(g.x1, g.y1, g.x2, g.y2);
                this.drawExtendedLine(g.refX1, g.refY1, g.refX2, g.refY2);
                this.drawGuideTag(`🔒 SHIFT: ${g.label} (Khớp ô ly)`, g.x2 + 12, g.y2 - 12, '#2563EB');
            }
        } else if (g.type === 'grid') {
            // Khớp ô ly chính: Vòng tròn tâm điểm & chữ nhỏ màu ngọc lục bảo
            this.ctx.strokeStyle = '#059669';
            this.ctx.setLineDash([3, 3]);
            this.ctx.lineWidth = 1.4;
            this.ctx.beginPath();
            this.ctx.arc(g.x, g.y, 10, 0, Math.PI * 2);
            this.ctx.stroke();

            this.drawGuideTag(`🔒 SHIFT: ${g.label}`, g.x + 14, g.y - 12, '#059669');
        }

        this.ctx.restore();
    }

    drawSquareCornerMarker(x, y, sz = 12) {
        this.ctx.save();
        this.ctx.setLineDash([]);
        this.ctx.strokeStyle = '#DC2626';
        this.ctx.lineWidth = 1.8;
        this.ctx.strokeRect(x - sz, y - sz, sz, sz);
        this.ctx.restore();
    }

    drawExtendedLine(x1, y1, x2, y2) {
        const dx = x2 - x1;
        const dy = y2 - y1;
        if (dx === 0 && dy === 0) return;
        const len = Math.hypot(dx, dy);
        const ux = dx / len;
        const uy = dy / len;
        this.ctx.beginPath();
        this.ctx.moveTo(x1 - ux * 1000, y1 - uy * 1000);
        this.ctx.lineTo(x2 + ux * 1000, y2 + uy * 1000);
        this.ctx.stroke();
    }

    drawGuideTag(text, x, y, bgColor = '#EF4444') {
        this.ctx.save();
        this.ctx.font = '700 11px "JetBrains Mono", monospace';
        const w = this.ctx.measureText(text).width;
        const safeX = Math.max(12, Math.min(this.width - w - 24, x));
        const safeY = Math.max(18, Math.min(this.height - 18, y));
        this.ctx.fillStyle = bgColor;
        this.ctx.beginPath();
        this.ctx.roundRect(safeX - 6, safeY - 12, w + 16, 22, 6);
        this.ctx.fill();
        this.ctx.fillStyle = '#FFFFFF';
        this.ctx.textAlign = 'left';
        this.ctx.textBaseline = 'middle';
        this.ctx.fillText(text, safeX + 2, safeY - 1);
        this.ctx.restore();
    }

    drawGrid() {
        this.ctx.save();

        // 1. Nền giấy ô ly toán học cao cấp
        this.ctx.fillStyle = '#FAFBFC';
        this.ctx.fillRect(0, 0, this.width, this.height);

        const sz = this.options.gridSize; // 25px = 1cm
        const halfSz = sz / 2; // 12.5px = 0.5cm

        // 2. Lưới phụ (Sub-grid 12.5px) - Nét siêu mảnh
        this.ctx.strokeStyle = '#F1F5F9';
        this.ctx.lineWidth = 0.6;
        for (let x = 0; x < this.width; x += halfSz) {
            if (x % sz !== 0) {
                this.ctx.beginPath();
                this.ctx.moveTo(x, 0);
                this.ctx.lineTo(x, this.height);
                this.ctx.stroke();
            }
        }
        for (let y = 0; y < this.height; y += halfSz) {
            if (y % sz !== 0) {
                this.ctx.beginPath();
                this.ctx.moveTo(0, y);
                this.ctx.lineTo(this.width, y);
                this.ctx.stroke();
            }
        }

        // 3. Lưới chính (Major-grid 25px = 1cm) - Nét kẻ rõ ràng
        this.ctx.strokeStyle = '#E2E8F0';
        this.ctx.lineWidth = 0.9;
        for (let x = 0; x < this.width; x += sz) {
            this.ctx.beginPath();
            this.ctx.moveTo(x, 0);
            this.ctx.lineTo(x, this.height);
            this.ctx.stroke();
        }
        for (let y = 0; y < this.height; y += sz) {
            this.ctx.beginPath();
            this.ctx.moveTo(0, y);
            this.ctx.lineTo(this.width, y);
            this.ctx.stroke();
        }

        // 4. Lưới mốc 50px (2cm) kèm đánh số cm ở viền trên và viền trái
        this.ctx.strokeStyle = '#CBD5E1';
        this.ctx.lineWidth = 1.2;
        this.ctx.font = '600 9.5px "JetBrains Mono", monospace';
        this.ctx.fillStyle = '#94A3B8';

        for (let x = sz * 2; x < this.width; x += sz * 2) {
            this.ctx.beginPath();
            this.ctx.moveTo(x, 0);
            this.ctx.lineTo(x, this.height);
            this.ctx.stroke();

            const cmVal = Math.round(x / this.PIXELS_PER_CM);
            this.ctx.fillText(`${cmVal}cm`, x + 3, 12);
        }

        for (let y = sz * 2; y < this.height; y += sz * 2) {
            this.ctx.beginPath();
            this.ctx.moveTo(0, y);
            this.ctx.lineTo(this.width, y);
            this.ctx.stroke();

            const cmVal = Math.round(y / this.PIXELS_PER_CM);
            this.ctx.fillText(`${cmVal}cm`, 4, y - 4);
        }

        // 5. Vẽ dấu chấm giao điểm tinh tế (Crosshair dots)
        this.ctx.fillStyle = '#94A3B8';
        for (let x = sz * 2; x < this.width; x += sz * 2) {
            for (let y = sz * 2; y < this.height; y += sz * 2) {
                this.ctx.beginPath();
                this.ctx.arc(x, y, 1.4, 0, Math.PI * 2);
                this.ctx.fill();
            }
        }

        this.ctx.restore();
    }

    drawDiagonals(m) {
        this.ctx.save();
        this.ctx.setLineDash([5, 5]);
        this.ctx.strokeStyle = '#94A3B8';
        this.ctx.lineWidth = 1.5;

        // AC
        this.ctx.beginPath();
        this.ctx.moveTo(this.vertices[0].x, this.vertices[0].y);
        this.ctx.lineTo(this.vertices[2].x, this.vertices[2].y);
        this.ctx.stroke();

        // BD
        this.ctx.beginPath();
        this.ctx.moveTo(this.vertices[1].x, this.vertices[1].y);
        this.ctx.lineTo(this.vertices[3].x, this.vertices[3].y);
        this.ctx.stroke();

        this.ctx.restore();
    }

    drawCornerAngles(m) {
        const angles = [m.angleA, m.angleB, m.angleC, m.angleD];
        const isRights = [m.rightA, m.rightB, m.rightC, m.rightD];

        for (let i = 0; i < 4; i++) {
            const v = this.vertices[i];
            const prev = this.vertices[(i + 3) % 4];
            const next = this.vertices[(i + 1) % 4];

            const a1 = Math.atan2(prev.y - v.y, prev.x - v.x);
            const a2 = Math.atan2(next.y - v.y, next.x - v.x);

            // Ký hiệu góc vuông (ô vuông nhỏ 12px)
            if (isRights[i]) {
                this.ctx.save();
                this.ctx.lineWidth = 1.8;
                this.ctx.strokeStyle = '#DC2626';

                const sz = 14;
                const u1 = { x: Math.cos(a1) * sz, y: Math.sin(a1) * sz };
                const u2 = { x: Math.cos(a2) * sz, y: Math.sin(a2) * sz };

                this.ctx.beginPath();
                this.ctx.moveTo(v.x + u1.x, v.y + u1.y);
                this.ctx.lineTo(v.x + u1.x + u2.x, v.y + u1.y + u2.y);
                this.ctx.lineTo(v.x + u2.x, v.y + u2.y);
                this.ctx.stroke();

                // Chấm đỏ vuông góc
                this.ctx.fillStyle = '#DC2626';
                this.ctx.beginPath();
                this.ctx.arc(v.x + (u1.x + u2.x) * 0.5, v.y + (u1.y + u2.y) * 0.5, 2, 0, Math.PI * 2);
                this.ctx.fill();

                this.ctx.restore();
            } else {
                // Cung tròn góc thông thường
                this.ctx.save();
                this.ctx.strokeStyle = '#64748B';
                this.ctx.lineWidth = 1.2;
                this.ctx.beginPath();
                this.ctx.arc(v.x, v.y, 20, Math.min(a1, a2), Math.max(a1, a2));
                this.ctx.stroke();
                this.ctx.restore();
            }

            // Text số đo góc
            const bisectAngle = (a1 + a2) / 2 + (Math.abs(a1 - a2) > Math.PI ? Math.PI : 0);
            const dist = isRights[i] ? 28 : 32;
            const textX = v.x + Math.cos(bisectAngle) * dist;
            const textY = v.y + Math.sin(bisectAngle) * dist;

            this.ctx.save();
            this.ctx.font = '600 11px "JetBrains Mono", monospace';
            this.ctx.fillStyle = isRights[i] ? '#DC2626' : '#475569';
            this.ctx.textAlign = 'center';
            this.ctx.textBaseline = 'middle';
            this.ctx.fillText(`${Math.round(angles[i])}°`, textX, textY);
            this.ctx.restore();
        }
    }

    drawEdgeDecorations(m) {
        // Vẽ ký hiệu song song mũi tên >> trên cạnh đối nếu song song
        if (m.para1) {
            this.drawParallelMarker(this.vertices[0], this.vertices[1]);
            this.drawParallelMarker(this.vertices[3], this.vertices[2]);
        }
        if (m.para2) {
            this.drawParallelMarker(this.vertices[1], this.vertices[2]);
            this.drawParallelMarker(this.vertices[0], this.vertices[3]);
        }
    }

    drawParallelMarker(v1, v2) {
        const mx = (v1.x + v2.x) / 2;
        const my = (v1.y + v2.y) / 2;
        const angle = Math.atan2(v2.y - v1.y, v2.x - v1.x);

        this.ctx.save();
        this.ctx.translate(mx, my);
        this.ctx.rotate(angle);
        this.ctx.strokeStyle = '#2563EB';
        this.ctx.lineWidth = 2;

        for (let offset of [-4, 4]) {
            this.ctx.beginPath();
            this.ctx.moveTo(offset - 4, -4);
            this.ctx.lineTo(offset, 0);
            this.ctx.lineTo(offset - 4, 4);
            this.ctx.stroke();
        }

        this.ctx.restore();
    }

    drawEdgeLabels(m) {
        const edgeNames = ['AB', 'BC', 'CD', 'DA'];
        const lens = [m.cmAB, m.cmBC, m.cmCD, m.cmDA];

        for (let i = 0; i < 4; i++) {
            const v1 = this.vertices[i];
            const v2 = this.vertices[(i + 1) % 4];

            const mx = (v1.x + v2.x) / 2;
            const my = (v1.y + v2.y) / 2;

            // Đẩy nhãn ra ngoài cạnh một chút
            const dx = v2.x - v1.x;
            const dy = v2.y - v1.y;
            const normalX = -dy / Math.hypot(dx, dy);
            const normalY = dx / Math.hypot(dx, dy);

            const labelX = mx + normalX * 16;
            const labelY = my + normalY * 16;

            const text = `${edgeNames[i]}: ${lens[i].toFixed(1)} cm`;

            this.ctx.save();
            this.ctx.font = '700 11.5px "Plus Jakarta Sans", sans-serif';
            const metrics = this.ctx.measureText(text);
            const padX = 6, padY = 3;

            this.ctx.fillStyle = 'rgba(255, 255, 255, 0.95)';
            this.ctx.strokeStyle = '#CBD5E1';
            this.ctx.lineWidth = 1;
            this.ctx.beginPath();
            this.ctx.roundRect(labelX - metrics.width / 2 - padX, labelY - 8 - padY, metrics.width + padX * 2, 16 + padY * 2, 4);
            this.ctx.fill();
            this.ctx.stroke();

            this.ctx.fillStyle = '#0F172A';
            this.ctx.textAlign = 'center';
            this.ctx.textBaseline = 'middle';
            this.ctx.fillText(text, labelX, labelY);
            this.ctx.restore();
        }
    }

    drawVertices() {
        for (let i = 0; i < this.vertices.length; i++) {
            const v = this.vertices[i];
            const isHovered = (this.isHoveringVertex === i) || (this.dragTargetIndex === i && this.dragMode === 'vertex');

            this.ctx.save();
            // Vòng tròn hào quang khi hover
            if (isHovered) {
                this.ctx.beginPath();
                this.ctx.arc(v.x, v.y, 22, 0, Math.PI * 2);
                this.ctx.fillStyle = v.color + '33';
                this.ctx.fill();
            }

            // Vòng tròn đỉnh
            this.ctx.beginPath();
            this.ctx.arc(v.x, v.y, 11, 0, Math.PI * 2);
            this.ctx.fillStyle = '#FFFFFF';
            this.ctx.fill();
            this.ctx.lineWidth = 3.5;
            this.ctx.strokeStyle = v.color;
            this.ctx.stroke();

            // Chữ tên đỉnh
            this.ctx.font = '800 13px "Plus Jakarta Sans", sans-serif';
            this.ctx.fillStyle = v.color;
            this.ctx.textAlign = 'center';
            this.ctx.textBaseline = 'middle';
            this.ctx.fillText(v.label, v.x, v.y);

            this.ctx.restore();
        }
    }

    updateSidebarUI(analysis) {
        const { shape, metrics } = analysis;

        // Cập nhật Hộp Nhận Diện Header
        const headerBox = document.getElementById('detected-box');
        if (headerBox) {
            headerBox.style.backgroundColor = shape.bgColor;
            headerBox.style.borderColor = shape.borderColor;
        }

        const titleEl = document.getElementById('detected-title');
        if (titleEl) {
            titleEl.style.color = shape.color;
            titleEl.innerHTML = `<span>${shape.name}</span> <span style="font-size:0.85rem;color:#64748B;font-weight:600;">(${shape.name_en})</span>`;
        }

        const badgeEl = document.getElementById('detected-badge');
        if (badgeEl) {
            badgeEl.innerText = shape.badge;
            badgeEl.style.backgroundColor = shape.color;
        }

        const gradeEl = document.getElementById('detected-grade');
        if (gradeEl) {
            gradeEl.innerText = shape.gradeText;
        }

        const defEl = document.getElementById('detected-desc');
        if (defEl) {
            defEl.innerText = shape.definition;
        }

        // Cập nhật Thông Số Đo Lường
        document.getElementById('metric-edges').innerText = 
            `AB=${metrics.cmAB.toFixed(1)} | BC=${metrics.cmBC.toFixed(1)} | CD=${metrics.cmCD.toFixed(1)} | DA=${metrics.cmDA.toFixed(1)}`;
        
        document.getElementById('metric-angles').innerText = 
            `∠A=${Math.round(metrics.angleA)}° | ∠B=${Math.round(metrics.angleB)}° | ∠C=${Math.round(metrics.angleC)}° | ∠D=${Math.round(metrics.angleD)}°`;

        document.getElementById('metric-perimeter').innerText = `${metrics.perimeter.toFixed(1)} cm`;
        document.getElementById('metric-area').innerText = `${metrics.area.toFixed(1)} cm²`;

        // Cập nhật Danh Sách Điều Kiện Toán Học (Live Checklist)
        const checklistEl = document.getElementById('condition-checklist');
        if (checklistEl) {
            checklistEl.innerHTML = shape.conditions.map(c => {
                const isMet = c.check(metrics);
                return `
                    <li class="condition-item ${isMet ? 'met' : 'not-met'}">
                        <span class="condition-icon">${isMet ? '✓' : '○'}</span>
                        <span>${c.text}</span>
                    </li>
                `;
            }).join('');
        }
    }

    // =========================================================================
    // CÁC HÌNH MẪU (PRESETS)
    // =========================================================================
    applyPreset(type) {
        const sz = this.options.gridSize; // 25px = 1.0cm
        const cx = Math.round((this.width / 2) / sz) * sz;
        const cy = Math.round((this.height / 2) / sz) * sz;

        document.querySelectorAll('.preset-chip').forEach(chip => {
            chip.classList.toggle('active', chip.getAttribute('data-preset') === type);
        });

        switch (type) {
            case 'hinh_vuong':
                const s = 100; // 4cm
                this.vertices = [
                    { id: 'A', x: cx - s, y: cy - s, label: 'A', color: '#EF4444' },
                    { id: 'B', x: cx + s, y: cy - s, label: 'B', color: '#3B82F6' },
                    { id: 'C', x: cx + s, y: cy + s, label: 'C', color: '#10B981' },
                    { id: 'D', x: cx - s, y: cy + s, label: 'D', color: '#F59E0B' }
                ];
                break;

            case 'hinh_chu_nhat':
                const w = 150, h = 100; // 6cm x 4cm
                this.vertices = [
                    { id: 'A', x: cx - w, y: cy - h, label: 'A', color: '#EF4444' },
                    { id: 'B', x: cx + w, y: cy - h, label: 'B', color: '#3B82F6' },
                    { id: 'C', x: cx + w, y: cy + h, label: 'C', color: '#10B981' },
                    { id: 'D', x: cx - w, y: cy + h, label: 'D', color: '#F59E0B' }
                ];
                break;

            case 'hinh_thoi':
                const rx = 125, ry = 100; // 5cm x 4cm
                this.vertices = [
                    { id: 'A', x: cx, y: cy - ry, label: 'A', color: '#EF4444' },
                    { id: 'B', x: cx + rx, y: cy, label: 'B', color: '#3B82F6' },
                    { id: 'C', x: cx, y: cy + ry, label: 'C', color: '#10B981' },
                    { id: 'D', x: cx - rx, y: cy, label: 'D', color: '#F59E0B' }
                ];
                break;

            case 'hinh_binh_hanh':
                const bw = 125, bh = 75, shift = 50; // bội số 25px
                this.vertices = [
                    { id: 'A', x: cx - bw + shift, y: cy - bh, label: 'A', color: '#EF4444' },
                    { id: 'B', x: cx + bw, y: cy - bh, label: 'B', color: '#3B82F6' },
                    { id: 'C', x: cx + bw - shift, y: cy + bh, label: 'C', color: '#10B981' },
                    { id: 'D', x: cx - bw, y: cy + bh, label: 'D', color: '#F59E0B' }
                ];
                break;

            case 'hinh_thang_can':
                const topW = 75, botW = 150, th = 75; // bội số 25px
                this.vertices = [
                    { id: 'A', x: cx - topW, y: cy - th, label: 'A', color: '#EF4444' },
                    { id: 'B', x: cx + topW, y: cy - th, label: 'B', color: '#3B82F6' },
                    { id: 'C', x: cx + botW, y: cy + th, label: 'C', color: '#10B981' },
                    { id: 'D', x: cx - botW, y: cy + th, label: 'D', color: '#F59E0B' }
                ];
                break;

            case 'hinh_thang_vuong':
                const tW = 75, bW = 150, vh = 75; // bội số 25px
                const baseX = cx - 100;
                this.vertices = [
                    { id: 'A', x: baseX, y: cy - vh, label: 'A', color: '#EF4444' },
                    { id: 'B', x: baseX + tW, y: cy - vh, label: 'B', color: '#3B82F6' },
                    { id: 'C', x: baseX + bW, y: cy + vh, label: 'C', color: '#10B981' },
                    { id: 'D', x: baseX, y: cy + vh, label: 'D', color: '#F59E0B' }
                ];
                break;

            case 'hinh_dieu':
                const kw = 100, khTop = 75, khBot = 125; // bội số 25px
                this.vertices = [
                    { id: 'A', x: cx, y: cy - khTop, label: 'A', color: '#EF4444' },
                    { id: 'B', x: cx + kw, y: cy, label: 'B', color: '#3B82F6' },
                    { id: 'C', x: cx, y: cy + khBot, label: 'C', color: '#10B981' },
                    { id: 'D', x: cx - kw, y: cy, label: 'D', color: '#F59E0B' }
                ];
                break;

            case 'tu_giac':
            default:
                this.vertices = [
                    { id: 'A', x: cx - 100, y: cy - 75, label: 'A', color: '#EF4444' },
                    { id: 'B', x: cx + 125, y: cy - 50, label: 'B', color: '#3B82F6' },
                    { id: 'C', x: cx + 75, y: cy + 100, label: 'C', color: '#10B981' },
                    { id: 'D', x: cx - 125, y: cy + 75, label: 'D', color: '#F59E0B' }
                ];
                break;
        }

        this.render();
    }

    toggleOption(opt) {
        if (this.options.hasOwnProperty(opt)) {
            this.options[opt] = !this.options[opt];
            this.render();
            return this.options[opt];
        }
        return false;
    }
}

// Khởi tạo cỗ máy vẽ khi trang tải xong
let quadLabInstance = null;
document.addEventListener('DOMContentLoaded', () => {
    quadLabInstance = new QuadrilateralDrawingLab('quad-canvas');
});

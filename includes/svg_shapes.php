<?php
/**
 * ==============================================================================
 * ĐỒ ÁN MÔN NOSQL (NEO4J) - NHÓM 9: BÉ HỌC TỨ GIÁC
 * File: includes/svg_shapes.php - Bộ sinh hình vẽ SVG minh họa sắc nét từng hình tứ giác
 * ==============================================================================
 */

function renderShapeSvg($shapeId, $width = 240, $height = 140) {
    switch ($shapeId) {
        case 'hinh_vuong':
            return '
            <svg viewBox="0 0 240 140" width="' . $width . '" height="' . $height . '" xmlns="http://www.w3.org/2000/svg">
                <rect x="70" y="20" width="100" height="100" rx="4" fill="rgba(236, 72, 153, 0.15)" stroke="#EC4899" stroke-width="3" />
                <rect x="70" y="20" width="14" height="14" fill="none" stroke="#EC4899" stroke-width="1.5" />
                <rect x="156" y="20" width="14" height="14" fill="none" stroke="#EC4899" stroke-width="1.5" />
                <rect x="156" y="106" width="14" height="14" fill="none" stroke="#EC4899" stroke-width="1.5" />
                <rect x="70" y="106" width="14" height="14" fill="none" stroke="#EC4899" stroke-width="1.5" />
                <line x1="117" y1="16" x2="123" y2="24" stroke="#EC4899" stroke-width="2" />
                <line x1="117" y1="116" x2="123" y2="124" stroke="#EC4899" stroke-width="2" />
                <line x1="66" y1="67" x2="74" y2="73" stroke="#EC4899" stroke-width="2" />
                <line x1="166" y1="67" x2="174" y2="73" stroke="#EC4899" stroke-width="2" />
            </svg>';

        case 'hinh_chu_nhat':
            return '
            <svg viewBox="0 0 240 140" width="' . $width . '" height="' . $height . '" xmlns="http://www.w3.org/2000/svg">
                <rect x="40" y="30" width="160" height="80" rx="4" fill="rgba(16, 185, 129, 0.15)" stroke="#10B981" stroke-width="3" />
                <rect x="40" y="30" width="14" height="14" fill="none" stroke="#10B981" stroke-width="1.5" />
                <rect x="186" y="30" width="14" height="14" fill="none" stroke="#10B981" stroke-width="1.5" />
                <rect x="186" y="96" width="14" height="14" fill="none" stroke="#10B981" stroke-width="1.5" />
                <rect x="40" y="96" width="14" height="14" fill="none" stroke="#10B981" stroke-width="1.5" />
                <text x="120" y="24" text-anchor="middle" font-weight="bold" fill="#047857" font-size="11">dài (a)</text>
                <text x="212" y="74" text-anchor="start" font-weight="bold" fill="#047857" font-size="11">rộng (b)</text>
            </svg>';

        case 'hinh_binh_hanh':
            return '
            <svg viewBox="0 0 240 140" width="' . $width . '" height="' . $height . '" xmlns="http://www.w3.org/2000/svg">
                <polygon points="65,30 205,30 175,110 35,110" fill="rgba(56, 189, 248, 0.18)" stroke="#0284C7" stroke-width="3" stroke-linejoin="round" />
                <line x1="65" y1="30" x2="65" y2="110" stroke="#EF4444" stroke-dasharray="3" stroke-width="1.5" />
                <rect x="65" y="98" width="12" height="12" fill="none" stroke="#EF4444" stroke-width="1" />
                <text x="56" y="75" text-anchor="end" font-weight="bold" fill="#EF4444" font-size="11">h</text>
                <text x="105" y="125" text-anchor="middle" font-weight="bold" fill="#0284C7" font-size="11">đáy (a)</text>
            </svg>';

        case 'hinh_thoi':
            return '
            <svg viewBox="0 0 240 140" width="' . $width . '" height="' . $height . '" xmlns="http://www.w3.org/2000/svg">
                <polygon points="120,20 200,70 120,120 40,70" fill="rgba(5, 150, 105, 0.18)" stroke="#059669" stroke-width="3" stroke-linejoin="round" />
                <line x1="40" y1="70" x2="200" y2="70" stroke="#F59E0B" stroke-dasharray="3" stroke-width="1.5" />
                <line x1="120" y1="20" x2="120" y2="120" stroke="#F59E0B" stroke-dasharray="3" stroke-width="1.5" />
                <rect x="120" y="70" width="10" height="10" fill="none" stroke="#F59E0B" stroke-width="1" />
            </svg>';

        case 'hinh_thang':
            return '
            <svg viewBox="0 0 240 140" width="' . $width . '" height="' . $height . '" xmlns="http://www.w3.org/2000/svg">
                <polygon points="75,35 165,35 205,110 35,110" fill="rgba(14, 165, 233, 0.18)" stroke="#0284C7" stroke-width="3" stroke-linejoin="round" />
                <line x1="75" y1="35" x2="75" y2="110" stroke="#EF4444" stroke-dasharray="3" stroke-width="1.5" />
                <text x="66" y="75" text-anchor="end" font-weight="bold" fill="#EF4444" font-size="11">h</text>
                <text x="120" y="27" text-anchor="middle" font-weight="bold" fill="#0284C7" font-size="11">đáy bé (b)</text>
                <text x="120" y="125" text-anchor="middle" font-weight="bold" fill="#0284C7" font-size="11">đáy lớn (a)</text>
            </svg>';

        case 'hinh_thang_vuong':
            return '
            <svg viewBox="0 0 240 140" width="' . $width . '" height="' . $height . '" xmlns="http://www.w3.org/2000/svg">
                <polygon points="50,35 150,35 200,110 50,110" fill="rgba(6, 182, 212, 0.18)" stroke="#0891B2" stroke-width="3" stroke-linejoin="round" />
                <rect x="50" y="35" width="12" height="12" fill="none" stroke="#0891B2" stroke-width="1.5" />
                <rect x="50" y="98" width="12" height="12" fill="none" stroke="#0891B2" stroke-width="1.5" />
                <text x="42" y="75" text-anchor="end" font-weight="bold" fill="#0891B2" font-size="11">h</text>
            </svg>';

        case 'hinh_thang_can':
            return '
            <svg viewBox="0 0 240 140" width="' . $width . '" height="' . $height . '" xmlns="http://www.w3.org/2000/svg">
                <polygon points="70,35 170,35 205,110 35,110" fill="rgba(20, 184, 166, 0.18)" stroke="#0D9488" stroke-width="3" stroke-linejoin="round" />
                <line x1="48" y1="70" x2="56" y2="75" stroke="#0D9488" stroke-width="2" />
                <line x1="184" y1="75" x2="192" y2="70" stroke="#0D9488" stroke-width="2" />
            </svg>';

        case 'hinh_dieu':
            return '
            <svg viewBox="0 0 240 140" width="' . $width . '" height="' . $height . '" xmlns="http://www.w3.org/2000/svg">
                <polygon points="120,15 180,55 120,125 60,55" fill="rgba(249, 115, 22, 0.18)" stroke="#EA580C" stroke-width="3" stroke-linejoin="round" />
                <line x1="60" y1="55" x2="180" y2="55" stroke="#C2410C" stroke-dasharray="3" stroke-width="1.5" />
                <line x1="120" y1="15" x2="120" y2="125" stroke="#C2410C" stroke-dasharray="3" stroke-width="1.5" />
                <circle cx="120" cy="55" r="3" fill="#C2410C" />
            </svg>';

        case 'tu_giac':
        default:
            return '
            <svg viewBox="0 0 240 140" width="' . $width . '" height="' . $height . '" xmlns="http://www.w3.org/2000/svg">
                <polygon points="50,40 180,25 200,105 40,115" fill="rgba(2, 132, 199, 0.15)" stroke="#0284C7" stroke-width="3" stroke-linejoin="round" />
                <text x="115" y="25" text-anchor="middle" font-weight="bold" fill="#0284C7" font-size="11">a</text>
                <text x="198" y="65" text-anchor="start" font-weight="bold" fill="#0284C7" font-size="11">b</text>
                <text x="120" y="125" text-anchor="middle" font-weight="bold" fill="#0284C7" font-size="11">c</text>
                <text x="36" y="80" text-anchor="end" font-weight="bold" fill="#0284C7" font-size="11">d</text>
            </svg>';
    }
}

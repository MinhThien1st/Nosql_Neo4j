/**
 * ==============================================================================
 * GEOQUADRILATERAL - NHÓM 9
 * File: assets/js/app.js - Tiện ích tương tác giao diện và hiệu ứng trực quan
 * ==============================================================================
 */

// 1. Hệ thống âm thanh đã được tắt hoàn toàn để đảm bảo yên tĩnh khi báo cáo
const SoundEffects = {
    init() {},
    playTone() {},
    click() {},
    correct() {},
    wrong() {},
    celebrate() {}
};

// 2. Chức năng đọc Text-to-Speech (tắt âm thanh theo yêu cầu)
function speakText(text) {
    // Tắt phát âm thanh
    return;
}

// 3. Hiệu ứng hạt giấy chúc mừng (Chỉ hiệu ứng hình ảnh, không âm thanh)
function triggerConfetti() {
    const colors = ['#2563EB', '#4F46E5', '#0D9488', '#D97706', '#E11D48', '#38BDF8'];
    const container = document.body;

    for (let i = 0; i < 50; i++) {
        const confetti = document.createElement('div');
        confetti.className = 'confetti-particle';
        confetti.style.position = 'fixed';
        confetti.style.zIndex = '9999';
        confetti.style.left = Math.random() * 100 + 'vw';
        confetti.style.top = '-20px';
        confetti.style.width = Math.random() * 10 + 6 + 'px';
        confetti.style.height = Math.random() * 10 + 6 + 'px';
        confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
        confetti.style.borderRadius = Math.random() > 0.5 ? '50%' : '2px';
        confetti.style.transform = `rotate(${Math.random() * 360}deg)`;
        confetti.style.pointerEvents = 'none';

        container.appendChild(confetti);

        const duration = Math.random() * 2 + 1.2;
        const drift = (Math.random() - 0.5) * 180;

        confetti.animate([
            { transform: `translate(0, 0) rotate(0deg)`, opacity: 1 },
            { transform: `translate(${drift}px, ${window.innerHeight + 40}px) rotate(${Math.random() * 720}deg)`, opacity: 0 }
        ], {
            duration: duration * 1000,
            easing: 'cubic-bezier(0.25, 0.46, 0.45, 0.94)'
        }).onfinish = () => confetti.remove();
    }
}

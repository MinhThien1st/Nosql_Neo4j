/**
 * ==============================================================================
 * ĐỒ ÁN MÔN NOSQL (NEO4J) - NHÓM 9: BÉ HỌC TỨ GIÁC
 * File: assets/js/app.js - Tiện ích âm thanh, Text-to-Speech và tương tác chung
 * ==============================================================================
 */

// 1. Hệ thống âm thanh Web Audio API (Synthesizer không phụ thuộc file mp3 ngoài)
const SoundEffects = {
    audioCtx: null,

    init() {
        if (!this.audioCtx) {
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            if (AudioContext) {
                this.audioCtx = new AudioContext();
            }
        }
    },

    playTone(freq, type, duration, delay = 0) {
        this.init();
        if (!this.audioCtx) return;

        setTimeout(() => {
            try {
                const osc = this.audioCtx.createOscillator();
                const gain = this.audioCtx.createGain();
                osc.type = type;
                osc.frequency.setValueAtTime(freq, this.audioCtx.currentTime);

                gain.gain.setValueAtTime(0.2, this.audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, this.audioCtx.currentTime + duration);

                osc.connect(gain);
                gain.connect(this.audioCtx.destination);

                osc.start();
                osc.stop(this.audioCtx.currentTime + duration);
            } catch (e) {
                console.log(e);
            }
        }, delay);
    },

    click() {
        this.playTone(600, 'sine', 0.08);
    },

    correct() {
        // Hợp âm chúc mừng ting ting
        this.playTone(523.25, 'triangle', 0.2, 0);   // C5
        this.playTone(659.25, 'triangle', 0.2, 100); // E5
        this.playTone(783.99, 'triangle', 0.35, 200); // G5
        this.playTone(1046.50, 'sine', 0.5, 300);   // C6
    },

    wrong() {
        // Âm báo sai tiếc nuối
        this.playTone(280, 'sawtooth', 0.2, 0);
        this.playTone(220, 'sawtooth', 0.3, 120);
    },

    celebrate() {
        // Pháo hoa âm thanh
        [523, 659, 783, 1046, 1318].forEach((f, idx) => {
            this.playTone(f, 'sine', 0.3, idx * 80);
        });
    }
};

// 2. Chức năng Text-to-Speech (Đọc to cho các em nhỏ chưa đọc nhanh)
function speakText(text) {
    if (!('speechSynthesis' in window)) {
        alert('Trình duyệt của bạn chưa hỗ trợ giọng đọc Text-to-Speech.');
        return;
    }

    window.speechSynthesis.cancel(); // Dừng câu trước nếu đang đọc
    const utterance = new SpeechSynthesisUtterance(text);
    utterance.lang = 'vi-VN';
    utterance.rate = 0.95; // Đọc chậm rãi, rõ ràng cho trẻ em
    utterance.pitch = 1.1;

    // Tìm giọng tiếng Việt nếu có
    const voices = window.speechSynthesis.getVoices();
    const vnVoice = voices.find(v => v.lang.includes('vi') || v.lang.includes('VN'));
    if (vnVoice) {
        utterance.voice = vnVoice;
    }

    window.speechSynthesis.speak(utterance);
}

// 3. Hiệu ứng hạt giấy Pháo hoa chúc mừng (Confetti)
function triggerConfetti() {
    SoundEffects.celebrate();
    const colors = ['#4F46E5', '#EC4899', '#10B981', '#F59E0B', '#06B6D4', '#8B5CF6'];
    const container = document.body;

    for (let i = 0; i < 60; i++) {
        const confetti = document.createElement('div');
        confetti.className = 'confetti-particle';
        confetti.style.position = 'fixed';
        confetti.style.zIndex = '9999';
        confetti.style.left = Math.random() * 100 + 'vw';
        confetti.style.top = '-20px';
        confetti.style.width = Math.random() * 12 + 6 + 'px';
        confetti.style.height = Math.random() * 12 + 6 + 'px';
        confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
        confetti.style.borderRadius = Math.random() > 0.5 ? '50%' : '2px';
        confetti.style.transform = `rotate(${Math.random() * 360}deg)`;
        confetti.style.pointerEvents = 'none';

        container.appendChild(confetti);

        const duration = Math.random() * 2.5 + 1.5;
        const drift = (Math.random() - 0.5) * 200;

        confetti.animate([
            { transform: `translate(0, 0) rotate(0deg)`, opacity: 1 },
            { transform: `translate(${drift}px, ${window.innerHeight + 50}px) rotate(${Math.random() * 720}deg)`, opacity: 0 }
        ], {
            duration: duration * 1000,
            easing: 'cubic-bezier(0.25, 0.46, 0.45, 0.94)'
        }).onfinish = () => confetti.remove();
    }
}

// Gán sự kiện click âm thanh cho các button
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.btn, .nav-item a, .btn-card').forEach(el => {
        el.addEventListener('click', () => SoundEffects.click());
    });
});

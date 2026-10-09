/**
 * ==============================================================================
 * GEOQUADRILATERAL - NHÓM 9
 * File: assets/js/quiz.js - Xử lý Quiz Test trắc nghiệm hình học
 * ==============================================================================
 */

let quizData = [];
let currentIdx = 0;
let score = 0;
let answered = false;

document.addEventListener('DOMContentLoaded', () => {
    const quizBox = document.getElementById('quiz-box');
    if (!quizBox) return;

    if (window.QUIZ_QUESTIONS && window.QUIZ_QUESTIONS.length > 0) {
        quizData = window.QUIZ_QUESTIONS;
        renderQuestion();
    }
});

function renderQuestion() {
    answered = false;
    const q = quizData[currentIdx];
    const quizBox = document.getElementById('quiz-box');
    if (!q || !quizBox) return;

    const letters = ['A', 'B', 'C', 'D'];
    let optionsHtml = '';
    q.options.forEach((opt, idx) => {
        optionsHtml += `
            <button class="quiz-opt-btn" onclick="selectQuizAnswer(this, '${opt.replace(/'/g, "\\'")}')">
                <span style="width:28px;height:28px;background:var(--bg-muted);border-radius:6px;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.85rem;color:var(--text-strong);flex-shrink:0;">
                    ${letters[idx]}
                </span>
                <span style="flex:1;">${opt}</span>
            </button>
        `;
    });

    quizBox.innerHTML = `
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
            <span class="quiz-badge">Câu hỏi ${currentIdx + 1} / ${quizData.length}</span>
            <div style="font-weight:700;color:var(--primary);font-size:0.95rem;font-family:'JetBrains Mono',monospace;">
                Điểm số: <span id="quiz-score-val" style="font-size:1.15rem;font-weight:800;">${score}</span> pts
            </div>
        </div>

        <h3 class="quiz-question">${q.question}</h3>
        ${q.hint ? `<div style="color:var(--text-muted);font-size:0.9rem;margin-bottom:20px;display:flex;align-items:center;gap:6px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
            Gợi ý: ${q.hint}
        </div>` : ''}

        <div class="quiz-options">
            ${optionsHtml}
        </div>

        <div id="quiz-feedback" class="quiz-feedback"></div>

        <div id="quiz-next-container" style="display:none;margin-top:24px;text-align:right;">
            <button id="btn-quiz-next" onclick="nextQuizQuestion()" class="btn btn-primary" style="padding:11px 24px;">
                ${currentIdx === quizData.length - 1 ? 'Xem Kết Quả Tổng Kết' : 'Câu Kế Tiếp ➔'}
            </button>
        </div>
    `;
}

function selectQuizAnswer(btnEl, selectedText) {
    if (answered) return;
    answered = true;

    const q = quizData[currentIdx];
    const isCorrect = selectedText.trim().toLowerCase() === q.answer.trim().toLowerCase();
    const feedback = document.getElementById('quiz-feedback');
    const nextContainer = document.getElementById('quiz-next-container');

    const allButtons = document.querySelectorAll('.quiz-opt-btn');
    allButtons.forEach(b => {
        if (b.innerText.includes(q.answer)) {
            b.classList.add('correct');
        }
    });

    if (isCorrect) {
        btnEl.classList.add('correct');
        score += 10;
        document.getElementById('quiz-score-val').innerText = score;
        SoundEffects.correct();
        triggerConfetti();

        feedback.style.display = 'block';
        feedback.style.background = '#ECFDF5';
        feedback.style.color = '#065F46';
        feedback.style.border = '1px solid #A7F3D0';
        feedback.innerHTML = `<strong>Chính xác!</strong> ${q.explanation}`;
    } else {
        btnEl.classList.add('wrong');
        SoundEffects.wrong();

        feedback.style.display = 'block';
        feedback.style.background = '#FEF2F2';
        feedback.style.color = '#991B1B';
        feedback.style.border = '1px solid #FECACA';
        feedback.innerHTML = `<strong>Chưa chính xác!</strong> Đáp án đúng là: <strong>${q.answer}</strong>.<br>${q.explanation}`;
    }

    if (nextContainer) {
        nextContainer.style.display = 'block';
    }
}

function nextQuizQuestion() {
    currentIdx++;
    if (currentIdx < quizData.length) {
        renderQuestion();
    } else {
        showFinalResult();
    }
}

function showFinalResult() {
    const quizBox = document.getElementById('quiz-box');
    const maxScore = quizData.length * 10;
    SoundEffects.celebrate();
    triggerConfetti();

    let rating = 'Đạt yêu cầu kiến thức';
    if (score === maxScore) {
        rating = 'Xuất Sắc - Nắm vững 100% bản chất hình học';
    } else if (score >= maxScore * 0.7) {
        rating = 'Khá Giỏi - Hiểu rõ các tính chất trọng tâm';
    } else {
        rating = 'Cần ôn tập lại các mối quan hệ trên sơ đồ';
    }

    quizBox.innerHTML = `
        <div style="padding:32px 16px;text-align:center;">
            <h2 style="font-size:1.8rem;font-weight:800;color:var(--text-strong);margin-bottom:8px;">Hoàn Thành Bài Kiểm Tra</h2>
            <p style="font-size:0.95rem;color:var(--text-muted);margin-bottom:28px;">Kết quả đánh giá kiến thức hình học tứ giác</p>

            <div style="background:#F8FAFC;border:1px solid var(--border-light);border-radius:var(--radius-lg);padding:28px;max-width:380px;margin:0 auto 30px;">
                <div style="font-size:0.8rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;">Tổng Điểm Đạt Được</div>
                <div style="font-size:3rem;font-weight:900;color:var(--primary);font-family:'JetBrains Mono',monospace;">${score} / ${maxScore}</div>
                <div style="display:inline-block;background:var(--primary-subtle);color:var(--primary);font-weight:700;padding:5px 14px;border-radius:999px;font-size:0.82rem;margin-top:12px;border:1px solid #BFDBFE;">
                    ${rating}
                </div>
            </div>

            <button onclick="restartQuiz()" class="btn btn-primary" style="padding:12px 28px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>
                Thực Hiện Lại Bài Test
            </button>
        </div>
    `;
}

function restartQuiz() {
    currentIdx = 0;
    score = 0;
    renderQuestion();
}

<?php
/**
 * ==============================================================================
 * GEOQUADRILATERAL - NHÓM 9
 * File: quiz.php - Quiz Test Kiểm tra kiến thức Hình học Tứ giác
 * ==============================================================================
 */

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/classes/GeometryModel.php';

$model = new GeometryModel();
$questions = $model->getQuizQuestions();
?>

<main class="main-wrapper">
    <div style="text-align: center; margin-bottom: 32px;">
        <span style="background: var(--primary-subtle); color: var(--primary); font-weight: 700; padding: 4px 14px; border-radius: 999px; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid #BFDBFE;">
            Knowledge Assessment
        </span>
        <h1 style="font-size: 2rem; font-weight: 800; margin: 12px 0 8px; color: var(--text-strong); letter-spacing: -0.5px;">
            Quiz Test - Kiểm Tra Kiến Thức
        </h1>
        <p style="color: var(--text-muted); font-size: 0.98rem; max-width: 600px; margin: 0 auto;">
            Hệ thống câu hỏi trắc nghiệm đánh giá mức độ am hiểu về định nghĩa, đặc tính và công thức tính toán các hình tứ giác.
        </p>
    </div>

    <!-- Hộp Chơi Quiz -->
    <div id="quiz-box" class="quiz-card">
        <!-- Render bởi assets/js/quiz.js -->
    </div>
</main>

<script>
    window.QUIZ_QUESTIONS = <?= json_encode($questions, JSON_UNESCAPED_UNICODE) ?>;
</script>
<script src="assets/js/quiz.js"></script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

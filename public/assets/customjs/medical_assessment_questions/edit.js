document.addEventListener('DOMContentLoaded', function () {
    const typeSelect = document.getElementById('questionType');
    const answerField = document.getElementById('answerOptionsField');

    function toggleAnswerOptions() {
        if (typeSelect.value === 'selection') {
            answerField.style.display = 'block';
        } else {
            answerField.style.display = 'none';
        }
    }

    toggleAnswerOptions();

    typeSelect.addEventListener('change', toggleAnswerOptions);
});

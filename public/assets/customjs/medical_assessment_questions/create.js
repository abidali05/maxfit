document.addEventListener('DOMContentLoaded', function () {
    const typeSelect = document.getElementById('questionType');
    const optionsField = document.getElementById('answerOptionsField');

    typeSelect.addEventListener('change', function () {
        if (this.value === 'selection') {
            optionsField.style.display = 'block';
        } else {
            optionsField.style.display = 'none';
        }
    });
});

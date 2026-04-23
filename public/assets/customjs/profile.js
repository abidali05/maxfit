function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
        document.getElementById('profile-preview').src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}

function togglePassword(fieldId, icon) {
    const field = document.getElementById(fieldId);
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
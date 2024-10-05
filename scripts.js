document.querySelectorAll('.input-box input').forEach(input => {
    input.addEventListener('input', () => {
        if (input.value.trim() !== "") {
            input.nextElementSibling.classList.add('active');
        } else {
            input.nextElementSibling.classList.remove('active');
        }
    });
});

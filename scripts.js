const wrapper = document.querySelector('.wrapper');
const loginLink = document.querySelector('.login-link');
const registerLink = document.querySelector('.register-link');
const loginbuttonPopup = document.querySelector('.loginbutton-popup');
const closeIcon = document.querySelector('.icon-close');

registerLink.addEventListener('click', ()=>{
    wrapper.classList.add('active');
});

loginLink.addEventListener('click', ()=>{
    wrapper.classList.remove('active');
});

loginbuttonPopup.addEventListener('click', ()=>{
    wrapper.classList.add('active-popup');
});

closeIcon.addEventListener('click', ()=>{
    wrapper.classList.remove('active-popup');
    wrapper.classList.remove('active');
});

document.querySelectorAll('.input-box input').forEach(input => {
    input.addEventListener('input', () => {
        if (input.value.trim() !== "") {
            input.nextElementSibling.classList.add('active');
        } else {
            input.nextElementSibling.classList.remove('active');
        }
    });
});

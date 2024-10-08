const preloader = document.getElementById('preloader');
const lastWord = document.querySelector('.word:last-child');

lastWord.addEventListener('animationend', hidePreloader);


function hidePreloader() {
    preloader.classList.add('hide-preloader');

    
    window.removeEventListener('keydown', skipPreloader);
    window.removeEventListener('mousedown', skipPreloader);
    lastWord.removeEventListener('animationend', hidePreloader);
}


function skipPreloader() {
    hidePreloader();
}

window.addEventListener('keydown', skipPreloader);
window.addEventListener('mousedown', skipPreloader);

// document.addEventListener('DOMContentLoaded', function() {
//     document.body.classList.add('loaded');
// });


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

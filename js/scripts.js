// Preloader functionality
document.addEventListener('DOMContentLoaded', () => {
    const preloader = document.getElementById('preloader');
    const lastWord = document.querySelector('.word:last-child');

    if (lastWord) {
        lastWord.addEventListener('animationend', hidePreloader);
    } else {
        console.warn('No .word:last-child element found.');
    }

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
});

window.addEventListener('load', () => {
    const preloader = document.getElementById('preloader');
    const preloaderDisabled = localStorage.getItem('preloaderDisabled');
    
    if (!preloaderDisabled) {
        preloader.style.display = 'block';

        setTimeout(() => {
            preloader.style.opacity = '0';
            preloader.style.transition = 'opacity 0.5s';
            setTimeout(() => {
                preloader.style.display = 'none';
            }, 500);
        }, 3000);
    } else {
        preloader.style.display = 'none';
    }

    const disablePreloaderButton = document.getElementById('disable-preloader');
    if (disablePreloaderButton) {
        disablePreloaderButton.addEventListener('click', () => {
            localStorage.setItem('preloaderDisabled', 'true');
        });
    }
});


// Login and Register form toggle
document.addEventListener('DOMContentLoaded', function() {
    // Получаем все необходимые элементы
    const wrapper = document.querySelector('.wrapper');
    const loginLink = document.querySelector('.login-link');
    const registerLink = document.querySelector('.register-link');
    const iconClose = document.querySelector('.icon-close');
    const loginForm = document.querySelector('.form-box.login');
    const registerForm = document.querySelector('.form-box.register');
    const loginButtonPopup = document.querySelector('.loginbutton-popup');
    

    loginForm.style.display = "block";
    registerForm.style.display = "none";
    wrapper.classList.remove('active');


    if (localStorage.getItem('formClosed') === 'true') {
        const lastForm = localStorage.getItem('formState');
        if (lastForm === 'register') {
            wrapper.classList.add('active');
            loginForm.style.display = "none";
            registerForm.style.display = "block";
        }
    }
    // Функция сброса форм
    function resetForms() {
        document.querySelectorAll('input').forEach(input => {
            if (input.type !== 'submit' && input.type !== 'button') {
                input.value = '';
                // Сбрасываем состояние label
                const label = input.nextElementSibling;
                if (label) {
                    label.classList.remove('active');
                }
            }
        });
        
        document.querySelectorAll('.error-message').forEach(error => {
            error.textContent = '';
        });
        
        document.querySelectorAll('.input-box input').forEach(input => {
            input.style.borderColor = '';
        });
        
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.checked = false;
        });
    }

    registerLink?.addEventListener('click', () => {
        wrapper.classList.add('active');
        loginForm.style.display = "none";
        registerForm.style.display = "block";
        localStorage.setItem('formState', 'register');
    });
    
    // В обработчике loginLink
    loginLink?.addEventListener('click', () => {
        wrapper.classList.remove('active');
        registerForm.style.display = "none";
        loginForm.style.display = "block";
        localStorage.setItem('formState', 'login');
    });
    
    // В начале DOMContentLoaded добавьте:
    const formState = localStorage.getItem('formState');
    if (formState === 'register') {
        wrapper.classList.add('active');
        loginForm.style.display = "none";
        registerForm.style.display = "block";
    } else {
        wrapper.classList.remove('active');
        registerForm.style.display = "none";
        loginForm.style.display = "block";
    }

    // Открытие popup
    loginButtonPopup?.addEventListener('click', () => {
        wrapper.style.display = 'flex';
        // Небольшая задержка перед добавлением класса для анимации
        setTimeout(() => {
            wrapper.classList.add('active-popup');
        }, 10);
    });

    // Добавим переменную для отслеживания первого посещения
    let isFirstVisit = !localStorage.getItem('hasVisited');

    // В обработчике DOMContentLoaded:
    if (isFirstVisit) {
    // При первом визите показываем логин форму
    wrapper.classList.remove('active');
    registerForm.style.display = "none";
    loginForm.style.display = "block";
    localStorage.setItem('hasVisited', 'true');
    } else {
    // Для последующих визитов проверяем последнее состояние
    const formState = localStorage.getItem('formState');
    if (formState === 'register') {
        wrapper.classList.add('active');
        loginForm.style.display = "none";
        registerForm.style.display = "block";
    } else {
        wrapper.classList.remove('active');
        registerForm.style.display = "none";
        loginForm.style.display = "block";
    }
}


    // Закрытие формы
    iconClose?.addEventListener('click', () => {
        localStorage.setItem('formState', wrapper.classList.contains('active') ? 'register' : 'login');
        localStorage.setItem('formClosed', 'true');
        // Сначала убираем active и active-popup
        wrapper.classList.remove('active');
        wrapper.classList.remove('active-popup');
        
        // Сброс стилей форм
        loginForm.style.display = "block";
        registerForm.style.display = "none";
        
        // Задержка перед скрытием wrapper
        setTimeout(() => {
            wrapper.style.display = 'none';
            resetForms();
        }, 300);
    });

    // Обработка input полей
    document.querySelectorAll('.input-box input').forEach(input => {
        input.addEventListener('input', () => {
            if (input.value.trim() !== "") {
                input.nextElementSibling?.classList.add('active');
            } else {
                input.nextElementSibling?.classList.remove('active');
            }
        });
    });

    // Preloader functionality
    const preloader = document.getElementById('preloader');
    if (preloader) {
        const preloaderDisabled = localStorage.getItem('preloaderDisabled');
        
        if (!preloaderDisabled) {
            preloader.style.display = 'block';
            setTimeout(() => {
                preloader.style.opacity = '0';
                preloader.style.transition = 'opacity 0.5s';
                setTimeout(() => {
                    preloader.style.display = 'none';
                }, 500);
            }, 3000);
        } else {
            preloader.style.display = 'none';
        }
    }

    // Disable preloader button
    const disablePreloaderButton = document.getElementById('disable-preloader');
    disablePreloaderButton?.addEventListener('click', () => {
        localStorage.setItem('preloaderDisabled', 'true');
    });
});



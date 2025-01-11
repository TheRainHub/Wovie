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

    const wrapper = document.querySelector('.wrapper');
    const loginForm = document.querySelector('.form-box.login');
    const registerForm = document.querySelector('.form-box.register');
    const loginLink = document.querySelector('.login-link');
    const registerLink = document.querySelector('.register-link');
    const iconClose = document.querySelector('.icon-close');
    const loginButtonPopup = document.querySelector('.loginbutton-popup');
    
    function initializeForms() {
        const formState = localStorage.getItem('formState') || 'login';
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

    // Form toggle handlers
    registerLink?.addEventListener('click', (e) => {
        e.preventDefault();
        wrapper.classList.add('active');
        loginForm.style.display = "none";
        registerForm.style.display = "block";
        localStorage.setItem('formState', 'register');
    });

    loginLink?.addEventListener('click', (e) => {
        e.preventDefault();
        wrapper.classList.remove('active');
        registerForm.style.display = "none";
        loginForm.style.display = "block";
        localStorage.setItem('formState', 'login');
    });

    // Form popup handlers
    loginButtonPopup?.addEventListener('click', () => {
        wrapper.style.display = 'flex';
        wrapper.classList.add('active-popup');
        initializeForms();
    });

    iconClose?.addEventListener('click', () => {
        wrapper.classList.remove('active-popup');
        setTimeout(() => {
            wrapper.style.display = 'none';
            wrapper.classList.remove('active');
            resetForms();
        }, 300);
    });

    // Initialize forms on load
    initializeForms();
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

document.addEventListener('DOMContentLoaded', function() {
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

    function resetForms() {
        document.querySelectorAll('input').forEach(input => {
            if (input.type !== 'submit' && input.type !== 'button') {
                input.value = '';
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
    
    loginLink?.addEventListener('click', () => {
        wrapper.classList.remove('active');
        registerForm.style.display = "none";
        loginForm.style.display = "block";
        localStorage.setItem('formState', 'login');
    });
    
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

    loginButtonPopup?.addEventListener('click', () => {
        wrapper.style.display = 'flex';
        setTimeout(() => {
            wrapper.classList.add('active-popup');
        }, 10);
    });

    let isFirstVisit = !localStorage.getItem('hasVisited');

    if (isFirstVisit) {
        wrapper.classList.remove('active');
        registerForm.style.display = "none";
        loginForm.style.display = "block";
        localStorage.setItem('hasVisited', 'true');
    } else {
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

    iconClose?.addEventListener('click', () => {
        const currentForm = wrapper.classList.contains('active') ? 'register' : 'login';
        localStorage.setItem('lastForm', currentForm);
        wrapper.classList.remove('active');
        wrapper.classList.remove('active-popup');
        
        loginForm.style.display = "block";
        registerForm.style.display = "none";
        
        setTimeout(() => {
            wrapper.style.display = 'none';
            resetForms();
        }, 300);
    });

    loginButtonPopup?.addEventListener('click', () => {
        const lastForm = localStorage.getItem('lastForm');
        if (lastForm === 'register') {
            wrapper.classList.add('active');
            loginForm.style.display = "none";
            registerForm.style.display = "block";
        }
        wrapper.style.display = 'flex';
        wrapper.classList.add('active-popup');
    });

    document.querySelectorAll('.input-box input').forEach(input => {
        input.addEventListener('input', () => {
            if (input.value.trim() !== "") {
                input.nextElementSibling?.classList.add('active');
            } else {
                input.nextElementSibling?.classList.remove('active');
            }
        });
    });

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

    const disablePreloaderButton = document.getElementById('disable-preloader');
    disablePreloaderButton?.addEventListener('click', () => {
        localStorage.setItem('preloaderDisabled', 'true');
    });

    const overlay = document.createElement('div');
    overlay.className = 'overlay';
    document.body.appendChild(overlay);

    loginButtonPopup?.addEventListener('click', () => {
        wrapper.style.display = 'flex';
        wrapper.classList.add('active-popup');
        overlay.classList.add('active');
    });

    iconClose?.addEventListener('click', () => {
        wrapper.classList.remove('active-popup');
        overlay.classList.remove('active');
        setTimeout(() => {
            wrapper.style.display = 'none';
            resetForms();
        }, 300);
    });

    overlay.addEventListener('click', () => {
        iconClose.click();
    });
});

document.querySelectorAll('[data-requires-auth]').forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        document.querySelector('.wrapper').style.display = 'flex';
        document.querySelector('.wrapper').classList.add('active-popup');
    });
});


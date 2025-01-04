document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.querySelector('#loginForm');
    const registerForm = document.querySelector('#registerForm');
    
    // Function to ensure error container exists
    function getOrCreateErrorContainer(inputElement) {
        const parentDiv = inputElement.parentElement;
        let errorDiv = parentDiv.querySelector('.error-message');
        
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.style.fontSize = '0.875rem';
            errorDiv.style.marginTop = '0.25rem';
            parentDiv.appendChild(errorDiv);
        }
        
        return errorDiv;
    }

    // Function to display errors
    function showError(inputElement, message) {
        const errorDiv = getOrCreateErrorContainer(inputElement);
        errorDiv.textContent = message;
        errorDiv.style.color = '#ff3e3e';
        inputElement.style.borderColor = '#ff3e3e';
    }

    // Function to clear errors
    function clearError(inputElement) {
        const errorDiv = getOrCreateErrorContainer(inputElement);
        errorDiv.textContent = '';
        inputElement.style.borderColor = '';
    }

    // Function to display notifications
    function showNotification(message, type = 'error') {
        // Check if notification container exists
        let container = document.querySelector('.notification-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'notification-container';
            container.style.position = 'fixed';
            container.style.top = '1rem';
            container.style.right = '1rem';
            container.style.zIndex = '1000';
            document.body.appendChild(container);
        }

        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.textContent = message;
        notification.style.padding = '1rem';
        notification.style.marginBottom = '0.5rem';
        notification.style.borderRadius = '0.25rem';
        notification.style.backgroundColor = type === 'success' ? '#4CAF50' : '#ff3e3e';
        notification.style.color = 'white';
        notification.style.transform = 'translateX(100%)';
        notification.style.transition = 'transform 0.3s ease-in-out';
        
        container.appendChild(notification);

        // Add class for appearance animation
        requestAnimationFrame(() => {
            notification.style.transform = 'translateX(0)';
        });

        // Remove notification after 3 seconds
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Login form handling
    if (loginForm) {
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Clear previous errors
            const inputs = loginForm.querySelectorAll('input');
            inputs.forEach(input => clearError(input));

            // Get form data
            const formData = {
                action: 'login',
                email: loginForm.querySelector('[name="login_email"]')?.value || '',
                password: loginForm.querySelector('[name="login_password"]')?.value || ''
            };

            // Validate required fields
            let hasErrors = false;
            if (!formData.email) {
                showError(loginForm.querySelector('[name="login_email"]'), 'Email is required');
                hasErrors = true;
            }
            if (!formData.password) {
                showError(loginForm.querySelector('[name="login_password"]'), 'Password is required');
                hasErrors = true;
            }

            if (hasErrors) return;

            // Add loading state
            const submitButton = loginForm.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.classList.add('loading');
            }

            try {
                const response = await fetch('auth_handlers.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (data.success) {
                    showNotification('Login successful!', 'success');
                    window.location.href = 'home.php';
                } else {
                    if (data.errors) {
                        Object.entries(data.errors).forEach(([field, message]) => {
                            const input = loginForm.querySelector(`[name="login_${field}"]`);
                            if (input) {
                                showError(input, message);
                            } else if (field === 'general') {
                                showNotification(message);
                            }
                        });
                    } else {
                        showNotification('Login error. Check the entered data.');
                    }
                }
            } catch (error) {
                console.error('Login error:', error);
                showNotification('An error occurred while trying to log in.');
            } finally {
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.classList.remove('loading');
                }
            }
        });
    }

    // Registration form handling
    if (registerForm) {
        registerForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Clear previous errors
            const inputs = registerForm.querySelectorAll('input');
            inputs.forEach(input => clearError(input));

            // Get form data
            const formData = {
                action: 'register',
                username: registerForm.querySelector('[name="username"]')?.value || '',
                email: registerForm.querySelector('[name="email"]')?.value || '',
                password: registerForm.querySelector('[name="password"]')?.value || '',
                confirm_password: registerForm.querySelector('[name="confirm_password"]')?.value || ''
            };

            // Validate required fields
            let hasErrors = false;
            if (!formData.username || formData.username.length < 3) {
                showError(registerForm.querySelector('[name="username"]'), 'Username must be at least 3 characters long');
                hasErrors = true;
            }
            if (!formData.email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(formData.email)) {
                showError(registerForm.querySelector('[name="email"]'), 'Enter a valid email');
                hasErrors = true;
            }
            if (!formData.password || formData.password.length < 8) {
                showError(registerForm.querySelector('[name="password"]'), 'Password must be at least 8 characters long');
                hasErrors = true;
            }
            if (formData.password !== formData.confirm_password) {
                showError(registerForm.querySelector('[name="confirm_password"]'), 'Passwords do not match');
                hasErrors = true;
            }

            if (hasErrors) return;

            // Add loading state
            const submitButton = registerForm.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.classList.add('loading');
            }

            try {
                const response = await fetch('auth_handlers.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (data.success) {
                    showNotification('Registration successful!', 'success');
                    window.location.href = 'home.php';
                } else {
                    if (data.errors) {
                        Object.entries(data.errors).forEach(([field, message]) => {
                            const input = registerForm.querySelector(`[name="${field}"]`);
                            if (input) {
                                showError(input, message);
                            } else if (field === 'general') {
                                showNotification(message);
                            }
                        });
                    } else {
                        showNotification('Registration error. Check the entered data.');
                    }
                }
            } catch (error) {
                console.error('Registration error:', error);
                showNotification('An error occurred while trying to register.');
            } finally {
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.classList.remove('loading');
                }
            }
        });

        // Real-time validation
        const inputs = registerForm.querySelectorAll('input');
        inputs.forEach(input => {
            input.addEventListener('input', () => {
                clearError(input);
                
                const value = input.value;
                
                switch (input.name) {
                    case 'password':
                        if (value.length < 8) {
                            showError(input, 'Password must be at least 8 characters long');
                        }
                        break;
                        
                    case 'confirm_password':
                        const password = registerForm.querySelector('[name="password"]')?.value;
                        if (value !== password) {
                            showError(input, 'Passwords do not match');
                        }
                        break;
                        
                    case 'email':
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(value)) {
                            showError(input, 'Enter a valid email');
                        }
                        break;
                        
                    case 'username':
                        if (value.length < 3) {
                            showError(input, 'Username must be at least 3 characters long');
                        }
                        break;
                }
            });
        });
    }
});
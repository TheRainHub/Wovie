function openSettingsModal() {
    const modal = document.getElementById('settings-modal');
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeSettingsModal() {
    const modal = document.getElementById('settings-modal');
    modal.classList.remove('active');
    document.body.style.overflow = '';
}

// Закрытие модального окна при клике вне его
document.addEventListener('click', (e) => {
    const modal = document.getElementById('settings-modal');
    if (e.target === modal) {
        closeSettingsModal();
    }
});

// Обработка загрузки аватара
document.getElementById('avatar-upload').addEventListener('change', function(e) {
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        const avatar = document.querySelector('.avatar');
        
        reader.onload = function(e) {
            // Создаем превью перед отправкой
            avatar.style.opacity = '0.5';
            setTimeout(() => {
                avatar.src = e.target.result;
                avatar.style.opacity = '1';
            }, 300);
        }
        
        reader.readAsDataURL(this.files[0]);
        
        // Автоматическая отправка формы
        const formData = new FormData();
        formData.append('avatar', this.files[0]);
        
        fetch('update_avatar.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Показываем уведомление об успехе
                showNotification('Avatar updated successfully!', 'success');
            } else {
                showNotification('Failed to update avatar', 'error');
            }
        })
        .catch(error => {
            showNotification('Error uploading avatar', 'error');
        });
    }
});

// Система уведомлений
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Анимация появления
    setTimeout(() => notification.classList.add('show'), 100);
    
    // Автоматическое скрытие
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Плавная анимация статистики
function animateStats() {
    const stats = document.querySelectorAll('.stat-number');
    stats.forEach(stat => {
        const target = parseInt(stat.textContent);
        let current = 0;
        const increment = target / 50; // Скорость анимации
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                clearInterval(timer);
                current = target;
            }
            stat.textContent = Math.round(current);
        }, 20);
    });
}

// Запуск анимации статистики при загрузке страницы
document.addEventListener('DOMContentLoaded', animateStats);

// Ленивая загрузка изображений
document.addEventListener('DOMContentLoaded', () => {
    const lazyImages = document.querySelectorAll('img[data-src]');
    
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.add('loaded');
                observer.unobserve(img);
            }
        });
    });
    
    lazyImages.forEach(img => imageObserver.observe(img));
});

document.getElementById('profile-settings-form').addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData(e.target);
    const response = await fetch('update_profile.php', {
        method: 'POST',
        body: formData,
    });

    if (!response.ok) {
        const error = await response.json();
        alert(error.error || 'An error occurred');
        return;
    }

    const result = await response.json();
    if (result.success) {
        alert(result.message);
        window.location.reload();
    } else {
        alert(result.error || 'An error occurred');
    }
});

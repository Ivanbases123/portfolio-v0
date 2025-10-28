// Cursor personalizado
document.addEventListener('mousemove', (e) => {
    const cursor = document.querySelector('.cursor');
    cursor.style.transform = `translate(${e.clientX - 8}px, ${e.clientY - 8}px)`;
});

// Elementos flotantes
function createFloatingElements() {
    const container = document.querySelector('.floating-elements');
    const colors = ['#60A5FA', '#34D399', '#F472B6'];
    
    for (let i = 0; i < 20; i++) {
        const element = document.createElement('div');
        element.className = 'floating-element';
        
        const size = Math.random() * 300 + 50;
        const color = colors[Math.floor(Math.random() * colors.length)];
        
        element.style.width = `${size}px`;
        element.style.height = `${size}px`;
        element.style.background = `radial-gradient(circle, ${color} 0%, transparent 70%)`;
        element.style.top = `${Math.random() * 100}%`;
        element.style.left = `${Math.random() * 100}%`;
        element.style.animation = `float ${Math.random() * 10 + 10}s infinite linear`;
        
        container.appendChild(element);
    }
}

// Toggle tema oscuro/claro
function setupThemeToggle() {
    const themeToggle = document.getElementById('theme-toggle');
    const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');
    
    // Establecer tema inicial
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        document.documentElement.setAttribute('data-theme', savedTheme);
        updateThemeIcon(savedTheme === 'dark');
    } else if (prefersDarkScheme.matches) {
        document.documentElement.setAttribute('data-theme', 'dark');
        updateThemeIcon(true);
    }
    
    themeToggle.addEventListener('click', () => {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateThemeIcon(newTheme === 'dark');
    });
}

function updateThemeIcon(isDark) {
    const icon = document.querySelector('.theme-toggle i');
    icon.className = isDark ? 'bx bx-sun' : 'bx bx-moon';
}

// Animaciones al scroll
function setupScrollAnimations() {
    const elements = document.querySelectorAll('[data-aos]');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('aos-animate');
            }
        });
    }, {
        threshold: 0.1
    });
    
    elements.forEach(element => observer.observe(element));
}

// Año actual en el footer
function setCurrentYear() {
    document.getElementById('current-year').textContent = new Date().getFullYear();
}

// Inicialización
document.addEventListener('DOMContentLoaded', () => {
    createFloatingElements();
    setupThemeToggle();
    setupScrollAnimations();
    setCurrentYear();
});

// Smooth scroll para los enlaces de navegación
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
});

// Animación de hover para las cards
document.querySelectorAll('.skill-card, .project-card').forEach(card => {
    card.addEventListener('mousemove', (e) => {
        const rect = card.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        card.style.transform = `
            perspective(1000px)
            rotateX(${(y - rect.height / 2) / 20}deg)
            rotateY(${-(x - rect.width / 2) / 20}deg)
            translateY(-5px)
        `;
    });
    
    card.addEventListener('mouseleave', () => {
        card.style.transform = '';
    });
});
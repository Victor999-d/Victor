function toggleTheme() {
    const body = document.body;
    const isDark = body.classList.toggle('dark-theme');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    const themeBtn = document.querySelector('.theme-toggle-btn');
    themeBtn.innerHTML = isDark ? '<i class="fas fa-sun"></i> Tema Claro' : '<i class="fas fa-moon"></i> Tema Escuro';
}

document.addEventListener('DOMContentLoaded', () => {
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-theme');
        const themeBtn = document.querySelector('.theme-toggle-btn');
        if (themeBtn) themeBtn.innerHTML = '<i class="fas fa-sun"></i> Tema Claro';
    }
});
document.addEventListener('DOMContentLoaded', () => {
    // Плавное появление карточек
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, i) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(6px)';
        card.style.transition = 'opacity .25s ease, transform .25s ease';
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, i * 40);
    });

    // Активная сортировка: подсветка выбранной ссылки без перезагрузки страницы
    // (на случай, если JS-навигация будет добавлена позже)
    const sortLinks = document.querySelectorAll('.sort a');
    sortLinks.forEach(link => {
        link.addEventListener('click', () => {
            sortLinks.forEach(l => l.classList.remove('active'));
            link.classList.add('active');
        });
    });
});
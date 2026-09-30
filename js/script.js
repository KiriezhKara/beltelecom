console.log('Скрипт загружен');

// ============================================
// СРАВНЕНИЕ ТАРИФОВ
// ============================================
let compareList = [];

function addToCompare(id) {
    if (compareList.includes(id)) {
        alert('Этот тариф уже добавлен в сравнение');
        return;
    }
    if (compareList.length >= 3) {
        alert('Можно сравнивать не более 3 тарифов');
        return;
    }
    compareList.push(id);
    renderCompareTable();
}

function removeFromCompare(id) {
    compareList = compareList.filter(item => item !== id);
    renderCompareTable();
}

function renderCompareTable() {
    const section = document.getElementById('compare-section');
    const table = document.getElementById('compare-table');

    if (!section || !table) return;

    if (compareList.length === 0) {
        section.style.display = 'none';
        return;
    }

    section.style.display = 'block';

    let headerRow = '<tr><th></th>';
    compareList.forEach(id => {
        const card = document.querySelector(`[onclick*="addToCompare(${id})"]`)?.closest('.tariff-card');
        if (card) {
            const name = card.querySelector('.name')?.innerText || 'Тариф';
            headerRow += `<th>${name} <button class="btn-remove" onclick="removeFromCompare(${id})">✕</button></th>`;
        }
    });
    headerRow += '</tr>';

    let rows = '';

    rows += '<tr><td class="row-label">Скорость Интернета</td>';
    compareList.forEach(id => {
        const card = document.querySelector(`[onclick*="addToCompare(${id})"]`)?.closest('.tariff-card');
        rows += `<td>${card?.querySelector('.speed')?.innerText || '–'}</td>`;
    });
    rows += '</tr>';

    rows += '<tr><td class="row-label">Услуги телефонной связи</td>';
    compareList.forEach(id => {
        const card = document.querySelector(`[onclick*="addToCompare(${id})"]`)?.closest('.tariff-card');
        rows += `<td>${card?.dataset.phone || '–'}</td>`;
    });
    rows += '</tr>';

    rows += '<tr><td class="row-label">Услуги телевидения</td>';
    compareList.forEach(id => {
        const card = document.querySelector(`[onclick*="addToCompare(${id})"]`)?.closest('.tariff-card');
        rows += `<td>${card?.dataset.tvText || '–'}</td>`;
    });
    rows += '</tr>';

    rows += '<tr><td class="row-label">Видеоконтроль</td>';
    compareList.forEach(id => {
        const card = document.querySelector(`[onclick*="addToCompare(${id})"]`)?.closest('.tariff-card');
        rows += `<td>${card?.dataset.videoText || '–'}</td>`;
    });
    rows += '</tr>';

    rows += '<tr><td class="row-label">Цена</td>';
    compareList.forEach(id => {
        const card = document.querySelector(`[onclick*="addToCompare(${id})"]`)?.closest('.tariff-card');
        rows += `<td>${card?.querySelector('.price')?.innerText || '–'}</td>`;
    });
    rows += '</tr>';

    table.innerHTML = `<table class="compare-table">${headerRow}${rows}</table>`;
}

// ============================================
// ФИЛЬТРЫ + ЧАТ + АККОРДЕОН
// ============================================
document.addEventListener('DOMContentLoaded', function () {

    // ---------- ФИЛЬТРЫ "ПАКЕТЫ УСЛУГ" ----------
    const techCheckboxes = document.querySelectorAll('.filter-tech');
    const videoCheckboxes = document.querySelectorAll('.filter-video');
    const tvCheckboxes = document.querySelectorAll('.filter-tv');
    const searchPackages = document.getElementById('search-packages');
    const cards = document.querySelectorAll('.tariff-card:not(.archive-card)');

    if (cards.length > 0) {
        function applyFilters() {
            const selectedTech = Array.from(techCheckboxes).filter(cb => cb.checked).map(cb => cb.value);
            const selectedVideo = Array.from(videoCheckboxes).filter(cb => cb.checked).map(cb => cb.value);
            const selectedTv = Array.from(tvCheckboxes).filter(cb => cb.checked).map(cb => cb.value);
            const searchText = (searchPackages?.value || '').toLowerCase().trim();

            cards.forEach(card => {
                let show = true;

                // Технология
                if (selectedTech.length > 0 && !selectedTech.includes(card.dataset.tech)) show = false;

                // Видеоконтроль
                if (selectedVideo.length > 0 && !selectedVideo.includes(card.dataset.video)) show = false;

                // ТВ
                if (selectedTv.length > 0 && !selectedTv.includes(card.dataset.tv)) show = false;

                // Поиск
                if (searchText && !card.dataset.name.includes(searchText)) show = false;

                card.style.display = show ? '' : 'none';
            });
        }

        techCheckboxes.forEach(cb => cb.addEventListener('change', applyFilters));
        videoCheckboxes.forEach(cb => cb.addEventListener('change', applyFilters));
        tvCheckboxes.forEach(cb => cb.addEventListener('change', applyFilters));
        if (searchPackages) searchPackages.addEventListener('input', applyFilters);
    }

    // ---------- ФИЛЬТРЫ "АРХИВ ТАРИФОВ" ----------
    const lineCheckboxes = document.querySelectorAll('.filter-line');
    const archiveTechCheckboxes = document.querySelectorAll('.filter-tech');
    const searchInput = document.getElementById('search-archive');
    const archiveCards = document.querySelectorAll('.archive-card');

    if (archiveCards.length > 0) {
        function applyArchiveFilters() {
            const selectedLines = Array.from(lineCheckboxes).filter(cb => cb.checked).map(cb => cb.value);
            const selectedTechs = Array.from(archiveTechCheckboxes).filter(cb => cb.checked).map(cb => cb.value);
            const searchText = (searchInput?.value || '').toLowerCase().trim();

            archiveCards.forEach(card => {
                let show = true;
                if (selectedLines.length > 0 && !selectedLines.includes(card.dataset.line)) show = false;
                if (selectedTechs.length > 0 && !selectedTechs.includes(card.dataset.tech)) show = false;
                if (searchText && !card.dataset.name.includes(searchText)) show = false;
                card.style.display = show ? '' : 'none';
            });
        }
        lineCheckboxes.forEach(cb => cb.addEventListener('change', applyArchiveFilters));
        archiveTechCheckboxes.forEach(cb => cb.addEventListener('change', applyArchiveFilters));
        if (searchInput) searchInput.addEventListener('input', applyArchiveFilters);
    }

    // ---------- ИИ-ЧАТ ----------
    const toggle = document.getElementById('aiChatToggle');
    const chatWindow = document.getElementById('aiChatWindow');
    const close = document.getElementById('aiChatClose');
    const input = document.getElementById('aiChatInput');
    const send = document.getElementById('aiChatSend');
    const messages = document.getElementById('aiChatMessages');
    const quickBtns = document.querySelectorAll('.quick-btn');

    if (!toggle) return;

    toggle.addEventListener('click', () => {
        chatWindow.style.display = chatWindow.style.display === 'none' ? 'flex' : 'none';
    });

    close.addEventListener('click', () => {
        chatWindow.style.display = 'none';
    });

    function addMessage(text, sender) {
        const msg = document.createElement('div');
        msg.className = `chat-message ${sender}`;
        msg.textContent = text;
        messages.appendChild(msg);
        messages.scrollTop = messages.scrollHeight;
    }

    function sendMessage(text) {
        if (!text.trim()) return;
        addMessage(text, 'user');
        input.value = '';

        fetch('get_faq.php?q=' + encodeURIComponent(text))
            .then(response => response.json())
            .then(data => {
                addMessage(data.answer, 'bot');
            })
            .catch(() => {
                addMessage('Ошибка соединения. Попробуйте позже.', 'bot');
            });
    }

    send.addEventListener('click', () => sendMessage(input.value));
    input.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') sendMessage(input.value);
    });

    quickBtns.forEach(btn => {
        btn.addEventListener('click', () => sendMessage(btn.dataset.question));
    });
});

// ============================================
// АККОРДЕОН (для страницы "Прочие пакеты")
// ============================================
function toggleAccordion(btn) {
    btn.classList.toggle('active');
    const panel = btn.nextElementSibling;
    if (panel.style.maxHeight) {
        panel.style.maxHeight = null;
    } else {
        panel.style.maxHeight = panel.scrollHeight + 'px';
    }
}

// ============================================
// КНОПКА "НАВЕРХ"
// ============================================
document.addEventListener('DOMContentLoaded', function () {
    const scrollTop = document.getElementById('scrollTop');
    if (!scrollTop) return;

    window.addEventListener('scroll', function () {
        if (window.pageYOffset > 300) {
            scrollTop.classList.add('visible');
        } else {
            scrollTop.classList.remove('visible');
        }
    });

    scrollTop.addEventListener('click', function (e) {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
});

// ============================================
// ПЕРЕКЛЮЧАТЕЛЬ ТЕМЫ
// ============================================
document.addEventListener('DOMContentLoaded', function () {
    const themeToggle = document.getElementById('themeToggle');
    if (!themeToggle) return;

            // Проверяем сохранённую тему
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-theme');
    }

    // Клик по кнопке
    themeToggle.addEventListener('click', function () {
        document.body.classList.toggle('dark-theme');

        if (document.body.classList.contains('dark-theme')) {
            localStorage.setItem('theme', 'dark');
        } else {
            localStorage.setItem('theme', 'light');
        }
    });
});
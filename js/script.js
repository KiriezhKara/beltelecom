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
    
    // Получаем переводы
    const tr = window.translations || {
        speed: 'Скорость Интернета', phone: 'Услуги телефонной связи', 
        tv: 'Услуги телевидения', video: 'Видеоконтроль', 
        price: 'Цена', tariff: 'Тариф'
    };

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
            const name = card.querySelector('.name')?.innerText || tr.tariff;
            headerRow += `<th>${name} <button class="btn-remove" onclick="removeFromCompare(${id})">✕</button></th>`;
        }
    });
    headerRow += '</tr>';

    let rows = '';

    // Скорость
    rows += `<tr><td class="row-label">${tr.speed}</td>`;
    compareList.forEach(id => {
        const card = document.querySelector(`[onclick*="addToCompare(${id})"]`)?.closest('.tariff-card');
        rows += `<td>${card?.querySelector('.speed')?.innerText || '—'}</td>`;
    });
    rows += '</tr>';

    // Телефон
    rows += `<tr><td class="row-label">${tr.phone}</td>`;
    compareList.forEach(id => {
        const card = document.querySelector(`[onclick*="addToCompare(${id})"]`)?.closest('.tariff-card');
        // Ищем текст телефона в блоке features
        const features = card?.querySelector('.features')?.innerText || '';
        const phoneLine = features.split('\n').find(line => line.includes('📞')) || '—';
        const cleanPhone = phoneLine.replace('📞', '').trim();
        rows += `<td>${cleanPhone !== '' ? cleanPhone : '—'}</td>`;
    });
    rows += '</tr>';

    // ТВ
    rows += `<tr><td class="row-label">${tr.tv}</td>`;
    compareList.forEach(id => {
        const card = document.querySelector(`[onclick*="addToCompare(${id})"]`)?.closest('.tariff-card');
        const features = card?.querySelector('.features')?.innerText || '';
        const tvLine = features.split('\n').find(line => line.includes('📺')) || '—';
        const cleanTv = tvLine.replace('📺', '').trim();
        rows += `<td>${cleanTv !== '' ? cleanTv : '—'}</td>`;
    });
    rows += '</tr>';

    // Видеоконтроль
    rows += `<tr><td class="row-label">${tr.video}</td>`;
    compareList.forEach(id => {
        const card = document.querySelector(`[onclick*="addToCompare(${id})"]`)?.closest('.tariff-card');
        const features = card?.querySelector('.features')?.innerText || '';
        const videoLine = features.split('\n').find(line => line.includes('📹')) || '—';
        const cleanVideo = videoLine.replace('📹', '').trim();
        rows += `<td>${cleanVideo !== '' ? cleanVideo : '—'}</td>`;
    });
    rows += '</tr>';

    // Цена
    rows += `<tr><td class="row-label">${tr.price}</td>`;
    compareList.forEach(id => {
        const card = document.querySelector(`[onclick*="addToCompare(${id})"]`)?.closest('.tariff-card');
        rows += `<td>${card?.querySelector('.price')?.innerText || '—'}</td>`;
    });
    rows += '</tr>';

    table.innerHTML = `<table class="compare-table">${headerRow}${rows}</table>`;
}

// ============================================
// ФИЛЬТРЫ, ПОИСК И ЧАТ
// ============================================
document.addEventListener('DOMContentLoaded', function () {

    // ---------- ФИЛЬТРЫ ДЛЯ INDEX.PHP ----------
    const filterForm = document.getElementById('filters-form');
    const mainCards = document.querySelectorAll('.tariff-card:not(.extra-card):not(.archive-card)'); 
    const searchPackages = document.getElementById('search-packages');

    if (mainCards.length > 0 && filterForm) {
        function applyFilters() {
            // Ищем выбранные радиокнопки
            const techValue = document.querySelector('input[name="tech"]:checked')?.value || 'all';
            const videoValue = document.querySelector('input[name="video"]:checked')?.value || 'all';
            const tvValue = document.querySelector('input[name="tv"]:checked')?.value || 'all';
            const searchText = (searchPackages?.value || '').toLowerCase().trim();

            mainCards.forEach(card => {
                let show = true;

                if (techValue !== 'all' && card.dataset.tech !== techValue) show = false;
                if (videoValue !== 'all' && card.dataset.video !== videoValue) show = false;
                if (tvValue !== 'all' && card.dataset.tv !== tvValue) show = false;

                // Поиск по названию, скорости и цене
                if (searchText) {
                    const nameMatch = card.dataset.name && card.dataset.name.includes(searchText);
                    const speedMatch = card.dataset.speed && card.dataset.speed.includes(searchText);
                    const priceMatch = card.dataset.price && card.dataset.price.includes(searchText);
                    if (!nameMatch && !speedMatch && !priceMatch) show = false;
                }

                card.style.display = show ? '' : 'none';
            });
        }

        filterForm.addEventListener('change', applyFilters);
        if (searchPackages) searchPackages.addEventListener('input', applyFilters);
    }

    // ---------- ФИЛЬТРЫ ДЛЯ ARCHIVE.PHP ----------
    const archiveForm = document.getElementById('filters-form');
    const archiveCards = document.querySelectorAll('.archive-card');
    const searchArchive = document.getElementById('search-archive');

    if (archiveCards.length > 0 && archiveForm) {
        function applyArchiveFilters() {
            const lineValue = document.querySelector('input[name="line"]:checked')?.value || 'all';
            const techValue = document.querySelector('input[name="archive-tech"]:checked')?.value || 'all';
            const searchText = (searchArchive?.value || '').toLowerCase().trim();

            archiveCards.forEach(card => {
                let show = true;

                if (lineValue !== 'all' && card.dataset.line !== lineValue) show = false;
                if (techValue !== 'all' && card.dataset.tech !== techValue) show = false;
                
                if (searchText && !card.dataset.name.includes(searchText)) show = false;

                card.style.display = show ? '' : 'none';
            });
        }

        archiveForm.addEventListener('change', applyArchiveFilters);
        if (searchArchive) searchArchive.addEventListener('input', applyArchiveFilters);
    }

    // ---------- ИИ-ЧАТ ----------
    const toggle = document.getElementById('aiChatToggle');
    const chatWindow = document.getElementById('aiChatWindow');
    const close = document.getElementById('aiChatClose');
    const input = document.getElementById('aiChatInput');
    const send = document.getElementById('aiChatSend');
    const messages = document.getElementById('aiChatMessages');
    const chatQuick = document.getElementById('aiChatQuick');

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
        msg.innerHTML = text; // используем innerHTML, чтобы выводить формы
        messages.appendChild(msg);
        messages.scrollTop = messages.scrollHeight;
    }

    // Приветствие при первом открытии
    if (messages.children.length === 0) {
        addMessage('Здравствуйте! Я помогу вам с выбором тарифа. Выберите тему или задайте вопрос.', 'bot');
    }

    // Улучшенное меню быстрых ответов (дерево)
        function renderQuickButtons(type) {
        chatQuick.innerHTML = ''; 
        let buttons = [];

        if (type === 'main') {
            buttons = [
                { text: '📦 Тарифы', action: 'tariffs' },
                { text: '🔌 Подключение и оплата', action: 'connect_pay' },
                { text: '📺 ТВ и услуги', action: 'tv_services' },
                { text: '🛠 Оборудование и Wi-Fi', action: 'equipment' },
                { text: '🔒 Блокировка и ЛК', action: 'block_cabinet' },
                { text: '❓ Помощь и контакты', action: 'help' }
            ];
        } 
        // --- Ветка 1: Тарифы ---
        else if (type === 'tariffs') {
            buttons = [
                { text: 'Какие тарифы популярны?', action: 'ask', q: 'Какие тарифы самые популярные?' },
                { text: 'Чем ЯСНА от Семейного?', action: 'ask', q: 'Чем отличается ЯСНА от Семейного?' },
                { text: 'Что такое ЯСНА Лайт?', action: 'ask', q: 'Что такое «ЯСНА Лайт»?' },
                { text: 'Что такое ЯСНА 100 Дом+Дача?', action: 'ask', q: 'Что такое «ЯСНА 100 Дом + Дача»?' },
                { text: 'Тарифы для бизнеса', action: 'ask', q: 'Какие тарифы для бизнеса?' },
                { text: 'Назад', action: 'main' }
            ];
        } 
        // --- Ветка 2: Подключение и оплата ---
        else if (type === 'connect_pay') {
            buttons = [
                { text: 'Как подключить пакет?', action: 'ask', q: 'Как подключить пакет услуг?' },
                { text: 'Без визита в офис', action: 'ask', q: 'Как подключить пакет без выезда в офис?' },
                { text: 'Какие нужны документы?', action: 'ask', q: 'Какие документы нужны для подключения?' },
                { text: 'Как сменить тариф?', action: 'ask', q: 'Как сменить тарифный план?' },
                { text: 'Как оплатить?', action: 'ask', q: 'Как платить за пакеты услуг?' },
                { text: 'Оплата через интернет', action: 'ask', q: 'Как оплатить пакет через интернет?' },
                { text: 'Что такое обещанный платеж?', action: 'ask', q: 'Что такое «Обещанный платёж»?' },
                { text: 'Где узнать баланс?', action: 'ask', q: 'Где узнать свой баланс?' },
                { text: 'Назад', action: 'main' }
            ];
        }
        // --- Ветка 3: ТВ и услуги ---
        else if (type === 'tv_services') {
            buttons = [
                { text: 'Что такое ZALA?', action: 'ask', q: 'Что такое ZALA?' },
                { text: 'Что такое SMART ZALA?', action: 'ask', q: 'Что такое SMART ZALA?' },
                { text: 'Какие каналы входят?', action: 'ask', q: 'Какие каналы входят в пакет?' },
                { text: 'Что такое Видеоконтроль?', action: 'ask', q: 'Что такое «Видеоконтроль»?' },
                { text: 'Что такое Видеодомофон?', action: 'ask', q: 'Что такое «Видеодомофон»?' },
                { text: 'Что такое Умный дом?', action: 'ask', q: 'Что такое «Умный дом»?' },
                { text: 'Родительский контроль', action: 'ask', q: 'Что такое «Родительский контроль»?' },
                { text: 'Назад', action: 'main' }
            ];
        }
        // --- Ветка 4: Оборудование и Wi-Fi ---
        else if (type === 'equipment') {
            buttons = [
                { text: 'Какое оборудование дают?', action: 'ask', q: 'Какое оборудование предоставляется?' },
                { text: 'Какой роутер выбрать?', action: 'ask', q: 'Какой роутер выбрать?' },
                { text: 'Как настроить Wi-Fi?', action: 'ask', q: 'Как настроить Wi-Fi?' },
                { text: 'Как усилить сигнал Wi-Fi?', action: 'ask', q: 'Как усилить сигнал Wi-Fi?' },
                { text: 'Купить роутер в рассрочку', action: 'ask', q: 'Можно ли купить роутер в рассрочку?' },
                { text: 'Оборудование сломалось', action: 'ask', q: 'Что делать, если оборудование сломалось?' },
                { text: 'Замена терминала', action: 'ask', q: 'В каких случаях возможна замена терминала?' },
                { text: 'Назад', action: 'main' }
            ];
        }
        // --- Ветка 5: Блокировка и Личный кабинет ---
        else if (type === 'block_cabinet') {
            buttons = [
                { text: 'Как заблокировать услугу?', action: 'ask', q: 'Как заблокировать услугу?' },
                { text: 'Блокировка на 180 дней', action: 'ask', q: 'Что такое блокировка на 180 дней?' },
                { text: 'Как разблокировать?', action: 'ask', q: 'Как разблокировать интернет?' },
                { text: 'Досрочная разблокировка', action: 'ask', q: 'Можно ли досрочно разблокировать услугу?' },
                { text: 'Как зайти в Кабинет?', action: 'ask', q: 'Как зайти в «Кабинет пользователя»?' },
                { text: 'Забыл пароль', action: 'ask', q: 'Что делать, если забыл пароль?' },
                { text: 'Возможности Кабинета', action: 'ask', q: 'Какие возможности у «Кабинета пользователя»?' },
                { text: 'Назад', action: 'main' }
            ];
        }
        // --- Ветка 6: Помощь и контакты ---
        else if (type === 'help') {
            buttons = [
                { text: 'Не работает интернет', action: 'ask', q: 'Что делать, если не работает интернет?' },
                { text: 'Низкая скорость', action: 'ask', q: 'Что делать, если низкая скорость?' },
                { text: 'Служба поддержки', action: 'ask', q: 'Как связаться со службой поддержки?' },
                { text: 'Где найти офис?', action: 'ask', q: 'Где найти офис продаж?' },
                { text: 'Вызвать специалиста', action: 'ask', q: 'Как вызвать специалиста на дом?' },
                { text: 'Сколько стоит вызов?', action: 'ask', q: 'Сколько стоит вызов специалиста?' },
                { text: 'Назад', action: 'main' }
            ];
        }

        buttons.forEach(btn => {
            const b = document.createElement('button');
            b.className = 'quick-btn';
            b.innerText = btn.text;
            b.dataset.action = btn.action;
            b.onclick = () => {
                if (btn.action === 'main' || ['tariffs', 'connect_pay', 'tv_services', 'equipment', 'block_cabinet', 'help'].includes(btn.action)) {
                    renderQuickButtons(btn.action);
                } else if (btn.action === 'ask') {
                    sendMessage(btn.q);
                }
            };
            chatQuick.appendChild(b);
        });
    }

    // Инициализация кнопок
    renderQuickButtons('main');

    // Функция отправки сообщения
    function sendMessage(text) {
        if (!text.trim()) return;
        addMessage(text, 'user');
        input.value = '';

        // Простые текстовые ответы
        const lowerText = text.toLowerCase().trim();
        if (['привет', 'здравствуйте', 'добрый день'].includes(lowerText)) {
            addMessage('Здравствуйте! Чем я могу вам помочь?', 'bot');
            return;
        }
        if (['пока', 'до свидания', 'всего доброго'].includes(lowerText)) {
            addMessage('До свидания! Хорошего дня!', 'bot');
            return;
        }

        // Поиск ответа в базе данных
        fetch('get_faq.php?q=' + encodeURIComponent(text))
            .then(response => response.json())
            .then(data => {
                if (data.answer) {
                    addMessage(data.answer, 'bot');
                } else {
                    // Если ответа нет, показываем форму для оператора
                    addMessage('К сожалению, я не нашел точного ответа. Пожалуйста, опишите ваш вопрос, и с вами свяжется оператор.', 'bot');
                    const formHTML = `
                        <div class="chat-form">
                            <input type="text" id="operatorQuestion" placeholder="Ваш вопрос..." />
                            <button onclick="submitOperatorQuestion()">Отправить оператору</button>
                        </div>
                    `;
                    addMessage(formHTML, 'bot');
                }
            })
            .catch(() => {
                addMessage('Ошибка соединения. Попробуйте позже.', 'bot');
            });
    }

    send.addEventListener('click', () => sendMessage(input.value));
    input.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') sendMessage(input.value);
    });
});

// ============================================
// АККОРДЕОН
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
// ГЛОБАЛЬНЫЕ ФУНКЦИИ
// ============================================
window.submitOperatorQuestion = function() {
    const q = document.getElementById('operatorQuestion').value;
    if (!q.trim()) return;
    const messages = document.getElementById('aiChatMessages');
    const msg = document.createElement('div');
    msg.className = 'chat-message bot';
    msg.innerText = 'Ваш вопрос отправлен оператору. Пожалуйста, ожидайте ответа.';
    messages.appendChild(msg);
    messages.scrollTop = messages.scrollHeight;
    document.getElementById('operatorQuestion').parentElement.style.display = 'none';
};

function printTariffs() {
    window.print();
}

// ============================================
// ПЕРЕКЛЮЧАТЕЛЬ ТЕМЫ И КНОПКА "НАВЕРХ"
// ============================================
document.addEventListener('DOMContentLoaded', function () {
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            document.body.classList.add('dark-theme');
        }

        themeToggle.addEventListener('click', function () {
            document.body.classList.toggle('dark-theme');
            if (document.body.classList.contains('dark-theme')) {
                localStorage.setItem('theme', 'dark');
            } else {
                localStorage.setItem('theme', 'light');
            }
        });
    }

    const scrollTop = document.getElementById('scrollTop');
    if (scrollTop) {
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
    }
});
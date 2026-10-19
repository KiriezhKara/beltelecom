<?php
// Определяем язык
$currentLang = $_GET['lang'] ?? 'ru';
if (!in_array($currentLang, ['ru', 'be', 'en'])) {
    $currentLang = 'ru';
}

// Загружаем переводы из БД
$t = [];      // Для интерфейса (кнопки, меню)
$texts = [];  // Для текстов страниц (массивы)

if (isset($pdo)) {
    $stmt = $pdo->prepare("SELECT key_name, text_value FROM translations WHERE lang = ?");
    $stmt->execute([$currentLang]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $row) {
        $key = $row['key_name'];
        $val = $row['text_value'];

        // Если это JSON (массив), декодируем обратно
        if (is_string($val) && (strpos($val, '[') === 0 || strpos($val, '{') === 0)) {
            $decoded = json_decode($val, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $val = $decoded;
            }
        }

        // Раскладываем по массивам $t и $texts в зависимости от префикса
        if (strpos($key, 'lang_') === 0) {
            $realKey = substr($key, 5); // убираем 'lang_'
            $t[$realKey] = $val;
        } else {
            // Для texts ключ имеет вид 'index_what_is_title'
            $parts = explode('_', $key, 2);
            $page = $parts[0];
            $realKey = $parts[1] ?? '';
            if ($realKey) {
                $texts[$page][$realKey] = $val;
            }
        }
    }
}
?>
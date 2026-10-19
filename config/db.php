<?php
$host = 'localhost';
$dbname = 'beltelecom';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}
// Функция для получения перевода тарифа из БД
function getTranslatedTariff($tariff, $lang) {
    global $pdo;
    // Если язык русский, возвращаем как есть (русский лежит в основной таблице)
    if ($lang === 'ru') return $tariff;
    
    // Кэшируем результаты, чтобы не делать 50 запросов к БД на одной странице
    static $cache = [];
    $id = $tariff['id'];
    
    if (!isset($cache[$id][$lang])) {
        $stmt = $pdo->prepare("SELECT name, speed, phone, tv, video_control FROM tariffs_translations WHERE tariff_id = ? AND lang = ?");
        $stmt->execute([$id, $lang]);
        $trans = $stmt->fetch(PDO::FETCH_ASSOC);
        $cache[$id][$lang] = $trans ?: [];
    }
    
    $trans = $cache[$id][$lang];
    // Подменяем поля, если перевод есть (и он не пустой)
    foreach (['name', 'speed', 'phone', 'tv', 'video_control'] as $field) {
        if (!empty($trans[$field])) {
            $tariff[$field] = $trans[$field];
        }
    }
    return $tariff;
}
?>
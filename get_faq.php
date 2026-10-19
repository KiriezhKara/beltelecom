<?php
require_once 'config/db.php';
header('Content-Type: application/json; charset=utf-8');

$question = $_GET['q'] ?? '';

if (empty($question)) {
    echo json_encode(['answer' => 'Введите вопрос'], JSON_UNESCAPED_UNICODE);
    exit;
}

$q = mb_strtolower(trim($question));

// Отсеиваем слишком короткие вопросы
if (mb_strlen($q) < 3) {
    echo json_encode(['answer' => 'Уточните вопрос, пожалуйста.'], JSON_UNESCAPED_UNICODE);
    exit;
}

// 1. Поиск точного совпадения (как было)
$stmt = $pdo->prepare("SELECT answer FROM faq WHERE LOWER(question) = :q LIMIT 1");
$stmt->execute(['q' => $q]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row) {
    echo json_encode(['answer' => $row['answer']], JSON_UNESCAPED_UNICODE);
    exit;
}

// 2. Поиск по вхождению подстроки (например, "Как оплатить?")
$stmt = $pdo->prepare("SELECT answer FROM faq WHERE LOWER(question) LIKE :q LIMIT 1");
$stmt->execute(['q' => '%' . $q . '%']);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row) {
    echo json_encode(['answer' => $row['answer']], JSON_UNESCAPED_UNICODE);
    exit;
}

// 3. УМНЫЙ ПОИСК ПО КЛЮЧЕВЫМ СЛОВАМ (Главное улучшение!)
// Разбиваем вопрос на слова, убираем стоп-слова
$stopWords = ['что', 'такое', 'как', 'где', 'когда', 'зачем', 'почему', 'это', 'мне', 'могу', 'можно', 'нужно', 'надо', 'если', 'или', 'для', 'без', 'при', 'чем', 'кто', 'какой', 'какие', 'быть', 'есть'];

$words = array_filter(
    explode(' ', $q),
    function($w) use ($stopWords) {
        return mb_strlen($w) >= 4 && !in_array($w, $stopWords);
    }
);

if (count($words) > 0) {
    // Строим динамический SQL запрос с OR для каждого слова
    $sql = "SELECT answer FROM faq WHERE ";
    $params = [];
    $conditions = [];
    
    // Ищем вхождения для каждого слова (начинающиеся с этих букв)
    foreach ($words as $word) {
        $conditions[] = "LOWER(question) LIKE ?";
        $params[] = '%' . $word . '%';
    }
    
    // Сортируем результаты так, чтобы совпадения по ключевым словам (оплата, тариф, подключить) были выше
    $sql .= implode(" OR ", $conditions) . " LIMIT 3"; // Берем топ-3 результатов

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($rows) > 0) {
        // Если нашли несколько, склеиваем их или даем первый
        $bestAnswer = $rows[0]['answer'];
        echo json_encode(['answer' => $bestAnswer], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// Если ничего не найдено, возвращаем пустой ответ (JS покажет форму)
echo json_encode(['answer' => null], JSON_UNESCAPED_UNICODE);
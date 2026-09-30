<?php
require_once 'config/db.php';
header('Content-Type: application/json; charset=utf-8');

$question = $_GET['q'] ?? '';

if (empty($question)) {
    echo json_encode(['answer' => 'Введите вопрос'], JSON_UNESCAPED_UNICODE);
    exit;
}

$q = mb_strtolower(trim($question));

// Отсеиваем слишком короткие/общие вопросы
if (mb_strlen($q) < 5) {
    echo json_encode(['answer' => 'Уточните вопрос, пожалуйста.'], JSON_UNESCAPED_UNICODE);
    exit;
}

// 1. Прямое совпадение
$stmt = $pdo->prepare("SELECT answer FROM faq WHERE LOWER(question) = :q LIMIT 1");
$stmt->execute(['q' => $q]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row) {
    echo json_encode(['answer' => $row['answer']], JSON_UNESCAPED_UNICODE);
    exit;
}

// 2. Поиск по подстроке (вопрос содержит ключевую фразу из базы)
$stmt = $pdo->prepare("SELECT answer FROM faq WHERE LOWER(question) LIKE :q LIMIT 1");
$stmt->execute(['q' => '%' . $q . '%']);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row) {
    echo json_encode(['answer' => $row['answer']], JSON_UNESCAPED_UNICODE);
    exit;
}

// 3. Поиск по ключевым словам – только для слов от 5 символов,
//    и только если таких слов не больше 3 (иначе – слишком общий запрос)
$stopWords = ['что', 'такое', 'как', 'где', 'когда', 'зачем', 'почему', 'это', 'мне', 'могу', 'можно', 'нужно', 'надо', 'если', 'или', 'для', 'без', 'при', 'чем', 'кто', 'какой', 'какие'];

$words = array_filter(
    explode(' ', $q),
    function($w) use ($stopWords) {
        return mb_strlen($w) >= 5 && !in_array($w, $stopWords);
    }
);

if (count($words) > 0 && count($words) <= 3) {
    foreach ($words as $word) {
        $stmt = $pdo->prepare("SELECT answer FROM faq WHERE LOWER(question) LIKE :q LIMIT 1");s
        $stmt->execute(['q' => '%' . $word . '%']);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            echo json_encode(['answer' => $row['answer']], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
}

// Если ничего не найдено
echo json_encode(['answer' => 'Извините, я не нашёл ответ на этот вопрос. Попробуйте переформулировать или позвоните по номеру 123.'], JSON_UNESCAPED_UNICODE);
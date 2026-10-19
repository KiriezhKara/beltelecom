<?php
if (!isset($currentLang)) {
    $currentLang = $_GET['lang'] ?? 'ru';
}
$langParam = $currentLang !== 'ru' ? '?lang=' . $currentLang : '';
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="<?php echo $currentLang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Белтелеком'; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
</head>
<body>

<!-- Кнопка "Наверх" -->
<a href="#" class="scroll-top" id="scrollTop" title="<?php echo $t['up']; ?>">↑</a>

<!-- Верхнее меню -->
<div class="top-menu">
    <div class="container">
        <ul>
            <li><a href="https://beltelecom.by/private" target="_blank"><?php echo $t['top_for_home']; ?></a></li>
            <li><a href="https://beltelecom.by/business" target="_blank"><?php echo $t['top_for_business']; ?></a></li>
            <li><a href="https://beltelecom.by/operators" target="_blank"><?php echo $t['top_operators']; ?></a></li>
            <li><a href="https://beltelecom.by/news/main" target="_blank"><?php echo $t['top_news']; ?></a></li>
            <li><a href="https://beltelecom.by/about" target="_blank"><?php echo $t['top_about']; ?></a></li>
            <li><a href="https://beltelecom.by/feedback" target="_blank"><?php echo $t['top_feedback']; ?></a></li>
            <li><a href="https://beltelecom.by/administrative-procedures" target="_blank"><?php echo $t['top_admin']; ?></a></li>
            <li><a href="https://beltelecom.by/help/checklist-for-settings" target="_blank"><?php echo $t['top_help']; ?></a></li>
        </ul>
        <div class="top-menu-right">
            <div class="lang">
                <a href="?lang=ru" class="<?php echo $currentLang === 'ru' ? 'active' : ''; ?>">RU</a>
                <a href="?lang=be" class="<?php echo $currentLang === 'be' ? 'active' : ''; ?>">BE</a>
                <a href="?lang=en" class="<?php echo $currentLang === 'en' ? 'active' : ''; ?>">EN</a>
            </div>
            <button class="theme-toggle" id="themeToggle" title="Тема">
                <span class="theme-icon">👁</span>
            </button>
        </div>
    </div>
</div>

<!-- Шапка -->
<header class="header fixed-header"> <!-- Добавлен класс fixed-header -->
    <div class="container header-inner">
        <div class="logo">
            <a href="index.php<?php echo $langParam; ?>"><img src="images/logo.svg" alt="Белтелеком"></a>
        </div>
        <div class="support">
            <span><?php echo $t['support']; ?></span>
            <span class="phone">📞 123</span>
        </div>
        <div class="header-buttons">
            <a href="https://beltelecom.by/order" class="btn-header" target="_blank"><?php echo $t['btn_connect']; ?></a>
            <a href="https://beltelecom.by/feedback/appeals/vopros_specialistu" class="btn-header" target="_blank"><?php echo $t['btn_question']; ?></a>
            <a href="https://my.beltelecom.by/login" class="btn-header" target="_blank"><?php echo $t['btn_cabinet']; ?></a>
            <a href="https://beltelecom.by/subscribers/payment/ways" class="btn-header" target="_blank"><?php echo $t['btn_pay']; ?></a>
            <a href="https://www.beltelecom.by/apk" class="btn-header" target="_blank"><?php echo $t['btn_mobile']; ?></a>
            <a href="https://shop.beltelecom.by/" class="btn-header shop" target="_blank">
                <img src="images/shop.png" alt="BELTELECOM SHOP" class="shop-logo">
            </a>
        </div>
    </div>
</header>

<!-- Меню раздела -->
<nav class="section-menu">
    <div class="container">
        <ul>
            <li class="<?php echo in_array($currentPage, ['index.php', 'archive.php', 'instruction.php', 'other.php']) ? 'active' : ''; ?>">
                <a href="index.php<?php echo $langParam; ?>"><?php echo $t['menu_packages']; ?></a>
            </li>
            <li><a href="https://beltelecom.by/private/internet" target="_blank"><?php echo $t['menu_internet']; ?></a></li>
            <li><a href="https://beltelecom.by/private/tv" target="_blank"><?php echo $t['menu_tv']; ?></a></li>
            <li><a href="https://beltelecom.by/private/videokontrol-private" target="_blank"><?php echo $t['menu_videokontrol']; ?></a></li>
            <li><a href="https://beltelecom.by/private/video-intercom" target="_blank"><?php echo $t['menu_videodomofon']; ?></a></li>
            <li><a href="https://beltelecom.by/private/telephony/ustanovka-i-polzovanie-telefonom" target="_blank"><?php echo $t['menu_phone']; ?></a></li>
            <li><a href="https://beltelecom.by/private/hosting" target="_blank"><?php echo $t['menu_hosting']; ?></a></li>
            <li><a href="https://beltelecom.by/private/providing-access-to-the-my-city-platform" target="_blank"><?php echo $t['menu_mycity']; ?></a></li>
            <li><a href="https://beltelecom.by/private/other/telegraf" target="_blank"><?php echo $t['menu_other']; ?></a></li>
            <li><a href="https://beltelecom.by/private/discounts/republic" target="_blank"><?php echo $t['menu_akcii']; ?></a></li>
        </ul>
    </div>
</nav>

<!-- Подменю страниц -->
<nav class="sub-menu">
    <div class="container">
        <ul>
            <li><a href="index.php<?php echo $langParam; ?>" class="<?php echo $currentPage == 'index.php' ? 'active' : ''; ?>"><?php echo $t['packages_short']; ?></a></li>
            <li><a href="archive.php<?php echo $langParam; ?>" class="<?php echo $currentPage == 'archive.php' ? 'active' : ''; ?>"><?php echo $t['archive_short']; ?></a></li>
            <li><a href="instruction.php<?php echo $langParam; ?>" class="<?php echo $currentPage == 'instruction.php' ? 'active' : ''; ?>"><?php echo $t['instruction_short']; ?></a></li>
            <li><a href="other.php<?php echo $langParam; ?>" class="<?php echo $currentPage == 'other.php' ? 'active' : ''; ?>"><?php echo $t['other_short']; ?></a></li>
        </ul>
    </div>
</nav>

<main>
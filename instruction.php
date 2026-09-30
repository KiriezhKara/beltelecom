<?php
require_once 'config/db.php';
require_once 'lang.php';

$pageTitle = $t['instruction_short'] . ' – Белтелеком';
$langParam = $currentLang !== 'ru' ? '?lang=' . $currentLang : '';
require_once 'includes/header.php';
?>

<div class="page-title">
    <div class="container">
        <h1><?php echo $t['instruction_title']; ?></h1>
    </div>
</div>

<div class="container">
    <!-- Навигация по блокам -->
    <div class="info-block">
        <h2><?php echo $texts['instruction']['nav_title']; ?></h2>
        <div class="nav-blocks">
            <a href="#block1"><?php echo $texts['instruction']['nav']['connect']; ?></a>
            <a href="#block2"><?php echo $texts['instruction']['nav']['switch']; ?></a>
            <a href="#block3"><?php echo $texts['instruction']['nav']['change']; ?></a>
            <a href="#block4"><?php echo $texts['instruction']['nav']['pay']; ?></a>
            <a href="#block5"><?php echo $texts['instruction']['nav']['block']; ?></a>
            <a href="#block6"><?php echo $texts['instruction']['nav']['social']; ?></a>
        </div>
    </div>

    <?php foreach ($texts['instruction']['blocks'] as $i => $block): ?>
        <div class="instruction-block" id="block<?php echo $i + 1; ?>">
            <h3><?php echo $block['title']; ?></h3>
            <p><?php echo $block['subtitle']; ?></p>
            <p><?php echo $block['intro']; ?></p>
            <p><?php echo $block['text']; ?></p>
        </div>
    <?php endforeach; ?>

    <!-- Печать -->
    <div class="other-packages-link">
        <span></span>
        <button class="print-btn" onclick="window.print()"><?php echo $t['print']; ?></button>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
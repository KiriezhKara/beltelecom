<?php
require_once 'config/db.php';
require_once 'lang.php';

$pageTitle = $t['instruction_short'] . ' – Белтелеком';
$langParam = $currentLang !== 'ru' ? '?lang=' . $currentLang : '';
require_once 'includes/header.php';

// Получаем данные инструкции с защитой от ошибок
$insNavTitle = $texts['instruction']['nav_title'] ?? 'Навигация';
$insNav = $texts['instruction']['nav'] ?? [];
$insBlocks = $texts['instruction']['blocks'] ?? [];
?>

<div class="page-title">
    <div class="container">
        <h1><?php echo $t['instruction_title'] ?? 'Инструкция'; ?></h1>
    </div>
</div>

<div class="container">
    <!-- Навигация по блокам -->
    <?php if (!empty($insNav)): ?>
    <div class="info-block">
        <h2><?php echo $insNavTitle; ?></h2>
                <div class="nav-blocks">
            <?php if(isset($insNav['connect'])): ?><a href="#block1"><?php echo $insNav['connect']; ?></a><?php endif; ?>
            <?php if(isset($insNav['switch'])): ?><a href="#block2"><?php echo $insNav['switch']; ?></a><?php endif; ?>
            <?php if(isset($insNav['change'])): ?><a href="#block3"><?php echo $insNav['change']; ?></a><?php endif; ?>
            <?php if(isset($insNav['pay'])): ?><a href="#block4"><?php echo $insNav['pay']; ?></a><?php endif; ?>
            <?php if(isset($insNav['block'])): ?><a href="#block5"><?php echo $insNav['block']; ?></a><?php endif; ?>
            <?php if(isset($insNav['social'])): ?><a href="#block6"><?php echo $insNav['social']; ?></a><?php endif; ?>
            <a href="#block7">Прочие условия</a> 
        </div>
    </div>
    <?php endif; ?>

    <!-- Сами блоки -->
    <?php if (!empty($insBlocks) && is_array($insBlocks)): ?>
        <?php foreach ($insBlocks as $i => $block): ?>
            <div class="instruction-block" id="block<?php echo $i + 1; ?>">
                <h3><?php echo $block['title']; ?></h3>
                <p><?php echo $block['subtitle']; ?></p>
                <p><?php echo $block['text']; ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Информация временно недоступна.</p>
    <?php endif; ?>

    <!-- Печать -->
    <div class="other-packages-link no-print">
        <span></span>
        <button class="print-btn" onclick="window.print()"><?php echo $t['print']; ?></button>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
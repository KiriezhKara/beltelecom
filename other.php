<?php
require_once 'config/db.php';
require_once 'lang.php';

$pageTitle = $t['other_short'] . ' – Белтелеком';
$langParam = $currentLang !== 'ru' ? '?lang=' . $currentLang : '';
require_once 'includes/header.php';
?>

<div class="page-title">
    <div class="container">
        <h1><?php echo $t['other_title']; ?></h1>
    </div>
</div>
<p class="page-subtitle"><?php echo $t['other_subtitle']; ?></p>

<div class="container">
    <!-- Раскрывающийся блок условий -->
    <button class="accordion" onclick="toggleAccordion(this)">
        <?php echo $texts['other']['conditions_title'] ?? ''; ?>
    </button>
    <div class="accordion-panel">
        <div class="accordion-panel-inner">
            <ul>
                <?php if(isset($texts['other']['conditions_list']) && is_array($texts['other']['conditions_list'])): ?>
                    <?php foreach ($texts['other']['conditions_list'] as $item): ?>
                        <li><?php echo $item; ?></li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
            <p><?php echo $texts['other']['conditions_after'] ?? ''; ?></p>
        </div>
    </div>

        <!-- Тарифы -->
    <div class="tariffs-section services-extra">
        <h2><?php echo $texts['other']['tariffs_title'] ?? ''; ?></h2>
        <p><?php echo $texts['other']['tariffs_subtitle'] ?? ''; ?></p>

        <div class="tariffs-grid">
            <?php
            // Ищем тариф "Супер экспресс" в базе данных
            $stmt = $pdo->prepare("SELECT * FROM tariffs WHERE name = 'Супер экспресс' LIMIT 1");
            $stmt->execute();
            $superExpress = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($superExpress) {
                $superExpress = getTranslatedTariff($superExpress, $currentLang);
            ?>
                <div class="tariff-card">
                    <div class="name"><?php echo htmlspecialchars($superExpress['name']); ?></div>
                    <div class="speed"><?php echo htmlspecialchars($superExpress['speed']); ?></div>
                    <div class="features">
                        <p><?php echo htmlspecialchars($superExpress['tv']); ?></p>
                    </div>
                    <div class="price"><?php echo number_format($superExpress['price'], 2); ?> <?php echo $t['currency'] ?? 'руб.'; ?></div>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Печать -->
    <div class="other-packages-link no-print">
        <a href="instruction.php<?php echo $langParam; ?>"><?php echo $texts['other']['instruction_link'] ?? ''; ?></a>
        <button class="print-btn" onclick="window.print()"><?php echo $t['print']; ?></button>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
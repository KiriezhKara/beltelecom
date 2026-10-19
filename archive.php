<?php
require_once 'config/db.php';
require_once 'lang.php';

$pageTitle = $t['archive_title'] . ' — Белтелеком';
$langParam = $currentLang !== 'ru' ? '?lang=' . $currentLang : '';
require_once 'includes/header.php';
?>

<div class="page-title">
    <div class="container">
        <h1><?php echo $t['archive_title']; ?></h1>
    </div>
</div>
<p class="page-subtitle"><?php echo $t['archive_subtitle']; ?></p>

<div class="container">
        <!-- Фильтры -->
    <div class="filters">
        <h2><?php echo $t['filters']; ?></h2>
        <form id="filters-form" onsubmit="return false;">
            <div class="filter-row">
                <!-- Линейка -->
                <div class="filter-box">
                    <label><?php echo $texts['archive']['filter_line']; ?></label>
                    <div class="checkbox-list">
                        <label><input type="radio" name="line" value="all" checked> <?php echo $t['filter_all']; ?></label>
                        <label><input type="radio" name="line" value="ЯСНА"> <?php echo $t['line_yasna']; ?></label>
                        <label><input type="radio" name="line" value="Семейный"> <?php echo $t['line_family']; ?></label>
                        <label><input type="radio" name="line" value="Другое"> <?php echo $texts['archive']['line_other']; ?></label>
                    </div>
                </div>
                <!-- Технология -->
                <div class="filter-box">
                    <label><?php echo $t['filter_technology']; ?></label>
                    <div class="checkbox-list">
                        <label><input type="radio" name="archive-tech" value="all" checked> <?php echo $t['filter_all']; ?></label>
                        <label><input type="radio" name="archive-tech" value="PON"> PON</label>
                        <label><input type="radio" name="archive-tech" value="xDSL"> xDSL</label>
                        <label><input type="radio" name="archive-tech" value="LTE"> LTE</label>
                    </div>
                </div>
                                               <!-- Поиск -->
                <div class="filter-box filter-search">
                    <label><?php echo $t['filter_search'] ?? 'Поиск'; ?>:</label>
                    <div class="search-wrapper">
                        <input type="text" id="search-archive" placeholder="<?php echo $t['search_placeholder']; ?>">
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Архивные тарифы -->
    <div class="tariffs-section">
        <h2><?php echo $texts['archive']['tariffs_title']; ?></h2>
        <div class="tariffs-grid" id="tariffs-archive">
            <?php
            // Выбираем тарифы, у которых дата окончания меньше текущей даты
            $stmt = $pdo->prepare("SELECT * FROM tariffs WHERE date_end < CURDATE() ORDER BY name ASC");
            $stmt->execute();
            $tariffs = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($tariffs as $tariff):
                $tariff = getTranslatedTariff($tariff, $currentLang);
            ?>
                <div class="tariff-card archive-card"
                     data-line="<?php echo htmlspecialchars($tariff['line']); ?>"
                     data-tech="<?php echo $tariff['technology']; ?>"
                     data-name="<?php echo htmlspecialchars(mb_strtolower($tariff['name'])); ?>">
                    <div class="archive-label"><?php echo $t['archive_label']; ?></div>
                    <div class="name"><?php echo htmlspecialchars($tariff['name']); ?></div>
                    <div class="speed"><?php echo htmlspecialchars($tariff['speed']); ?></div>
                    <div class="features">
                        <?php if ($tariff['phone']): ?>
                            <p>📞 <?php echo htmlspecialchars($tariff['phone']); ?></p>
                        <?php endif; ?>
                        <?php if ($tariff['tv']): ?>
                            <p>📺 <?php echo htmlspecialchars($tariff['tv']); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="price"><?php echo number_format($tariff['price'], 2); ?> <?php echo $t['currency'] ?? 'руб.'; ?></div>
                    <button class="btn-compare" onclick="addToCompare(<?php echo $tariff['id']; ?>)"><?php echo $t['compare']; ?></button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Примечания -->
    <div class="notes-section">
        <h2><?php echo $texts['archive']['notes_title']; ?></h2>
        <table class="notes-table">
            <?php foreach ($texts['archive']['notes'] as $i => $note): ?>
                <tr>
                    <td><?php echo $i + 1; ?>.</td>
                    <td><?php echo $note; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <!-- Печать -->
    <div class="other-packages-link no-print">
        <span></span>
        <button class="print-btn" onclick="window.print()"><?php echo $t['print']; ?></button>
    </div>

    <!-- Таблица сравнения -->
    <div class="tariffs-section" id="compare-section" style="display: none;">
        <h2><?php echo $t['compare_title']; ?></h2>
        <div id="compare-table"></div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
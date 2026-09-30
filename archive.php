<?php
require_once 'config/db.php';
require_once 'lang.php';

$pageTitle = $t['archive_short'] . ' – Белтелеком';
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
        <div class="filter-row">
            <div class="filter-box">
                <label><?php echo $texts['archive']['filter_line']; ?></label>
                <div class="checkbox-list">
                    <label><input type="checkbox" class="filter-line" value="ЯСНА"> <?php echo $texts['archive']['line_yasna']; ?></label>
                    <label><input type="checkbox" class="filter-line" value="Семейный"> <?php echo $texts['archive']['line_family']; ?></label>
                    <label><input type="checkbox" class="filter-line" value="Другое"> <?php echo $texts['archive']['line_other']; ?></label>
                </div>
            </div>
            <div class="filter-box">
                <label><?php echo $t['filter_technology']; ?></label>
                <div class="checkbox-list">
                    <label><input type="checkbox" class="filter-tech" value="PON"> PON</label>
                    <label><input type="checkbox" class="filter-tech" value="xDSL"> xDSL</label>
                    <label><input type="checkbox" class="filter-tech" value="LTE"> LTE</label>
                </div>
            </div>
            <div class="filter-box filter-search">
                <label><?php echo $t['filter_search']; ?></label>
                <div class="search-wrapper">
                    <input type="text" id="search-archive" placeholder="<?php echo $t['search_placeholder']; ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- Архивные тарифы -->
    <div class="tariffs-section">
        <h2><?php echo $texts['archive']['tariffs_title']; ?></h2>
        <div class="tariffs-grid" id="tariffs-archive">
            <?php
            $stmt = $pdo->prepare("SELECT * FROM tariffs WHERE is_archive = 1 ORDER BY price DESC");
            $stmt->execute();
            $tariffs = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($tariffs as $tariff):
            ?>
                <div class="tariff-card archive-card"
                     data-line="<?php echo htmlspecialchars($tariff['line']); ?>"
                     data-tech="<?php echo $tariff['technology']; ?>"
                     data-name="<?php echo htmlspecialchars(mb_strtolower($tariff['name'])); ?>"
                     data-phone="<?php echo htmlspecialchars(tc($tariff['phone'] ?? '')); ?>"
                     data-tv-text="<?php echo htmlspecialchars(tc($tariff['tv'] ?? '')); ?>"
                     data-video-text="<?php echo htmlspecialchars(tc($tariff['video_control'] ?? '')); ?>">
                    <div class="archive-label"><?php echo $t['archive_label']; ?></div>
                    <div class="name"><?php echo htmlspecialchars($tariff['name']); ?></div>
                    <div class="speed"><?php echo htmlspecialchars($tariff['speed']); ?></div>
                    <div class="features">
                        <?php if ($tariff['phone']): ?>
                            <p>📞 <?php echo htmlspecialchars(tc($tariff['phone'])); ?></p>
                        <?php endif; ?>
                        <?php if ($tariff['tv']): ?>
                            <p>📺 <?php echo htmlspecialchars(tc($tariff['tv'])); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="price"><?php echo number_format($tariff['price'], 2); ?> руб.</div>
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
    <div class="other-packages-link">
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
<?php
require_once 'config/db.php';
require_once 'lang.php';

$pageTitle = $t['packages_short'] . ' – Белтелеком';
$langParam = $currentLang !== 'ru' ? '?lang=' . $currentLang : '';
require_once 'includes/header.php';
?>

<!-- Заголовок -->
<div class="page-title">
    <div class="container">
        <h1><?php echo $t['packages_title']; ?></h1>
    </div>
</div>
<p class="page-subtitle"><?php echo $t['packages_subtitle']; ?></p>

<div class="container">

    <!-- Что такое пакет услуг -->
    <div class="info-block">
        <h2><?php echo $texts['index']['what_is_title']; ?></h2>
        <p><?php echo $texts['index']['what_is_text']; ?></p>

        <h3><?php echo $texts['index']['features_intro']; ?></h3>
        <ul>
            <?php foreach ($texts['index']['features_list'] as $item): ?>
                <li><?php echo $item; ?></li>
            <?php endforeach; ?>
        </ul>

        <h3><?php echo $texts['index']['additional_intro']; ?></h3>
        <ul>
            <?php foreach ($texts['index']['additional_list'] as $item): ?>
                <li><?php echo $item; ?></li>
            <?php endforeach; ?>
        </ul>

        <p><strong><?php echo $texts['index']['attention']; ?></strong></p>
        <p><?php echo $texts['index']['main_terms']; ?></p>
    </div>

    <!-- Информация о пакетах Семейный -->
    <div class="info-block info-block-family">
        <h2><?php echo $t['family_info']; ?></h2>
        <p><?php echo $texts['index']['family_text']; ?></p>
    </div>

    <!-- Фильтры -->
    <div class="filters">
        <h2><?php echo $t['filters']; ?></h2>
        <div class="filter-row">
            <div class="filter-box">
                <label><?php echo $t['filter_technology']; ?></label>
                <div class="checkbox-list">
                    <label><input type="checkbox" class="filter-tech" value="PON"> PON</label>
                    <label><input type="checkbox" class="filter-tech" value="xDSL"> xDSL</label>
                    <label><input type="checkbox" class="filter-tech" value="LTE"> LTE</label>
                </div>
            </div>
            <div class="filter-box">
                <label><?php echo $t['filter_videokontrol']; ?></label>
                <div class="checkbox-list">
                    <label><input type="checkbox" class="filter-video" value="yes"> <?php echo $t['yes']; ?></label>
                    <label><input type="checkbox" class="filter-video" value="no"> <?php echo $t['no']; ?></label>
                </div>
            </div>
            <div class="filter-box">
                <label><?php echo $t['filter_tv']; ?></label>
                <div class="checkbox-list">
                    <label><input type="checkbox" class="filter-tv" value="yes"> <?php echo $t['yes']; ?></label>
                    <label><input type="checkbox" class="filter-tv" value="no"> <?php echo $t['no']; ?></label>
                </div>
            </div>
            <div class="filter-box filter-search">
                <label><?php echo $t['filter_search']; ?></label>
                <div class="search-wrapper">
                    <input type="text" id="search-packages" placeholder="<?php echo $t['search_placeholder']; ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- Пакеты ЯСНА -->
    <div class="tariffs-section">
        <h2><?php echo $t['menu_packages']; ?> «ЯСНА»</h2>
        <div class="tariffs-grid" id="tariffs-yasna">
            <?php
            $stmt = $pdo->prepare("SELECT * FROM tariffs WHERE line = 'ЯСНА' AND is_archive = 0 ORDER BY price DESC");
            $stmt->execute();
            $tariffs = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($tariffs as $tariff):
            ?>
                <div class="tariff-card"
                     data-tech="<?php echo $tariff['technology']; ?>"
                     data-video="<?php echo $tariff['video_control'] ? 'yes' : 'no'; ?>"
                     data-tv="<?php echo $tariff['tv'] ? 'yes' : 'no'; ?>"
                     data-phone="<?php echo htmlspecialchars(tc($tariff['phone'] ?? '')); ?>"
                     data-tv-text="<?php echo htmlspecialchars(tc($tariff['tv'] ?? '')); ?>"
                     data-video-text="<?php echo htmlspecialchars(tc($tariff['video_control'] ?? '')); ?>"
                     data-name="<?php echo htmlspecialchars(mb_strtolower($tariff['name'])); ?>">
                    <div class="name"><?php echo htmlspecialchars($tariff['name']); ?></div>
                    <div class="speed"><?php echo htmlspecialchars($tariff['speed']); ?></div>
                    <div class="features">
                        <?php if ($tariff['phone']): ?>
                            <p>📞 <?php echo htmlspecialchars(tc($tariff['phone'])); ?></p>
                        <?php endif; ?>
                        <?php if ($tariff['tv']): ?>
                            <p>📺 <?php echo htmlspecialchars(tc($tariff['tv'])); ?></p>
                        <?php endif; ?>
                        <?php if ($tariff['video_control']): ?>
                            <p>📹 <?php echo htmlspecialchars(tc($tariff['video_control'])); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="price"><?php echo number_format($tariff['price'], 2); ?> руб.</div>
                    <button class="btn-compare" onclick="addToCompare(<?php echo $tariff['id']; ?>)"><?php echo $t['compare']; ?></button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <p class="note"><?php echo $texts['index']['note_zala']; ?></p>

    <!-- Пакеты Семейный -->
    <div class="tariffs-section">
        <h2><?php echo $t['menu_packages']; ?> «Семейный»</h2>
        <div class="tariffs-grid" id="tariffs-family">
            <?php
            $stmt = $pdo->prepare("SELECT * FROM tariffs WHERE line = 'Семейный' AND is_archive = 0 ORDER BY price DESC");
            $stmt->execute();
            $tariffs = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($tariffs as $tariff):
            ?>
                <div class="tariff-card"
                     data-tech="<?php echo $tariff['technology']; ?>"
                     data-video="<?php echo $tariff['video_control'] ? 'yes' : 'no'; ?>"
                     data-tv="<?php echo $tariff['tv'] ? 'yes' : 'no'; ?>"
                     data-phone="<?php echo htmlspecialchars(tc($tariff['phone'] ?? '')); ?>"
                     data-tv-text="<?php echo htmlspecialchars(tc($tariff['tv'] ?? '')); ?>"
                     data-video-text="<?php echo htmlspecialchars(tc($tariff['video_control'] ?? '')); ?>"
                     data-name="<?php echo htmlspecialchars(mb_strtolower($tariff['name'])); ?>">
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

    <p class="note"><?php echo $texts['index']['note_mb']; ?></p>
    <p class="note"><?php echo $texts['index']['note_lte']; ?></p>
    <p class="note"><?php echo $texts['index']['note_zala']; ?></p>
    <p class="note"><?php echo $texts['index']['note_channels']; ?></p>

    <!-- Информация о блокировке -->
    <div class="info-block">
        <h2><?php echo $texts['index']['block_title']; ?></h2>
        <p><?php echo $texts['index']['block_intro']; ?></p>
        <ul>
            <?php foreach ($texts['index']['block_list'] as $item): ?>
                <li><?php echo $item; ?></li>
            <?php endforeach; ?>
        </ul>
        <p><?php echo $texts['index']['block_after']; ?></p>
    </div>

    <!-- Дополнительные услуги -->
    <div class="tariffs-section services-extra">
        <h2><?php echo $t['additional_services']; ?></h2>
        <div class="tariffs-grid">
            <?php
            $extraServices = [
                ['name' => 'Восстановление доступа', 'price' => 15.60],
                ['name' => 'Блокировка на 15 дней', 'price' => 3.10],
                ['name' => 'Блокировка на 30 дней', 'price' => 4.20],
                ['name' => 'Блокировка на 45 дней', 'price' => 5.20],
                ['name' => 'Блокировка на 90 дней', 'price' => 9.45],
                ['name' => 'Блокировка на 180 дней', 'price' => 52.50],
                ['name' => 'Приставка SMART ZALA', 'price' => 1.00],
                ['name' => 'SMS-оповещение', 'price' => 0.30],
                ['name' => 'Кредит доверия', 'price' => 1.00],
            ];
            foreach ($extraServices as $service):
            ?>
                <div class="tariff-card">
                    <div class="name"><?php echo tc($service['name']); ?></div>
                    <div class="price"><?php echo number_format($service['price'], 2); ?> руб.</div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Ссылка на прочие пакеты + Печать -->
    <div class="other-packages-link">
        <a href="other.php<?php echo $langParam; ?>"><?php echo $texts['index']['other_link']; ?></a>
        <button class="print-btn" onclick="window.print()"><?php echo $t['print']; ?></button>
    </div>

    <!-- Таблица сравнения -->
    <div class="tariffs-section" id="compare-section" style="display: none;">
        <h2><?php echo $t['compare_title']; ?></h2>
        <div id="compare-table"></div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
<?php
require_once 'config/db.php';
require_once 'lang.php';

$pageTitle = $t['packages_title'] . ' — Белтелеком';
$langParam = $currentLang !== 'ru' ? '?lang=' . $currentLang : '';
require_once 'includes/header.php';

// Функция вывода карточки тарифа
function renderTariffCard($tariff, $t, $currentLang) {
    $tariff = getTranslatedTariff($tariff, $currentLang);
    
    $searchName = htmlspecialchars(mb_strtolower($tariff['name']));
    $searchSpeed = htmlspecialchars(mb_strtolower($tariff['speed']));
    $searchPrice = $tariff['price'];
    ?>
    <div class="tariff-card"
         data-tech="<?php echo $tariff['technology']; ?>"
         data-video="<?php echo $tariff['video_control'] ? 'yes' : 'no'; ?>"
         data-tv="<?php echo $tariff['tv'] ? 'yes' : 'no'; ?>"
         data-name="<?php echo $searchName; ?>"
         data-speed="<?php echo $searchSpeed; ?>"
         data-price="<?php echo $searchPrice; ?>">
        <div class="name"><?php echo htmlspecialchars($tariff['name']); ?></div>
        <div class="speed"><?php echo htmlspecialchars($tariff['speed']); ?></div>
        <div class="features">
            <?php if (!empty($tariff['phone'])): ?><p>📞 <?php echo htmlspecialchars($tariff['phone']); ?></p><?php endif; ?>
            <?php if (!empty($tariff['tv'])): ?><p>📺 <?php echo htmlspecialchars($tariff['tv']); ?></p><?php endif; ?>
            <?php if (!empty($tariff['video_control'])): ?><p>📹 <?php echo htmlspecialchars($tariff['video_control']); ?></p><?php endif; ?>
        </div>
        <div class="price"><?php echo number_format($tariff['price'], 2); ?> <?php echo $t['currency'] ?? 'руб.'; ?></div>
        <button class="btn-compare" onclick="addToCompare(<?php echo $tariff['id']; ?>)"><?php echo $t['compare']; ?></button>
    </div>
    <?php
}
?>

<div class="page-title">
    <div class="container">
        <h1><?php echo $t['packages_title']; ?></h1>
    </div>
</div>
<p class="page-subtitle"><?php echo $t['packages_subtitle']; ?></p>

<div class="container">
    <!-- Блок "Что такое пакет услуг" -->
    <div class="info-block">
        <h2><?php echo $texts['index']['what_is_title'] ?? ''; ?></h2>
        <p><?php echo $texts['index']['what_is_text'] ?? ''; ?></p>
        
        <h3><?php echo $texts['index']['features_intro'] ?? ''; ?></h3>
        <ul>
            <?php if(isset($texts['index']['features_list']) && is_array($texts['index']['features_list'])): ?>
                <?php foreach ($texts['index']['features_list'] as $item): ?>
                    <li><?php echo $item; ?></li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>

        <h3><?php echo $texts['index']['additional_intro'] ?? ''; ?></h3>
        <ul>
            <?php if(isset($texts['index']['additional_list']) && is_array($texts['index']['additional_list'])): ?>
                <?php foreach ($texts['index']['additional_list'] as $item): ?>
                    <li><?php echo $item; ?></li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>

    <!-- Фильтры -->
    <div class="filters" id="filters-block">
        <h2><?php echo $t['filters']; ?></h2>
        <form id="filters-form" onsubmit="return false;">
            <div class="filter-row">
                <div class="filter-box">
                    <label><?php echo $t['filter_technology']; ?></label>
                    <div class="checkbox-list">
                        <label><input type="radio" name="tech" value="all" checked> <?php echo $t['filter_all'] ?? 'Все'; ?></label>
                        <label><input type="radio" name="tech" value="PON"> PON</label>
                        <label><input type="radio" name="tech" value="xDSL"> xDSL</label>
                        <label><input type="radio" name="tech" value="LTE"> LTE</label>
                    </div>
                </div>
                <div class="filter-box">
                    <label><?php echo $t['filter_videokontrol']; ?></label>
                    <div class="checkbox-list">
                        <label><input type="radio" name="video" value="all" checked> <?php echo $t['filter_all'] ?? 'Все'; ?></label>
                        <label><input type="radio" name="video" value="yes"> <?php echo $t['filter_yes'] ?? 'Да'; ?></label>
                        <label><input type="radio" name="video" value="no"> <?php echo $t['filter_no'] ?? 'Нет'; ?></label>
                    </div>
                </div>
                <div class="filter-box">
                    <label><?php echo $t['filter_tv']; ?></label>
                    <div class="checkbox-list">
                        <label><input type="radio" name="tv" value="all" checked> <?php echo $t['filter_all'] ?? 'Все'; ?></label>
                        <label><input type="radio" name="tv" value="yes"> <?php echo $t['filter_yes'] ?? 'Да'; ?></label>
                        <label><input type="radio" name="tv" value="no"> <?php echo $t['filter_no'] ?? 'Нет'; ?></label>
                    </div>
                </div>
                                               <!-- Поиск -->
                <div class="filter-box filter-search">
                    <label><?php echo $t['filter_search'] ?? 'Поиск'; ?>:</label>
                    <div class="search-wrapper">
                        <input type="text" id="search-packages" placeholder="<?php echo $t['search_placeholder']; ?>">
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Пакеты ЯСНА -->
    <div class="tariffs-section" id="tariffs-yasna-container">
        <h2><?php echo $t['menu_packages']; ?> «<?php echo $t['line_yasna']; ?>»</h2>
        <div class="tariffs-grid" id="tariffs-yasna">
            <?php
            $stmt = $pdo->prepare("SELECT * FROM tariffs WHERE line = 'ЯСНА' AND date_start <= CURDATE() AND (date_end IS NULL OR date_end >= CURDATE()) ORDER BY name ASC");
            $stmt->execute();
            while ($tariff = $stmt->fetch(PDO::FETCH_ASSOC)) {
                renderTariffCard($tariff, $t, $currentLang);
            }
            ?>
        </div>
    </div>

    <!-- Пакеты Семейный -->
    <div class="tariffs-section" id="tariffs-family-container">
        <h2><?php echo $t['menu_packages']; ?> «<?php echo $t['line_family']; ?>»</h2>
        <div class="tariffs-grid" id="tariffs-family">
            <?php
            $stmt = $pdo->prepare("SELECT * FROM tariffs WHERE line = 'Семейный' AND date_start <= CURDATE() AND (date_end IS NULL OR date_end >= CURDATE()) ORDER BY name ASC");
            $stmt->execute();
            while ($tariff = $stmt->fetch(PDO::FETCH_ASSOC)) {
                renderTariffCard($tariff, $t, $currentLang);
            }
            ?>
        </div>
    </div>

    <!-- Дополнительные услуги -->
    <div class="tariffs-section services-extra" id="extra-services-block">
        <h2><?php echo $t['additional_services']; ?></h2>
        <div class="tariffs-grid" id="extra-services-grid">
            <?php
            $stmt = $pdo->query("SELECT * FROM additional_services ORDER BY id ASC");
            $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($services as $service):
                $serviceName = $service['name'];
                if ($currentLang !== 'ru') {
                    // Ищем перевод, явно указывая язык
                    $transStmt = $pdo->prepare("SELECT name_en, name_be FROM additional_services_translations WHERE service_id = ? ORDER BY (lang = ?) DESC LIMIT 1");
                    $transStmt->execute([$service['id'], $currentLang]);
                    $trans = $transStmt->fetch(PDO::FETCH_ASSOC);
                    if ($trans) {
                        if ($currentLang == 'en' && !empty($trans['name_en'])) {
                            $serviceName = $trans['name_en'];
                        } elseif ($currentLang == 'be' && !empty($trans['name_be'])) {
                            $serviceName = $trans['name_be'];
                        }
                    }
                }
            ?>
                <div class="tariff-card extra-card">
                    <div class="name"><?php echo htmlspecialchars($serviceName); ?></div>
                    <div class="price"><?php echo number_format($service['price'], 2); ?> <?php echo $t['currency'] ?? 'руб.'; ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Печать и ссылка -->
    <div class="other-packages-link no-print">
        <a href="other.php<?php echo $langParam; ?>"><?php echo $texts['index']['other_link'] ?? ''; ?></a>
        <button class="print-btn" onclick="printTariffs()"><?php echo $t['print']; ?></button>
    </div>

    <div class="tariffs-section" id="compare-section" style="display: none;">
        <h2><?php echo $t['compare_title']; ?></h2>
        <div id="compare-table"></div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
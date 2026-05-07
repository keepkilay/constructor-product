<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
use Bitrix\Main\Loader;
\Bitrix\Main\Loader::includeModule('iblock');

global $viewProduct;

$gallery = get_element_property(2, $viewProduct['ID'], 'GALLERY');

$arPictures = [];
foreach ($gallery as $fileId) {
    $arPictures[] = CFile::GetPath($fileId);
}

$arPrices = get_price_with_discounts($viewProduct);

include_once($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/include/product–form–consultation.php");
?>
<style>
    :root {
    <?php
    // Получаем значение градиента
    $gradientValue = $arResult["PROPERTIES"]["COLOR_BACKGROUND"]["VALUE"];

    // Извлекаем rgba значения из строки
    preg_match_all('/rgba\(([^)]+)\)/', $gradientValue, $matches);
    $rgba1 = isset($matches[0][0]) ? str_replace(' ', '', $matches[0][0]) : 'rgba(255,239,203)';
    $rgba2 = isset($matches[0][1]) ? str_replace(' ', '', $matches[0][1]) : 'rgba(255,159,100)';
    ?> --accent: #<?=$arResult["PROPERTIES"]["COLOR_BUTTONS"]["VALUE"]?>;
        --gradient: <?=$rgba1?>;
        --gradient-2: <?=$rgba2?>;

        --button-bg: #<?=$arResult["PROPERTIES"]["COLOR_BUTTONS"]["VALUE"]?>;
        --button-bg-secondary: #<?=$arResult["PROPERTIES"]["COLOR_BTN_TEXT"]["VALUE"]?>;
        --button-border: #<?=$arResult["PROPERTIES"]["COLOR_BUTTONS"]["VALUE"]?>;
        --button-hover: #<?=$arResult["PROPERTIES"]["COLOR_BUTTONS_ACTIVE"]["VALUE"]?>;
        --button-text: #<?=$arResult["PROPERTIES"]["COLOR_BTN_TEXT"]["VALUE"]?>;
        --button-text-secondary: #<?=$arResult["PROPERTIES"]["COLOR_BUTTONS"]["VALUE"]?>;

        --title: #<?=$arResult["PROPERTIES"]["COLOR_TEXT"]["VALUE"]?>;
        --sub-title: #<?=$arResult["PROPERTIES"]["COLOR_TEXT"]["VALUE"]?>;
        --text: #<?=$arResult["PROPERTIES"]["COLOR_TEXT"]["VALUE"]?>;
        --text-accent: #<?=$arResult["PROPERTIES"]["COLOR_VIDEO_DECOR"]["VALUE"]?>;
    }
</style>

<?php
$this->SetViewTarget('DOP_CLASS'); ?><?php
echo $arResult["PROPERTIES"]["SECTION_TEMPLATE"]["VALUE_XML_ID"] . ' ' . $arResult["PROPERTIES"]["NEW_TEMPLATE"]["VALUE_XML_ID"];
?><?php $this->EndViewTarget(); ?>


<!--Всплывашка купить (в правом углу)-->
<div class="cta-top">
    <div class="cta-top__left">
        <span class="cta-top__title"><?= $arResult["NAME"]; ?></span>
        <span class="cta-top__subtitle">
			<?= htmlspecialcharsBack($arResult["PROPERTIES"]["DESCRIPTION"]["VALUE"]); ?>
		</span>
    </div>
    <a class="cta-top__button common-button common-button--light " href="/cart/?add2cart=<?= $arResult['ID']; ?>">
        <?= GetMessage("BUY_BTN"); ?>
    </a>
</div>
<!--Всплывашка купить (в правом углу)-->

<section class="first-screen first-screen--step">
    <div class="container">
        <div class="first-screen__inner">
            <div class="first-screen__left">
                <h1 class="first-screen__title">
                    <?= $arResult["NAME"] ?>
                </h1>
                <?php
                if (!empty($arResult["PROPERTIES"]["DESC_BANNER_1"]["VALUE"]["TEXT"])) {
                    ?>
                    <p class="first-screen__subtitle">
                        <?= htmlspecialcharsBack($arResult["PROPERTIES"]["DESC_BANNER_1"]["VALUE"]["TEXT"]); ?>
                    </p>
                <?php } ?>
                <?php
                if (!empty($arResult["PROPERTIES"]["DESC_BANNER_2"]["VALUE"]["TEXT"])) {
                    ?>
                    <p class="first-screen__description <?= $arResult["PROPERTIES"]["DOP_FOR_DESC_BANNER"]["VALUE"]; ?>">
                        <?= htmlspecialcharsBack($arResult["PROPERTIES"]["DESC_BANNER_2"]["VALUE"]["TEXT"]); ?>
                    </p>
                <?php } ?>

                <?php
                if ($arResult["PROPERTIES"]["ESCAPE_BTN"]["VALUE_XML_ID"] === 'yes') { ?>
                    <div class="first-screen__action">
                        <button class="first-screen__button common-button js-open-feedback-modal " type="button">
                            <?= GetMessage("GET_CONSULTATION"); ?>
                        </button>
                    </div>
                <?php } ?>
            </div>

            <div class="first-screen__right">
                <div class="first-screen__picture-box">
                    <picture class="first-screen__picture <?=$arResult["PROPERTIES"]["CLASS_BANNER"]["VALUE"]?>">
                        <img class="first-screen__image impulse-flow impulse-step"
                             src="<?= CFile::GetPath($arResult["PROPERTIES"]["IMG_BANNER"]["VALUE"]); ?>"
                             alt="<?= $arResult["NAME"];?>">
                    </picture>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
foreach ($arResult["BLOCKS"] as $key => $arItemConstr) {

    switch ($arItemConstr['PROPERTIES']['TYPE']['VALUE_XML_ID']) {
        case 'advantages-list-img':

            $arItems = [];
            $length = false;
            $half = false;

            if (!empty($arItemConstr['PROPERTIES']['TEXT_LIST']['VALUE'])) {

                foreach ($arItemConstr['PROPERTIES']['TEXT_LIST']['VALUE'] as $ky => $v) {
                    $arItemConstr['PROPERTIES']['TEXT_LIST']['VALUE'][$ky]['DESCRIPTION'] = $arItemConstr['PROPERTIES']['TEXT_LIST']['DESCRIPTION'][$ky];

                    $img = $arItemConstr['PROPERTIES']['PICTURE_LIST']['VALUE'][$ky];
                    if (!empty($img)) {
                        $arItemConstr['PROPERTIES']['TEXT_LIST']['VALUE'][$ky]['IMG'] = CFile::GetPath($img);
                    }

                }

                $length = count($arItemConstr['PROPERTIES']['TEXT_LIST']['VALUE']);
                $half = ceil($length / 2);
                $arItems[] = array_slice($arItemConstr['PROPERTIES']['TEXT_LIST']['VALUE'], 0, $half);
                $arItems[] = array_slice($arItemConstr['PROPERTIES']['TEXT_LIST']['VALUE'], $half);
            }

            ?>

            <section class="features features--1">
                <h2 class="features__title title">
					<span class="title__inner">
						<?= htmlspecialcharsBack($arItemConstr['PROPERTIES']['DESC_FIRST_BLOCK']['VALUE']); ?>
					</span>
                </h2>
                <div class="features__wrapper">
                    <div class="features__picture">
                        <img class="features__image"
                             src="<?= CFile::GetPath($arItemConstr['PROPERTIES']['B_MAIN_IMG']['VALUE']) ?>"
                             width="266" height="266" loading="lazy"
                             alt="<?= $arItemConstr["NAME"] ?>">
                    </div>
                    <?php
                    foreach ($arItems as $arr) { ?>
                        <ul class="features__list">
                            <?php
                            foreach ($arr as $itemAr) { ?>
                                <li class="features__item">
                                    <div class="features-item">
                                        <div class="features-item__image-wrapper">
                                            <img class="features-item__image" src="<?= $itemAr['IMG']; ?>" width="46"
                                                 height="46" loading="lazy" alt="<?= $arItemConstr["NAME"] ?>">
                                        </div>
                                        <div class="features-item__content">
                                            <p class="features-item__title"><?= $itemAr['DESCRIPTION']; ?></p>
                                            <p class="features-item__description"><?= htmlspecialcharsBack($itemAr['TEXT']); ?></p>
                                        </div>
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php } ?>
                </div>
            </section>

            <?php
            break;

            case 'who-is-this':

            $arItems = [];
            $length = false;
            $half = false;

            if (!empty($arItemConstr['PROPERTIES']['B_TOO_LIST_TEXT']['VALUE'])) {

                foreach ($arItemConstr['PROPERTIES']['B_TOO_LIST_TEXT']['VALUE'] as $ky => $v) {

                    $img = $arItemConstr['PROPERTIES']['BLOCK_TWO_PICTURE']['VALUE'][$ky];
                    $imgDesc = $arItemConstr['PROPERTIES']['BLOCK_TWO_PICTURE']['DESCRIPTION'][$ky];

                    $arItems[$ky]['VALUE'] = $v;
                    if (!empty($img)) {
                        $arItems[$ky]['IMG'] = CFile::GetPath($img);
                    }
                    if (!empty($imgDesc)) {
                        $arItems[$ky]['IMG_DESC'] = $imgDesc;
                    }
                }
            }
            ?>

            <?php
            $usersCount = count($arItems);
            $modifier = ($usersCount % 2 === 0) ? "users--4" : "users--3";
            ?>

            <section class="users <?= $modifier ?>">
                <?php
                if ($arItemConstr["PROPERTIES"]["B_TWO_SHOW_TITLE"]["VALUE_XML_ID"] === "yes") {
                    ?>
                    <p class="users__small-title small-title titleNew">
                        <?= $arItemConstr["NAME"]; ?>
                    </p>
                <?php } ?>
                <h2 class="users__title users__title--step title">
                        <span class="title__inner">
                            <?= htmlspecialcharsBack($arItemConstr["PROPERTIES"]["DESC_SEC_BLOCK"]["VALUE"]); ?>
                        </span>
                </h2>
                <div class="container">
                    <div class="users__inner">
                        <ul class="users__list">
                            <?php
                            foreach ($arItems as $key => $itemAr) { ?>
                                <?php
                                if ($key == 0) { ?>
                                    <li style="background-size: 70% 50%; position: relative; top: 0; left: 0;"
                                        class="users__item users__item--first newUsers users__item--active">
                                        <div class="users__picture">
                                            <img class="users__image"
                                                 src="<?= CFile::GetPath($arItemConstr["PROPERTIES"]["B_TOO_IMG_MAIN"]["VALUE"]); ?>"
                                                 width="266" height="266" loading="lazy"
                                                 alt="<?= $arItemConstr["NAME"]; ?>">
                                        </div>
                                    </li>
                                <?php } ?>
                                <li class="users__item users__item--active">
                                    <div class="users__picture">
                                        <img class="users__image" src="<?= $itemAr['IMG']; ?>" width="266"
                                             height="266"
                                             loading="lazy" alt="<?= $arItemConstr["NAME"]; ?>">
                                    </div>
                                    <h3 class="users__heading users__headingNew">
                                        <?= $itemAr['IMG_DESC']; ?>
                                    </h3>
                                    <?= htmlspecialcharsBack($itemAr["VALUE"]["TEXT"]); ?>
                                </li>
                            <?php } ?>
                        </ul>
                        <?php
                        if ($arItemConstr["PROPERTIES"]["B_TOO_ESCAPE_BTN"]["VALUE_XML_ID"] === 'yes') { ?>
                            <button class="users__button common-button js-scroll-to-price newBtn">
                                <?= $arItemConstr["PROPERTIES"]["B_TOO_TEXT_BTN"]["VALUE"]; ?>
                            </button>
                        <?php } ?>
                    </div>
                </div>
            </section>

            <?php
            break;

            case 'advantages-list-img-too-block':

            $arItems = [];
            $length = false;
            $half = false;

            if (!empty($arItemConstr['PROPERTIES']['B_THREE_TEXTS']['VALUE'])) {

                foreach ($arItemConstr['PROPERTIES']['B_THREE_TEXTS']['VALUE'] as $ky => $v) {
                    $arItemConstr['PROPERTIES']['B_THREE_TEXTS']['VALUE'][$ky]['DESCRIPTION'] = $arItemConstr['PROPERTIES']['B_THREE_TEXTS']['DESCRIPTION'][$ky];

                    $img = $arItemConstr['PROPERTIES']['B_THREE_IMGS']['VALUE'][$ky];
                    if (!empty($img)) {
                        $arItemConstr['PROPERTIES']['B_THREE_TEXTS']['VALUE'][$ky]['IMG'] = CFile::GetPath($img);
                    }

                }

                $length = count($arItemConstr['PROPERTIES']['B_THREE_TEXTS']['VALUE']);
                $half = ceil($length / 2);
                $arItems[] = array_slice($arItemConstr['PROPERTIES']['B_THREE_TEXTS']['VALUE'], 0, $half);
                $arItems[] = array_slice($arItemConstr['PROPERTIES']['B_THREE_TEXTS']['VALUE'], $half);
            }

            ?>
            <section class="features features--2">
                <?php
                if (!empty($arItemConstr["PROPERTIES"]["B_THREE_QUESTION"]["VALUE"])) {
                    ?>
                    <p class="features__small-title small-title">
                        <?= $arItemConstr["PROPERTIES"]["B_THREE_QUESTION"]["VALUE"]; ?>
                    </p>
                <?php } ?>
                <?php
                if (!empty($arItemConstr["PROPERTIES"]["B_THREE_DESCRIPTION"]["VALUE"])) {
                    ?>
                    <h2 class="features__title features__title--type title">
                        <span class="title__inner">
                            <?= htmlspecialcharsBack($arItemConstr["PROPERTIES"]["B_THREE_DESCRIPTION"]["VALUE"]); ?>
                        </span>
                    </h2>
                <?php } ?>
                <div class="features__wrapper">
                    <picture class="features__picture">
                        <img class="features__image"
                             src="<?= CFile::GetPath($arItemConstr["PROPERTIES"]["B_THREE_MAIN_IMG"]["VALUE"]); ?>"
                             width="266" height="266" loading="lazy" alt="<?= $arItemConstr["NAME"]; ?>">
                    </picture>
                    <?php
                    foreach ($arItems as $arr) { ?>
                        <ul class="features__list">
                            <?php
                            foreach ($arr as $itemAr) { ?>
                                <li class="features__item">
                                    <div class="features-item">
                                        <div class="features-item__image-wrapper">
                                            <img class="features-item__image" src="<?= $itemAr['IMG']; ?>" width="46"
                                                 height="46" loading="lazy" alt="<?= $arItemConstr["NAME"]; ?>">
                                        </div>
                                        <div class="features-item__content">
                                            <p class="features-item__description">
                                                <?= htmlspecialcharsBack($itemAr['TEXT']); ?>
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php } ?>
                </div>
            </section>

            <?php
            break;

            case 'who-this-working':

            $arItems = [];
            $length = false;
            $half = false;

            if (!empty($arItemConstr['PROPERTIES']['B_FOUR_FIRST_TEXT']['VALUE']['TEXT']) || !empty($arItemConstr['PROPERTIES']['B_FOUR_SEC_TEXT']['VALUE']['TEXT'])) {


                $length = count($arItemConstr['PROPERTIES']['B_FOUR_FIRST_TEXT']['VALUE']);
                $half = ceil($length / 2);
                $arItems[] = array_slice($arItemConstr['PROPERTIES']['B_FOUR_FIRST_TEXT']['VALUE'], 0, $half);
                $arItems[] = array_slice($arItemConstr['PROPERTIES']['B_FOUR_FIRST_TEXT']['VALUE'], $half);
            }

            ?>

            <section class="action">
                <p class="action__small-title small-title">
                    <?= $arItemConstr["PROPERTIES"]["B_FOUR_QUESTION"]["VALUE"]; ?>
                </p>
                <h2 class="action__title title">
					<span class="title__inner">
						<?= htmlspecialcharsBack($arItemConstr["PROPERTIES"]["B_FOUR_DESCRIPTION"]["VALUE"]); ?>
					</span>
                </h2>
                <div class="action__inner">
                    <picture class="action__picture action__picture--first">
                        <img class="action__image"
                             src="<?= CFile::GetPath($arItemConstr["PROPERTIES"]["B_FOUR_FIRST_IMG"]["VALUE"]); ?>"
                             width="346" height="262" loading="lazy" alt="<?= $arItemConstr["NAME"]; ?>">
                    </picture>
                    <div class="action__description">
                        <div class="action__paragraph">
                            <?= htmlspecialcharsBack($arItemConstr["PROPERTIES"]["B_FOUR_FIRST_TEXT"]["VALUE"]["TEXT"]); ?>
                        </div>
                    </div>
                </div>
                <br>
                <div class="action__inner flex-start">
                    <picture class="action__picture action__picture--second">
                        <source media="(min-width: 768px)"
                                srcset="<?= CFile::GetPath($arItemConstr["PROPERTIES"]["B_FOUR_SEC_IMG"]["VALUE"]); ?>">
                        <img class="action__image"
                             src="<?= CFile::GetPath($arItemConstr["PROPERTIES"]["B_FOUR_SEC_IMG"]["VALUE"]); ?>"
                             width="346" height="262" loading="lazy" alt="<?= $arItemConstr["NAME"]; ?>">
                    </picture>
                    <div class="action__description action__description--mr">
                        <div class="action__paragraph">
                            <?= htmlspecialcharsBack($arItemConstr["PROPERTIES"]["B_FOUR_SEC_TEXT"]["VALUE"]["TEXT"]); ?>
                        </div>
                    </div>
                </div>
            </section>
                <?php
                if ($arItemConstr["PROPERTIES"]["B_FOUR_ESCAPE_BTN"]["VALUE_XML_ID"] === 'yes') {
                    ?>
                    <div class="independent">
                        <button class="independent__button common-button js-scroll-to-price newBtn">
                            <?= $arItemConstr["PROPERTIES"]["B_FOUR_TEXT_BTN"]["VALUE"]; ?>
                        </button>
                    </div>
                <?php } ?>


            <?php
            break;

            case 'advantages-list-img-three-block':

            $arItems = [];
            $length = false;
            $half = false;

            if (!empty($arItemConstr['PROPERTIES']['B_FIVE_TEXT']['VALUE'])) {

                foreach ($arItemConstr['PROPERTIES']['B_FIVE_TEXT']['VALUE'] as $ky => $v) {
                    $arItemConstr['PROPERTIES']['B_FIVE_TEXT']['VALUE'][$ky]['DESCRIPTION'] = $arItemConstr['PROPERTIES']['B_FIVE_TEXT']['DESCRIPTION'][$ky];

                    $img = $arItemConstr['PROPERTIES']['B_FIVE_IMG']['VALUE'][$ky];
                    if (!empty($img)) {
                        $arItemConstr['PROPERTIES']['B_FIVE_TEXT']['VALUE'][$ky]['IMG'] = CFile::GetPath($img);
                    }

                }

                $length = count($arItemConstr['PROPERTIES']['B_FIVE_TEXT']['VALUE']);
                $half = ceil($length / 2);
                $arItems[] = array_slice($arItemConstr['PROPERTIES']['B_FIVE_TEXT']['VALUE'], 0, $half);
                $arItems[] = array_slice($arItemConstr['PROPERTIES']['B_FIVE_TEXT']['VALUE'], $half);
            }

            ?>
            <section class="features features--3">
                <?php
                if (!empty($arItemConstr["PROPERTIES"]["B_FIVE_QUESTION"]["VALUE"])) {
                    ?>
                    <p class="users__small-title small-title titleNew">
                        <?= $arItemConstr["PROPERTIES"]["B_FIVE_QUESTION"]["VALUE"]; ?>
                    </p>
                <?php } ?>
                <h2 class="features__title title">
                    <?php
                    if (!empty($arItemConstr["PROPERTIES"]["B_FIVE_DESCRIPTION"]["VALUE"]["TEXT"])) {
                        ?>
                        <span class="title__inner">
                    <?= htmlspecialcharsBack($arItemConstr["PROPERTIES"]["B_FIVE_DESCRIPTION"]["VALUE"]["TEXT"]); ?>
                </span>
                    <?php } ?>
                </h2>
                <div class="features__wrapper">
                    <?php
                    if (!empty($arItemConstr["PROPERTIES"]["B_FIVE_MAIN_IMG"]["VALUE"])) {
                        ?>
                        <picture class="features__picture">
                            <img class="features__image"
                                 src="<?= CFile::GetPath($arItemConstr["PROPERTIES"]["B_FIVE_MAIN_IMG"]["VALUE"]); ?>"
                                 width="266" height="266" loading="lazy" alt="<?= $arItemConstr["NAME"]; ?>">
                        </picture>
                    <?php } ?>
                    <?php
                    foreach ($arItems as $arr) { ?>
                        <ul class="features__list">
                            <?php
                            foreach ($arr as $itemAr) { ?>
                                <li class="features__item">
                                    <div class="features-item">
                                        <div class="features-item__image-wrapper">
                                            <img class="features-item__image" src="<?= $itemAr['IMG']; ?>" width="46"
                                                 height="46" loading="lazy" alt="<?= $arItemConstr["NAME"]; ?>">
                                        </div>
                                        <div class="features-item__content">
                                            <p class="features-item__description">
                                                <?= htmlspecialcharsBack($itemAr['TEXT']); ?>
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php } ?>
                </div>
            </section>

            <?php
            break;
            case 'block-with-price':

            ?>

            <section class="price" id="price-block">
                <div class="price__wrapper">
                    <p class="price__small-title small-title">
                        <?= $arItemConstr["PROPERTIES"]["B_SIX_QUESTION"]["VALUE"]; ?>
                    </p>
                    <h2 class="price__title title">
                        <span class="title__inner">
                           <?= htmlspecialcharsBack($arItemConstr["NAME"]); ?>
                        </span>
                    </h2>
                    <div class="price__slider">
                        <div class="price__photogallery">
                            <div class="swiper swiper--main">
                                <div class="swiper-wrapper">
                                    <?php
                                    foreach ($arItemConstr["PROPERTIES"]["B_SIX_IMGS_LIST"]["VALUE"] as $picture) { ?>
                                        <div class="swiper-slide">
                                            <div class="price__image-wrapper">
                                                <picture>
                                                    <img src="<?= CFile::GetPath($picture); ?>"
                                                         width="315"
                                                         height="315"
                                                         loading="lazy"
                                                         alt="">
                                                </picture>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="price__miniature">
                            <div class="swiper swiper--preview">
                                <div class="swiper-wrapper">
                                    <?php
                                    foreach ($arItemConstr["PROPERTIES"]["B_SIX_IMGS_LIST"]["VALUE"] as $picture) { ?>
                                        <div class="swiper-slide">
                                            <picture>
                                                <img src="<?= CFile::GetPath($picture); ?>"
                                                     width="68"
                                                     height="68"
                                                     loading="lazy"
                                                     alt="">
                                            </picture>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php
                            $colorProp = $arItemConstr['PROPERTIES']['B_SIX_COLOR_WAY']['VALUE'] ?? '';
                            if (is_array($colorProp)) { $colorProp = reset($colorProp); }
                            $strokeColor = trim((string)$colorProp) !== '' ? $colorProp : '#ED7349';
                            ?>
                            <div class="swiper-controls">
                                <div class="swiper__button swiper__button--prev">
                                    <svg class="swiper__button-icon" width="13" height="22" viewBox="0 0 13 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 21L2 10.9524L11.9048 1"
                                              stroke="#A48DFF" stroke-width="1.5"
                                              style="stroke: <?= htmlspecialchars($strokeColor, ENT_QUOTES) ?>;"
                                              stroke-miterlimit="10"/>
                                    </svg>
                                </div>
                                <div class="swiper__button swiper__button--next">
                                    <svg class="swiper__button-icon" width="13" height="22" viewBox="0 0 13 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1 0.999999L11 11.0476L1.09524 21"
                                              stroke="#A48DFF" stroke-width="1.5"
                                              style="stroke: <?= htmlspecialchars($strokeColor, ENT_QUOTES) ?>;"
                                              stroke-miterlimit="10"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="price__body">
                        <div class="price__description">
                            <?= htmlspecialcharsBack($arItemConstr["PROPERTIES"]["B_SIX_LIST"]["VALUE"]["TEXT"]); ?>
                        </div>
                        <div class="price__cta">
                            <div class="price__value">
                                <span class="price__new-value">
                                    <?= htmlspecialcharsBack($arItemConstr["PROPERTIES"]["B_SIX_PRICE"]["VALUE"]); ?> ₽
                                </span>
                                <span class="price__old-value"></span>
                            </div>
                            <?php
                            if (!empty($arItemConstr["PROPERTIES"]["B_SIX_BTN_NAME"]["NAME"])) {
                                ?>
                                <a href="/cart/?add2cart=<?= $arResult["ID"]; ?>"
                                   class="price__button common-button common-button--link" data-kmt="1">
                                    <?= $arItemConstr["PROPERTIES"]["B_SIX_BTN_NAME"]["VALUE"]; ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </section>

            <?php
            break;
            case 'technical-specifications':

            $arItems = [];
            $length = false;
            $half = false;

            if (!empty($arItemConstr['PROPERTIES']['B_SEVEN_TECH_LIST']['VALUE'])) {

                foreach ($arItemConstr['PROPERTIES']['B_SEVEN_TECH_LIST']['VALUE'] as $ky => $v) {
                    $arItemConstr['PROPERTIES']['B_SEVEN_TECH_LIST']['VALUE'][$ky]['DESCRIPTION'] = $arItemConstr['PROPERTIES']['B_SEVEN_TECH_LIST']['DESCRIPTION'][$ky];

                    $img = $arItemConstr['PROPERTIES']['B_THREE_IMGS']['VALUE'][$ky];
                    if (!empty($img)) {
                        $arItemConstr['PROPERTIES']['B_SEVEN_TECH_LIST']['VALUE'][$ky]['IMG'] = CFile::GetPath($img);
                    }

                }

                $length = count($arItemConstr['PROPERTIES']['B_SEVEN_TECH_LIST']['VALUE']);
                $half = ceil($length / 2);
                $arItems[] = array_slice($arItemConstr['PROPERTIES']['B_SEVEN_TECH_LIST']['VALUE'], 0, $half);
                $arItems[] = array_slice($arItemConstr['PROPERTIES']['B_SEVEN_TECH_LIST']['VALUE'], $half);
            }

            ?>

            <section class="information">
                <div class="information__wrapper">
                    <div class="container">
                        <div class="information__accordion accordion accordion--visible accordion--active">
                            <h2 class="accordion__title title">
                            <span class="title__inner">
                                <?= htmlspecialcharsBack($arItemConstr["NAME"]); ?>
                            </span>
                                <button class="accordion__button js-accordion-button" type="button"
                                        aria-label="Открыть описание">
                                    <svg class="accordion__icon" width="30" height="30" viewBox="0 0 30 30" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0.857422 15H29.1417" stroke="#667079" stroke-width="1.5"/>
                                        <path d="M15 29.1426L15 0.858306" stroke="#667079" stroke-width="1.5"/>
                                    </svg>
                                </button>
                            </h2>
                            <div class="accordion__outer">
                                <div class="accordion__inner description">
                                    <?php
                                    foreach ($arItems as $arItemConstr) { ?>
                                        <ul class="description__list">
                                            <?php
                                            foreach ($arItemConstr as $itemAr) { ?>
                                                <li class="description__row">
                                                    <p class="description__title">
                                                        <?= htmlspecialcharsBack($itemAr["DESCRIPTION"]); ?>
                                                    </p>
                                                    <div class="description__body">
                                                        <?= htmlspecialcharsBack($itemAr["TEXT"]); ?>
                                                    </div>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                        <?php
                        $APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "",
                            array(
                                "AREA_FILE_SHOW" => "file",
                                "ID_ELEMENT" => $arResult['ID'],
                                "PATH" => SITE_DIR . "include/products/common/11-product-info-faq.php"
                            ),
                            false,
                            array('HIDE_ICONS' => 'Y')
                        ); ?>
                    </div>
                </div>
            </section>

            <?php
            break;
            case 'slider-nasadki':

            $linkedProductIds = $arItemConstr["PROPERTIES"]["B_NINE_LINK_NOZZLE"]["VALUE"];

            if (!empty($linkedProductIds)) {
                $arLinkedSelect = ["ID", "IBLOCK_ID", "NAME", "PREVIEW_PICTURE", "PROPERTY_*"];
                $arLinkedFilter = [
                    "IBLOCK_ID" => getIblockIdByCode("nasadki-products"),
                    "ID" => $linkedProductIds,
                    "ACTIVE" => "Y"
                ];
                $linkedRes = CIBlockElement::GetList([], $arLinkedFilter, false, false, $arLinkedSelect);

                $arResult["SLIDER_NASADKI"] = [];
                while ($linkedOb = $linkedRes->GetNextElement()) {
                    $linkedFields = $linkedOb->GetFields();
                    $linkedFields["PROPERTIES"] = $linkedOb->GetProperties();
                    $arResult["SLIDER_NASADKI"][] = $linkedFields; // Добавляем без привязки к ID
                }
            }

            ?>

            <section class="section section--slider">
                <div class="container">
                    <?php
                    if (!empty($arItemConstr["PROPERTIES"]["B_NINE_TITLE"]["VALUE"])) {
                        ?>
                        <p class="features__small-title small-title">
                            <?= $arItemConstr["PROPERTIES"]["B_NINE_TITLE"]["VALUE"]; ?>
                        </p>
                    <?php } ?>
                    <h2 class="section__title"><?= htmlspecialcharsBack($arItemConstr["PROPERTIES"]["B_NINE_DESC"]["VALUE"]["TEXT"]); ?></h2>
                    <?php
                    if ($arItemConstr["PROPERTIES"]["B_NINE_DOP_CLASS"]["VALUE_XML_ID"] === "yes") {
                    ?>
                    <div class="swiper swiper--hot-cold swiper--nozzle">
                        <?php } else { ?>
                        <div class="swiper swiper--nozzle">
                            <?php } ?>
                            <div class="swiper-wrapper">
                                <?php
                                foreach ($arResult["SLIDER_NASADKI"] as $infoResNasadka) {
                                    if (!empty($infoResNasadka["PROPERTIES"]["DESC_MIDL"]["VALUE"]["TEXT"])) {
                                        ?>
                                        <div class="nozzle-card swiper-slide">
                                            <h3 class="h3"><?= $infoResNasadka["NAME"]; ?></h3>
                                            <div class="nozzle-card__wrapper">
                                                <?php
                                                if (!empty($infoResNasadka["PREVIEW_PICTURE"])) { ?>
                                                    <img class="nozzle-card__img"
                                                         src="<?= CFile::GetPath($infoResNasadka["PREVIEW_PICTURE"]); ?>"
                                                         alt="<?= $infoResNasadka["NAME"]; ?>">
                                                <?php } ?>
                                                <div class="nozzle-card__info">
                                                    <div class="nozzle-card__info-wrap">
                                                        <?= htmlspecialcharsBack($infoResNasadka["PROPERTIES"]["DESC_MIDL"]["VALUE"]["TEXT"]); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="nozzle-card swiper-slide">
                                            <h3 class="h3"><?= $infoResNasadka["NAME"]; ?></h3>
                                            <div class="nozzle-card__wrapper">
                                                <?php
                                                if (!empty($infoResNasadka["PREVIEW_PICTURE"])) { ?>
                                                    <img class="nozzle-card__img"
                                                         src="<?= CFile::GetPath($infoResNasadka["PREVIEW_PICTURE"]); ?>"
                                                         alt="<?= $infoResNasadka["NAME"]; ?>">
                                                <?php } ?>

                                                <div class="nozzle-card__info">
                                                    <!-- UP_DESC (верхнее описание) -->
                                                    <?php
                                                    if (!empty($infoResNasadka["PROPERTIES"]["UP_DESC"]["VALUE"])) { ?>
                                                        <div class="nozzle-card__info-wrap">
                                                            <?php
                                                            if (!empty($infoResNasadka["PROPERTIES"]["UP_DESC"]["DESCRIPTION"])) { ?>
                                                                <h4 class="h4"><?= $infoResNasadka["PROPERTIES"]["UP_DESC"]["DESCRIPTION"]; ?></h4>
                                                            <?php } ?>
                                                            <?= htmlspecialcharsBack($infoResNasadka["PROPERTIES"]["UP_DESC"]["VALUE"]["TEXT"]); ?>
                                                        </div>
                                                    <?php } ?>

                                                    <!-- DOWN_DESC (нижнее описание) -->
                                                    <?php
                                                    if (!empty($infoResNasadka["PROPERTIES"]["DOWN_DESC"]["VALUE"])) { ?>
                                                        <div class="nozzle-card__info-wrap">
                                                            <?php
                                                            if (!empty($infoResNasadka["PROPERTIES"]["DOWN_DESC"]["DESCRIPTION"])) { ?>
                                                                <h4 class="h4"><?= $infoResNasadka["PROPERTIES"]["DOWN_DESC"]["DESCRIPTION"]; ?></h4>
                                                            <?php } ?>
                                                            <?= htmlspecialcharsBack($infoResNasadka["PROPERTIES"]["DOWN_DESC"]["VALUE"]["TEXT"]); ?>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            </div>

                            <div class="swiper-navigation swiper-navigation--mobile">
                                <div class="swiper-button-prev swiper-button-prev--nozzle"></div>
                                <div class="swiper-button-next swiper-button-next--nozzle"></div>
                            </div>

                            <div class="swiper-navigation">
                                <div class="swiper-button-prev swiper-button-prev--nozzle"></div>
                                <div class="swiper-pagination"></div>
                                <div class="swiper-button-next swiper-button-next--nozzle"></div>
                            </div>

                        </div>
                    </div>
            </section>

            <?php
            break;
            case 'additional-accessory':

            $linkedProductIds = $arItemConstr["PROPERTIES"]["B_EIGHT_LINK_ELEMENT"]["VALUE"];
            $sliderExtraClass = "";

            if (!empty($linkedProductIds)) {
                if (!is_array($linkedProductIds)) {
                    $linkedProductIds = explode(',', $linkedProductIds);
                }

                if (count($linkedProductIds) >= 1) {
                    $sliderExtraClass = $arItemConstr["PROPERTIES"]["B_EIGHT_SLIDER_DOP"]["VALUE"];
                }

                $arLinkedSelect = [
                    "ID", "IBLOCK_ID", "NAME", "PREVIEW_PICTURE",
                    "PROPERTY_IMG_ADD_ACCESS",
                    "PROPERTY_DESC_ADD_ACCESS",
                    "PROPERTY_PRICE_ADD_ACCESS",
                    "PROPERTY_SHOW_BTN_BUY",
                    "PROPERTY_SHOW_BTN_ONE_CLICK"
                ];

                $arLinkedFilter = [
                    "IBLOCK_ID" => getIblockIdByCode("products"),
                    "ID" => $linkedProductIds,
                    "ACTIVE" => "Y"
                ];

                $linkedRes = CIBlockElement::GetList([], $arLinkedFilter, false, false, $arLinkedSelect);

                $arResult["ADD_ACCESSORY"] = [];
                while ($linkedOb = $linkedRes->GetNextElement()) {
                    $linkedFields = $linkedOb->GetFields();
                    $linkedFields["PROPERTIES"] = $linkedOb->GetProperties();
                    $arResult["ADD_ACCESSORY"][$linkedFields["ID"]] = $linkedFields;
                }

            }
            ?>

            <section class="section accessories">
                <div class="container">
                    <h2 class="section__title"><?= GetMessage("ADDITIONAL_ACCESSORY"); ?></h2>
                    <div class="swiper swiper--accessories">
                        <div class="swiper-wrapper">
                            <?php
                            foreach ($linkedProductIds as $id) {
                                $accessory = $arResult["ADD_ACCESSORY"][$id];
                                ?>
                                <div class="swiper-slide <?= $sliderExtraClass; ?>">
                                    <div class="accessories-card">
                                        <div class="accessories-card__wrap">
                                            <?php
                                            if (!empty($accessory["PROPERTIES"]["IMG_ADD_ACCESS"]["VALUE"])) { ?>
                                                <img class="accessories-card__img"
                                                     src="<?= CFile::GetPath($accessory["PROPERTIES"]["IMG_ADD_ACCESS"]["VALUE"]); ?>"
                                                     alt="<?= $accessory["NAME"]; ?>">
                                            <?php } ?>
                                            <h3 class="accessories-card__title">
                                                <?= $accessory["NAME"]; ?>
                                            </h3>
                                            <div class="accessories-card__text">
                                                <?= !empty($accessory["PROPERTIES"]["DESC_ADD_ACCESS"]["VALUE"]["TEXT"])
                                                    ? htmlspecialcharsBack($accessory["PROPERTIES"]["DESC_ADD_ACCESS"]["VALUE"]["TEXT"])
                                                    : ''; ?>
                                            </div>
                                            <div class="accessories-card__price">
                                                <?= $accessory["PROPERTIES"]["PRICE_ADD_ACCESS"]["VALUE"] ?? ''; ?> ₽
                                            </div>
                                            <?php
                                            if ($accessory["PROPERTIES"]["SHOW_BTN_BUY"]["VALUE_XML_ID"] === "yes") { ?>
                                                <a href="/cart/?add2cart=<?= $accessory['ID']; ?>"
                                                   class="button button--primary" type="button">
                                                    <?= GetMessage("BUY_BTN"); ?>
                                                </a>
                                            <?php } ?>

                                            <?php
                                            if ($accessory["PROPERTIES"]["SHOW_BTN_ONE_CLICK"]["VALUE_XML_ID"] === "yes") { ?>
                                                <a href="javascript:;"
                                                   onclick="btn_1click(<?= $accessory['ID']; ?>); do_open_popup_1click()"
                                                   class="button button--secondary" type="button">
                                                    <?= GetMessage("ONE_CLICK_BTN"); ?>
                                                </a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="swiper-navigation swiper-navigation--accessories">
                            <div class="swiper-button-prev swiper-button-prev--accessories"></div>
                            <div class="swiper-button-next swiper-button-next--accessories"></div>
                        </div>
                    </div>
                </div>
            </section>

            <?php
            break;
            case 'influencer-reviews':

            $this->setFrameMode(true);
            $page = $APPLICATION->GetCurPage();

            $linkedProductIds = $arItemConstr["PROPERTIES"]["B_TEN_REVIEWS"]["VALUE"];
            if (!empty($linkedProductIds)) {
                if (!is_array($linkedProductIds)) {
                    $linkedProductIds = explode(',', $linkedProductIds);
                }

                $arLinkedSelect = [
                    "ID", "IBLOCK_ID", "NAME", "*PROPERTIES", "PREVIEW_PICTURE"
                ];

                $arLinkedFilter = [
                    "IBLOCK_ID" =>  getIblockIdByCode("reviews_profi"),
                    "ID" => $linkedProductIds,
                    "ACTIVE" => "Y"
                ];

                $linkedRes = CIBlockElement::GetList([], $arLinkedFilter, false, false, $arLinkedSelect);

                $arResult["ADD_REVIEWS"] = [];
                while ($linkedOb = $linkedRes->GetNextElement()) {
                    $linkedFields = $linkedOb->GetFields();
                    $linkedFields["PROPERTIES"] = $linkedOb->GetProperties();
                    $arResult["ADD_REVIEWS"][] = $linkedFields;
                }
            }

            ?>

            <section class="famous-reviews<?= c_get_block_additional_classes('famous-reviews') ?> newClassSvg">
                <h2 class="famous-reviews__title title">
                    <?php
                    if ($page == '/products/impulse-pneumo/') { ?>
                        <span class="title__inner">Нам доверяют известные спортсмены, врачи и бизнесмены</span>
                    <?php } else { ?>

                        <?php
                        if (!empty($arItemConstr["PROPERTIES"]["B_TEN_DESC_BLOCK"]["VALUE"]["TEXT"])) {
                            ?>
                            <span class="title__inner">
                                  <?= htmlspecialcharsBack($arItemConstr["PROPERTIES"]["B_TEN_DESC_BLOCK"]["VALUE"]["TEXT"]); ?>
                                </span>

                        <?php } else { ?>
                            <span class="title__inner">
                                   <?= c_get_text_by_page_code([
                                       'sport' => 'Нам доверяют известные&nbsp;спортсмены, врачи&nbsp;и&nbsp;бизнесмены',
                                       'derma' => 'Impulse Derma Pro пользуются косметологи, врачи и блогеры',
                                       'clean' => 'Impulse Clean пользуются косметологи и блогеры',
                                       'lift' => 'Impulse Lift пользуются косметологи и блогеры',
                                       'sport-mini' => 'Нам доверяют известные спортсмены, врачи и бизнесмены',
                                       'flow' => 'Нам доверяют известные врачи, блоггеры и бизнесмены',
                                   ]); ?>
                                </span>
                        <?php } ?>

                    <?php } ?>
                </h2>
                <div class="container">

                    <div class="famous-reviews__slider">
                        <div class="swiper">
                            <div class="swiper-wrapper">
                                <?php
                                foreach ($arResult["ADD_REVIEWS"] as $arReviews) {
                                    ?>
                                    <div class="famous-reviews__slide swiper-slide">
                                        <div class="review-item">
                                            <div class="review-item__top">
                                                <picture class="review-item__picture">
                                                    <?php
                                                    if ($page == '/products/impulse-flow/') { ?>
                                                        <div class="review-item__image"
                                                             style="background: #D4DEFF;">
                                                        </div>
                                                    <?php } ?>

                                                    <?php if ($page == '/products/impulse-pneumo/') { ?>
                                                        <div class="review-item__image <?php if ($page == '/products/impulse-flow/') { ?>noneBtn<?php } ?>"
                                                             style="background: #FF5656;">
                                                        </div>
                                                    <?php } else { ?>
                                                        <img class="review-item__image <?php if ($page == '/products/impulse-flow/') { ?>noneBtn<?php } ?>"
                                                             src="<?= CFile::GetPath($arReviews["PREVIEW_PICTURE"]); ?>"
                                                             loading="lazy"
                                                             alt="<?= htmlspecialcharsEx($arReviews['NAME']); ?>">
                                                    <?php } ?>

                                                </picture>
                                                <div class="review-item__title">
                                                    <p class="review-item__name">
                                                        <?= $arReviews["NAME"]; ?>
                                                    </p>
                                                    <p class="review-item__description">
                                                        <?= htmlspecialcharsBack($arReviews["PROPERTIES"]["BIO_PREVIEW"]["VALUE"]["TEXT"]); ?>
                                                    </p>
                                                    <p class="review-item__full-description">
                                                        <?= $arReviews["PROPERTIES"]["BIO_DETAIL"]["VALUE"]["TEXT"]; ?>

                                                        <?php
                                                        if (!empty($arReviews["PROPERTIES"]["SM_LINK"]["VALUE"])) { ?>
                                                            <a class="review-item__person-link"
                                                               href="<?= $arReviews["PROPERTIES"]["SM_LINK"]["VALUE"]; ?>"
                                                               target="_blank"
                                                            ><?= $arReviews["PROPERTIES"]["SM_LINK"]["VALUE"]; ?></a>
                                                        <?php } ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="review-item__body borderNew">
                                                <div class="review-item__preview-text">
                                                    <?= htmlspecialcharsBack($arReviews["PROPERTIES"]["REVIEW_PREVIEW"]["VALUE"]["TEXT"]); ?>
                                                </div>
                                                <div class="review-item__full-text">
                                                    <?= $arReviews["DETAIL_TEXT"]; ?>
                                                </div>
                                                <div class="review-item__more">
                                                    <button class="review-item__more-button link-icon js-review-more-link newClassBtn">
                                                        Подробнее
                                                        <?php
                                                        if ($page == '/products/impulse-flow/') { ?>
                                                            <svg class="link-icon__icon" width="8" height="14"
                                                                 viewBox="0 0 8 14"
                                                                 fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M1 1L7 7.02857L1.05143 13" stroke="#7795FF"
                                                                      stroke-miterlimit="10"/>
                                                            </svg>
                                                        <?php } else { ?>
                                                            <svg class="link-icon__icon" width="8" height="14"
                                                                 viewBox="0 0 8 14"
                                                                 fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M1 1L7 7.02857L1.05143 13" stroke="#FF5656"
                                                                      stroke-miterlimit="10"/>
                                                            </svg>
                                                        <?php } ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                        <?php
                        if ($page == '/products/impulse-flow/') { ?>
                            <div class="swiper-controls">
                                <div class="swiper__button swiper__button--prev">
                                    <svg class="swiper__button-icon" width="13" height="22" viewBox="0 0 13 22"
                                         fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 21L2 10.9524L11.9048 1" stroke="#7795FF" stroke-width="1.5"
                                              stroke-miterlimit="10"/>
                                    </svg>
                                </div>
                                <div class="swiper__button swiper__button--next">
                                    <svg class="swiper__button-icon" width="13" height="22" viewBox="0 0 13 22"
                                         fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1 0.999999L11 11.0476L1.09524 21" stroke="#7795FF"
                                              stroke-width="1.5"
                                              stroke-miterlimit="10"/>
                                    </svg>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="swiper-controls">
                                <div class="swiper__button swiper__button--prev">
                                    <svg class="swiper__button-icon" width="13" height="22" viewBox="0 0 13 22"
                                         fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 21L2 10.9524L11.9048 1" stroke="#FF5656" stroke-width="1.5"
                                              stroke-miterlimit="10"/>
                                    </svg>
                                </div>
                                <div class="swiper__button swiper__button--next">
                                    <svg class="swiper__button-icon" width="13" height="22" viewBox="0 0 13 22"
                                         fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1 0.999999L11 11.0476L1.09524 21" stroke="#FF5656"
                                              stroke-width="1.5"
                                              stroke-miterlimit="10"/>
                                    </svg>
                                </div>
                            </div>

                        <?php } ?>

                    </div>

                    <?php
                    if ($arItemConstr["PROPERTIES"]["B_TEN_SHOW_BTN"]["VALUE_XML_ID"] === "yes") {
                        ?>
                        <div class="famous-reviews__bottom">
                            <button class="famous-reviews__button common-button js-scroll-to-price btnColor"
                                    type="button">
                                <?php
                                if (!empty($arItemConstr["PROPERTIES"]["B_TEN_TEXT_BTN"]["VALUE"])) {
                                    ?>
                                    <?= $arItemConstr["PROPERTIES"]["B_TEN_TEXT_BTN"]["VALUE"]; ?>

                                <?php } else { ?>

                                    <?= c_get_text_by_page_code([
                                        'sport' => 'Заказать массажер',
                                        'pneumo' => 'Заказать массажер',
                                        'derma' => 'Заказать маску',
                                        'clean' => 'Заказать щетку',
                                        'lift' => 'Заказать массажер',
                                        'sport-mini' => 'Заказать массажер',
                                        'flow' => 'Заказать ирригатор',
                                    ]); ?>

                                <?php } ?>
                            </button>
                        </div>
                    <?php } ?>
                </div>
            </section>


            <?php
            if ($page == '/products/impulse-flow/') { ?>
                <style>
                    body .btnColor {
                        border: 1px solid #6887F5;
                        background-color: #6887F5;
                    }

                    body .newClassBtn {
                        color: #7795FF;
                    }

                    body .newClassSvg .swiper__button-icon path {
                        stroke: #7795FF;
                    }

                    body .borderNew::before {
                        background-color: #C9D9FF;
                    }
                </style>
            <?php } else { ?>

                <?php
                if ($page == '/products/impulse-pneumo/') { ?>
                    <style>

                        .noneBtn {
                            display: none;
                        }

                        body .btnColor {
                            border: 1px solid #FF5656;
                            background-color: #FF5656;
                        }

                        body .newClassBtn {
                            color: #FF5656;
                        }

                        body .newClassSvg .swiper__button-icon path {
                            stroke: #FF5656;
                        }

                        body .borderNew::before {
                            background-color: #FF5656;
                        }
                    </style>
                <?php } ?>
            <?php } ?>


            <?php
            break;
    }
    ?>

<?php } ?>

<?php
$APPLICATION->IncludeComponent(
    "bitrix:main.include",
    "",
    array(
        "AREA_FILE_SHOW" => "file",
        "PATH" => SITE_DIR . "include/products/common/12-product-contact-subscribe.php"
    ),
    false,
); ?>
<?php
$APPLICATION->IncludeComponent(
    "bitrix:main.include",
    "",
    array(
        "AREA_FILE_SHOW" => "file",
        "PATH" => SITE_DIR . "include/products/common/13–product-where-to-buy.php"
    ),
    false,
    array('HIDE_ICONS' => 'Y')
); ?>


<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/** @var array $arResult */
?>
<div class="related-products">
    <h3>Похожие товары (<?= $arResult['COUNT'] ?>)</h3>

    <?php if (empty($arResult['ITEMS'])): ?>
        <p>Нет связанных товаров.</p>
    <?php else: ?>
        <ul class="related-products__list">
            <?php foreach ($arResult['ITEMS'] as $item): ?>
                <li class="related-products__item">
                    <span class="related-products__name"><?= $item['NAME'] ?></span>
                    <span class="related-products__price"><?= $item['FINAL_PRICE'] ?> руб</span>
                    <div class="related-products__desc"><?= $item['PREVIEW_TEXT'] ?></div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
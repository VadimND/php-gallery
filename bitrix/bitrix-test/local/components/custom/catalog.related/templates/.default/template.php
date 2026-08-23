<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/** @var array $arResult */
?>
/**
 Выявленные проблемы безопасности:
 - нет экранирования вывода (риск XSS-атаки)
 - нет обрботки пустых описаний
 - не хватает приведение цены к типу float и количества товаров к типу int
**/

<div class="related-products">
    <h3>Похожие товары (<?= (int)$arResult['COUNT'] ?>)</h3>

    <?php if (empty($arResult['ITEMS'])): ?>
        <p>Нет связанных товаров.</p>
    <?php else: ?>
        <ul class="related-products__list">
            <?php foreach ($arResult['ITEMS'] as $item):
                $name = htmlspecialcharsbx($item['NAME'] ?? '');
                $desc = htmlspecialcharsbx($item['PREVIEW_TEXT'] ?? '');
                $price = (float)($item['FINAL_PRICE'] ?? 0);
            ?>
                <li class="related-products__item">
                    <span class="related-products__name"><?= $name ?></span>
                    <span class="related-products__price"><?= number_format($price, 2, '.', ' ') ?> руб</span>
                    <?php if (!empty($desc)): ?>
                        <div class="related-products__desc">
                            <?= $desc ?>
                        </div>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
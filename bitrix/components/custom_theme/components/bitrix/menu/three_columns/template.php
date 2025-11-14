<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!empty($arResult)) { ?>
<section class="background">
    <div class="container">
        <h1 class="section-name-center"><?= $arParams["SECTION_TITLE"] ?></h1>
        <div class="flex-bel-list">
            <?php
            $chunkSize = ceil(count($arResult) / 3);
            $columns = array_chunk($arResult, $chunkSize);
            
            foreach($columns as $columnIndex => $column): ?>
                <div class="column">
                    <ul class="seclevel">
                        <?php foreach($column as $item) : ?>
                            <li class="bel-item" id="<?= $this->GetEditAreaId($item['ID']); ?>">
                                <a href="/belarus<?=$item["LINK"]?>">
                                    <?=$item["TEXT"]?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>     
    </div> 
</section>
<? } ?>
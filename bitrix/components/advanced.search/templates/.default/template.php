<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<section class="color-pesok">
    <div class="container">
        <div class="advanced-search-block">
            <h1 class="big-article-name center-name">Расширенный поиск</h1>
            <form method="POST" action="">
                <?= bitrix_sessid_post()?>
                <div class="input-ok">
                    <input class="advanced-search-input" type="search" name="searchword" placeholder="Поиск" required
                        maxlength="50" value="<?= htmlspecialchars($arResult['SEARCH_WORD'])?>">

                    <div class="btn-ok">
                        <button class="btn-go" type="submit">Ок</button>
                    </div>

                    <div class="case-option">
                        <label>
                            <input type="checkbox" name="caseoption" value="Y" <?= $arResult['CASE_OPTION'] ? 'checked' : ''?>>
                            <span>с учётом регистра</span>
                        </label>
                    </div>

                    <div class="case-option">
                        <label>
                            <input type="checkbox" name="filterempty" value="Y" <?= $arResult['FILTER_EMPTY'] ? 'checked' : ''?>>
                            <span>исключать "Статья в работе"</span>
                        </label>
                    </div>
                </div>
                <div class="advanced-search-row">
                    <div class="advanced-search-col">
                        <div class="select">
                            <select name="razdel" id="">
                                <option value="0">Все темы</option>
                                <option value="1" <?= $arResult['RAZDEL'] == '1' ? 'selected' : ''?>>Республика Беларусь</option>
                                <option value="29" <?= $arResult['RAZDEL'] == '29' ? 'selected' : ''?>>Природа</option>
                                <option value="221" <?= $arResult['RAZDEL'] == '221' ? 'selected' : ''?>>&nbsp;&nbsp;&nbsp;&nbsp;Недра. Полезные ископаемые</option>
                                <option value="214" <?= $arResult['RAZDEL'] == '214' ? 'selected' : ''?>>&nbsp;&nbsp;&nbsp;&nbsp;Рельеф</option>
                                <option value="211" <?= $arResult['RAZDEL'] == '211' ? 'selected' : ''?>>&nbsp;&nbsp;&nbsp;&nbsp;Климат и природные явления</option>
                                <option value="207" <?= $arResult['RAZDEL'] == '207' ? 'selected' : ''?>>&nbsp;&nbsp;&nbsp;&nbsp;Гидрографическая сеть. Водные объекты</option>
                                <option value="205" <?= $arResult['RAZDEL'] == '205' ? 'selected' : ''?>>&nbsp;&nbsp;&nbsp;&nbsp;Флора. Растительность</option>
                                <option value="215" <?= $arResult['RAZDEL'] == '215' ? 'selected' : ''?>>&nbsp;&nbsp;&nbsp;&nbsp;Природопользование и охрана природы</option>
                                <option value="28" <?= $arResult['RAZDEL'] == '28' ? 'selected' : ''?>>История</option>
                                <option value="52" <?= $arResult['RAZDEL'] == '52' ? 'selected' : ''?>>&nbsp;&nbsp;&nbsp;&nbsp;Беларусь во Второй мировой и Великой Отечественной войнах</option>
                                <option value="30" <?= $arResult['RAZDEL'] == '30' ? 'selected' : ''?>>Общество</option>
                                <option value="34" <?= $arResult['RAZDEL'] == '34' ? 'selected' : ''?>>Государство</option>
                                <option value="35" <?= $arResult['RAZDEL'] == '35' ? 'selected' : ''?>>Экономика</option>
                                <option value="36" <?= $arResult['RAZDEL'] == '36' ? 'selected' : ''?>>Культура</option>
                                <option value="37" <?= $arResult['RAZDEL'] == '37' ? 'selected' : ''?>>Образование</option>
                                <option value="38" <?= $arResult['RAZDEL'] == '38' ? 'selected' : ''?>>Охрана здоровья. Физкультура и спорт</option>
                                <option value="39" <?= $arResult['RAZDEL'] == '39' ? 'selected' : ''?>>Наука</option>
                                <option value="40" <?= $arResult['RAZDEL'] == '40' ? 'selected' : ''?>>Административно-территориальное устройство</option>
                            </select>
                        </div>
                        <div class="link-tolkovaniya-search">
                            <a class="abbreviature-search-link" href="/slovnik/baza-tolkovaniy/">БАЗА ТОЛКОВАНИЙ</a>
                        </div>
                    </div>
                    <div class="advanced-search-col">
                        <h3 class="search-section-name">область поиска</h3>
                        <div class="adv-options">
                            <input type="radio" id="search-by-thems" name="drone" value="themesFilter"
                                <?= ($arResult['DRONE'] == 'themesFilter' || empty($arResult['DRONE'])) ? 'checked' : ''?>>
                            <label for="search-by-thems">поиск везде</label>
                        </div>
                        <div class="adv-options">
                            <input type="radio" id="search-by-persons" name="drone" value="personsFilter"
                                <?= $arResult['DRONE'] == 'personsFilter' ? 'checked' : ''?>>
                            <label for="search-by-persons">поиск по персоналиям</label>
                        </div>
                    </div>
                    <div class="advanced-search-col">
                        <h3 class="search-section-name">участок поиска</h3>
                        <div class="adv-options">
                            <input type="radio" id="articles-nazva" name="by-fields" value="nazva"
                                <?= ($arResult['BY_FIELDS'] == 'nazva' || empty($arResult['BY_FIELDS'])) ? 'checked' : ''?>>
                            <label for="articles-nazva">искать в названиях статей</label>
                        </div>
                        <div class="adv-options">
                            <input type="radio" id="articles-text" name="by-fields" value="details"
                                <?= $arResult['BY_FIELDS'] == 'details' ? 'checked' : ''?>>
                            <label for="articles-text">искать в текстах статей</label>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<? if ($arResult['SEARCH_WORD'] || $_GET['PAGEN_1']): ?>
<section class="background-beg">
    <div class="container">
        <div class="resultss">
            <h3 class="section-name-center">Результаты поиска</h3>
            <div class="results" style="min-height: unset; margin-bottom: 40px; padding: 0 20px;">
                <p>Вы искали: "<b><?= htmlspecialchars($arResult['SEARCH_WORD'])?></b>"</p>
                <p>Тематика: <b>
                        <?php
                            if ($arResult['RAZDEL'] != 0 && $arResult['RAZDEL'] != 1) {
                                $result = CIBlockSection::GetByID($arResult['RAZDEL']);
                                if ($ar_res = $result->GetNext()) {
                                    echo htmlspecialchars($ar_res['NAME']);
                                }
                            } else if ($arResult['RAZDEL'] == 1) {
                                echo 'Республика Беларусь';
                            } else {
                                echo 'Любая';
                            }
                        ?>
                    </b></p>
                <p>По вашему запросу
                    <?= call_user_func($arResult['END_OF_WORD_FUNC'], 'найдено', $arResult['TOTAL_ITEMS'])?>
                    <b><?= $arResult['TOTAL_ITEMS']?></b>
                    <?= call_user_func($arResult['END_OF_WORD_FUNC'], 'запись', $arResult['TOTAL_ITEMS'])?>
                </p>
            </div>
            <div class="results">
                <ul class="dictionary-words">
                    <? if (!empty($arResult['VISIBLE_ITEMS'])): ?>
                        <? foreach ($arResult['VISIBLE_ITEMS'] as $item): ?>
                            <li>
                                <a href="<?= htmlspecialchars($item['URL'])?>" target="_blank">
                                    <?= htmlspecialchars($item['NAME'])?>
                                </a>
                            </li>
                        <? endforeach; ?>
                    <? else: ?>
                        <li>Результатов не найдено.</li>
                    <? endif; ?>
                </ul>
            </div>
        </div>

        <? if (!empty($arResult['NAV_STRING'])): ?>
            <div style="text-align: center; padding-bottom: 40px;">
                <?= $arResult['NAV_STRING']?>
            </div>
        <? endif; ?>
    </div>
</section>
<? endif; ?>
<?php
$uri = $_SERVER['REQUEST_URI'];
$menu_levels = explode('/', $uri);
$menu_levels = array_diff($menu_levels, array(''));
$mainMenu = array(
    '/' => array(
        'NAME' => 'Главная',
        'LINK' => '/',
        'CHILDS' => array(
            0 => array(
                'NAME' => 'Новини',
                'LINK' => '/main/news/',
            ),
            1 => array(
                'NAME' => 'Eskaro Україна',
                'LINK' => '/main/eskaro_ukraine/',
            ),
            2 => array(
                'NAME' => 'Eskaro в світі',
                'LINK' => '/main/eskaro_v_mire/',
            ),
            3 => array(
                'NAME' => 'Стійкий розвиток',
                'LINK' => '/main/ustojchivoe_razvitie/',
            ),
            4 => array(
                'NAME' => 'Відео',
                'LINK' => '/main/eskaro_ukraine_video_ukr/',
            ),
            5 => array(
                'NAME' => 'Вакансії',
                'LINK' => '/main/job_opportunities_ukr1/',
            ),
        ),
    ),
    '/products/' => array(
        'NAME' => 'ПРОДУКЦІЯ',
        'LINK' => '/products/',
        'CHILDS' => array(
            0 => array(
                'NAME' => 'Каталог',
                'LINK' => '/catalog1/',
            ),
            1 => array(
                'NAME' => 'Бренди',
                'LINK' => '/brands/',
            ),
            2 => array(
                'NAME' => 'Алфавітний показник',
                'LINK' => '/list/',
            ),
            3 => array(
                'NAME' => 'Калькулятор витрати',
                'LINK' => '/calc/',
            ),
            4 => array(
                'NAME' => 'Сертифікати',
                'LINK' => '/cert/',
            ),
            5 => array(
                'NAME' => 'Пошук за назвою',
                'LINK' => '/products/search/',
            ),
        ),
    ),
    '/gde_kupit/' => array(
        'NAME' => 'ДЕ КУПИТИ',
        'LINK' => '/gde_kupit/',
        'CHILDS' => array(
            0 => array(
                'NAME' => 'Місця продажу',
                'LINK' => '/sale_ponits_map_ukr/',
            ),
        ),
    ),
    '/kolerovka/' => array(
        'NAME' => 'КОЛЕРУВАННЯ',
        'LINK' => '/kolerovka/',
        'CHILDS' => array(
            0 => array(
                'NAME' => 'Eskarocolor',
                'LINK' => '/kolerovka/ob_eskarocolor/',
            ),
            1 => array(
                'NAME' => 'Кольори та емоції',
                'LINK' => '/kolerovka/cveta_i_emocii/',
            ),
            2 => array(
                'NAME' => 'Life style – кольорові рішення',
                'LINK' => '/kolerovka/life_style_cvetovye_resheniya1/',
            ),
            3 => array(
                'NAME' => 'Технології та можливості',
                'LINK' => '/kolerovka/tehnologiya_i_vozmozhnosti/',
            ),
        ),
    ),
    '/shkola_krasok/' => array(
        'NAME' => 'ШКОЛА ФАРБ',
        'LINK' => '/shkola_krasok/',
        'CHILDS' => array(
            0 => array(
                'NAME' => 'Публікації',
                'LINK' => '/publikacii/',
            ),
            1 => array(
                'NAME' => 'Запитання-Відповіді',
                'LINK' => '/voprosy-otvety/',
            ),
            2 => array(
                'NAME' => 'Поради',
                'LINK' => '/sovety/',
            ),
            3 => array(
                'NAME' => 'Семінари',
                'LINK' => '/seminary/',
            ),
        ),
    ),
    '/testfaq/' => array(
        'NAME' => 'ВАШІ ПИТАННЯ',
        'LINK' => '/testfaq/',
        'CHILDS' => array(
            0 => array(
                'NAME' => 'Запитання-Відповіді',
                'LINK' => '/voprosy-otvety/',
            ),
        ),
    ),
    '/kontakty_ukr/' => array(
        'NAME' => 'КОНТАКТИ',
        'LINK' => '/kontakty_ukr/',
    ),
);
?>
<nav>
    <div class="main_menu">
        <ul>
        <?
        foreach ($mainMenu as $key => $value) {
        ?>
            <li class="<?= ($key == '/' . $menu_levels[1] . '/') ? 'active' : '' ?>"><a href="<?= $key ?>"><?= $value['NAME'] ?></a></li>
            <?
        }
        ?>
        </ul>
    </div>
    
<?

$menu_key = ($menu_levels) ? '/' . $menu_levels[1] . '/' : '/';
if ($mainMenu[$menu_key]['CHILDS']) {?>
    <div class="sub_menu">
        <ul>
    <? foreach ($mainMenu[$menu_key]['CHILDS'] as $key => $value) {?>
        <li class="<?= ($value['LINK'] == '/' . $menu_levels[2] . '/') ? 'active' : '' ?>"><a href="<?= $value['LINK'] ?>"><?= $value['NAME'] ?></a></li>
        <? } ?>
        </ul>
    </div> 
<? } ?>
</nav>
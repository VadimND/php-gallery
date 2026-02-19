<?php
/**
 * Function allows you to get breadcrumbs for a page by its ID.
 * The function uses two arrays: one with links and another with the corresponding breadcrumb chains.
 * It checks if the page link is in the first array and then builds the breadcrumb navigation based on the second array.
 * If the page belongs to a specific category, it adjusts the breadcrumb accordingly.
 */
function getManualBreadcrumbs(int $page_id) : string {

    $link = get_permalink($page_id);
    $page_title = get_the_title($page_id);
    $post_categories = wp_get_post_categories($page_id);
    $chain = '';

    $arr = [

        ['https://site.com/mrt/',
        'https://site.com/uzi/',
        'https://site.com/nevrolog/',
        'https://site.com/ortopediya/'],

        ['https://site.com/mrt-golovnogo-mozga-gipofiza-orbit-ppn/',
        'https://site.com/mrt-pozvonochnika/',
        'https://site.com/mrt-shei/',
        'https://site.com/mrt-sustavov/'],

        ['https://site.com/uzi-serdcza-ehokardiografiya/',
        'https://site.com/strejn-ehokardiografiya-spekl-treking-ehokardiografiya/',
        'https://site.com/uzi-organov-bryushnoj-polosti-i-pochek/'],

        ['https://site.com/neurologia/',
        'https://site.com/blokada-lechebnaya/'],

        ['https://site.com/travmatolog-ortoped/',
        'https://site.com/korrekcziya-deformaczii-stop/',
        'https://site.com/artroskopiya/',
        'https://site.com/issledovanie-sinovialnoj-zhidkosti/'],

        ['https://site.com/artroskopiya-kolennogo-sustava-pri-povrezhdenii-meniska/',
        'https://site.com/artroskopiya-s-hondroplastikoj-debridzhment-kolennogo-sustava/',
        'https://site.com/artroskopiya-plastika-krestoobraznyh-svyazok-kolennogo-sustava/',
        'https://site.com/artroskopiya-golenostopnogo-sustava/'],

        ['https://site.com/konsultacziya-revmatologa/'],

        ['https://site.com/konsultacziya-flebologa/',
        'https://site.com/skleroterapiya-ven/',
        'https://site.com/clacs-klaks/',
        'https://site.com/endovenoznaya-lazernaya-koagulyacziya-evlk/'],

        ['https://site.com/konsultacziya-hirurga/',
        'https://site.com/udalenie-novoobrazovanij-kozhi-i-myagkih-tkanej/'],

        ['https://site.com/konsultacziya-proktologa/'],

        ['https://site.com/konsultacziya-onkologa/'],

        ['https://site.com/udarno-volnovaya-terapiya-2/',
        'https://site.com/karboksiterapiya/']
    ];

    $arr_map = [
        [
            "Услуги" => 'services',
        ],
        [
            "Услуги" => 'services',
            "МРТ" => 'mrt',
        ],
        [
            "Услуги" => 'services',
            "УЗИ" => 'uzi',
        ],
        [
            "Услуги" => 'services',
            "Неврология" => 'nevrolog',
        ],
        [
            "Услуги" => 'services',
            "Ортопедия и травматология" => 'ortopediya',
        ],
        [
            "Услуги" => 'services',
            "Ортопедия и травматология" => 'ortopediya',
            "Артроскопия" => 'artroskopiya',
        ],
        [
            "Услуги" => 'services',
            "Ревматология" => 'revmatolog',
        ],
        [
            "Услуги" => 'services',
            "Флебология" => 'flebologia',
        ],
        [
            "Услуги" => 'services',
            "Хирургия" => 'hirurg',
        ],
        [
            "Услуги" => 'services',
            "Проктология" => 'proktologiya',
        ],
        [
            "Услуги" => 'services',
            "Онкология" => 'onkolog',
        ],
        [
            "Услуги" => 'services',
            "Физиотерапия" => 'fizioterapiya',
        ],
    ];

    foreach($arr as $key => $a) {
        if(in_array($link, $a)) {
            $chain = $key;
            break;
        }
    }

    $str_nav = '<nav aria-label="breadcrumbs"><a href="/">Главная</a><span class="separator"> » </span>';

    if (empty($chain) === false || $chain === 0) {
        foreach ($arr_map[$chain] as $key=>$slug) {
            $str_nav .= "<a href='/{$slug}/'>{$key}</a>";
            $str_nav .= "<span class='separator'> » </span>";
        }
    }

    if (in_array(22, $post_categories)) {
        $str_nav .= '<a href="/doctors-filter/">Врачи</a><span class="separator"> » </span>';
    }

    $str_nav .= "<span class='last'>$page_title</span></nav>";

    // At the end of the function, we return the generated breadcrumb navigation string
    return $str_nav;
}

// Example usage: echo getManualBreadcrumbs(get_the_ID());
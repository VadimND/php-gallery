<?php

/**
 * Plugin Name: Swiper Gallery Block
 * Plugin URI:
 * Description: Блок галереи на основе Swiper.js с поддержкой LightGallery
 * Version: 1.0.0
 * Author: javadimus
 * License: GPL v2 or later
 * Text Domain: swiper-gallery
 */

if (!defined('ABSPATH')) {
    exit;
}

// Инициализация плагина
add_action('init', 'swiper_gallery_init');

function swiper_gallery_init()
{
    // Регистрируем блок
    register_block_type(__DIR__ . '/build', array(
        'render_callback' => 'swiper_gallery_render_callback',
    ));

    // Подключаем скрипты и стили
    add_action('wp_enqueue_scripts', 'swiper_gallery_enqueue_scripts');
}

// Подключение скриптов на фронтенде
function swiper_gallery_enqueue_scripts() {
    // Swiper.js
    wp_enqueue_style(
        'swiper-css',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
        array(),
        '11.0.0'
    );

    wp_enqueue_script(
        'swiper-js',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
        array('jquery'),
        '11.0.0',
        true
    );

    // Используем cdnjs для LightGallery 1.10.0 (там полный набор)
    wp_enqueue_style(
        'lightgallery-css',
        'https://cdnjs.cloudflare.com/ajax/libs/lightgallery/1.10.0/css/lightgallery.min.css',
        array(),
        '1.10.0'
    );

    // LightGallery core + все плагины в одном файле
    wp_enqueue_script(
        'lightgallery-all-js',
        'https://cdnjs.cloudflare.com/ajax/libs/lightgallery/1.10.0/js/lightgallery-all.min.js',
        array('jquery'),
        '1.10.0',
        true
    );

    // Скрипт инициализации
    wp_enqueue_script(
        'swiper-gallery-init',
        plugin_dir_url(__FILE__) . 'assets/js/frontend.js',
        array('jquery', 'swiper-js', 'lightgallery-all-js'),
        '1.0.0',
        true
    );

    // Стили плагина
    wp_enqueue_style(
        'swiper-gallery-style',
        plugin_dir_url(__FILE__) . 'assets/css/style.css',
        array('lightgallery-css'),
        '1.0.0'
    );
}

// Callback для рендеринга блока
function swiper_gallery_render_callback($attributes)
{
    if (empty($attributes['images'])) {
        return '';
    }

    $images = $attributes['images'];
    $slides_per_view = isset($attributes['slidesPerView']) ? $attributes['slidesPerView'] : 3;
    $space_between = isset($attributes['spaceBetween']) ? $attributes['spaceBetween'] : 15;
    $show_navigation = isset($attributes['showNavigation']) ? $attributes['showNavigation'] : true;
    $show_pagination = isset($attributes['showPagination']) ? $attributes['showPagination'] : true;
    $autoplay = isset($attributes['autoplay']) ? $attributes['autoplay'] : false;
    $autoplay_delay = isset($attributes['autoplayDelay']) ? $attributes['autoplayDelay'] : 3000;
    $loop = isset($attributes['loop']) ? $attributes['loop'] : true;
    $slider_id = 'swiper-gallery-' . uniqid();

    ob_start();

    ?>

    <div id="<?php echo esc_attr($slider_id); ?>" class="swiper-gallery-block"
         data-slides-per-view="<?php echo esc_attr($slides_per_view); ?>"
         data-space-between="<?php echo esc_attr($space_between); ?>"
         data-show-navigation="<?php echo esc_attr($show_navigation ? 'true' : 'false'); ?>"
         data-show-pagination="<?php echo esc_attr($show_pagination ? 'true' : 'false'); ?>"
         data-autoplay="<?php echo esc_attr($autoplay ? 'true' : 'false'); ?>"
         data-autoplay-delay="<?php echo esc_attr($autoplay_delay); ?>"
         data-loop="<?php echo esc_attr($loop ? 'true' : 'false'); ?>">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php
                foreach ($images as $image):
                    $thumbnail = isset($image['thumbnail']) ? $image['thumbnail'] : (isset($image['url']) ? $image['url'] : '');
                    $full_size = isset($image['full_size']) ? $image['full_size'] : (isset($image['url']) ? $image['url'] : '');
                    $alt = (!empty($image['alt'])) ? $image['alt'] : sprintf(
                        'Галерея дизайнерских решений - проект №%d',
                        get_the_ID()
                    );
                    ?>

                    <div class="swiper-slide"
                         data-thumb="<?php echo esc_url($thumbnail); ?>"
                         data-src="<?php echo esc_url($full_size); ?>">
                        <img src="<?php echo esc_url($thumbnail); ?>"
                             alt="<?php echo esc_attr($alt); ?>"
                             loading="lazy" />
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if ($show_navigation): ?>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            <?php endif; ?>
            <?php if ($show_pagination): ?>
                <div class="swiper-pagination"></div>
            <?php endif; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

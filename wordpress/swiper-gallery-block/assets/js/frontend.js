jQuery(document).ready(function($) {
    function initGallery($gallery, galleryIndex) {
        const galleryId = 'gallery-' + galleryIndex;
        const container = $gallery.find('.swiper-container')[0];

        if (!container) return;

        // Получаем настройки Swiper
        const slidesPerView = parseInt($gallery.data('slides-per-view')) || 3;
        const spaceBetween = parseInt($gallery.data('space-between')) || 15;
        const showNavigation = $gallery.data('show-navigation') === 'true';
        const showPagination = $gallery.data('show-pagination') === 'true';
        const autoplay = $gallery.data('autoplay') === 'true';
        const autoplayDelay = parseInt($gallery.data('autoplay-delay')) || 3000;
        const loop = $gallery.data('loop') !== 'false';

        // Получаем настройки LightGallery
        const lgThumbnail = $gallery.data('lg-thumbnail') !== 'false';
        const lgShare = $gallery.data('lg-share') !== 'false';

        // Настройки Swiper
        const swiperConfig = {
            slidesPerView: slidesPerView,
            spaceBetween: spaceBetween,
            loop: loop,
            breakpoints: {
                320: {
                    slidesPerView: 1,
                    spaceBetween: 10
                },
                480: {
                    slidesPerView: 2,
                    spaceBetween: 10
                },
                768: {
                    slidesPerView: slidesPerView,
                    spaceBetween: spaceBetween
                }
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
                type: 'bullets',
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        };

        if (showPagination) {
            swiperConfig.pagination = {
                el: '.swiper-pagination',
                clickable: true,
                type: 'bullets',
            };
        }

        if (showNavigation) {
            swiperConfig.navigation = {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            };
        }

        if (autoplay) {
            swiperConfig.autoplay = {
                delay: autoplayDelay,
                disableOnInteraction: false,
            };
        }

        // Инициализация Swiper
        new Swiper(container, swiperConfig);

        // Собираем данные для LightGallery
        const galleryItems = [];
        $gallery.find('.swiper-slide').each(function(index) {
            const $slide = $(this);
            galleryItems.push({
                index: index,
                src: $slide.data('src') || $slide.find('img').attr('src'),
                thumb: $slide.data('thumb') || $slide.find('img').attr('src'),
                alt: $slide.find('img').attr('alt') || ''
            });

            // Добавляем data-атрибут к слайду
            $slide.attr('data-slide-index', index);
        });

        $gallery.on('click', '.swiper-slide', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const $clickedSlide = $(this);

            let targetIndex = parseInt($clickedSlide.attr('data-slide-index'));

            // Если data-атрибут не сработал, ищем по src
            if (isNaN(targetIndex)) {
                const clickedImageSrc = $clickedSlide.data('src') || $clickedSlide.find('img').attr('src');
                targetIndex = galleryItems.findIndex(item => item.src === clickedImageSrc);

                if (targetIndex === -1) {
                    targetIndex = 0;
                }
            }

            // Гарантируем что индекс в пределах массива
            targetIndex = Math.max(0, Math.min(targetIndex, galleryItems.length - 1));

            console.log('Opening gallery at index:', targetIndex);

            // Создаем контейнер ДО создания фона
            const containerId = 'lightgallery-' + galleryId + '-' + Date.now();
            const $container = $('<div>', {
                id: containerId,
                style: 'display: none;'
            });

            // Добавляем изображения
            galleryItems.forEach((item, index) => {
                const htmlContent = item.alt ? `<h4>${item.alt}</h4>` : '';

                const $link = $('<a>')
                    .attr('href', item.src)
                    .attr('data-subhtml', htmlContent)
                    .append($('<img>').attr('src', item.thumb).attr('alt', item.alt));

                $container.append($link);
            });

            $('body').append($container);

            try {
                // Настройки LightGallery - ВКЛЮЧАЕМ встроенный фон
                const lgSettings = {
                    selector: 'a',
                    mode: 'lg-fade',
                    cssEasing: 'cubic-bezier(0.25, 0, 0.25, 1)',
                    speed: 600,
                    height: '100%',
                    width: '100%',
                    backdrop: true,
                    backdropOpacity: 0.9,
                    backdropColor: '#000',
                    backdropDuration: 300,
                    closable: true,
                    loop: false,
                    escKey: true,
                    keyPress: true,
                    controls: true,
                    slideEndAnimation: true,
                    hideControlOnEnd: false,
                    mousewheel: true,
                    getCaptionFromTitleOrAlt: true,
                    appendSubHtmlTo: '.lg-sub-html',
                    subHtmlSelectorRelative: false,
                    preload: 1,
                    showAfterLoad: true,
                    index: targetIndex,
                    download: false,
                    counter: true,
                    enableDrag: true,
                    enableTouch: true,
                    enableSwipe: true,
                    hideBarsDelay: 6000
                };

                // Настройки плагинов
                if (lgThumbnail) {
                    lgSettings.thumbnail = true;
                    lgSettings.animateThumb = true;
                    lgSettings.showThumbByDefault = true;
                    lgSettings.thumbWidth = 80;
                    lgSettings.thumbHeight = 60;
                    lgSettings.thumbMargin = 5;
                    lgSettings.thumbContHeight = 80;
                    lgSettings.currentPagerPosition = 'middle';
                }

                if (lgShare) {
                    lgSettings.share = true;
                    lgSettings.facebook = true;
                    lgSettings.twitter = true;
                    lgSettings.googlePlus = false;
                    lgSettings.pinterest = true;
                }

                // Инициализируем LightGallery
                let lightGalleryInstance = null;

                if ($.fn.lightGallery) {
                    $container.lightGallery(lgSettings);
                    lightGalleryInstance = $container.data('lightGallery');

                    // Принудительно открываем нужный слайд
                    setTimeout(() => {
                        if (lightGalleryInstance && lightGalleryInstance.openGallery) {
                            lightGalleryInstance.openGallery(targetIndex);
                        } else {
                            // Fallback: кликаем на нужный элемент
                            const $targetLink = $container.find('a').eq(targetIndex);
                            if ($targetLink.length) {
                                $targetLink.trigger('click');
                            }
                        }
                    }, 100);

                } else if (typeof lightGallery === 'function') {
                    lightGalleryInstance = lightGallery($container[0], lgSettings);
                }

                // МОНИТОРИМ создание фона LightGallery
                let backdropCheckCount = 0;
                const maxBackdropChecks = 30;
                const checkForBackdrop = setInterval(() => {
                    backdropCheckCount++;

                    // Проверяем создал ли LightGallery фон
                    if ($('.lg-backdrop').length) {
                        clearInterval(checkForBackdrop);
                        console.log('LightGallery backdrop created successfully');
                    }
                    // Если LightGallery не создал фон за 500ms, создаем его сами
                    else if (backdropCheckCount > 5) { // после 500ms
                        clearInterval(checkForBackdrop);
                        console.log('Creating custom backdrop');

                        // Создаем фон НИЖЕ LightGallery
                        const $customBackdrop = $('<div>', {
                            'class': 'lg-backdrop-custom',
                            style: 'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 1040; opacity: 0;'
                        }).appendTo('body');

                        // Анимируем появление
                        setTimeout(() => {
                            $customBackdrop.css('opacity', '1');
                        }, 10);

                        // Убедимся что LightGallery выше фона
                        $('.lg-container, .lg-outer').css('z-index', '1050');
                    }

                    else if (backdropCheckCount >= maxBackdropChecks) {
                        clearInterval(checkForBackdrop);
                        console.error('Failed to create backdrop');
                    }
                }, 100);

                // Функция закрытия галереи
                function closeGallery() {
                    // Удаляем кастомный фон если есть
                    $('.lg-backdrop-custom').remove();

                    // Уничтожаем LightGallery
                    if (lightGalleryInstance && lightGalleryInstance.destroy) {
                        lightGalleryInstance.destroy(true);
                    } else if ($container.data('lightGallery')) {
                        $container.data('lightGallery').destroy(true);
                    }

                    // Удаляем контейнеры
                    $('.lg-container, .lg-outer, .lg-backdrop').remove();
                    $container.remove();
                }

                // Закрытие по ESC
                $(document).on('keydown.lgClose', function(e) {
                    if (e.key === 'Escape') {
                        closeGallery();
                        $(document).off('keydown.lgClose');
                    }
                });

                // Закрытие через LightGallery
                $container.on('onCloseAfter.lg', closeGallery);

            } catch (error) {
                console.error('LightGallery error:', error);
                window.open(galleryItems[targetIndex].src, '_blank');
                $container.remove();
            }
        });

        // Предотвращаем всплытие
        $gallery.on('click', '.swiper-button-next, .swiper-button-prev', function(e) {
            e.stopPropagation();
        });
    }

    // Инициализируем все галереи
    $('.swiper-gallery-block').each(function(index) {
        initGallery($(this), index);
    });
});
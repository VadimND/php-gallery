(function (wp) {
    const { registerBlockType } = wp.blocks;
    const { createElement: el, useState, useEffect, useRef } = wp.element;
    const { useBlockProps, InspectorControls, MediaUpload, MediaUploadCheck } =
        wp.blockEditor;
    const {
        PanelBody,
        Button,
        TextControl,
        ToggleControl,
        RangeControl,
        BaseControl,
        PanelRow,
        Dashicon,
    } = wp.components;
    const { __ } = wp.i18n;

    registerBlockType("swiper-gallery/block", {
        edit: function (props) {
            const { attributes, setAttributes } = props;
            const {
                images = [],
                slidesPerView = 3,
                spaceBetween = 15,
                showNavigation = true,
                showPagination = true,
                autoplay = false,
                autoplayDelay = 3000,
                loop = true,
            } = attributes;
            const blockProps = useBlockProps({
                className: "swiper-gallery-block-editor",
            });
            const dragItem = useRef();
            const dragOverItem = useRef();

            // Функция добавления изображений
            const onSelectImages = (selectedImages) => {
                const newImages = selectedImages.map((image) => ({
                    id: image.id,
                    url: image.url,
                    thumbnail: image.sizes?.medium?.url || image.url,
                    full_size: image.url,
                    alt: image.alt || "",
                    caption: image.caption || "",
                }));
                setAttributes({
                    images: [...images, ...newImages],
                });
            };

            // Удаление изображения
            const removeImage = (index) => {
                const newImages = [...images];
                newImages.splice(index, 1);
                setAttributes({ images: newImages });
            };

            // Обновление изображения
            const updateImage = (index, field, value) => {
                const newImages = [...images];
                newImages[index][field] = value;
                setAttributes({ images: newImages });
            };

            // Drag Start
            const dragStart = (e, position) => {
                dragItem.current = position;
                e.dataTransfer.effectAllowed = "move";
                e.dataTransfer.setData("text/html", e.target);
            };

            // Drag Enter
            const dragEnter = (e, position) => {
                dragOverItem.current = position;
                e.currentTarget.classList.add("drag-over");
            };

            // Drag Leave
            const dragLeave = (e) => {
                e.currentTarget.classList.remove("drag-over");
            };

            // Drag Over
            const dragOver = (e) => {
                e.preventDefault();
            };

            // Drop
            const drop = (e) => {
                e.preventDefault();
                e.currentTarget.classList.remove("drag-over");
                if (dragItem.current !== dragOverItem.current) {
                    const newImages = [...images];
                    const draggedItem = newImages[dragItem.current];

                    // Удаляем элемент из старой позиции
                    newImages.splice(dragItem.current, 1);

                    // Вставляем элемент в новую позицию
                    newImages.splice(dragOverItem.current, 0, draggedItem);
                    setAttributes({ images: newImages });
                }
                dragItem.current = null;
                dragOverItem.current = null;
            };

            // Простая сортировка с помощью кнопок
            const moveImageUp = (index) => {
                if (index === 0) return;
                const newImages = [...images];
                [newImages[index], newImages[index - 1]] = [
                    newImages[index - 1],
                    newImages[index],
                ];
                setAttributes({ images: newImages });
            };
            const moveImageDown = (index) => {
                if (index === images.length - 1) return;
                const newImages = [...images];
                [newImages[index], newImages[index + 1]] = [
                    newImages[index + 1],
                    newImages[index],
                ];
                setAttributes({ images: newImages });
            };
            return el(
                "div",
                blockProps,

                // Панель инспектора
                el(
                    InspectorControls,
                    null,
                    el(
                        PanelBody,
                        {
                            title: __("Настройки слайдера", "swiper-gallery"),
                            initialOpen: true,
                        },
                        el(RangeControl, {
                            label: __("Слайдов на экране", "swiper-gallery"),
                            value: slidesPerView,
                            onChange: (value) => setAttributes({ slidesPerView: value }),
                            min: 1,
                            max: 5,
                            step: 1,
                        }),
                        el(RangeControl, {
                            label: __("Расстояние между слайдами (px)", "swiper-gallery"),
                            value: spaceBetween,
                            onChange: (value) => setAttributes({ spaceBetween: value }),
                            min: 0,
                            max: 100,
                            step: 5,
                        }),
                        el(ToggleControl, {
                            label: __("Показывать навигацию", "swiper-gallery"),
                            checked: showNavigation,
                            onChange: (value) => setAttributes({ showNavigation: value }),
                        }),
                        el(ToggleControl, {
                            label: __("Показывать пагинацию", "swiper-gallery"),
                            checked: showPagination,
                            onChange: (value) => setAttributes({ showPagination: value }),
                        }),
                        el(ToggleControl, {
                            label: __("Автопрокрутка", "swiper-gallery"),
                            checked: autoplay,
                            onChange: (value) => setAttributes({ autoplay: value }),
                        }),
                        autoplay &&
                        el(RangeControl, {
                            label: __("Задержка автопрокрутки (мс)", "swiper-gallery"),
                            value: autoplayDelay,
                            onChange: (value) => setAttributes({ autoplayDelay: value }),
                            min: 1000,
                            max: 10000,
                            step: 500,
                        }),
                        el(ToggleControl, {
                            label: __("Зациклить слайдер", "swiper-gallery"),
                            checked: loop,
                            onChange: (value) => setAttributes({ loop: value }),
                        }),
                    ),
                ),

                // Основной интерфейс редактора
                el(
                    "div",
                    { className: "swiper-gallery-editor-wrapper" },

                    // Кнопка добавления изображений
                    el(
                        MediaUploadCheck,
                        null,
                        el(MediaUpload, {
                            onSelect: onSelectImages,
                            allowedTypes: ["image"],
                            multiple: true,
                            gallery: true,
                            render: function ({ open }) {
                                return el(
                                    "div",
                                    { className: "swiper-gallery-add-images" },
                                    el(
                                        Button,
                                        {
                                            isPrimary: true,
                                            onClick: open,
                                            icon: "plus",
                                            style: { marginBottom: "10px" },
                                        },
                                        __("Добавить изображения", "swiper-gallery"),
                                    ),
                                    images.length > 0 &&
                                    el(
                                        "p",
                                        {
                                            className: "swiper-gallery-hint",
                                            style: {
                                                fontSize: "12px",
                                                color: "#666",
                                                marginTop: "5px",
                                            },
                                        },
                                        __(
                                            "Используйте кнопки ↑↓ для сортировки или перетаскивайте изображения",
                                            "swiper-gallery",
                                        ),
                                    ),
                                );
                            },
                        }),
                    ),

                    // Список изображений
                    images.length > 0 &&
                    el(
                        "div",
                        { className: "swiper-gallery-images-list" },
                        images.map(function (image, index) {
                            return el(
                                "div",
                                {
                                    key: image.id || image.url || index,
                                    className: "swiper-gallery-image-item",
                                    draggable: true,
                                    onDragStart: (e) => dragStart(e, index),
                                    onDragEnter: (e) => dragEnter(e, index),
                                    onDragLeave: (e) => dragLeave(e),
                                    onDragOver: (e) => dragOver(e),
                                    onDrop: (e) => drop(e),
                                    style: {
                                        display: "flex",
                                        alignItems: "center",
                                        padding: "15px",
                                        marginBottom: "10px",
                                        backgroundColor: "#f9f9f9",
                                        border: "1px solid #ddd",
                                        borderRadius: "4px",
                                        position: "relative",
                                    },
                                },

                                // Иконка перетаскивания
                                el(
                                    "div",
                                    {
                                        className: "swiper-gallery-drag-handle",
                                        style: {
                                            marginRight: "15px",
                                            cursor: "move",
                                            color: "#8c8f94",
                                        },
                                    },
                                    el(Dashicon, { icon: "menu" }),
                                ),

                                // Изображение
                                el(
                                    "div",
                                    {
                                        className: "swiper-gallery-image-preview",
                                        style: {
                                            flex: "0 0 100px",
                                            marginRight: "15px",
                                        },
                                    },
                                    el("img", {
                                        src: image.thumbnail || image.url,
                                        alt: image.alt || "",
                                        className: "swiper-gallery-thumbnail",
                                        style: {
                                            width: "100px",
                                            height: "100px",
                                            objectFit: "cover",
                                            borderRadius: "4px",
                                        },
                                    }),
                                ),

                                // Управление
                                el(
                                    "div",
                                    {
                                        className: "swiper-gallery-image-controls",
                                        style: { flex: 1 },
                                    },
                                    el(TextControl, {
                                        label: __("Alt текст", "swiper-gallery"),
                                        value: image.alt || "",
                                        onChange: (value) => updateImage(index, "alt", value),
                                        style: { marginBottom: "10px" },
                                    }),
                                    el(
                                        "div",
                                        {
                                            className: "swiper-gallery-image-buttons",
                                            style: {
                                                display: "flex",
                                                gap: "10px",
                                                flexWrap: "wrap",
                                            },
                                        },
                                        el(MediaUpload, {
                                            onSelect: (media) =>
                                                updateImage(index, "thumbnail", media.url),
                                            allowedTypes: ["image"],
                                            render: function ({ open }) {
                                                return el(
                                                    Button,
                                                    {
                                                        isSecondary: true,
                                                        onClick: open,
                                                        icon: "format-image",
                                                        className: "swiper-gallery-button-small",
                                                    },
                                                    __("Миниатюра", "swiper-gallery"),
                                                );
                                            },
                                        }),
                                        el(MediaUpload, {
                                            onSelect: (media) =>
                                                updateImage(index, "full_size", media.url),
                                            allowedTypes: ["image"],
                                            render: function ({ open }) {
                                                return el(
                                                    Button,
                                                    {
                                                        isSecondary: true,
                                                        onClick: open,
                                                        icon: "fullscreen-alt",
                                                        className: "swiper-gallery-button-small",
                                                    },
                                                    __("Полноразмерное", "swiper-gallery"),
                                                );
                                            },
                                        }),

                                        // Кнопки сортировки
                                        el(
                                            "div",
                                            {
                                                style: {
                                                    display: "flex",
                                                    gap: "5px",
                                                    marginLeft: "10px",
                                                },
                                            },
                                            el(Button, {
                                                isSmall: true,
                                                onClick: () => moveImageUp(index),
                                                disabled: index === 0,
                                                icon: "arrow-up-alt2",
                                                style: { minWidth: "30px" },
                                            }),
                                            el(Button, {
                                                isSmall: true,
                                                onClick: () => moveImageDown(index),
                                                disabled: index === images.length - 1,
                                                icon: "arrow-down-alt2",
                                                style: { minWidth: "30px" },
                                            }),
                                        ),
                                        el(
                                            Button,
                                            {
                                                isDestructive: true,
                                                onClick: () => removeImage(index),
                                                icon: "trash",
                                                className: "swiper-gallery-button-small",
                                                style: { marginLeft: "auto" },
                                            },
                                            __("Удалить", "swiper-gallery"),
                                        ),
                                    ),
                                ),
                            );
                        }),
                    ),

                    // Предпросмотр
                    images.length > 0 &&
                    el(
                        "div",
                        {
                            className: "swiper-gallery-preview",
                            style: {
                                marginTop: "30px",
                                padding: "15px",
                                backgroundColor: "#f5f5f5",
                                borderRadius: "4px",
                            },
                        },
                        el(
                            "h4",
                            {
                                style: {
                                    marginTop: "0",
                                    marginBottom: "15px",
                                    color: "#1e1e1e",
                                },
                            },
                            __("Предпросмотр слайдера:", "swiper-gallery"),
                        ),
                        el(
                            "div",
                            {
                                className: "swiper-gallery-preview-container",
                                style: {
                                    padding: "15px",
                                    backgroundColor: "white",
                                    border: "1px solid #ddd",
                                    borderRadius: "4px",
                                },
                            },
                            el(
                                "div",
                                {
                                    className: "swiper-container-preview",
                                    style: {
                                        position: "relative",
                                        overflow: "hidden",
                                        padding: "10px 40px",
                                    },
                                },
                                el(
                                    "div",
                                    {
                                        className: "swiper-wrapper-preview",
                                        style: {
                                            display: "flex",
                                            gap: "15px",
                                            padding: "10px 0",
                                        },
                                    },
                                    images.map(function (image, index) {
                                        return el(
                                            "div",
                                            {
                                                key: "preview-" + index,
                                                className: "swiper-slide-preview",
                                                style: {
                                                    flex: "0 0 calc(33.333% - 10px)",
                                                    display: "flex",
                                                    alignItems: "center",
                                                    justifyContent: "center",
                                                    backgroundColor: "#f9f9f9",
                                                    borderRadius: "4px",
                                                    padding: "10px",
                                                    height: "150px",
                                                    overflow: "hidden",
                                                },
                                            },
                                            el("img", {
                                                src: image.thumbnail || image.url,
                                                alt: image.alt || "",
                                                style: {
                                                    width: "100%",
                                                    height: "100%",
                                                    objectFit: "cover",
                                                },
                                            }),
                                        );
                                    }),
                                ),
                                showNavigation &&
                                el(
                                    "div",
                                    null,
                                    el("div", { className: "swiper-button-prev-preview" }),
                                    el("div", { className: "swiper-button-next-preview" }),
                                ),
                                showPagination &&
                                el("div", { className: "swiper-pagination-preview" }),
                            ),
                        ),
                    ),

                    // Сообщение если нет изображений
                    images.length === 0 &&
                    el(
                        "div",
                        {
                            className: "swiper-gallery-empty",
                            style: {
                                textAlign: "center",
                                padding: "40px",
                                color: "#666",
                                backgroundColor: "#f9f9f9",
                                borderRadius: "4px",
                                border: "2px dashed #ddd",
                            },
                        },
                        el(
                            "p",
                            null,
                            __(
                                "Добавьте изображения, чтобы создать галерею",
                                "swiper-gallery",
                            ),
                        ),
                    ),
                ),
            );
        },
        save: function () {
            return null; 
        },
    });
})(window.wp);

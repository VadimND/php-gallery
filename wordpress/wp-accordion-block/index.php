<?php

/**
 * Plugin Name: Аккордеон (InnerBlocks, WP 5.9)
 * Version: 1.0.0
 * Author: VadimND
 * Description: Plugin for Gutenberg which adds new Accordion widget to editor panel. WP API (ver. 5.9.3)
 */
add_action('init', function () {
    register_block_type('aio/accordion', [
        'editor_script' => 'aio-accordion-editor',
        'render_callback' => function ($attrs, $content) {
            return '<div class="aio-accordion">' . $content . '</div>';
        }
    ]);

    register_block_type('aio/accordion-item', [
        'editor_script' => 'aio-accordion-editor',
        'attributes' => [
            'title' => [
                'type' => 'string',
                'default' => 'Заголовок'
            ]
        ],
        'render_callback' => function ($attrs, $content) {
            ob_start();
            ?>
            <div class="aio-item">
                <button class="aio-header">
                    <?php echo esc_html($attrs['title']); ?>
                    <svg viewBox="0 0 24 24" class="aio-arrow">
                        <path d="M6 9l6 6 6-6"
                              fill="none"
                              stroke="currentColor"
                              stroke-width="2"/>
                    </svg>
                </button>
                <div class="aio-body">
                    <div class="aio-inner">
                        <?php echo $content; ?>
                    </div>
                </div>
            </div>
            <?php
            return ob_get_clean();
        }
    ]);
});

/** Editor JS */
add_action('enqueue_block_editor_assets', function () {
    wp_enqueue_script(
        'aio-accordion-editor',
        plugins_url('', __FILE__),
        ['wp-blocks', 'wp-element', 'wp-editor', 'wp-components'],
        '1.0.0'
    );

    wp_add_inline_script('aio-accordion-editor', '
        (function () {
            const { registerBlockType } = wp.blocks;
            const { createElement: el } = wp.element;
            const { InnerBlocks } = wp.blockEditor;
            const { TextControl } = wp.components;

            registerBlockType("aio/accordion", {
                title: "Аккордеон",
                icon: "menu",
                category: "layout",
                edit: () =>
                    el("div", { className: "aio-editor" },
                        el(InnerBlocks, {
                            allowedBlocks: ["aio/accordion-item"],
                            template: [["aio/accordion-item"]],
                            templateLock: false
                        })
                    ),
                save: () => el(InnerBlocks.Content)
            });

            registerBlockType("aio/accordion-item", {
                title: "Пункт аккордеона",
                icon: "excerpt-view",
                parent: ["aio/accordion"],
                category: "layout",
                attributes: {
                    title: { type: "string", default: "Заголовок" }
                },
                edit: (props) =>
                    el("div", { className: "aio-item-editor" },
                        el(TextControl, {
                            label: "Заголовок",
                            value: props.attributes.title,
                            onChange: (v) => props.setAttributes({ title: v })
                        }),
                        el("div", { style: { paddingLeft: "12px", borderLeft: "2px solid #ddd" } },
                            el(InnerBlocks)
                        )
                    ),
                save: () => el(InnerBlocks.Content)
            });
        })();
    ');
});

/** Frontend styles + JS */
add_action('wp_footer', function () {
    ?>
<style>
.aio-accordion {
    border: 1px solid #ddd;
	padding: 10px;
}
.aio-header {
    width: 100%;
    padding: 15px;
    background: #f3f3f3;
    border: none;
    font-weight: bold;
    display: flex;
    justify-content: space-between;
    cursor: pointer;
    color: #681B21;
    text-transform: uppercase;
    margin: 0;
}

.aio-body {
    height: 0;
    overflow: hidden;
    transition: height .3s ease;
}
.aio-item:not(:last-child) {
    border-bottom: 10px solid #fff;
}
.aio-inner {
    padding: 12px;
}

.aio-arrow {
    width: 16px;
    height: 16px;
    transition: transform .3s ease;
}
div.aio-item.active > button.aio-header > svg.aio-arrow {
	transform: rotate(180deg);
}
button.aio-header:hover, button.aio-header:focus, button.aio-header:active {
    background: var(--imred--main--wine--color--dark);
    box-shadow: none;
    color: #fff;
}
</style>

<script>
document.addEventListener("click", function (e) {
    const header = e.target.closest(".aio-header");
    if (!header) return;

    const item = header.parentElement;
    const body = item.querySelector(".aio-body");

    if (item.classList.contains("active")) {
        body.style.height = body.scrollHeight + "px";
        requestAnimationFrame(() => body.style.height = "0");
        item.classList.remove("active");
    } else {
        body.style.height = body.scrollHeight + "px";
        item.classList.add("active");

        body.addEventListener("transitionend", function h() {
            body.style.height = "auto";
            body.removeEventListener("transitionend", h);
        });
    }
});
</script>
<?php
});

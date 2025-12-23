<?php

    /**
     * Plugin Name: Аккордеон (InnerBlocks, WP 5.9)
     * Version: 1.0.0
     * Author: VadimND
     * Description: Plugin for Gutenberg which adds new Accordion widget to editor panel. WP API (ver. 5.9.3)
     */
    add_action('init', function () {

        register_block_type('aio/accordion', [
            'editor_script'   => 'aio-accordion-editor',
            'render_callback' => function ($attrs, $content) {
                return '<div class="aio-accordion">' . $content . '</div>';
            },
        ]);

        register_block_type('aio/accordion-item', [
            'editor_script'   => 'aio-accordion-editor',
            'attributes'      => [
                'title'  => [
                    'type'    => 'string',
                    'default' => 'Заголовок',
                ],
                'itemId' => [
                    'type'    => 'string',
                    'default' => '',
                ],
            ],
            'render_callback' => function ($attrs, $content) {
                $item_id = '';
                if (! empty($attrs['itemId'])) {
                    $item_id = ' id="' . esc_attr($attrs['itemId']) . '"';
                }
            ob_start(); ?>
            <div class="aio-item"<?php echo $item_id; ?>>
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
                        },
                    ]);
                });

                /**
                 * Editor JS
                 */
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
                    title: { type: "string", default: "Заголовок" },
                    itemId: { type: "string", default: "" }
                },
                 edit: (props) =>
                    el("div", { className: "aio-item-editor" },
                        el(TextControl, {
                            label: "Заголовок",
                            value: props.attributes.title,
                            onChange: (v) => props.setAttributes({ title: v })
                        }),
                        el(TextControl, { // Поле для ввода ID
                            label: "ID элемента (опционально)",
                            help: "Уникальный идентификатор для ссылок",
                            value: props.attributes.itemId,
                            onChange: (v) => props.setAttributes({ itemId: v })
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

                /**
                 * Frontend styles + JS
                 */
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
	font-size: 18px;
    display: flex;
    justify-content: space-between;
    cursor: pointer;
    color: #681B21;
    text-transform: uppercase;
    margin: 0;
	text-align: left;
}
.aio-accordion .aio-accordion .aio-header {
	font-size: 24px;
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
    width: 30px;
    height: 30px;
    transition: transform .3s ease;
    display: block;
    margin: auto 0;
    min-width: 22px;
}
div.aio-item.active > button.aio-header > svg.aio-arrow {
	transform: rotate(180deg);
}
button.aio-header:hover, button.aio-header:focus, button.aio-header:active {
    background: var(--imred--main--wine--color--dark);
    box-shadow: none;
    color: #fff;
}
@media screen and (max-width: 414px) {
	.aio-header {
		font-size: 18px;
	}
	.aio-accordion .aio-accordion .aio-header {
		font-size: 18px;
	}
}
</style>

<script>
function openBox(item, body) {
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
}

window.onload = function(){
    const items = document.querySelectorAll('.aio-item');
	const hash = window.location.hash.substring(1);

    if (!hash) return;

    setTimeout(() => {
        const tabhead = document.getElementById(hash);
        const tabbody = tabhead.querySelector(".aio-body");

        if (tabhead && tabbody) {

            openBox(tabhead, tabbody);

            tabhead.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    }, 100);	

    document.addEventListener("click", function (e) {
        if (e.target.tagName === 'A' || e.target.closest('a')) {
            const link = e.target.tagName === 'A' ? e.target : e.target.closest('a');
            const urlWithoutHash = link.href.split('#')[0];
            const urlWithHash = link.hash;

            if (urlWithoutHash === 'https://xxxxx.xx/xxxx-xxxxx/') {
               window.location.hash = urlWithHash;
               window.location.reload();
            }
        }
        const header = e.target.closest(".aio-header");
        if (!header) return;
        const item = header.parentElement;
        const body = item.querySelector(".aio-body");
        openBox(item, body);

    });
};

</script>
<?php
});

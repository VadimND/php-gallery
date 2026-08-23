<?php

use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses('custom.catalog', [
    'Custom\\Catalog\\PriceHelper'    => 'lib/PriceHelper.php',
    'Custom\\Catalog\\RelatedProducts' => 'lib/RelatedProducts.php',
    'Custom\\Catalog\\EventHandlers'  => 'lib/EventHandlers.php',
]);

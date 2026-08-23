#!/usr/bin/env bash
# Быстрый запуск проверки логики без полного ядра Bitrix.
# Использует лёгкую эмуляцию Bitrix API (tests/bootstrap.php).
#
# ВНИМАНИЕ: этот скрипт запускает только smoke-проверку того, что код
# подключается и отрабатывает.

set -e
echo ">> PHP version:"
php --version | head -1
echo ""
echo ">> Smoke-запуск логики связанных товаров:"
php -r '
require "tests/bootstrap.php";
$lib = "local/modules/custom.catalog/lib/";
require $lib."PriceHelper.php";
require $lib."RelatedProducts.php";
require $lib."EventHandlers.php";
use Custom\Catalog\RelatedProducts;
$items = RelatedProducts::get(100, 0);
echo "Найдено связанных товаров: ".count($items)."\n";
foreach ($items as $i) {
    printf("  #%d  %-22s FINAL_PRICE=%s\n", $i["ID"], $i["NAME"], $i["FINAL_PRICE"]);
}
'
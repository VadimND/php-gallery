<?php

/* Фильтрация товаров по категориям */

function filterProducts(array $products, string $category): string
{
    $str = '';
    $counter = 0;
    foreach ($products as $product) {
        if ($category === $product['category'] || $category === '') {
            $counter++;
            $str .= "<p>Товар: {$product['name']}, категория: {$product['category']}, цена: {$product['price']}</p>";
        }
    }
    if ($counter === 0) {
        $str = '<p>Такой категории нет.</p>';
    }
    return $str;
}

/* addToFavorites(&$favorites, $productName) добавляет название продукта в массив $favorites,
Не добавляет повторно один и тот же продукт */

function addToFavorites(array &$favorites, string $productName): void
{
    if (!in_array($productName, $favorites)) {
        $favorites[] = $productName;
    }
}

/* Список отфильтрованных продуктов */

function getFilters(array $products, string $category = ''): void
{
    echo '<p>Отфильтрованные товары: </p>' . filterProducts($products, $category);
}

$products = [
    ['name' => 'Сляб', 'category' => 'Металл', 'price' => 100],
    ['name' => 'Лист', 'category' => 'Металл', 'price' => 80],
    ['name' => 'Бетон', 'category' => 'Строительные', 'price' => 50],
    ['name' => 'Рулон', 'category' => 'Металл', 'price' => 90],
    ['name' => 'Песок', 'category' => 'Строительные', 'price' => 20],
];

getFilters($products, 'Металл');

/* Текущий список избранного */

function getFavourites(array $favorites = []): void
{
    if (count($favorites) > 0) {
        echo 'Избранное: ' . implode("\n", $favorites);
    } else {
        echo 'Вы не добавили товары в Избранное';
    }
}

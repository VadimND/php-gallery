<?php
/*
Задача:

1. Написать SQL запрос, чтобы получить актуальную цену товара 1, которая действовала на 05.03.2024

2. Написать SQL запрос, чтобы получить актуальные цены двух товаров на 05.03.2024

*/

$result1 = $mysqli->query("SELECT price 
FROM price_history 
WHERE product_id = 1 AND created_at <= '2024-03-05' 
ORDER BY created_at 
DESC LIMIT 1");

$result2 = $mysqli->query("SELECT ph.product_id, ph.price
FROM price_history ph1
INNER JOIN (
    SELECT product_id, MAX(created_at) as max_date
    FROM price_history
    WHERE created_at <= '2024-03-05'
    GROUP BY product_id
) ph2 ON ph1.product_id = ph2.product_id AND ph1.created_at = ph2.max_date
WHERE ph1.product_id IN (1, 2)");

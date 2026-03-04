<?php

function fibonacci($n) {
    if ($n <= 0) return 0;
    if ($n == 1) return 1;
    return fibonacci($n - 1) + fibonacci($n - 2);
}

// Пример вывода последовательности
for ($i = 0; $i <= 16; $i++) {
    echo fibonacci($i) . ", ";
    if($i === 16) echo fibonacci($i);
}

// 0, 1, 1, 2, 3, 5, 8, 13, 21, 34, 55, 89, 144, 233, 377, 610, 987
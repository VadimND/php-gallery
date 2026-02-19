<?php
function myfunction($v1, $v2)
{
    if (empty($v1)) {
        return $v2;
    }
    return $v1 . "-" . $v2;
}

$a = array("Dog", "Cat", "Horse");

echo array_reduce($a, "myfunction"); 
// Вывод: Dog-Cat-Horse

function myfunction2($v1, $v2)
{
    return $v1 . "-" . $v2;
}

$a = array("Dog", "Cat", "Horse");
$first = array_shift($a); // Извлекаем первый элемент
$result = array_reduce($a, "myfunction2", $first);
echo $result; 
// Вывод: Dog-Cat-Horse

function myfunction3($v1, $v2)
{
    return $v1 . "-" . $v2;
}

$a = array("Dog", "Cat", "Horse");
$result = array_reduce(array_slice($a, 1), "myfunction3", $a[0]);
echo $result; 
// Вывод: Dog-Cat-Horse
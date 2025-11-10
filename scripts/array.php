<?php

/*
    Создание массива из вводимых символов и вывод нечетных элементов
*/

$massiv = false;
$x = false;

if (isset($_POST['myform'])) {
    $x = $_POST['x'] ?? false;
    if ($x !== false)
        $massiv = $_POST['x'];
    $piece = preg_split('//u', $massiv, -1, PREG_SPLIT_NO_EMPTY);
}
?>
<?php if ($massiv !== false): ?>
    <p>Введено: <?php foreach ($piece as $key => $value)
        if ($key % 2 !== 0)
            echo $value . " "; ?></p><?php endif ?>

<!DOCTYPE html>
<html>

<head>
    <meta lang='ru'>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7,IE=edge">
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
</head>

<body>

<form name="myform" accept-charset="UTF-8" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
    <div>
        <textarea rows="4" cols="55" name="x" placeholder="Введите любые символы"></textarea>
    </div>

    <div>
        <input type="submit" name="myform" value="Отправить" />
    </div>
</form>
</body>

</html>
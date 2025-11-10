<?php
if (!isset($_REQUEST['doGo'])) {
  return;
}

$age = $_REQUEST['age'] ?? '';

if ($age === '') {
  echo 'Вы ввели пустое значение';
  return;
}

if (!is_numeric($age)) {
  echo 'Это не число';
  return;
}

$age = (int) $age;

if ($age < 5) {
  echo 'Возраст не может быть меньше 5 лет';
} elseif ($age > 100) {
  echo 'Возраст не может превышать 100 лет';
} elseif ($age < 30) {
  echo 'Вам меньше 30 лет';
} elseif ($age === 30) {
  echo 'Вам 30 лет';
} else {
  echo 'Вам больше 30 лет';
}
?>
<!doctype html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <meta name="viewport"
    content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>

<body>
  <form action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="post">
    <label>Ваш возраст: <input type="number" name="age"></label>
    <br/>
    <input type="submit" name="doGo" value="Отправить">
  </form>
</body>

</html>
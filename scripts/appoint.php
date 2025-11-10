<?php
// Create letter to admin from contact form
$to = 'info@site.by';
$subject = 'Заявка на сервис';

$fields = [
	'fval' => 'Имя',
	'mobval' => 'Телефон',
	'arrVal' => 'Выбранные услуги',
	'mval' => 'Модель',
	'kval' => 'Пробег',
	'rval' => 'Год выпуска',
	'dval' => 'Дата визита',
	'tval' => 'Время визита',
	'url' => 'URL'
];

$data = [];
foreach ($fields as $key => $label) {
	$data[$key] = htmlspecialchars(trim($_POST[$key] ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

$rows = '';
foreach ($fields as $key => $label) {
	$value = $data[$key] ?: '—';
	$rows .= "
        <tr>
            <td style='width: 200px; padding: 5px;'><b>{$label}:</b></td>
            <td>{$value}</td>
        </tr>";
}

$message = "
<!DOCTYPE html>
<html lang='ru'>
<head>
    <meta charset='UTF-8'>
    <title>Заявка на сервис</title>
</head>
<body style='font-family: Arial, sans-serif; font-size: 14px; color: #000;'>
    <h2 style='background-color: #eee; padding: 10px;'>Заявка на сервис</h2>
    <table cellspacing='0' cellpadding='0' style='width: 100%; border-collapse: collapse;'>
        <tbody>$rows</tbody>
    </table>
</body>
</html>
";

$headers = [
	'From: site.by <info@site.by>',
	"Reply-To: {$data['mobval']}",
	'MIME-Version: 1.0',
	'Content-Type: text/html; charset=UTF-8'
];

$success = mail($to, $subject, $message, implode("\r\n", $headers));

if ($success) {
	echo "<div class='resp success'>Благодарим за заявку!</div>";
} else {
	echo "<div class='resp error'>Ошибка при отправке письма. Попробуйте позже.</div>";
}

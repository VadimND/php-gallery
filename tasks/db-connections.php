<?php
/**
 * Варианты подключения к базе данных MySQL
 * 1 - mysqli_connect
 * 2 - создание объекта подключения
 * 3 - создание подключения PDO
 *
 */
// 1 - mysqli_connect
$host = 'localhost';
$user = 'root';
$password = 'secret';
$database = 'my_database';

$mysqli = mysqli_connect($host, $user, $password, $database);

if (!$mysqli) {
    die('Ошибка подключения: ' . mysqli_connect_error());
}

// Пример получения простого запроса
$query = "SELECT id, name, email FROM users WHERE active = 1";
$result = mysqli_query($mysqli, $query);

if (!$result) {
    die('Ошибка запроса: ' . mysqli_error($mysqli));
}

$users = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Подготовленный запрос (рекомендуется для безопасности)
$user_id = 5;
$stmt = mysqli_prepare($mysqli, "SELECT * FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $user_id); // 'i' - integer
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

mysqli_free_result($result);
mysqli_stmt_close($stmt);
mysqli_close($mysqli);


// 2 - создание объекта подключения
$mysqli = new mysqli('localhost', 'user', 'password', 'database');

if ($mysqli->connect_error) {
    die('Ошибка подключения: ' . $mysqli->connect_error);
}

$mysqli->set_charset('utf8mb4');

// Пример получения простого запроса
$result = $mysqli->query("SELECT * FROM products WHERE price > 100");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        echo "Товар: {$row['name']}, Цена: {$row['price']}<br>";
    }
    $result->free();
}

// Подготовленный запрос
$city = "Минск";
$stmt = $mysqli->prepare("SELECT name, email FROM users WHERE city = ? AND age > ?");

$stmt->bind_param('si', $city, $age);
$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_all(MYSQLI_ASSOC);

foreach ($users as $user) {
    echo "{$user['name']} - {$user['email']}<br>";
}

$stmt->close();
$mysqli->close();

// 3 - создание подключения PDO
try {
    $dsn = 'mysql:host=localhost;dbname=my_database;charset=utf8mb4';
    $pdo = new PDO($dsn, 'user', 'password', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);

    echo "Подключение успешно!<br>";

    // Пример получения простого запроса
    $stmt = $pdo->query("SELECT * FROM users LIMIT 5");
    $users = $stmt->fetchAll();

    foreach ($users as $user) {
        echo "{$user['name']}<br>";
    }

    // Подготовленный запрос
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE status = :status AND total > :min_total");
    $stmt->execute([
        ':status' => 'completed',
        ':min_total' => 1000
    ]);

    $orders = $stmt->fetchAll();

    // Построчное чтение (экономит память)
    while ($row = $stmt->fetch()) {
        echo "{$row['name']} - {$row['price']}<br>";
    }

} catch (PDOException $e) {
    die('Ошибка подключения: ' . $e->getMessage());
}
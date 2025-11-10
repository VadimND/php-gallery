<?php
/*
Пример:
Есть один заказ в магазине, в нем два товара: товар1 - 3 шт., товар2 - 2шт.
При оформлении заказа покупатель хочет оплатить товар1 - 1шт., товар2 - 2шт., оставшийся товар1 ему не нужен.

Задача:
1. данные о заказе, товарах, их статусах и стоимости хранить в БД
2. рассчитать стоимость оплаты для покупателя
3. раcсчитать стоимость оставшихся товаров в магазине

В решении нужно:
1. структура БД, необходимая для решения задачи
2. код на нативном PHP (без использования фреймворков), желательно использование функционала последних версий PHP там, где это уместно


Структура БД будет иметь следующий вид:

CREATE TABLE IF NOT EXISTS `products` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL
);

CREATE TABLE IF NOT EXISTS `orders` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    total_price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `order_items` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT,
    price DECIMAL(10, 2),
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

*** Заносятся первичные данные ***
INSERT INTO products (name, price, stock) VALUES
('Product 1', 10.00, 3),
('Product 2', 15.00, 2);

*/

// Класс для соединения и работы с БД
class Database
{

    private $mysqli;

    public function __construct($host, $user, $password, $database)
    {
        $this->mysqli = new mysqli($host, $user, $password, $database);
        if ($this->mysqli->connect_error) {
            die('Connect Error (' . $this->mysqli->connect_errno . ') ' . $this->mysqli->connect_error);
        }
    }

    public function query($sql)
    {
        return $this->mysqli->query($sql);
    }

    public function prepare($sql)
    {
        return $this->mysqli->prepare($sql);
    }
}

// Класс для товара. Служит для получения данных о товаре, обновления информации и подсчета суммы
class Product
{

    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function getProduct($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateStock($id, $quantity)
    {
        $stmt = $this->db->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        $stmt->bind_param("ii", $quantity, $id);
        $stmt->execute();
    }

    public function getStockPrice($id)
    {
        $stmt = $this->db->prepare("SELECT price, stock FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result(); 
        $total_price_arr = $result->fetch_assoc();
        $total_price = number_format($total_price_arr["price"] * $total_price_arr["stock"], 2);        
        return $total_price;
    }
}

// Класс для создания, получения заказа, проверки наличия заказов в таблицы
class Order
{

    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function createOrder($items)
    {
        $totalPrice = 0;

        foreach ($items as $item) {
            $product = (new Product($this->db))->getProduct($item['product_id']);
            $totalPrice += $product['price'] * $item['quantity'];
        }

        $stmt = $this->db->prepare("INSERT INTO orders (total_price) VALUES (?)");
        $stmt->bind_param("d", $totalPrice);
        $stmt->execute();
        $orderId = $stmt->insert_id;

        foreach ($items as $item) {
            $product = (new Product($this->db))->getProduct($item['product_id']);
            $stmt = $this->db->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiid", $orderId, $item['product_id'], $item['quantity'], $product['price']);
            $stmt->execute();            

            if($product['stock'] > 0) {
                (new Product($this->db))->updateStock($item['product_id'], $item['quantity']);
            }
            
        }

        return $orderId;
    }

    public function getOrder($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function countOrders()
    {
        $result = $this->db->query("SELECT COUNT(*) FROM orders");        
        return $result->fetch_array()[0];
    }    

}

$db = new Database('localhost', 'root', '', 'koleso_db1');
$order = new Order($db);
$product = new Product($db);

$orderItems = [
    ['product_id' => 1, 'quantity' => 1],
    ['product_id' => 2, 'quantity' => 2]
];
if($order->countOrders() == 0) {
    $orderId = $order->createOrder($orderItems);
}

// Cтоимость оплаты для покупателя
$orderDetails = $order->getOrder(1);
echo 'Общая стоимость заказа: ' . $orderDetails['total_price'] . "<br/>";

// Cтоимость оставшихся товаров магазине
$product1Stock = $product->getStockPrice(1);
echo 'Общая стоимость оставшегося товара1 составит: ' . $product1Stock;

// Общая стоимость заказа: 40.00
// Общая стоимость оставшегося товара1 составит: 20.00
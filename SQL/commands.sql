SELECT
	order_num,
	SUM( quantity * item_price ) AS order_price
FROM OrderItems
GROUP BY order_num
HAVING SUM( quantity * item_price ) >= 1000
ORDER BY order_num;


SELECT
	cust_id,
	SUM( order_summa ) AS total_ordered
FROM
(
	SELECT
		cust_id,
		(
			SELECT SUM( quantity * item_price )
			FROM OrderItems
			WHERE OrderItems.order_num = Orders.order_num
		) AS order_summa
	FROM Orders
) AS OrdersSummas
GROUP BY cust_id
ORDER BY total_ordered DESC;


select
    cust_name,
    sum( quantity * item_price ) orders_price
from customers c
inner join orders o on c.cust_id = o.cust_id
inner join orderitems oi on o.order_num = oi.order_num
group by cust_name
having sum( quantity * item_price ) >= 1000
order by cust_name;

select prod_name
from products
union
select cust_name
from customers
order by prod_name;

insert into customers(cust_id, cust_name)
values(1, 'Anton');

update customers
set cust_state = upper(cust_state)
where cust_country = 'USA';

delete
from customers
where cust_id = '1';



SELECT
    DISTINCT p1.PRODUCT_ID,
    e1.NAME,
    p1.AMOUNT
FROM
    b_catalog_store_product p1
    LEFT JOIN b_iblock_element e1 ON p1.PRODUCT_ID = e1.ID
WHERE
    p1.STORE_ID IN (
        56,
        53,
        77,
        81,
        90,
        107,
        54,
        80,
        82,
        84,
        88,
        89,
        102,
        105,
        106,
        52,
        2,
        103,
        93,
        1,
        51,
        92,
        97,
        99,
        100,
        96,
        98,
        109,
        94,
        95,
        5,
        6,
        104,
        96,
        125,
        109,
        119,
        123,
        1,
        51,
        118,
        124
    )
    AND p1.AMOUNT > 0
    AND e1.ACTIVE LIKE 'Y'
    AND p1.PRODUCT_ID IN (
        SELECT
            p2.PRODUCT_ID
        FROM
            b_catalog_store_product p2
            LEFT JOIN b_iblock_element e2 ON p2.PRODUCT_ID = e2.ID
        WHERE
            p2.STORE_ID IN (
                3,
                4,
                7,
                8,
                9,
                10,
                11,
                56,
                57,
                58,
                59,
                60,
                61,
                62,
                63,
                64,
                65,
                66,
                67,
                68,
                69,
                70,
                71,
                72,
                73,
                74,
                75,
                76,
                79,
                83,
                85,
                86,
                87,
                91,
                101,
                110,
                112,
                111,
                120,
                121,
                126,
                115,
                108,
                113,
                114,
                122
            )
            AND e2.ACTIVE LIKE 'Y'
        GROUP BY
            p2.PRODUCT_ID
        HAVING
            SUM(p2.AMOUNT) = 0
    );

SELECT
    b."ФИО",
    b."РАБОТА",
    b."ТЕЛЕФОН",
    b."ПОЧТА",
    b."ГОРОД",
    b.lawtype,
    a."ВИД_ИЗД",
    a."ШИФР",
    a."АВТОР",
    a."НАЗВ_ИЗД",
    a."МЕСТО_ИЗД",
    a."ИЗДАТ",
    a."ГОД",
    a."ТОМ",
    a."СЕРИЯ",
    a."НОМЕР",
    a."НАЗВ_СТАТЬИ",
    a."СТРАНИЦЫ",
    a."ВИД_ДОСТАВКИ",
    a."ДАТА_ПОСТ",
    a."ДАТА_ВЫП",
    a."СТАТУС",
    a."СТРАНИЦЫ2",
    a.id_katalog,
    a."АВТОР_СТАТЬИ",
    a.stoimost,
    a."СТОИМОСТЬ"
FROM
    edd."ЗАКАЗЫ" a
    LEFT JOIN edd."КЛИЕНТЫ" b ON a.id_clients = b.id
where
    (to_char(a."ДАТА_ВЫП", 'YYYYMMDD')) BETWEEN 20210801
    AND 20230831
ORDER BY
    a."ДАТА_ПОСТ";

$wpdb -> get_results(
    $wpdb -> prepare(
        "
					SELECT ID, CONCAT((%d - LEFT(meta.meta_value, 4))%%5, RIGHT(meta.meta_value, 4)) AS year
					FROM $wpdb->posts AS post
					INNER JOIN $wpdb->postmeta AS meta ON post.ID = meta.post_id
					WHERE post.post_type = 'personalii'
						AND meta.meta_key = 'first-date'
						AND ((%d - LEFT(meta.meta_value, 4))%%5 = 0 OR 4)
						AND CONCAT((%d - LEFT(meta.meta_value, 4))%%5, RIGHT(meta.meta_value, 4)) >= %d
					ORDER BY year ASC
					LIMIT %d
					",
        2023,
        2023,
        2023,
        '0'.wp_date('md'),
        1
    )
);

if request.form("search") <> "" then
set
    con = Server.CreateObject("ADODB.Connection")
set
    rs = Server.CreateObject("ADODB.Recordset") con.Open "DRIVER={Oracle in OraClient10g_home};UID=opac;PWD=opac05;DBQ=libcat"
    fio = request.form("fio_readers")
    num_ticket = request.form("num_ticket")
    rs.Open "select a.фото, a.номер_билета, a.срок_действия, a.фио, a.место_работы, a.должность, a.статус, b.код, b.статус as status from ЧИТАТЕЛИ a, статус_чит b where b.код = a.статус and (a.номер_билета= '" & num_ticket & "' or REGEXP_LIKE (ФИО, '" & fio & "', 'i'))",
    con

    
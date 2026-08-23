-- =====================================================================
-- SEED-данные для тестового задания «Каталог: связанные товары»
-- =====================================================================
-- Предполагается, что базовые таблицы Bitrix (b_iblock, b_iblock_element,
-- b_iblock_property, b_iblock_element_property) уже существуют.
--
-- Инфоблок каталога: ID = 2
-- Свойства:
--   ID=3  RELATED_PRODUCTS  (E, множественное, привязка к элементам)
--   ID=4  DISCOUNT_PERCENT  (N)
--   ID=5  IS_PROMO          (L, Y/N)
--   ID=6  IN_STOCK          (L, Y/N)
--   ID=7  PRICE             (N)
-- =====================================================================

-- --- Инфоблок ---------------------------------------------------------
INSERT INTO b_iblock (ID, IBLOCK_TYPE_ID, LID, NAME, ACTIVE, SORT, CODE, VERSION)
VALUES (2, 'catalog', 's1', 'Каталог', 'Y', 100, 'catalog', 2)
ON DUPLICATE KEY UPDATE NAME = VALUES(NAME);

-- --- Свойства ---------------------------------------------------------
INSERT INTO b_iblock_property (ID, IBLOCK_ID, NAME, ACTIVE, SORT, CODE, PROPERTY_TYPE, MULTIPLE, LINK_IBLOCK_ID, LIST_TYPE)
VALUES
 (3, 2, 'Связанные товары', 'Y', 100, 'RELATED_PRODUCTS', 'E', 'Y', 2, 'L'),
 (4, 2, 'Скидка, %',        'Y', 200, 'DISCOUNT_PERCENT',  'N', 'N', NULL, 'L'),
 (5, 2, 'Акция',            'Y', 300, 'IS_PROMO',          'L', 'N', NULL, 'C'),
 (6, 2, 'В наличии',        'Y', 400, 'IN_STOCK',          'L', 'N', NULL, 'C'),
 (7, 2, 'Цена',             'Y', 500, 'PRICE',             'N', 'N', NULL, 'L')
ON DUPLICATE KEY UPDATE NAME = VALUES(NAME);

-- Значения списков для L-свойств (Y/N)
INSERT INTO b_iblock_property_enum (ID, PROPERTY_ID, VALUE, DEF, SORT, XML_ID)
VALUES
 (10, 5, 'Y', 'N', 100, 'IS_PROMO_Y'),
 (11, 5, 'N', 'Y', 200, 'IS_PROMO_N'),
 (12, 6, 'Y', 'N', 100, 'IN_STOCK_Y'),
 (13, 6, 'N', 'Y', 200, 'IN_STOCK_N')
ON DUPLICATE KEY UPDATE VALUE = VALUES(VALUE);

-- --- Элементы каталога ------------------------------------------------
-- Основной товар (100) + 6 связанных (101..106)
INSERT INTO b_iblock_element (ID, IBLOCK_ID, NAME, ACTIVE, SORT, PREVIEW_TEXT, DATE_CREATE, TIMESTAMP_X, MODIFIED_BY)
VALUES
 (100, 2, 'Смартфон Alpha',        'Y', 100, 'Флагманский смартфон',          NOW(), NOW(), 1),
 (101, 2, 'Чехол Alpha',           'Y', 100, 'Защитный чехол',                NOW(), NOW(), 1),
 (102, 2, 'Стекло Alpha',          'Y', 100, 'Защитное стекло',               NOW(), NOW(), 1),
 (103, 2, 'Зарядка Alpha 30W',     'Y', 100, 'Быстрая зарядка',               NOW(), NOW(), 1),
 (104, 2, 'Наушники Alpha Buds',   'Y', 100, 'Беспроводные наушники',         NOW(), NOW(), 1),
 (105, 2, 'Кабель USB-C',          'N', 100, 'Снят с продажи',                NOW(), NOW(), 1),
 (106, 2, 'Power Bank Alpha',      'Y', 100, 'Внешний аккумулятор',           NOW(), NOW(), 1)
ON DUPLICATE KEY UPDATE NAME = VALUES(NAME);

-- --- Значения свойств -------------------------------------------------
-- Формат b_iblock_element_property: (ID, IBLOCK_PROPERTY_ID, IBLOCK_ELEMENT_ID, VALUE, VALUE_ENUM, VALUE_NUM)

-- RELATED_PRODUCTS у товара 100 -> 101,102,103,104,105,106
INSERT INTO b_iblock_element_property (IBLOCK_PROPERTY_ID, IBLOCK_ELEMENT_ID, VALUE, VALUE_NUM)
VALUES
 (3, 100, '101', 101),
 (3, 100, '102', 102),
 (3, 100, '103', 103),
 (3, 100, '104', 104),
 (3, 100, '105', 105),
 (3, 100, '106', 106);

-- DISCOUNT_PERCENT
INSERT INTO b_iblock_element_property (IBLOCK_PROPERTY_ID, IBLOCK_ELEMENT_ID, VALUE, VALUE_NUM)
VALUES
 (4, 101, '10', 10),
 (4, 102, '0',  0),
 (4, 103, '20', 20),
 (4, 104, '15', 15),
 (4, 105, '50', 50),
 (4, 106, '5',  5);

-- IS_PROMO (VALUE_ENUM ссылается на enum id)
INSERT INTO b_iblock_element_property (IBLOCK_PROPERTY_ID, IBLOCK_ELEMENT_ID, VALUE, VALUE_ENUM)
VALUES
 (5, 103, 'Y', 10),
 (5, 104, 'Y', 10);

-- IN_STOCK
INSERT INTO b_iblock_element_property (IBLOCK_PROPERTY_ID, IBLOCK_ELEMENT_ID, VALUE, VALUE_ENUM)
VALUES
 (6, 101, 'Y', 12),
 (6, 102, 'Y', 12),
 (6, 103, 'Y', 12),
 (6, 104, 'N', 13),   -- нет в наличии -> должен быть исключён
 (6, 105, 'Y', 12),
 (6, 106, 'Y', 12);

-- PRICE
INSERT INTO b_iblock_element_property (IBLOCK_PROPERTY_ID, IBLOCK_ELEMENT_ID, VALUE, VALUE_NUM)
VALUES
 (7, 101, '1000', 1000),
 (7, 102, '500',  500),
 (7, 103, '2000', 2000),
 (7, 104, '5000', 5000),
 (7, 105, '300',  300),
 (7, 106, '1500', 1500);


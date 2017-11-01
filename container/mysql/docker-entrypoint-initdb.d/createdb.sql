CREATE DATABASE IF NOT EXISTS `catalog` COLLATE 'utf8_general_ci' ;
GRANT ALL ON `catalog`.* TO 'catalog'@'%' ;

DROP TABLE IF EXISTS catalog.product_types;
DROP TABLE IF EXISTS catalog.products;

CREATE TABLE catalog.product_types
(
    id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name varchar(20) NOT NULL,
    code varchar(5) NOT NULL,
    type enum('square', '6/9'),
    size_min decimal(10,2) NOT NULL,
    size_max decimal(10,2) NOT NULL,
    unit_weight decimal(10,3) NOT NULL,
    unit_price decimal(10,2) NOT NULL
) ENGINE = INNODB CHARACTER SET utf8  COLLATE utf8_general_ci;

INSERT INTO catalog.product_types (name, type, code, unit_weight, unit_price, size_min, size_max) VALUES ('Картон A', 'A', 1, 230, 45, 60, 300);
INSERT INTO catalog.product_types (name, type, code, unit_weight, unit_price, size_min, size_max) VALUES ('Картон B', 'B', 2, 360, 82, 10, 200);
INSERT INTO catalog.product_types (name, type, code, unit_weight, unit_price, size_min, size_max) VALUES ('Картон C', 'C', 1, 650, 64, 10, 300);


CREATE TABLE catalog.products
(
    id INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    type_id INT(11) NOT NULL,
    size DECIMAL(10,2) NOT NULL
) ENGINE = INNODB CHARACTER SET utf8  COLLATE utf8_general_ci;
CREATE INDEX id_index ON catalog.products (type_id) USING BTREE;


INSERT INTO catalog.products (type_id, size) VALUES (1, 100);

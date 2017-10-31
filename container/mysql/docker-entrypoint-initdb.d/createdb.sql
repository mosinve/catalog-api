CREATE DATABASE IF NOT EXISTS `catalog` COLLATE 'utf8_general_ci' ;
GRANT ALL ON `catalog`.* TO 'catalog'@'%' ;

CREATE TABLE catalog.products
(
    id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name varchar(20) NOT NULL,
    type enum('square', '6/9'),
    size decimal(10,2) NOT NULL,
    weight decimal(10,3) NOT NULL,
    price decimal(10,2) NOT NULL
);

INSERT INTO catalog.products (name, type, size, weight, price) VALUES ('Картон A', 1, 9.00, 3.060, 137.70);
INSERT INTO catalog.products (name, type, size, weight, price) VALUES ('Картон B', 2, 1.00, 0.360, 29.52);
INSERT INTO catalog.products (name, type, size, weight, price) VALUES ('Картон C', 1, 4.00, 2.600, 166.40);
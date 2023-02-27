CREATE DATABASE literie3000;
USE literie3000;

CREATE TABLE matelas (
    id smallint primary key auto_increment,
    marque varchar(250) NOT NULL, 
    name varchar(250) NOT NULL, 
    dimensions varchar(50),
    picture VARCHAR(255),
    price DECIMAL(6,2),
    solde DECIMAL(6,2)
);

INSERT INTO matelas 
(marque, name, dimensions, picture, price, solde)
VALUES 
("EPEDA", "Matelas Transition", "90x190", "https://media.but.fr/images_produits/produit-zoom/3663728719257_AMB1.jpg", 759.00, 529.00),
("DREAMWAY", "Matelas Stan", "90x190", "https://www.conforama.fr/fstrz/r/s/cdn2.conforama.fr/product/image/abf4/G_CNF_H75819325_C.jpeg", 809.00, 709.00),
("BULTEX", "Matelas Teamasse", "140x190", "https://www.alinea.com/fstrz/r/s/www.alinea.com/dw/image/v2/BCKM_PRD/on/demandware.static/-/Sites-ali_master/default/dw6a756d29/images/26456614/Matelas-140x190cm-NAIADES-26456614-A-1.jpg?sw=900&sh=900&sm=fit&sfrm=png&bgcolor=eef1eb&frz-v=152", 759.00, 529.00),
("EPEDA", "Matelas Coup de boule", "160x200", "https://consolab.fr/wp-content/uploads/2020/11/matelas-epeda.jpg", 1019.00, 509.00);
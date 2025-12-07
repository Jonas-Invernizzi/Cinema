DROP TABLE IF EXISTS poltronas;
DROP TABLE IF EXISTS usuarios;

CREATE TABLE usuarios (
    id INT NOT NULL AUTO_INCREMENT,
    email VARCHAR(100) NOT NULL UNIQUE, 
    senha VARCHAR(100) NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE poltronas (
    id INT NOT NULL AUTO_INCREMENT,
    fileira INT NOT NULL,
    coluna INT NOT NULL,
    usuario_id INT DEFAULT NULL,
    status ENUM("Vendido", "Disponivel") NOT NULL DEFAULT "Disponivel", 
    PRIMARY KEY (id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);
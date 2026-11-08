CREATE DATABASE IF NOT EXISTS CLIENTES
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE CLIENTES;

CREATE TABLE usuarios (
    dni       VARCHAR(9)  NOT NULL,
    nombre    VARCHAR(30) NOT NULL,
    direccion VARCHAR(50) NULL,
    localidad VARCHAR(30) NULL,
    provincia VARCHAR(30) NULL,
    telefono  VARCHAR(9)  NULL,
    email     VARCHAR(30) NOT NULL,

    PRIMARY KEY (dni)
) ENGINE = InnoDB
  DEFAULT CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

INSERT INTO usuarios (
    dni,
    nombre,
    direccion,
    localidad,
    provincia,
    telefono,
    email
)
VALUES
    (
        '22333444b',
        'Juan Jose Martinez López',
        'C/ el peral 24h',
        'Aspe',
        'Alicante',
        NULL,
        'juanjo@hotmail.com'
    ),
    (
        '11222333e',
        'Maria Gómez Giner',
        'Avda. Reyes Catolicos 23',
        'Alicante',
        'Alicante',
        '677765567',
        'maria@gmail.com'
    ),
    (
        '555555555',
        'juanito Valdes Garcia',
        'Cantal de Eraes s/n',
        'Aspe',
        'Alicante',
        NULL,
        'jafl@laskdjf.com'
    );
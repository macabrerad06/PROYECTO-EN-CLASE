CREATE TABLE author(
    id int AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(100) NOT NULL,
    orcid VARCHAR(20) NOT NULL,
    afiliation VARCHAR(50) NOT NULL
);

CREATE TABLE publication (
    id int AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description VARCHAR(100) NOT NULL,
    publication_date DATE NOT NULL,
    author_id int NOT NULL,
    type ENUM('book','article') NOT NULL,
    Foreign Key (author_id) REFERENCES author(id)
        ON DELETE CASCADE
);

CREATE TABLE book(
    publication_id int AUTO_INCREMENT PRIMARY KEY,
    isbn VARCHAR(20) NOT NULL,
    genre VARCHAR(20) NOT NULL,
    edition int NOT NULL,
    Foreign Key (publication_id) REFERENCES publication(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE article (
    publication_id int AUTO_INCREMENT PRIMARY KEY,
    doi VARCHAR(20) NOT NULL,
    abstract VARCHAR(300) NOT NULL,
    keywords VARCHAR(50) NOT NULL,
    indexation VARCHAR(20) NOT NULL,
    magazine VARCHAR(50) NOT NULL,
    aknowledge_are VARCHAR(50) NOT NULL,
    Foreign Key (publication_id) REFERENCES publication(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)

DELIMITER $$
CREATE PROCEDURE `sp_book_list`()
BEGIN
    SELECT
        b.isbn,
        b.genre,
        b.edition,
        b.publication_id,
        p.id,
        p.description,
        p.publication_date,
        p.title,
        p.author_id,
        a.id AS author_id_from_db,
        a.first_name,
        a.last_name,
        a.username,
        a.email,
        a.password,
        a.orcid,
        a.afiliation
    FROM book b
    JOIN publication p ON b.publication_id = p.id
    JOIN author a ON p.author_id = a.id
    WHERE p.type = 'book'
    ORDER BY p.publication_date DESC;
END$$
DELIMITER ;

DELIMITER //

-- Crear el procedimiento con el nombre de columna corregido
CREATE PROCEDURE sp_article_list()
BEGIN
    SELECT
        p.id AS publication_id,
        p.title,
        p.description,
        p.publication_date,
        p.author_id,
        a.doi AS DOI,
        a.abstract,
        a.keywords,
        a.indexation,
        a.magazine,
        a.aknowledge_are, -- ¡CORREGIDO! Ahora usa 'a.aknowledge_are' directamente
        au.id AS id,
        au.first_name,
        au.last_name AS second_name,
        au.username,
        au.email,
        au.password,
        au.orcid,
        au.afiliation
    FROM
        publication p
    JOIN
        article a ON p.id = a.publication_id
    JOIN
        author au ON p.author_id = au.id
    WHERE
        p.type = 'article';
END //

DELIMITER ;

call sp_article_list();
call sp_find_book(12);
call sp_create_book(
    'Sample Book Title',
    'Sample Description',
    '2023-10-01',
    7,
    '1234567890',
    'Fiction',
    1
);
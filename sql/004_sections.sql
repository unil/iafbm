DROP TABLE IF EXISTS sections;
CREATE TABLE sections (
    id INT NOT NULL,
    code VARCHAR(8) NOT NULL,
    nom VARCHAR(100) NOT NULL,
    PRIMARY KEY (id)
) TYPE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO sections VALUES (1, 'SSC', 'Section des sciences cliniques');
INSERT INTO sections VALUES (2, 'SSF', 'Section des sciences fondamentales');
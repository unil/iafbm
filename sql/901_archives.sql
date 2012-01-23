DROP TABLE IF EXISTS archives;
CREATE TABLE archives (
    id INT NOT NULL AUTO_INCREMENT,
    created TIMESTAMP NULL DEFAULT NULL,
    modified TIMESTAMP NULL DEFAULT NULL,
    util_creat INT,
    util_modif INT,
    table_name varchar(255) NOT NULL,
    model_name varchar(255) NOT NULL,
    id_field_name varchar(255) NOT NULL,
    id_field_value varchar(255) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS archives_data;
CREATE TABLE archives_data (
    id INT NOT NULL AUTO_INCREMENT,
    archive_id INT NOT NULL,
    table_name varchar(255) NOT NULL,
    model_name varchar(255) NOT NULL,
    table_field_name varchar(255) NOT NULL,
    model_field_name varchar(255) NOT NULL,
    value TEXT,
    PRIMARY KEY (id),
    FOREIGN KEY (archive_id) REFERENCES archives(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
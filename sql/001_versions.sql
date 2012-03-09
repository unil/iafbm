DROP TABLE IF EXISTS versions;
CREATE TABLE versions (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    creator VARCHAR(255) NOT NULL,
    table_name VARCHAR(255) NOT NULL,
    model_name VARCHAR(255) NOT NULL,
    id_field_name VARCHAR(255) NOT NULL,
    id_field_value INT NOT NULL,
    operation VARCHAR(8) NOT NULL,
    commentaire VARCHAR(255),
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Creates an empty initial version
INSERT INTO versions (table_name, model_name, id_field_name, id_field_value, operation, commentaire) VALUES ('*', '*', '*', 0, 'tag', 'Initial version (empty storage)');


DROP TABLE IF EXISTS versions_data;
CREATE TABLE versions_data (
    id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
    version_id INT UNSIGNED NOT NULL,
    field_name VARCHAR(255) NOT NULL,
    old_value TEXT,
    new_value TEXT,
    PRIMARY KEY (id),
    FOREIGN KEY (version_id) REFERENCES versions(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS versions_relations;
CREATE TABLE versions_relations (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    version_id INT UNSIGNED NOT NULL,
    table_name VARCHAR(255) NOT NULL,
    model_name VARCHAR(255) NOT NULL,
    id_field_name VARCHAR(255) NOT NULL,
    id_field_value INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (version_id) REFERENCES versions(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
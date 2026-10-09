-- Import this file in phpMyAdmin or run: mysql -u root -p < app_db.sql
CREATE DATABASE IF NOT EXISTS app_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE app_db;

CREATE TABLE IF NOT EXISTS contacts (
  id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name       VARCHAR(100) NOT NULL,
  email      VARCHAR(150) NOT NULL,
  phone      VARCHAR(30)  DEFAULT NULL,
  address    VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_contacts_email (email)
) ENGINE=InnoDB;

INSERT INTO contacts (name, email, phone, address) VALUES
  ('Catherine Laparan', 'catherine@example.com',  '0917 123 4567', 'Lian, Batangas'),
  ('Kristine Dimayuga', 'kristine@example.com', '0928 765 4321', 'Lian, Batangas'),
  ('Blessie', 'blessie@example.com',  '0905 555 0101', 'Nasugbu, Batangas');
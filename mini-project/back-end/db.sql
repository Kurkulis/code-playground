-- sukuria DB jei jos nera ir lentelę registracijoms
-- noudoti tik kai kuri DB ir lentele neegzistuoja

CREATE DATABASE IF NOT EXISTS giluzio
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE giluzio;

CREATE TABLE IF NOT EXISTS registrations (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  phone VARCHAR(50)  NOT NULL,
  email VARCHAR(160) NOT NULL,
  rooms VARCHAR(20)  NOT NULL,
  ip VARCHAR(45)     NULL,
  user_agent TEXT    NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

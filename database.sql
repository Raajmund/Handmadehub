-- ============================================
-- PORTÁL PRE REMESELNÍKOV - Databázová schéma
-- Spusti tento súbor v phpMyAdmin alebo cez MySQL CLI
-- ============================================

CREATE DATABASE IF NOT EXISTS remeslari CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE remeslari;

-- Tabuľka remeselníkov (hlavná entita pre CRUD)
CREATE TABLE IF NOT EXISTS remeslari (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    meno        VARCHAR(100)  NOT NULL,
    email       VARCHAR(150)  NOT NULL UNIQUE,
    telefon     VARCHAR(20)   DEFAULT NULL,
    mesto       VARCHAR(100)  NOT NULL,
    odbor       VARCHAR(100)  NOT NULL,       -- napr. "Hrnčiarstvo", "Tkáčstvo"
    popis       TEXT          DEFAULT NULL,
    fotka       VARCHAR(255)  DEFAULT NULL,   -- názov súboru fotky
    vytvorene   DATETIME      DEFAULT CURRENT_TIMESTAMP,
    upravene    DATETIME      DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabuľka adminov (pre prihlasovanie)
CREATE TABLE IF NOT EXISTS admins (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    username    VARCHAR(50)   NOT NULL UNIQUE,
    password    VARCHAR(255)  NOT NULL,       -- bcrypt hash
    vytvorene   DATETIME      DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Ukážkové dáta – remeselníci
INSERT INTO remeslari (meno, email, telefon, mesto, odbor, popis) VALUES
('Marta Kováčová',  'marta@example.com', '0901 111 222', 'Trenčín',    'Hrnčiarstvo',  'Vyrábam tradičnú keramiku ručne točenú na kruhu.'),
('Jozef Tkáč',      'jozef@example.com', '0902 333 444', 'Nitra',      'Tkáčstvo',     'Tkám koberce a gobelíny z prírodných vlákien.'),
('Eva Krajčírová',  'eva@example.com',   '0903 555 666', 'Bratislava', 'Krajčírstvo',  'Šijem ľudové kroje a moderné oblečenie na mieru.');

-- Admin účet: heslo je "admin123" (zmenené nižšie ako bcrypt hash)
-- DÔLEŽITÉ: Po inštalácii si heslo zmeň!
-- Toto je bcrypt hash pre "admin123"
INSERT INTO admins (username, password) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
